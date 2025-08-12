<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BreatheSafe</title>

    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
<style>
html, body {
    height: 100%;
    margin: 0;
    font-family: 'Roboto', sans-serif;
    background-color: white;
}

.login-container {
    display: flex;
    flex-direction: column;
    height: 100vh;
    max-width: 100vw;
    overflow: hidden;
}

@media (min-width: 576px) {
    .login-container {
    flex-direction: row;
    }
}

.left-side {
    background-color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 3rem;
    flex: 1;
}

.left-side img {
    max-width: 100%;
    max-height: 250px;
}

.right-side {
    background-color: #dc2626; /* red-600 */
    color: white;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex: 1;
}

.right-side h1 {
    font-weight: 800;
    font-size: 1.25rem;
    margin-bottom: 0.25rem;
}

.right-side h2 {
    font-weight: 600;
    font-size: 1.125rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.25rem;
}

.form-control::placeholder {
    color: #d1d5db; /* placeholder-gray-400 */
}

.btn-login {
    background-color: #4f46e5; /* indigo-700 */
    border: none;
    font-weight: 500;
    font-size: 0.875rem;
    padding: 0.5rem 0;
    transition: background-color 0.3s ease;
}

.btn-login:hover {
    background-color: #4338ca; /* indigo-800 */
}
</style>
</head>
<body>
    <div class="login-container">
        <!-- Left Image Side -->
        <div class="left-side">
            <img src="{{ asset('img/logo.png') }}" alt="No smoking icon with cigarette, vape, and school building"/>
        </div>
        <!-- Right Login Form -->
        <div class="right-side">
            <h1 class="text-center">BreatheSafe</h1>
            <h2 class="text-center">Register</h2>
            @if(session('warning'))
                <div class="alert alert-danger text-center alert-dismissible fade show">
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success text-center alert-dismissible fade show">
                    {{ session('success') }}
                </div>
            @endif
            <form action="{{ route('registerakun') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name" class="small font-weight-bold">Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name" />
                </div>
                <div class="form-group">
                    <label for="email" class="small font-weight-bold">Email</label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="Enter your email" />
                </div>
                <div class="form-group">
                    <label for="username" class="small font-weight-bold">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" />
                </div>
                <div class="form-group">
                    <label for="password" class="small font-weight-bold">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" />
                </div>
                <button type="submit" class="btn btn-login btn-block mt-2 text-white">Create Account</button>
                <div class="text-center mt-3">
                    <small>Already have an account? <a href="/" class="text-white font-weight-bold">Login</a></small>
                </div>
            </form>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        setTimeout(function () {
            $('.alert').fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 2000);
    </script>
</body>
</html>