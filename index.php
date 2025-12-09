<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S-PLUS Computer - ทดสอบระบบ</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ffe4f0 0%, #ffffff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 105, 180, 0.2);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #ff69b4;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
        }
        .status {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        .links {
            display: grid;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            display: block;
            padding: 15px 20px;
            background: linear-gradient(135deg, #ff69b4 0%, #ffb6d9 100%);
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 105, 180, 0.3);
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 105, 180, 0.4);
        }
        .info {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            border-left: 4px solid #ffc107;
        }
        .check-list {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .check-item {
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .check-item:last-child {
            border-bottom: none;
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌸 S-PLUS Computer 🌸</h1>
        
        <div class="links">
            <a href="customer/login.php" class="btn">👤 เข้าสู่ระบบลูกค้า</a>
            <a href="customer/register.php" class="btn">📝 สมัครสมาชิกลูกค้า</a>
            <a href="admin/login.php" class="btn">🔐 เข้าสู่ระบบแอดมิน</a>
        </div>

       
    </div>
</body>
</html>
