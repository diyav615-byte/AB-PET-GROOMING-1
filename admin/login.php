<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';

// Check for timeout message
if (isset($_GET['timeout'])) {
    $error = 'Session expired. Please login again.';
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // For demo purposes, using hardcoded credentials
        // In production, use proper database with hashed passwords
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['admin_id'] = 1;
            $_SESSION['admin_username'] = 'admin';
            $_SESSION['last_activity'] = time();
            header("Location: dashboard.php");
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Pet Grooming</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #E6D9F5 0%, #D4C5E8 100%);
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 50px 40px;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #D4C5E8 0%, #B8A8D8 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 20px;
            color: white;
        }

        .login-header h1 {
            font-size: 28px;
            color: #4A4A4A;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #7A7A7A;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4A4A4A;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #E0D5F0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #B8A8D8;
            box-shadow: 0 0 0 3px rgba(212, 197, 232, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #D4C5E8 0%, #B8A8D8 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(212, 197, 232, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }

        .alert-danger {
            background-color: #F8D7DA;
            color: #721c24;
            border-left-color: #E74C3C;
        }

        .alert-success {
            background-color: #D4EDDA;
            color: #155724;
            border-left-color: #6BBF59;
        }

        .demo-info {
            background-color: #D1ECF1;
            color: #0c5460;
            border-left: 4px solid #3498DB;
            padding: 12px 15px;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 13px;
        }

        .demo-info strong {
            display: block;
            margin-bottom: 5px;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">🐾</div>
            <h1>Pet Grooming</h1>
            <p>Admin Management Panel</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Enter your username" 
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Enter your password" 
                    required
                >
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="demo-info">
            <strong>Demo Credentials:</strong>
            Username: <code>admin</code><br>
            Password: <code>admin123</code>
        </div>
    </div>
</body>
</html>
