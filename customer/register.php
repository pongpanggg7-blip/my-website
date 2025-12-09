<?php
require_once '../config/database.php';

// ถ้าล็อกอินแล้วให้ไปหน้า index
if (isset($_SESSION['customer_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - S-PLUS Computer</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h2>🌸 สมัครสมาชิก 🌸</h2>
            <p style="text-align: center; color: var(--text-light); margin-bottom: 30px;">
                S-PLUS Computer Service
            </p>
            
            <form id="registerForm" method="POST">
                <div class="form-group">
                    <label for="username">ชื่อผู้ใช้ *</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">รหัสผ่าน *</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">ยืนยันรหัสผ่าน *</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="fullname">ชื่อ-นามสกุล *</label>
                    <input type="text" id="fullname" name="fullname" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">เบอร์โทรศัพท์ *</label>
                    <input type="tel" id="phone" name="phone" class="form-control" required pattern="[0-9]{10}" placeholder="0812345678">
                </div>
                
                <div class="form-group">
                    <label for="email">อีเมล</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="example@email.com">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">สมัครสมาชิก</button>
            </form>
            
            <div class="auth-links">
                มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสผ่านไม่ตรงกัน',
                    text: 'กรุณากรอกรหัสผ่านให้ตรงกันทั้งสองช่อง',
                    confirmButtonColor: '#ff69b4'
                });
                return;
            }
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('../actions/customer_register.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'สมัครสมาชิกสำเร็จ!',
                        text: 'กำลังพาคุณไปหน้าเข้าสู่ระบบ...',
                        confirmButtonColor: '#ff69b4',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: result.message,
                        confirmButtonColor: '#ff69b4'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้',
                    confirmButtonColor: '#ff69b4'
                });
            }
        });
    </script>
</body>
</html>