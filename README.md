Aplikasi Web Studio Booking Musik berbasis PHP Native dan MySQL yang dibuat sebagai proyek latihan CRUD (Create, Read, Update, Delete) dengan implementasi REST API sederhana. Aplikasi ini digunakan untuk mengelola data reservasi studio musik melalui endpoint API yang berkomunikasi menggunakan format JSON.

Proyek ini dikembangkan sebagai bagian dari tugas UAS dengan fokus pada backend development, pengelolaan database relasional, serta pemahaman dasar arsitektur RESTful API.

Fitur Utama
CRUD data reservasi studio
REST API menggunakan metode HTTP:
GET – mengambil data reservasi
POST – menambahkan reservasi
PUT – memperbarui reservasi
DELETE – menghapus reservasi
Response API berbasis JSON
Dashboard admin sederhana
Validasi parameter dasar
Relasi database menggunakan Foreign Key

Teknologi yang Digunakan
PHP Native
MySQL
Apache (XAMPP)
REST API
JSON
Chrome API Tester / Postman

Struktur Database
Database menggunakan MySQL dengan tabel utama:
reservasi
studio
Tabel reservasi memiliki relasi ke tabel studio melalui kolom id_studio (Foreign Key).

Endpoint API
Base URL:
http://localhost/StudioBooking/API/api_reservasi.php

GET – Ambil Semua Reservasi
GET /api_reservasi.php

POST – Tambah Reservasi
POST /api_reservasi.php

Body (x-www-form-urlencoded):

nama_penyewa
id_studio
tanggal
jam_mulai
durasi
total_harga

PUT – Update Reservasi
PUT /api_reservasi.php

Body (raw JSON):

{
  "id_reservasi": 1,
  "nama_penyewa": "Update Nama",
  "durasi": 3,
  "total_harga": 300000,
  "status": "aktif"
}

DELETE – Hapus Reservasi
DELETE /api_reservasi.php

Body (raw JSON):

{
  "id_reservasi": 1
}

Cara Menjalankan Proyek
Clone repository ini
Pindahkan folder ke direktori htdocs
Import database db_studio ke MySQL
Jalankan Apache & MySQL melalui XAMPP
Akses API menggunakan Postman atau Chrome API Tester

Catatan
Proyek ini menggunakan PHP Native tanpa framework
Validasi dan keamanan masih bersifat dasar
Digunakan untuk keperluan pembelajaran dan tugas akademik

Author
Mochamad Januar Sugiarto, Mochamad Fathur Rahman, Nugroho Riziq Darmawan Nata Saputra, M. Affandi, Bayu Riyanto
Proyek UAS – CRUD & REST API
