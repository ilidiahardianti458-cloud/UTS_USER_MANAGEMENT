# User Management - Admin Gudang (PHP + MySQL)

Panduan singkat:

1. Salin folder `usermanagement/` ke `htdocs/` (XAMPP).
2. Akses di browser:
   - `http://localhost/usermanagement/dev/create_db.php`
   - `http://localhost/usermanagement/dev/create_users_table.php`
   - `http://localhost/usermanagement/dev/create_products_table.php`
3. Buka `http://localhost/usermanagement/` (akan redirect ke login).
4. Registrasi lewat `auth/register.php`. Sistem akan kirim email aktivasi (atau menampilkan link jika mail() tidak terkonfigurasi).
5. Setelah aktif, login dan akses dashboard untuk CRUD produk dan ubah profil/password.

Catatan:
- Untuk pengiriman email nyata (Gmail/SMTP) disarankan menggunakan PHPMailer/SMTP. Jika mau, saya bantu ubah.
- Pastikan `db_name.txt` dibuat di folder `dev/` (create_db.php membuatnya).
