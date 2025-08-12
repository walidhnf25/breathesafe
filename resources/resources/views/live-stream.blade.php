@extends('layouts.tabler')

@section('content')

    <style>
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 3em;
            margin-bottom: 15px;
        }

        .header p {
            font-size: 1.3em;
            opacity: 0.9;
        }

        .main-content {
            padding: 20px 40px 40px 40px;
        }

        .api-controls {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .api-info h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .api-toggle {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .api-toggle.enabled {
            background: #28a745;
        }

        .api-toggle.disabled {
            background: #dc3545;
        }

        .cameras-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .camera-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .camera-section.active {
            border-color: #28a745;
            background: linear-gradient(135deg, #f8fff8, #f0fff0);
        }

        .camera-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .camera-title {
            font-size: 1.4em;
            font-weight: bold;
            color: #333;
        }

        .camera-controls {
            display: flex;
            gap: 10px;
        }

        .stream-btn {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .stream-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }

        .stream-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .stream-btn.stop {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }

        .stream-btn.start {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .camera-status {
            margin: 15px 0;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .video-container {
            background: #000;
            border-radius: 10px;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1em;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .video-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .detection-stats {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            text-align: center;
        }

        .stat-item {
            padding: 15px;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .stat-value {
            font-size: 1.8em;
            font-weight: bold;
            color: #667eea;
            display: block;
        }

        .stat-label {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }

        .detections-section {
            margin-top: 40px;
        }

        .detections-header {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }

        .detections-header h3 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 10px;
        }

        .detections-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .camera-detections {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
        }

        .camera-detections h4 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
            font-size: 1.2em;
        }

        .detection-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .detection-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .detection-item img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .detection-info {
            flex: 1;
        }

        .detection-info h5 {
            margin: 0 0 8px 0;
            color: #ff6b6b;
            font-size: 1em;
        }

        .detection-info p {
            margin: 3px 0;
            font-size: 0.85em;
            color: #666;
        }

        .error {
            background: #ff6b6b;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            font-weight: 600;
        }

        .no-detections {
            text-align: center;
            color: #666;
            padding: 40px;
            font-style: italic;
            font-size: 1.1em;
        }

        .system-info {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 30px;
            border-radius: 15px;
            margin-top: 40px;
        }

        .system-info h3 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            font-size: 1.4em;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .info-label {
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .info-value {
            color: #666;
        }

        @media (max-width: 768px) {
            .cameras-grid {
                grid-template-columns: 1fr;
            }

            .detections-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mt-5 mb-3">
        <h1 class="h3 mb-0 text-gray-800">Live Stream Camera</h1>
    </div>

    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    <!-- API Controls -->
    <div class="api-controls">
        <div class="api-info">
            <div id="apiInfo">Status: Checking...</div>
        </div>
        <button id="apiToggle" class="api-toggle" onclick="toggleAPI()">Toggle API</button>
    </div>

    <div id="error" class="error" style="display: none;"></div>

    <!-- Dual Camera Grid -->
    <div class="cameras-grid">

        <!-- Camera 1 -->
        <div class="camera-section" id="camera1Section">
            <div class="camera-header">
                <div class="camera-title">📹 Camera 1 - Building A</div>
                <div class="camera-controls">
                    <button id="startBtn1" class="stream-btn start" onclick="startStream(1)">▶️ Start Stream</button>
                    <button id="stopBtn1" class="stream-btn stop" onclick="stopStream(1)" disabled>⏹️ Stop Stream</button>
                </div>
            </div>

            <div id="cameraStatus1" class="camera-status status-inactive">
                Ready to start monitoring
            </div>

            <div class="video-container">
                <div id="videoFrame1">Click "Start Stream" to Begin Camera 1 Monitoring</div>
            </div>

            <div class="detection-stats">
                <div class="stats-grid">
                    <div class="stat-item">
                        <span id="totalDetections1" class="stat-value">0</span>
                        <div class="stat-label">Total Detections</div>
                    </div>
                    <div class="stat-item">
                        <span id="lastDetection1" class="stat-value">-</span>
                        <div class="stat-label">Last Detection</div>
                    </div>
                    <div class="stat-item">
                        <span id="apiSaved1" class="stat-value">0</span>
                        <div class="stat-label">Saved to DB</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Camera 2 -->
        <div class="camera-section" id="camera2Section">
            <div class="camera-header">
                <div class="camera-title">📹 Camera 2 - Building B</div>
                <div class="camera-controls">
                    <button id="startBtn2" class="stream-btn start" onclick="startStream(2)">▶️ Start Stream</button>
                    <button id="stopBtn2" class="stream-btn stop" onclick="stopStream(2)" disabled>⏹️ Stop Stream</button>
                </div>
            </div>

            <div id="cameraStatus2" class="camera-status status-inactive">
                Ready to start monitoring
            </div>

            <div class="video-container">
                <div id="videoFrame2">Click "Start Stream" to Begin Camera 2 Monitoring</div>
            </div>

            <div class="detection-stats">
                <div class="stats-grid">
                    <div class="stat-item">
                        <span id="totalDetections2" class="stat-value">0</span>
                        <div class="stat-label">Total Detections</div>
                    </div>
                    <div class="stat-item">
                        <span id="lastDetection2" class="stat-value">-</span>
                        <div class="stat-label">Last Detection</div>
                    </div>
                    <div class="stat-item">
                        <span id="apiSaved2" class="stat-value">0</span>
                        <div class="stat-label">Saved to DB</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detection History Section -->
    <div class="detections-section">
        <div class="detections-header">
            <h3>🚨 Recent Detection History</h3>
            <p>Real-time Smoke Detection Alerts from Both Monitoring Locations</p>

            {{-- 
            @foreach ($detections as $item)
            <div class="detection-item">
                <img src="{{asset('/storage/detection_image/' . $item->detection_image)}}" alt="Smoke Detection Alert">
                <div class="detection-info">
                    <h5>🚨 Smoke Detection Alert</h5>
                    <p><strong>Time:</strong> {{$item->created_at}}</p>
                </div>
            </div>
            @endforeach 
            --}}
        </div>

        <div class="detections-grid">
            <div class="camera-detections">
                <h4>📹 Camera 1 - Recent Detections</h4>
                <div id="detectionsContainer1">
                    <div class="no-detections">No Detections Yet - Monitoring Standby</div>
                </div>
            </div>

            <div class="camera-detections">
                <h4>📹 Camera 2 - Recent Detections</h4>
                <div id="detectionsContainer2">
                    <div class="no-detections">No Detections Yet - Monitoring Standby</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const baseURL = 'http://127.0.0.1:5000';

        function apiFetch(endpoint, options = {}) {
            const url = new URL(endpoint, baseURL);
            return fetch(url, options);
        }

        let streamInterval1 = null;
        let streamInterval2 = null;
        let detectionsInterval1 = null;
        let detectionsInterval2 = null;

        window.addEventListener('load', function () {
            checkAPIStatus();
        });


        function checkAPIStatus() {
            apiFetch('/api-status')
                .then(response => response.json())
                .then(data => {
                    updateAPIStatus(data.api_enabled);
                })
                .catch(err => {
                    console.error('Error checking API status:', err);
                    document.getElementById('apiInfo').textContent = 'Status: Error checking API';
                });
        }

        function toggleAPI() {
            apiFetch('/toggle-api', {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateAPIStatus(data.api_enabled);
                        console.log(data.message);
                    } else {
                        showError('Failed to toggle API');
                    }
                })
                .catch(err => {
                    console.error('Error toggling API:', err);
                    showError('Network error when toggling API');
                });
        }

        function startStream(cameraId) {
            apiFetch(`/start-stream/${cameraId}`, {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`startBtn${cameraId}`).disabled = true;
                        document.getElementById(`stopBtn${cameraId}`).disabled = false;
                        document.getElementById(`cameraStatus${cameraId}`).innerHTML = 'Status: <span style="color: #28a745; font-weight: bold;">🟢 Live Stream Active - Monitoring for Smoke</span>';
                        document.getElementById(`cameraStatus${cameraId}`).className = 'camera-status status-active';
                        document.getElementById(`camera${cameraId}Section`).classList.add('active');

                        if (cameraId === 1) {
                            streamInterval1 = setInterval(() => updateStreamFrame(1), 100);
                            detectionsInterval1 = setInterval(() => updateDetections(1), 2000);
                        } else {
                            streamInterval2 = setInterval(() => updateStreamFrame(2), 100);
                            detectionsInterval2 = setInterval(() => updateDetections(2), 2000);
                        }

                        hideError();
                    } else {
                        showError(data.error || `Failed to start Camera ${cameraId} stream`);
                    }
                })
                .catch(err => {
                    showError('Network error: ' + err.message);
                });
        }

        function stopStream(cameraId) {
            apiFetch(`/stop-stream/${cameraId}`, {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`startBtn${cameraId}`).disabled = false;
                        document.getElementById(`stopBtn${cameraId}`).disabled = true;
                        document.getElementById(`cameraStatus${cameraId}`).innerHTML = 'Status: <span style="color: #dc3545; font-weight: bold;">🔴 Stream stopped - Click start to resume</span>';
                        document.getElementById(`cameraStatus${cameraId}`).className = 'camera-status status-inactive';
                        document.getElementById(`camera${cameraId}Section`).classList.remove('active');

                        if (cameraId === 1) {
                            clearInterval(streamInterval1); streamInterval1 = null;
                            clearInterval(detectionsInterval1); detectionsInterval1 = null;
                        } else {
                            clearInterval(streamInterval2); streamInterval2 = null;
                            clearInterval(detectionsInterval2); detectionsInterval2 = null;
                        }

                        document.getElementById(`videoFrame${cameraId}`).innerHTML = `Camera ${cameraId} monitoring stopped - click start to resume`;
                        hideError();
                    } else {
                        showError(data.error || `Failed to stop Camera ${cameraId} stream`);
                    }
                })
                .catch(err => {
                    showError('Network error: ' + err.message);
                });
        }

        function updateStreamFrame(cameraId) {
            apiFetch(`/stream-frame/${cameraId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.frame) {
                        const videoFrame = document.getElementById(`videoFrame${cameraId}`);
                        videoFrame.innerHTML = `<img src="${data.frame}" alt="Camera ${cameraId} Live Feed">`;
                    }
                })
                .catch(err => {
                    console.error(`Error updating Camera ${cameraId} frame:`, err);
                });
        }

        function updateDetections(cameraId) {
            apiFetch(`/stream-detections/${cameraId}`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById(`detectionsContainer${cameraId}`);
                    const totalDetections = document.getElementById(`totalDetections${cameraId}`);
                    const lastDetection = document.getElementById(`lastDetection${cameraId}`);
                    const apiSaved = document.getElementById(`apiSaved${cameraId}`);

                    totalDetections.textContent = data.total_count || 0;

                    if (data.detections && data.detections.length > 0) {
                        container.innerHTML = '';
                        const savedCount = data.detections.filter(d => d.saved_to_api).length;
                        apiSaved.textContent = savedCount;

                        const latestDetection = data.detections[data.detections.length - 1];
                        const timestamp = new Date(latestDetection.timestamp);
                        lastDetection.textContent = timestamp.toLocaleTimeString();

                        data.detections.reverse().forEach(detection => {

                            console.log(detection)

                            const detectionDiv = document.createElement('div');
                            detectionDiv.className = 'detection-item';

                            const timestamp = new Date(detection.timestamp);
                            const timeStr = timestamp.toLocaleTimeString();

                            detectionDiv.innerHTML = `
                                                            <img src="${detection.overlay_image}" alt="Smoke Detection Alert">
                                                            <div class="detection-info">
                                                                <h5>🚨 Smoke Detection Alert</h5>
                                                                <p><strong>Time:</strong> ${timeStr}</p>
                                                                <p><strong>Frame:</strong> ${detection.frame_number}</p>
                                                                <p><strong>Smoke Level:</strong> ${detection.smoke_percentage}%</p>
                                                                <p><strong>Confidence:</strong> ${detection.max_probability}</p>
                                                                <p style="color: ${detection.saved_to_api ? '#28a745' : '#dc3545'}; font-weight: bold;">
                                                                    ${detection.saved_to_api ? '✅ Saved to Database' : '❌ Save Failed'}
                                                                </p>
                                                            </div>
                                                        `;

                            container.appendChild(detectionDiv);
                        });
                    } else {
                        container.innerHTML = '<div class="no-detections">No detections yet - monitoring active</div>';
                        lastDetection.textContent = '-';
                        apiSaved.textContent = '0';
                    }
                })
                .catch(err => {
                    console.error(`Error updating Camera ${cameraId} detections:`, err);
                });
        }

        function updateAPIStatus(enabled) {
            const apiInfo = document.getElementById('apiInfo');
            const apiToggle = document.getElementById('apiToggle');

            if (enabled) {
                apiInfo.innerHTML = 'Status: <span style="color: #28a745; font-weight: bold;">🟢 Database Enabled - Saving Detections to Database</span>';
                apiToggle.textContent = '🔴 Disable Database';
                apiToggle.className = 'api-toggle enabled';
            } else {
                apiInfo.innerHTML = 'Status: <span style="color: #dc3545; font-weight: bold;">🔴 Database Disabled - Local Monitoring Only</span>';
                apiToggle.textContent = '🟢 Enable Database';
                apiToggle.className = 'api-toggle disabled';
            }
        }

        function showError(message) {
            const error = document.getElementById('error');
            error.textContent = '❌ Error: ' + message;
            error.style.display = 'block';
        }

        function hideError() {
            const error = document.getElementById('error');
            error.style.display = 'none';
        }


    </script>

@endsection
