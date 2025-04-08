<?php
require_once 'config/database.php';
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: account.php');
    exit;
}

// Special registration code for managers/admins
$special_reg_code = 'HONKAI_MANAGER_2024'; // Change this to a strong secret code
$role = 'customer'; // Default role

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $registration_code = trim($_POST['registration_code'] ?? '');

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } else {
        // Check for special registration code
        if (!empty($registration_code) && $registration_code === $special_reg_code) {
            $role = 'manager'; // Upgrade to manager role
        }

        $db = new Database();
        $conn = $db->connect();

        // Check if email or username exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        
        if ($stmt->rowCount() > 0) {
            $error = 'Email or username already registered';
        } else {
            // Create account with role
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password, $role]);
            
            // Auto-login
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['username'] = $username;
            $_SESSION['user_role'] = $role;
            
            // Redirect based on role
            if ($role === 'manager') {
                header('Location: /honkaishop/admin/manager.php');
            } else {
                header('Location: account.php');
            }
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | HonkaiShop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --space-purple: #2A2356;
            --star-cyan: #4ED8D8;
            --nebula-pink: #FF69B4;
            --dark-space: #0f0f1a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(rgba(42, 35, 86, 0.9), rgba(42, 35, 86, 0.7)), 
                        url('/honkaishop/assets/images/space-bg.jpg') center/cover no-repeat;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .auth-container {
            display: grid;
            grid-template-columns: 1fr;
            width: 100%;
            max-width: 1200px;
            margin: 2rem;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }

        .auth-card {
            background: rgba(15, 15, 26, 0.85);
            padding: 2rem;
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(78, 216, 216, 0.2);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo {
            height: 70px;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 0 5px var(--star-cyan));
        }

        .auth-header h1 {
            color: var(--star-cyan);
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .auth-header p {
            color: rgba(255,255,255,0.8);
            font-size: 1.1rem;
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .input-group label {
            color: var(--star-cyan);
            font-weight: 600;
            font-size: 0.95rem;
        }

        .input-icon {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon i {
            position: absolute;
            left: 15px;
            color: var(--star-cyan);
        }

        .input-icon input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            background: rgba(255,255,255,0.1);
            border: 2px solid rgba(78, 216, 216, 0.3);
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-icon input:focus {
            outline: none;
            border-color: var(--star-cyan);
            box-shadow: 0 0 0 3px rgba(78, 216, 216, 0.2);
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            background: none;
            border: none;
            color: var(--star-cyan);
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: var(--star-cyan);
            color: var(--space-purple);
            padding: 14px 24px;
            border: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(78, 216, 216, 0.4);
        }

        .auth-footer {
            text-align: center;
            padding-top: 1.5rem;
            margin-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .auth-link {
            color: var(--star-cyan);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        /* Registration code specific styles */
        .registration-code-group {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(78, 216, 216, 0.3);
        }

        .registration-code-group label {
            color: var(--star-cyan);
            font-weight: bold;
            display: block;
            margin-bottom: 0.5rem;
        }

        .registration-code-group .hint {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.6);
            margin-top: 0.5rem;
        }

        /* Alert styles */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.3s ease;
        }

        .alert.error {
            background: rgba(231, 76, 60, 0.2);
            border-left: 4px solid #e74c3c;
            color: #ff6b6b;
        }

        /* Auth image styling */
        .auth-image {
            display: none;
            background-size: cover;
            background-position: center;
        }

        @media (min-width: 992px) {
            .auth-container {
                grid-template-columns: 1fr 1fr;
            }
            .auth-card {
                padding: 3rem;
            }
            .auth-image {
                display: block;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <img src="/honkaishop/assets/images/honkai-logo.png" alt="HonkaiShop" class="auth-logo">
                <h1>Join The Journey</h1>
                <p>Create your account to start shopping</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="input-group">
                    <label for="username">Username</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" required>
                    </div>
                </div>
                
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                
                <div class="input-group">
                    <label for="password">Password (8+ characters)</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required>
                        <button type="button" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                
                <!-- Hidden manager registration field -->
                <div class="registration-code-group" id="registrationCodeGroup" style="display: none;">
                    <label for="registration_code">Manager Registration Code</label>
                    <div class="input-icon">
                        <i class="fas fa-key"></i>
                        <input type="password" id="registration_code" name="registration_code">
                    </div>
                    <p class="hint">Only for authorized personnel</p>
                </div>
                
                <div class="auth-actions">
                    <button type="submit" class="btn btn-primary btn-auth">
                        <i class="fas fa-user-plus"></i> Register
                    </button>
                </div>
            </form>
            
            <div class="auth-footer">
                <p>Already have an account? <a href="login.php" class="auth-link">Log in</a></p>
                <p>
                    <a href="#" id="toggleManagerReg" style="color: var(--star-cyan); font-size: 0.9rem;">
                        <i class="fas fa-user-shield"></i> Manager Registration
                    </a>
                </p>
            </div>
        </div>
        
        <div class="auth-image" style="background-image: url('/honkaishop/assets/images/register-bg.jpg')"></div>
    </div>

    <script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        });
    });

    // Toggle manager registration field
    document.getElementById('toggleManagerReg').addEventListener('click', function(e) {
        e.preventDefault();
        const codeGroup = document.getElementById('registrationCodeGroup');
        if (codeGroup.style.display === 'none') {
            codeGroup.style.display = 'block';
            this.innerHTML = '<i class="fas fa-user-shield"></i> Hide Manager Registration';
        } else {
            codeGroup.style.display = 'none';
            this.innerHTML = '<i class="fas fa-user-shield"></i> Manager Registration';
        }
    });

    // Add animation to form elements
    document.querySelectorAll('.input-group').forEach((group, index) => {
        group.style.animation = `fadeIn 0.3s ease ${index * 0.1}s forwards`;
        group.style.opacity = '0';
    });
    </script>
</body>
</html>
