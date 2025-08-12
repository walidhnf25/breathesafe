from flask import Flask, render_template, request, jsonify
import torch
import torch.nn as nn
import cv2
import numpy as np
from PIL import Image
import io
import base64
from torchvision.models.segmentation import deeplabv3_mobilenet_v3_large
import os
import tempfile
import time
import requests
import threading
from datetime import datetime

app = Flask(__name__)

# Laravel API Configuration
LARAVEL_API_URL = "https://breathesafe.solvethink.id/api/detection-images"
DEFAULT_CAMERA_ID_1 = 1
DEFAULT_CAMERA_ID_2 = 2

# Live Stream Configuration
LOCAL_VIDEO_PATH_1 = "stream_video.mp4"    # Camera 1
LOCAL_VIDEO_PATH_2 = "stream_video2.mp4"   # Camera 2
STREAM_FRAME_SKIP = 30  # Capture setiap 30 frame untuk analisis

# API Toggle Configuration
ENABLE_API_SAVE = True  # Default: API enabled

# Model parameters
IMAGE_SIZE = (256, 256)
NUM_CLASSES = 2
DEVICE = torch.device('cuda' if torch.cuda.is_available() else 'cpu')

# Global variables for dual camera live streams
stream_active_1 = False
stream_active_2 = False
stream_thread_1 = None
stream_thread_2 = None
latest_frame_1 = None
latest_frame_2 = None
detection_history_1 = []
detection_history_2 = []

# Model definition
class SmokeSegmentationModel(nn.Module):
    def __init__(self, num_classes=2):
        super(SmokeSegmentationModel, self).__init__()
        self.model = deeplabv3_mobilenet_v3_large(pretrained=True)
        self.model.classifier[4] = nn.Conv2d(256, num_classes, kernel_size=1)

    def forward(self, x):
        return self.model(x)['out']

# Load model
print("Loading model...")
model = SmokeSegmentationModel(num_classes=NUM_CLASSES).to(DEVICE)

# Load state dict with error handling
try:
    state_dict = torch.load('best_model2.pth', map_location=DEVICE)
    model.load_state_dict(state_dict)
    print("✅ Model loaded successfully!")
except Exception as e:
    print(f"❌ Error loading model: {e}")
    exit(1)

model.eval()
print(f"Model device: {next(model.parameters()).device}")
print(f"Model parameters count: {sum(p.numel() for p in model.parameters()):,}")

# Use the same threshold as in your Colab (G-Mean optimized)
OPTIMAL_THRESHOLD = 0.012

def preprocess_image(image_array):
    """Preprocess image for model input"""
    if len(image_array.shape) == 3 and image_array.shape[2] == 3:
        image_rgb = image_array
    else:
        image_rgb = cv2.cvtColor(image_array, cv2.COLOR_BGR2RGB)
    
    image_resized = cv2.resize(image_rgb, IMAGE_SIZE)
    image_tensor = torch.from_numpy(image_resized).permute(2, 0, 1).float() / 255.0
    image_tensor = image_tensor.unsqueeze(0).to(DEVICE)
    
    return image_tensor, image_resized

def predict_smoke(image_tensor):
    """Predict smoke from image tensor"""
    model.eval()
    
    with torch.no_grad():
        output = model(image_tensor)
        probs = torch.softmax(output, dim=1)
        prob = probs[0, 1].cpu().numpy()
        pred_mask = (prob > OPTIMAL_THRESHOLD).astype(np.uint8)
        
        print(f"Debug - Prob shape: {prob.shape}")
        print(f"Debug - Prob min/max: {prob.min():.6f}/{prob.max():.6f}")
        print(f"Debug - Threshold: {OPTIMAL_THRESHOLD}")
        print(f"Debug - Pred mask sum: {pred_mask.sum()}")
    
    return prob, pred_mask

