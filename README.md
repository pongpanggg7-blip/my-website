# 🌸 ระบบจัดการร้าน S-PLUS Computer

## ✨ ฟีเจอร์หลัก

### 👥 สำหรับลูกค้า (Customer)
- สมัครสมาชิกและเข้าสู่ระบบ
- แจ้งซ่อมอุปกรณ์คอมพิวเตอร์
- รับเลขที่ซ่อมเพื่อติดตามสถานะ


### 🔐 สำหรับแอดมิน (Admin)
- เข้าสู่ระบบแอดมิน
- Dashboard แสดงสถิติรายการซ่อม
- จัดการรายการซ่อมทั้งหมด
- เพิ่มรายการซ่อมใหม่
- แก้ไข/ลบรายการซ่อม
- ค้นหารายการซ่อม
- อัพเดทสถานะ (รอตรวจสอบ, กำลังซ่อม, ซ่อมเสร็จ)

### ภาษาที่เขียน
- PHP
- MySQL
- JavaScript
- HTML5 & CSS3
- SweetAlert2
- Responsive Design

## 📁 โครงสร้างไฟล์

```
s-plus-computer/
├── config/
│   └── database.php           # การเชื่อมต่อฐานข้อมูล
├── css/
│   ├── style.css             # css Frontend
│   └── admin_style.css       # css Backend
├── customer/                  # ในส่วนของลูกค้า
│   ├── register.php          # สมัครสมาชิก
│   ├── login.php             # เข้าสู่ระบบ
│   ├── index.php             # หน้าแรก
│   ├── repair_form.php       # ฟอร์มแจ้งซ่อม
│   └── logout.php            # ออกจากระบบ
├── admin/                     # ในส่วนของแอดมิน (เจ้าของร้าน)
│   ├── login.php             # เข้าสู่ระบบแอดมิน
│   ├── dashboard.php         # แดชบอร์ด
│   ├── repair_list.php       # รายการซ่อม
│   ├── add_repair.php        # เพิ่มรายการ
│   ├── edit_repair.php       # แก้ไขรายการ
│   └── logout.php            # ออกจากระบบ
├── actions/                   # ประมวลผล Backend
│   ├── customer_register.php
│   ├── customer_login.php
│   ├── admin_login.php
│   ├── submit_repair.php
│   └── manage_repair.php
└── database/
    └── splus_computer.sql    # ไฟล์ SQL
```



#### เปิดใช้งาน
- หน้าลูกค้า: http://localhost/s-plus-computer/customer/
- หน้าแอดมิน: http://localhost/s-plus-computer/admin/

## 🔑 ข้อมูลเข้าสู่ระบบเริ่มต้น

### Admin
- **Username:** admin
- **Password:** admin123

### Customer (ตัวอย่าง)
- **Username:** customer
- **Password:** customer123

⚠️

### ตาราง: repairs
- ข้อมูลรายการซ่อม
- เก็บเลขที่ซ่อม, ข้อมูลลูกค้า, รายละเอียดอุปกรณ์, อาการเสีย, สถานะ

## 🎨 ธีมสี

- **Primary Pink:** #ff69b4
- **Light Pink:** #ffb6d9
- **Soft Pink:** #ffe4f0
- **White:** #ffffff
- **Text Dark:** #333333

## ⚙️ ฟีเจอร์พิเศษ

✅ เลขที่ซ่อมอัตโนมัติ (R2024001, R2024002...)  
✅ แจ้งเตือนด้วย SweetAlert2  
✅ Responsive Design รองรับมือถือ  
✅ ป้องกัน SQL Injection  
✅ Hash รหัสผ่านด้วย PHP password_hash  
✅ Session Management ที่ปลอดภัย  

## 🔒 ความปลอดภัย

- รหัสผ่านถูกเข้ารหัสด้วย `password_hash()`
- ป้องกัน SQL Injection ด้วยฟังก์ชัน `clean_input()`
- ตรวจสอบ Session ทุกหน้า
- XSS Protection ด้วย `htmlspecialchars()`

## 📝 การใช้งาน

### สำหรับลูกค้า
1. สมัครสมาชิกที่หน้า Register
2. เข้าสู่ระบบ
3. กดปุ่ม "แจ้งซ่อมเลย"
4. กรอกข้อมูลอุปกรณ์และอาการเสีย
5. รับเลขที่ซ่อมเพื่อติดตามงาน

### สำหรับแอดมิน
1. เข้าสู่ระบบที่หน้า Admin
2. ดู Dashboard สรุปสถิติ
3. จัดการรายการซ่อมใน "รายการซ่อม"
4. เพิ่มรายการใหม่ได้จากปุ่ม "เพิ่มรายการ"
5. แก้ไข/ลบ รายการได้จากตาราง

