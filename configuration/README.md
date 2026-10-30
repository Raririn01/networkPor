# Configuration และเวอร์ชัน PHP

## เวอร์ชันจากผลคำสั่งที่ผู้ดูแลส่งมา (30 ตุลาคม 2026)

| สภาพแวดล้อม | คำสั่งตรวจสอบ | เวอร์ชัน |
| --- | --- | --- |
| WordPress container `alien-buu-docker-wordpress-1` | `sudo docker exec alien-buu-docker-wordpress-1 php -v` | PHP 8.5.12 (CLI) |
| โฮสต์ Alien-VM | `php -v` | PHP 8.3.6 (CLI) |
| Local development | ต้องตรวจใน Local Site Shell | ยังไม่ได้ยืนยัน |

Docker มี PHP ภายใน container แยกจาก PHP บนโฮสต์ จึงมีเวอร์ชันต่างกันได้ ผลข้างต้นเป็นการตรวจ CLI; ไม่ใช่ผลตรวจ PHP ผ่าน HTTP โดยตรง

## ไฟล์ใน repository

- `local/nginx/`: เทมเพลต Nginx สำหรับ Local
- `local/mysql/`: เทมเพลต MySQL โดยแทนรหัสผ่านด้วย `REPLACE_WITH_LOCAL_DATABASE_PASSWORD`
- `local/php/`: เทมเพลต PHP และ PHP-FPM แบบไม่ระบุเวอร์ชัน คัดลอกจาก `conf/php/` ของ Local; ไม่ได้ยืนยันว่าใช้กับ PHP 8.5.12 บนเซิร์ฟเวอร์

นำสำเนา `php-8.2.29/` และ `php-8.2.35/` ออกจากชุดส่งงานแล้ว เพราะเป็นเทมเพลตเก่าของ Local ที่ไม่ตรงกับเวอร์ชันใน container ที่ผู้ดูแลแสดง

Local เป็นผู้แทนค่า `{{...}}` ในเทมเพลตเหล่านี้ ก่อนใช้งานต้องตั้งค่าผ่านสภาพแวดล้อม Local และตรวจการแสดงข้อผิดพลาดกับขีดจำกัดการอัปโหลดให้เหมาะสม

ไฟล์เหล่านี้เป็น configuration ของ Local ส่วน configuration จริงของ Docker/Apache บน production ยังไม่ได้ส่งออกมาใน repository นี้ การแก้ชื่อโฟลเดอร์เป็น PHP 8.5.12 จะไม่ทำให้เทมเพลต Local กลายเป็น configuration จริงของ production

ไม่รวมรหัสผ่านจริง, WordPress salts, private keys หรือฐานข้อมูล