def create_overlay(original_image, pred_mask):
    """Create overlay visualization with enhanced smoke detection display"""
    overlay = original_image.copy()
    
    if pred_mask.max() > 0:
        # Create red mask for smoke areas
        red_mask = np.zeros_like(original_image)
        red_mask[:, :, 0] = pred_mask * 255  # Red channel
        
        # Add semi-transparent red overlay
        overlay = cv2.addWeighted(overlay, 0.7, red_mask, 0.3, 0)
        
        # Add contours for better visibility
        contours, _ = cv2.findContours(pred_mask, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
        cv2.drawContours(overlay, contours, -1, (255, 0, 0), 2)  # Red contours
    
    # Add text overlay for real-time info
    smoke_percentage = float((pred_mask.sum() / pred_mask.size) * 100)
    status_text = f"Smoke: {smoke_percentage:.1f}%"
    status_color = (0, 255, 0) if smoke_percentage < 1.0 else (255, 0, 0)  # Green if safe, Red if smoke
    
    # Add background rectangle for text
    cv2.rectangle(overlay, (5, 5), (200, 35), (0, 0, 0), -1)
    cv2.putText(overlay, status_text, (10, 25), cv2.FONT_HERSHEY_SIMPLEX, 0.6, status_color, 2)
    
    return overlay

def array_to_base64(image_array):
    """Convert numpy array to base64 string for web display"""
    try:
        if image_array.dtype != np.uint8:
            image_array = image_array.astype(np.uint8)
        
        pil_image = Image.fromarray(image_array)
        buffer = io.BytesIO()
        pil_image.save(buffer, format='PNG')
        img_str = base64.b64encode(buffer.getvalue()).decode()
        
        return f"data:image/png;base64,{img_str}"
    except Exception as e:
        print(f"Error in array_to_base64: {e}")
        return None

def save_detection_to_laravel(image_array, camera_id=1):
    """Save detection result to Laravel API"""
    global ENABLE_API_SAVE
    
    if not ENABLE_API_SAVE:
        print("ℹ️ API save disabled - skipping Laravel upload")
        return False, "API save disabled"
    
    try:
        if image_array.dtype != np.uint8:
            image_array = image_array.astype(np.uint8)
        pil_image = Image.fromarray(image_array)
        
        temp_dir = tempfile.mkdtemp()
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S_%f")
        temp_filename = f"detection_live_stream_cam{camera_id}_{timestamp}.png"
        temp_path = os.path.join(temp_dir, temp_filename)
        
        pil_image.save(temp_path, 'PNG')
        
        files = {
            'detection_image': (temp_filename, open(temp_path, 'rb'), 'image/png')
        }
        data = {
            'camera_id': camera_id
        }
        
        print(f"🔄 Sending to Laravel API: {LARAVEL_API_URL} (Camera {camera_id})")
        response = requests.post(LARAVEL_API_URL, files=files, data=data, timeout=10)
        
        files['detection_image'][1].close()
        os.remove(temp_path)
        os.rmdir(temp_dir)
        
        if response.status_code == 201:
            result = response.json()
            print(f"✅ Successfully saved to Laravel (Camera {camera_id}): {result['message']}")
            return True, result
        else:
            print(f"❌ Laravel API error (Camera {camera_id}): {response.status_code} - {response.text}")
            return False, f"API error: {response.status_code}"
            
    except requests.exceptions.Timeout:
        print(f"❌ Laravel API timeout (Camera {camera_id})")
        return False, "API timeout"
    except requests.exceptions.ConnectionError:
        print(f"❌ Cannot connect to Laravel API (Camera {camera_id}) - check if Laravel server is running")
        return False, "Connection error - Laravel server not accessible"
    except requests.exceptions.RequestException as e:
        print(f"❌ Network error when calling Laravel API (Camera {camera_id}): {e}")
        return False, f"Network error: {str(e)}"
    except Exception as e:
        print(f"❌ Error saving to Laravel (Camera {camera_id}): {e}")
        return False, f"Error: {str(e)}"

def process_live_stream(camera_id, video_path):
    """Process live stream from local video file for specific camera"""
    global stream_active_1, stream_active_2, latest_frame_1, latest_frame_2, detection_history_1, detection_history_2
    
    if not os.path.exists(video_path):
        print(f"❌ Video file not found: {video_path}")
        return
    
    cap = cv2.VideoCapture(video_path)
    if not cap.isOpened():
        print(f"❌ Cannot open video: {video_path}")
        return
    
    fps = cap.get(cv2.CAP_PROP_FPS)
    total_frames = int(cap.get(cv2.CAP_PROP_FRAME_COUNT))
    frame_count = 0
    
    print(f"🎥 Starting live stream Camera {camera_id}: {total_frames} frames, FPS: {fps}")
    
    # Determine which stream is active and which variables to use
    stream_active = stream_active_1 if camera_id == 1 else stream_active_2
    detection_history = detection_history_1 if camera_id == 1 else detection_history_2
    
    while stream_active:
        # Update stream_active status
        stream_active = stream_active_1 if camera_id == 1 else stream_active_2
        if not stream_active:
            break
            
        ret, frame = cap.read()
        if not ret:
            # Loop video when it ends
            cap.set(cv2.CAP_PROP_POS_FRAMES, 0)
            frame_count = 0
            continue
        
        # Process every frame for real-time segmentation display
        try:
            frame_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
            image_tensor, image_resized = preprocess_image(frame_rgb)
            prob, pred_mask = predict_smoke(image_tensor)
            
            # Always create overlay for display (even if no significant smoke)
            overlay = create_overlay(image_resized, pred_mask)
            
            # Convert overlay to base64 for web display
            overlay_bgr = cv2.cvtColor(overlay, cv2.COLOR_RGB2BGR)
            _, buffer = cv2.imencode('.jpg', overlay_bgr)
            frame_b64 = base64.b64encode(buffer).decode('utf-8')
            frame_data = f"data:image/jpeg;base64,{frame_b64}"
            
            # Update latest frame for specific camera (now with overlay)
            if camera_id == 1:
                latest_frame_1 = frame_data
            else:
                latest_frame_2 = frame_data
            
        except Exception as e:
            print(f"❌ Error processing frame {frame_count} (Camera {camera_id}): {e}")
            # Fallback to original frame if processing fails
            _, buffer = cv2.imencode('.jpg', frame)
            frame_b64 = base64.b64encode(buffer).decode('utf-8')
            frame_data = f"data:image/jpeg;base64,{frame_b64}"
            
            if camera_id == 1:
                latest_frame_1 = frame_data
            else:
                latest_frame_2 = frame_data
        
        # Process every 30th frame for detection logging and API saving
        if frame_count % STREAM_FRAME_SKIP == 0:
            try:
                # Reuse the already computed values from above
                smoke_percentage = float((pred_mask.sum() / pred_mask.size) * 100)
                max_probability = float(prob.max())
                
                # If smoke detected significantly, save to API and add to detection history
                if smoke_percentage > 1.0:
                    # Save to Laravel API with correct camera ID
                    api_camera_id = DEFAULT_CAMERA_ID_1 if camera_id == 1 else DEFAULT_CAMERA_ID_2
                    api_saved, api_response = save_detection_to_laravel(overlay, api_camera_id)
                    
                    # Add to detection history
                    detection_data = {
                        'timestamp': datetime.now().isoformat(),
                        'frame_number': frame_count,
                        'camera_id': camera_id,
                        'smoke_percentage': round(smoke_percentage, 2),
                        'max_probability': round(max_probability, 4),
                        'saved_to_api': api_saved,
                        'overlay_image': array_to_base64(overlay)
                    }
                    
                    if camera_id == 1:
                        detection_history_1.append(detection_data)
                        # Keep only last 50 detections
                        if len(detection_history_1) > 50:
                            detection_history_1.pop(0)
                    else:
                        detection_history_2.append(detection_data)
                        # Keep only last 50 detections
                        if len(detection_history_2) > 50:
                            detection_history_2.pop(0)
                    
                    print(f"🚨 Camera {camera_id} detection - Frame {frame_count}: {smoke_percentage:.2f}% smoke")
                
            except Exception as e:
                print(f"❌ Error processing detection logic frame {frame_count} (Camera {camera_id}): {e}")
        
        frame_count += 1
        
        # Control frame rate (simulate real-time)
        time.sleep(1.0 / fps)
    
    cap.release()
    print(f"🛑 Live stream Camera {camera_id} stopped")

# Routes
@app.route('/')
def index():
    return render_template('index.html')

# API endpoints
@app.route('/api-status')
def api_status():
    global ENABLE_API_SAVE
    return jsonify({
        'api_enabled': ENABLE_API_SAVE,
        'api_url': LARAVEL_API_URL,
        'camera_1_id': DEFAULT_CAMERA_ID_1,
        'camera_2_id': DEFAULT_CAMERA_ID_2
    })

@app.route('/toggle-api', methods=['POST'])
def toggle_api():
    global ENABLE_API_SAVE
    ENABLE_API_SAVE = not ENABLE_API_SAVE
    status_text = "enabled" if ENABLE_API_SAVE else "disabled"
    print(f"🔄 API save {status_text}")
    return jsonify({
        'success': True,
        'api_enabled': ENABLE_API_SAVE,
        'message': f"API save {status_text}"
    })

# Dual Camera Stream Routes
@app.route('/start-stream/<int:camera_id>', methods=['POST'])
def start_stream(camera_id):
    global stream_active_1, stream_active_2, stream_thread_1, stream_thread_2, detection_history_1, detection_history_2
    
    if camera_id not in [1, 2]:
        return jsonify({'error': 'Invalid camera ID. Use 1 or 2.'})
    
    video_path = LOCAL_VIDEO_PATH_1 if camera_id == 1 else LOCAL_VIDEO_PATH_2
    stream_active = stream_active_1 if camera_id == 1 else stream_active_2
    
    if stream_active:
        return jsonify({'error': f'Camera {camera_id} stream already active'})
    
    if not os.path.exists(video_path):
        return jsonify({'error': f'Video file not found: {video_path}'})
    
    # Clear detection history for specific camera
    if camera_id == 1:
        detection_history_1 = []
        stream_active_1 = True
        stream_thread_1 = threading.Thread(target=process_live_stream, args=(camera_id, video_path))
        stream_thread_1.daemon = True
        stream_thread_1.start()
    else:
        detection_history_2 = []
        stream_active_2 = True
        stream_thread_2 = threading.Thread(target=process_live_stream, args=(camera_id, video_path))
        stream_thread_2.daemon = True
        stream_thread_2.start()
    
    return jsonify({
        'success': True,
        'message': f'Camera {camera_id} live stream started',
        'video_path': video_path,
        'camera_id': camera_id
    })

@app.route('/stop-stream/<int:camera_id>', methods=['POST'])
def stop_stream(camera_id):
    global stream_active_1, stream_active_2
    
    if camera_id not in [1, 2]:
        return jsonify({'error': 'Invalid camera ID. Use 1 or 2.'})
    
    if camera_id == 1:
        if not stream_active_1:
            return jsonify({'error': 'Camera 1 stream not active'})
        stream_active_1 = False
    else:
        if not stream_active_2:
            return jsonify({'error': 'Camera 2 stream not active'})
        stream_active_2 = False
    
    return jsonify({
        'success': True,
        'message': f'Camera {camera_id} live stream stopped'
    })

@app.route('/stream-status')
def stream_status():
    return jsonify({
        'camera_1': {
            'active': stream_active_1,
            'video_path': LOCAL_VIDEO_PATH_1,
            'total_detections': len(detection_history_1),
            'latest_frame': latest_frame_1
        },
        'camera_2': {
            'active': stream_active_2,
            'video_path': LOCAL_VIDEO_PATH_2,
            'total_detections': len(detection_history_2),
            'latest_frame': latest_frame_2
        }
    })

@app.route('/stream-detections/<int:camera_id>')
def stream_detections(camera_id):
    if camera_id == 1:
        detections = detection_history_1[-10:]  # Last 10 detections
        total_count = len(detection_history_1)
    elif camera_id == 2:
        detections = detection_history_2[-10:]  # Last 10 detections
        total_count = len(detection_history_2)
    else:
        return jsonify({'error': 'Invalid camera ID'})
    
    return jsonify({
        'detections': detections,
        'total_count': total_count,
        'camera_id': camera_id
    })

@app.route('/stream-frame/<int:camera_id>')
def stream_frame(camera_id):
    if camera_id == 1:
        frame = latest_frame_1
    elif camera_id == 2:
        frame = latest_frame_2
    else:
        return jsonify({'error': 'Invalid camera ID'})
    
    return jsonify({
        'frame': frame,
        'timestamp': datetime.now().isoformat(),
        'camera_id': camera_id
    })

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)