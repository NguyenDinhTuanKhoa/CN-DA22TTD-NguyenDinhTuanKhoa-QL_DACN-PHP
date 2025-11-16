<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Quản lý Đồ án</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #0626a7b3 0%, #037bcaff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 450px;
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(253, 207, 23, 1);
        }
            .login-header {
        background: linear-gradient(135deg, #c4ff23ff 0%, #888c14ff 20%, #25fd34ff 45%, #2600ffff 100%);
        color: white;
        padding: 30px;
        border-radius: 15px 15px 0 0;
        text-align: center;
    }

    </style>
</head>
<body>
    <div class="login-card card">
        <div class="login-header">
            <img src="/PHP-CN/public/images/logoTVU.png" alt="Logo TVU" style="width: 100px; height: 100px; background: white; border-radius: 50%; padding: 10px; margin-bottom: 15px;">
            <h3 class="mt-2">Quản lý Đồ án Chuyên Ngành CNTT</h3>
            <p class="mb-0">Trường Đại học Trà Vinh</p>
        </div>
        <div class="card-body p-4">
            <?php if (isset($data['error'])): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= $data['error'] ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/PHP-CN/public/login">
                <div class="mb-3">
                    <label class="form-label">Tên đăng nhập</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required autofocus>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                </button>
            </form>
            
            <hr class="my-4">
            
            <div class="text-center">
                <small class="text-muted">
                    <strong>Nếu Quên mật khẩu xin Liên hệ admin để cấp lại  </strong>    
                </small>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>