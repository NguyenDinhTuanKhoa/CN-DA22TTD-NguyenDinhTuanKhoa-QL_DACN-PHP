<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Quản lý Đồ án</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: linear-gradient(135deg, #0766ffff 0%, #0e7490 50%, #155e75 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(14, 116, 144, 0.3) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-50px, -50px); }
        }
        .login-container {
            display: flex;
            max-width: 1000px;
            width: 90%;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.25);
            position: relative;
            z-index: 1;
        }
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #63c8ffff 0%, #1373c2ff 50%, #0615f2a8 100%);
            color: white;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
        }
        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
        }
        .logo-wrapper {
            position: relative;
            z-index: 1;
        }
        .login-left img {
            width: 160px;
            height: 160px;
            background: white;
            border-radius: 50%;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .login-left h3 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .login-left .subtitle {
            font-size: 14px;
            opacity: 0.95;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }
        .login-left .divider {
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #fbbf24, transparent);
            margin: 25px auto;
            border-radius: 2px;
        }
        .login-left .system-info {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 20px 25px;
            border-radius: 16px;
            margin-top: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .login-left .system-info i {
            color: #fbbf24;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .login-left .system-info strong {
            display: block;
            font-size: 16px;
            margin-bottom: 8px;
        }
        .login-left .system-info p {
            font-size: 13px;
            opacity: 0.9;
            margin: 0;
        }
        .login-right {
            flex: 1;
            padding: 60px 50px;
            background: #fafafa;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .login-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        .login-header-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            flex-shrink: 0;
        }
        .login-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        .login-subtitle {
            color: #64748b;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 18px;
            z-index: 2;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 18px;
            cursor: pointer;
            z-index: 2;
            transition: color 0.3s;
        }
        .toggle-password:hover {
            color: #0ea5e9;
        }
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 15px 14px 45px;
            transition: all 0.3s;
            font-size: 15px;
            background: #f8fafc;
        }
        .form-control:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
            background: white;
        }
        .form-control::placeholder {
            color: #cbd5e1;
        }
        .btn-login {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        }
        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #cbd5e1;
            border-radius: 4px;
        }
        .form-check-input:checked {
            background-color: #0ea5e9;
            border-color: #0ea5e9;
        }
        .form-check-label {
            color: #475569;
            font-size: 14px;
            margin-left: 5px;
        }
        .forgot-link {
            color: #0ea5e9;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s;
        }
        .forgot-link:hover {
            color: #0284c7;
        }
        .footer-text {
            color: #94a3b8;
            font-size: 13px;
        }
        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-container { max-width: 480px; }
            .login-right { padding: 40px 30px; }
            .login-card { padding: 30px 25px; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side -->
        <div class="login-left">
            <div class="logo-wrapper">
                <img src="<?= BASE_URL ?>/images/logoTVU.png" alt="Logo TVU">
                <h3>Trường Đại học Trà Vinh</h3>
                <p class="subtitle">TRA VINH UNIVERSITY</p>
                <div class="divider"></div>
                <div class="system-info">
                    <i class="bi bi-mortarboard-fill"></i>
                    <strong>Hệ thống Quản lý Đồ án Chuyên Ngành </strong>
                    <p>Trường Kỹ thuật và Công nghệ</p>
                </div>
            </div>
        </div>
        
        <!-- Right Side -->
        <div class="login-right">
            <div class="login-card">
                <div class="login-header">
                    <div class="login-header-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h2 class="login-title">Đăng nhập</h2>
                </div>
                <p class="login-subtitle">Chào mừng bạn trở lại!</p>
                
                <?php if (isset($data['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?= $data['error'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?= BASE_URL ?>/login">
                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập</label>
                        <div class="input-group-custom">
                            <i class="bi bi-person-fill input-icon"></i>
                            <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required autofocus>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <div class="input-group-custom">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu" required>
                            <i class="bi bi-eye-fill toggle-password" id="togglePassword"></i>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Ghi nhớ đăng nhập</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-login w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Đăng nhập hệ thống
                    </button>
                </form>
                
                <div class="text-center mt-4">
                    <a href="#" class="forgot-link">
                        <i class="bi bi-question-circle"></i> Quên mật khẩu liên hệ Admin để lấy lại mật khẩu
                    </a>
                </div>
                
                <div class="text-center mt-4">
                    <p class="footer-text mb-0">© 2025 Trường Đại học Trà Vinh</p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            this.classList.toggle('bi-eye-fill');
            this.classList.toggle('bi-eye-slash-fill');
        });
    </script>
</body>
</html>
