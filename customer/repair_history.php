<?php
require_once '../config/database.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$customer_name = $_SESSION['customer_name'];

// ดึงประวัติการซ่อมของลูกค้า
$sql = "SELECT * FROM repairs WHERE customer_id = $customer_id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการแจ้งซ่อม - S-PLUS Computer</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .history-card {
            background: var(--white);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px var(--shadow);
            transition: transform 0.3s ease;
        }
        .history-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px var(--shadow);
        }
        .repair-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--soft-pink);
        }
        .repair-number {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--primary-pink);
        }
        .status-badge {
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-inprogress {
            background: #d1ecf1;
            color: #0c5460;
        }
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        .repair-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        .info-item {
            display: flex;
            align-items: start;
            gap: 10px;
        }
        .info-label {
            font-weight: 600;
            color: var(--text-dark);
            min-width: 100px;
        }
        .info-value {
            color: var(--text-light);
        }
        .problem-box {
            background: var(--soft-pink);
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-state img {
            width: 150px;
            opacity: 0.5;
            margin-bottom: 20px;
        }
        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .filter-tab {
            padding: 10px 25px;
            border: 2px solid var(--light-pink);
            background: var(--white);
            color: var(--primary-pink);
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .filter-tab:hover {
            background: var(--soft-pink);
        }
        .filter-tab.active {
            background: linear-gradient(135deg, var(--primary-pink) 0%, var(--light-pink) 100%);
            color: var(--white);
            border-color: var(--primary-pink);
        }
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-box {
            background: linear-gradient(135deg, var(--primary-pink) 0%, var(--light-pink) 100%);
            color: var(--white);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        @media (max-width: 768px) {
            .repair-header {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
            .repair-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>🌸 S-PLUS Computer 🌸</h1>
            <div class="user-info">
                <span>สวัสดี, <?php echo htmlspecialchars($customer_name); ?></span>
                <button onclick="logout()" class="btn-logout">ออกจากระบบ</button>
            </div>
        </div>
    </div>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="color: var(--primary-pink); margin: 0;">📋 ประวัติการแจ้งซ่อม</h2>
            <div style="display: flex; gap: 10px;">
                <a href="index.php" class="btn btn-secondary">← กลับหน้าแรก</a>
                <a href="repair_form.php" class="btn btn-primary">+ แจ้งซ่อมใหม่</a>
            </div>
        </div>

        <?php
        // คำนวณสถิติ
        $total = $result->num_rows;
        $pending = 0;
        $inprogress = 0;
        $completed = 0;
        
        // นับสถานะ
        $result->data_seek(0);
        while($row = $result->fetch_assoc()) {
            switch($row['status']) {
                case 'รอตรวจสอบ': $pending++; break;
                case 'กำลังซ่อม': $inprogress++; break;
                case 'ซ่อมเสร็จ': $completed++; break;
            }
        }
        $result->data_seek(0);
        ?>

        <!-- สถิติสรุป -->
        <div class="stats-summary">
            <div class="stat-box">
                <div class="stat-number"><?php echo $total; ?></div>
                <div class="stat-label">ทั้งหมด</div>
            </div>
            <div class="stat-box" style="background: linear-gradient(135deg, #ffc107 0%, #ffdb4d 100%);">
                <div class="stat-number"><?php echo $pending; ?></div>
                <div class="stat-label">รอตรวจสอบ</div>
            </div>
            <div class="stat-box" style="background: linear-gradient(135deg, #17a2b8 0%, #5bc0de 100%);">
                <div class="stat-number"><?php echo $inprogress; ?></div>
                <div class="stat-label">กำลังซ่อม</div>
            </div>
            <div class="stat-box" style="background: linear-gradient(135deg, #28a745 0%, #5cb85c 100%);">
                <div class="stat-number"><?php echo $completed; ?></div>
                <div class="stat-label">ซ่อมเสร็จ</div>
            </div>
        </div>

        <!-- ตัวกรองสถานะ -->
        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterStatus('all')">ทั้งหมด (<?php echo $total; ?>)</button>
            <button class="filter-tab" onclick="filterStatus('รอตรวจสอบ')">รอตรวจสอบ (<?php echo $pending; ?>)</button>
            <button class="filter-tab" onclick="filterStatus('กำลังซ่อม')">กำลังซ่อม (<?php echo $inprogress; ?>)</button>
            <button class="filter-tab" onclick="filterStatus('ซ่อมเสร็จ')">ซ่อมเสร็จ (<?php echo $completed; ?>)</button>
        </div>

        <!-- รายการซ่อม -->
        <div id="repairList">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <?php
                    $status_class = '';
                    $status_icon = '';
                    switch($row['status']) {
                        case 'รอตรวจสอบ':
                            $status_class = 'status-pending';
                            $status_icon = '⏳';
                            break;
                        case 'กำลังซ่อม':
                            $status_class = 'status-inprogress';
                            $status_icon = '🔧';
                            break;
                        case 'ซ่อมเสร็จ':
                            $status_class = 'status-completed';
                            $status_icon = '✅';
                            break;
                    }
                    ?>
                    <div class="history-card" data-status="<?php echo htmlspecialchars($row['status']); ?>">
                        <div class="repair-header">
                            <div class="repair-number">
                                <?php echo htmlspecialchars($row['repair_number']); ?>
                            </div>
                            <span class="status-badge <?php echo $status_class; ?>">
                                <?php echo $status_icon . ' ' . htmlspecialchars($row['status']); ?>
                            </span>
                        </div>

                        <div class="repair-info">
                            <div class="info-item">
                                <span class="info-label">📅 วันที่แจ้ง:</span>
                                <span class="info-value"><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?> น.</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">💻 ประเภท:</span>
                                <span class="info-value"><?php echo htmlspecialchars($row['device_type']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">🏷️ ยี่ห้อ:</span>
                                <span class="info-value">
                                    <?php 
                                    $brand_model = [];
                                    if (!empty($row['device_brand'])) $brand_model[] = $row['device_brand'];
                                    if (!empty($row['device_model'])) $brand_model[] = $row['device_model'];
                                    echo htmlspecialchars(implode(' ', $brand_model)) ?: '-';
                                    ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">📞 เบอร์โทร:</span>
                                <span class="info-value"><?php echo htmlspecialchars($row['phone']); ?></span>
                            </div>
                        </div>

                        <div class="problem-box">
                            <strong style="color: var(--primary-pink);">🔍 อาการเสีย:</strong><br>
                            <p style="margin-top: 10px; color: var(--text-dark); line-height: 1.6;">
                                <?php echo nl2br(htmlspecialchars($row['problem_description'])); ?>
                            </p>
                        </div>

                        <?php if ($row['updated_at'] != $row['created_at']): ?>
                            <div style="margin-top: 10px; font-size: 0.85rem; color: var(--text-light); text-align: right;">
                                <em>อัพเดทล่าสุด: <?php echo date('d/m/Y H:i', strtotime($row['updated_at'])); ?> น.</em>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div style="font-size: 5rem; margin-bottom: 20px;">📭</div>
                    <h3 style="color: var(--primary-pink); margin-bottom: 15px;">ยังไม่มีประวัติการแจ้งซ่อม</h3>
                    <p style="color: var(--text-light); margin-bottom: 25px;">
                        คุณยังไม่เคยแจ้งซ่อมอุปกรณ์กับเรา<br>
                        เริ่มต้นแจ้งซ่อมเลยตอนนี้!
                    </p>
                    <a href="repair_form.php" class="btn btn-primary">📝 แจ้งซ่อมเลย</a>
                </div>
            <?php endif; ?>
        </div>

        <div id="noResults" style="display: none;" class="empty-state">
            <div style="font-size: 4rem; margin-bottom: 15px;">🔍</div>
            <h3 style="color: var(--primary-pink);">ไม่พบรายการในสถานะนี้</h3>
            <p style="color: var(--text-light); margin-top: 10px;">
                ลองเลือกสถานะอื่นดูครับ
            </p>
        </div>
    </div>

 

    <script>
        function logout() {
            Swal.fire({
                title: 'ออกจากระบบ?',
                text: 'คุณต้องการออกจากระบบหรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ff69b4',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ออกจากระบบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        }

        function filterStatus(status) {
            const cards = document.querySelectorAll('.history-card');
            const tabs = document.querySelectorAll('.filter-tab');
            const noResults = document.getElementById('noResults');
            let visibleCount = 0;

            // อัพเดทสถานะปุ่ม
            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');

            // กรองการ์ด
            cards.forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // แสดงข้อความถ้าไม่พบ
            if (visibleCount === 0) {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        }
    </script>
</body>
</html>