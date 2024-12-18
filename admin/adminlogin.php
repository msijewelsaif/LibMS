<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="adminCSS/admin_login.css">
    <style>
        /* Add loading spinner styles */
        .spinner-border {
            display: none; /* Initially hidden */
            width: 1.5rem;
            height: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="card shadow" style="width: 400px;">
            <div class="card-body">
                <h2 class="text-center mb-4">Login</h2>
                <form id="loginForm" action="login_process.php" method="POST" onsubmit="showLoader()">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        Login
                        <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>
                    </button>
                </form>
            </div>
            <div class="card-footer text-center">
                <small>Don't have an account? <a href="register.php">Register here</a></small>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showLoader() {
            const button = document.querySelector('button[type="submit"]');
            const spinner = button.querySelector('.spinner-border');
            button.disabled = true; // Disable the button
            spinner.style.display = 'inline-block'; // Show the loading spinner
        }
    </script>
</body>
</html>
