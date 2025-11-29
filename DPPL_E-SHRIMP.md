# DOKUMENTASI PERANCANGAN PERANGKAT LUNAK (DPPL)
## SISTEM MONITORING BUDIDAYA UDANG E-SHRIMP

---

**Versi Dokumen**: 2.0  
**Tanggal**: 2025  
**Status**: Revisi dan Perbaikan  
**Penulis**: Tim Pengembang E-SHRIMP

---

## DAFTAR ISI

1. [Pendahuluan](#1-pendahuluan)
2. [Analisis Sistem](#2-analisis-sistem)
   - 2.1 [Analisis Kebutuhan Fungsional](#21-analisis-kebutuhan-fungsional)
   - 2.2 [Perancangan Data](#22-perancangan-data)
     - 2.2.1 [Dekomposisi Data](#221-dekomposisi-data)
     - 2.2.2 [Dekomposisi Fungsional](#222-dekomposisi-fungsional)
     - 2.2.3 [Struktur Data dan Relasi](#223-struktur-data-dan-relasi)
   - 2.3 [Analisis Kebutuhan Non-Fungsional](#23-analisis-kebutuhan-non-fungsional)
   - 2.4 [Analisis Stakeholder](#24-analisis-stakeholder)
3. [Perancangan Sistem](#3-perancangan-sistem)
4. [Perancangan Database](#4-perancangan-database)
5. [Perancangan Antarmuka](#5-perancangan-antarmuka)
6. [Perancangan Keamanan](#6-perancangan-keamanan)
7. [Implementasi](#7-implementasi)
8. [Pengujian](#8-pengujian)
9. [Dokumentasi API](#9-dokumentasi-api)
10. [Lampiran](#10-lampiran)

---

## 1. PENDAHULUAN

### 1.1 Latar Belakang
Budidaya udang merupakan salah satu sektor perikanan yang memiliki potensi ekonomi tinggi di Indonesia. Namun, peternak udang sering menghadapi tantangan dalam memantau kualitas air kolam secara real-time, yang dapat mempengaruhi produktivitas dan kesehatan udang. Sistem E-SHRIMP dirancang untuk mengatasi masalah ini dengan menyediakan solusi monitoring berbasis IoT dan web application.

### 1.2 Tujuan
1. Membangun sistem monitoring kualitas air kolam budidaya udang berbasis web
2. Mengintegrasikan teknologi IoT dengan robot kapal monitoring
3. Menyediakan prediksi pertumbuhan udang menggunakan machine learning
4. Memfasilitasi manajemen kolam dan kapal monitoring
5. Menyediakan platform informasi dan komunitas untuk peternak

### 1.3 Ruang Lingkup
Sistem ini mencakup:
- Web application untuk monitoring real-time
- Integrasi dengan Firebase untuk autentikasi dan real-time database
- Machine learning untuk prediksi pertumbuhan
- Manajemen data sensor dan monitoring sessions
- Sistem notifikasi dan alert
- Price tracker untuk harga udang
- Forum komunitas dan artikel edukasi

### 1.4 Metodologi
- **Framework**: Laravel 12.0 (Backend)
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL
- **Authentication**: Firebase Authentication + Firebase Realtime Database
- **ML**: Python + Scikit-learn

---

## 2. ANALISIS SISTEM

### 2.1 Analisis Kebutuhan Fungsional

#### 2.1.1 Kebutuhan Peternak
1. **Monitoring Real-time**
   - Memantau parameter kualitas air (pH, suhu, oksigen, salinitas)
   - Visualisasi data dalam bentuk grafik
   - Notifikasi ketika parameter melebihi threshold

2. **Manajemen Kolam**
   - Menambah/menghapus kolam
   - Melihat history monitoring per kolam
   - Statistik kolam

3. **Manajemen Kapal**
   - Menambah/mengedit/menghapus robot kapal
   - Mengelola monitoring sessions
   - Pause/Resume/Restart monitoring

4. **Prediksi Pertumbuhan**
   - Input data sensor
   - Prediksi berat udang, laju pertumbuhan, kebutuhan pakan
   - Rekomendasi berdasarkan prediksi

5. **Informasi dan Komunitas**
   - Membaca artikel edukasi
   - Berpartisipasi dalam forum diskusi
   - Melihat harga udang di pasar

#### 2.1.2 Kebutuhan Admin
1. **Manajemen User**
   - Melihat daftar peternak
   - Melihat detail peternak
   - Menghapus user

2. **Manajemen Konten**
   - Mengelola artikel
   - Mengelola forum posts
   - Moderasi konten

### 2.2 Perancangan Data

#### 2.2.1 Dekomposisi Data

Dekomposisi data dilakukan untuk mengidentifikasi entitas-entitas data utama dalam sistem E-SHRIMP beserta atribut-atributnya. Berikut adalah dekomposisi data berdasarkan analisis kebutuhan sistem:

##### 1. Entitas Peternak (peternaks)
**Deskripsi**: Menyimpan data pengguna peternak yang menggunakan sistem.

**Atribut**:
- `email_peternak` (Primary Key): Email unik sebagai identifier peternak
- `nama`: Nama lengkap peternak
- `password`: Password untuk autentikasi (disimpan di Firebase RDTB)
- `tracker_id`: ID tracker untuk tracking
- `role`: Role user (default: 'peternak')
- `created_at`, `updated_at`: Timestamp untuk audit trail

**Kardinalitas**: 
- Satu peternak dapat memiliki banyak kolam (1:N)
- Satu peternak dapat memiliki banyak robot kapal (1:N)

##### 2. Entitas Admin (admins)
**Deskripsi**: Menyimpan data administrator sistem.

**Atribut**:
- `admin_id` (Primary Key): ID unik admin
- `nama`: Nama lengkap admin
- `email`: Email admin
- `password`: Password untuk autentikasi (disimpan di Firebase Auth)
- `role`: Role user (default: 'admin')
- `created_at`, `updated_at`: Timestamp untuk audit trail

**Kardinalitas**: 
- Admin mengelola banyak peternak (1:N)
- Admin mengelola banyak artikel (1:N)
- Admin mengelola banyak forum posts (1:N)

##### 3. Entitas Robot Kapal (robot_kapal_eshrimps)
**Deskripsi**: Menyimpan data robot kapal monitoring yang dimiliki peternak.

**Atribut**:
- `robot_id` (Primary Key): ID unik robot kapal
- `email_peternak` (Foreign Key): Email pemilik kapal
- `status`: Status kapal (idle, active, maintenance)
- `lokasi`: Lokasi fisik kapal
- `created_at`, `updated_at`: Timestamp untuk audit trail

**Kardinalitas**: 
- Satu peternak memiliki banyak robot kapal (1:N)
- Satu robot kapal menghasilkan banyak data sensor (1:N)
- Satu robot kapal dapat memiliki banyak monitoring session (1:N)

##### 4. Entitas Kolam (dashboard_monitorings)
**Deskripsi**: Menyimpan data kolam budidaya udang milik peternak.

**Atribut**:
- `kolam_id` (Primary Key): ID unik kolam
- `email_peternak` (Foreign Key): Email pemilik kolam
- `foto_posisi_kapal`: URL atau path foto posisi kapal
- `treshold_id`: Referensi ke threshold (optional)
- `notif_id`: Referensi ke notifikasi (optional)
- `data_id`: Referensi ke data sensor (optional)
- `created_at`, `updated_at`: Timestamp untuk audit trail

**Kardinalitas**: 
- Satu peternak memiliki banyak kolam (1:N)
- Satu kolam memiliki banyak data sensor (1:N)
- Satu kolam memiliki banyak threshold (1:N)
- Satu kolam memiliki banyak notifikasi (1:N)
- Satu kolam dapat memiliki banyak monitoring session (1:N)

##### 5. Entitas Data Sensor (sensor_data)
**Deskripsi**: Menyimpan data sensor kualitas air yang dikumpulkan dari robot kapal.

**Atribut**:
- `id` (Primary Key): Auto increment ID
- `robot_id` (Foreign Key): ID robot kapal yang mengumpulkan data
- `kolam_id` (Foreign Key): ID kolam yang dimonitor
- `umur_budidaya`: Umur budidaya dalam hari
- `waktu`: Timestamp pengambilan data
- `ph`: Nilai pH air (DECIMAL 5,2)
- `suhu`: Suhu air dalam °C (DECIMAL 5,2)
- `oksigen`: Oksigen terlarut dalam mg/L (DECIMAL 5,2)
- `salinitas`: Salinitas dalam ppt (DECIMAL 5,2)
- `kualitas_air`: Kualitas air (Sangat Baik, Baik, Cukup, Buruk)
- `created_at`, `updated_at`: Timestamp untuk audit trail

**Kardinalitas**: 
- Satu robot kapal menghasilkan banyak data sensor (1:N)
- Satu kolam memiliki banyak data sensor (1:N)

##### 6. Entitas Monitoring Session (monitoring_sessions)
**Deskripsi**: Menyimpan data session monitoring yang sedang berjalan atau telah selesai.

**Atribut**:
- `session_id` (Primary Key): Auto increment ID session
- `kolam_id` (Foreign Key): ID kolam yang dimonitor
- `nama_kapal` (Foreign Key): ID robot kapal yang digunakan
- `umur_budidaya`: Umur budidaya saat monitoring dimulai
- `threshold_suhu`: JSON string threshold suhu (min, max)
- `threshold_ph`: JSON string threshold pH (min, max)
- `threshold_oksigen`: JSON string threshold oksigen (min, max)
- `threshold_salinitas`: JSON string threshold salinitas (min, max)
- `timer_monitoring`: Interval monitoring dalam detik (default: '10')
- `mulai_monitoring`: Timestamp mulai monitoring
- `selesai_monitoring`: Timestamp selesai monitoring (nullable)
- `is_active`: Status aktif monitoring (boolean, default: true)
- `is_paused`: Status pause monitoring (boolean, default: false)
- `total_paused_seconds`: Total waktu pause dalam detik (default: 0)
- `created_at`, `updated_at`: Timestamp untuk audit trail

**Kardinalitas**: 
- Satu kolam dapat memiliki banyak monitoring session (1:N)
- Satu robot kapal dapat memiliki banyak monitoring session (1:N)

##### 7. Entitas Threshold (thresholds)
**Deskripsi**: Menyimpan batas nilai threshold untuk setiap parameter sensor per kolam.

**Atribut**:
- `threshold_id` (Primary Key): Auto increment ID
- `kolam_id` (Foreign Key): ID kolam
- `sensor_tipe`: Tipe sensor (ph, suhu, oksigen, salinitas)
- `nilai`: Nilai threshold (DECIMAL)
- `timer`: Timestamp update threshold
- `created_at`, `updated_at`: Timestamp untuk audit trail

**Kardinalitas**: 
- Satu kolam memiliki banyak threshold (1:N)

##### 8. Entitas Notifikasi (notifikasis)
**Deskripsi**: Menyimpan notifikasi alert ketika parameter sensor melebihi threshold.

**Atribut**:
- `notifikasi_id` (Primary Key): Auto increment ID
- `kolam_id` (Foreign Key): ID kolam
- `nama_kapal`: Nama robot kapal yang trigger notifikasi
- `pesan`: Pesan notifikasi
- `waktu`: Timestamp notifikasi
- `status`: Status read/unread (boolean, false = unread)
- `created_at`, `updated_at`: Timestamp untuk audit trail

**Kardinalitas**: 
- Satu kolam memiliki banyak notifikasi (1:N)

##### 9. Entitas Forum (forums)
**Deskripsi**: Menyimpan post dan reply di forum diskusi komunitas.

**Atribut**:
- `forum_id` (Primary Key): ID unik forum post
- `judul`: Judul post
- `isi`: Isi/konten post (TEXT)
- `tanggal`: Tanggal post
- `email_peternak` (Foreign Key): Email pembuat post (nullable)
- `post_peternak_id`: ID post parent untuk reply (nullable)
- `created_at`, `updated_at`: Timestamp untuk audit trail

**Kardinalitas**: 
- Satu peternak dapat membuat banyak post (1:N)
- Satu post dapat memiliki banyak reply (1:N, self-referencing)

##### 10. Entitas Artikel (artikels)
**Deskripsi**: Menyimpan artikel edukasi yang dibuat oleh admin.

**Atribut**:
- `artikel_id` (Primary Key): ID unik artikel
- `judul`: Judul artikel
- `konten`: Konten artikel (TEXT)
- `tanggal`: Tanggal publikasi
- `created_at`, `updated_at`: Timestamp untuk audit trail

**Kardinalitas**: 
- Admin membuat banyak artikel (1:N)

##### 11. Entitas Harga Udang (harga_udang)
**Deskripsi**: Menyimpan data harga udang berbagai ukuran dari berbagai sumber.

**Atribut**:
- `id` (Primary Key): Auto increment ID
- `ukuran`: Ukuran udang (Size 100, Size 80, etc.)
- `ukuran_display`: Display ukuran (Size 100 (10g))
- `harga`: Harga per kg (DECIMAL 12,2)
- `tanggal`: Tanggal harga berlaku
- `sumber`: Sumber data (Database, Commodities-API, Manual, etc.)
- `harga_sebelumnya`: Harga sebelumnya untuk kalkulasi perubahan (DECIMAL 12,2, nullable)
- `created_at`, `updated_at`: Timestamp untuk audit trail

**Kardinalitas**: 
- Standalone entity (tidak memiliki relasi dengan entitas lain)

#### 2.2.2 Dekomposisi Fungsional

Dekomposisi fungsional dilakukan untuk memecah sistem E-SHRIMP menjadi modul-modul fungsional yang lebih kecil dan dapat dikelola dengan baik. Berikut adalah dekomposisi fungsional sistem:

##### Level 0: Sistem E-SHRIMP
Sistem monitoring budidaya udang berbasis web dengan integrasi IoT dan machine learning.

##### Level 1: Modul Utama

**1. Modul Autentikasi dan Otorisasi**
- **F1.1**: Login Admin (Firebase Authentication)
- **F1.2**: Login Peternak (Firebase Realtime Database)
- **F1.3**: Logout
- **F1.4**: Session Management
- **F1.5**: Role-based Access Control

**2. Modul Monitoring Real-time**
- **F2.1**: Menampilkan Dashboard Monitoring
- **F2.2**: Mengumpulkan Data Sensor Real-time
- **F2.3**: Visualisasi Data dalam Grafik
- **F2.4**: Simulasi Data Sensor (untuk testing)
- **F2.5**: Menampilkan Data Sensor Terbaru
- **F2.6**: Menampilkan Histori Data Sensor

**3. Modul Manajemen Monitoring Session**
- **F3.1**: Membuat Monitoring Session Baru
- **F3.2**: Mengatur Threshold Parameter
- **F3.3**: Memulai Monitoring
- **F3.4**: Pause Monitoring Session
- **F3.5**: Resume Monitoring Session
- **F3.6**: Restart Monitoring Session
- **F3.7**: Menghentikan Monitoring Session
- **F3.8**: Menghapus Monitoring Session
- **F3.9**: Menampilkan Detail Monitoring Session
- **F3.10**: Menampilkan Daftar Monitoring Sessions

**4. Modul Manajemen Kolam**
- **F4.1**: Menambah Kolam Baru
- **F4.2**: Menghapus Kolam
- **F4.3**: Menampilkan Daftar Kolam
- **F4.4**: Menampilkan Detail Kolam
- **F4.5**: Menampilkan History Monitoring per Kolam
- **F4.6**: Menampilkan Statistik Kolam

**5. Modul Manajemen Robot Kapal**
- **F5.1**: Menambah Robot Kapal Baru
- **F5.2**: Mengedit Informasi Robot Kapal
- **F5.3**: Menghapus Robot Kapal
- **F5.4**: Menampilkan Daftar Robot Kapal
- **F5.5**: Menampilkan Status Robot Kapal
- **F5.6**: Menampilkan Monitoring Sessions Aktif per Kapal

**6. Modul Threshold Management**
- **F6.1**: Mengatur Threshold Parameter Sensor
- **F6.2**: Menampilkan Threshold yang Telah Diatur
- **F6.3**: Menghapus Threshold
- **F6.4**: Validasi Threshold (min/max)
- **F6.5**: Pengecekan Threshold terhadap Data Sensor

**7. Modul Notifikasi dan Alert**
- **F7.1**: Membuat Notifikasi Otomatis
- **F7.2**: Menampilkan Daftar Notifikasi
- **F7.3**: Menandai Notifikasi sebagai Dibaca
- **F7.4**: Menghapus Notifikasi
- **F7.5**: Menghapus Semua Notifikasi
- **F7.6**: Real-time Notifikasi (via Firebase)

**8. Modul Prediksi Pertumbuhan**
- **F8.1**: Validasi Kelayakan Prediksi (umur minimal 14 hari)
- **F8.2**: Mengambil Data Sensor Terbaru
- **F8.3**: Memanggil Python ML Script
- **F8.4**: Memproses Prediksi dengan Random Forest Model
- **F8.5**: Menampilkan Hasil Prediksi (Berat, Laju Pertumbuhan, Feed Rate, Pakan per Hari, Akumulasi Pakan)
- **F8.6**: Menampilkan Daftar Kolam yang Eligible untuk Prediksi

**9. Modul Price Tracker**
- **F9.1**: Mengambil Data Harga dari Database
- **F9.2**: Mengambil Data Harga dari Commodities-API
- **F9.3**: Menyimpan Data Harga ke Database
- **F9.4**: Menampilkan Harga Berbagai Ukuran Udang
- **F9.5**: Menghitung Perubahan Harga
- **F9.6**: Refresh Data Harga
- **F9.7**: Caching Data Harga (6 jam)

**10. Modul Artikel**
- **F10.1**: Menampilkan Daftar Artikel (Peternak)
- **F10.2**: Menampilkan Detail Artikel
- **F10.3**: Menambah Artikel Baru (Admin)
- **F10.4**: Mengedit Artikel (Admin)
- **F10.5**: Menghapus Artikel (Admin)
- **F10.6**: Pagination Artikel

**11. Modul Forum**
- **F11.1**: Menampilkan Daftar Post Forum
- **F11.2**: Membuat Post Forum Baru
- **F11.3**: Membuat Reply pada Post
- **F11.4**: Menghapus Post Forum (Admin)
- **F11.5**: Pagination Forum

**12. Modul Manajemen User (Admin)**
- **F12.1**: Menampilkan Daftar Peternak
- **F12.2**: Menampilkan Detail Peternak
- **F12.3**: Menghapus User
- **F12.4**: Menampilkan Statistik User

**13. Modul Profil**
- **F13.1**: Menampilkan Profil User
- **F13.2**: Mengedit Profil (future)

##### Level 2: Sub-fungsi Detail

**F2.2.1**: Menerima Data dari Robot Kapal IoT
**F2.2.2**: Menyimpan Data Sensor ke Database
**F2.2.3**: Menghitung Kualitas Air berdasarkan Parameter

**F3.1.1**: Validasi Kolam dan Kapal Tersedia
**F3.1.2**: Validasi Tidak Ada Session Aktif untuk Kapal
**F3.1.3**: Validasi Kombinasi Kolam-Umur Belum Pernah Digunakan

**F6.5.1**: Membandingkan Nilai Sensor dengan Threshold Min
**F6.5.2**: Membandingkan Nilai Sensor dengan Threshold Max
**F6.5.3**: Trigger Notifikasi jika Melebihi Threshold

**F8.3.1**: Menyiapkan Input Data untuk ML Model
**F8.3.2**: Eksekusi Python Script
**F8.3.3**: Parsing Output dari ML Model
**F8.3.4**: Fallback ke Dummy Prediction jika ML Gagal

**F9.2.1**: Request ke Commodities-API
**F9.2.2**: Parsing Response API
**F9.2.3**: Konversi USD ke IDR
**F9.2.4**: Error Handling jika API Gagal

##### Hierarki Dekomposisi Fungsional

```
Sistem E-SHRIMP (Level 0)
│
├── Modul Autentikasi (F1)
│   ├── Login Admin (F1.1)
│   ├── Login Peternak (F1.2)
│   ├── Logout (F1.3)
│   ├── Session Management (F1.4)
│   └── Access Control (F1.5)
│
├── Modul Monitoring (F2)
│   ├── Dashboard (F2.1)
│   ├── Koleksi Data (F2.2)
│   │   ├── Terima Data IoT (F2.2.1)
│   │   ├── Simpan ke DB (F2.2.2)
│   │   └── Hitung Kualitas (F2.2.3)
│   ├── Visualisasi (F2.3)
│   ├── Simulasi (F2.4)
│   ├── Data Terbaru (F2.5)
│   └── Histori (F2.6)
│
├── Modul Session (F3)
│   ├── Buat Session (F3.1)
│   │   ├── Validasi Kolam/Kapal (F3.1.1)
│   │   ├── Validasi Session Aktif (F3.1.2)
│   │   └── Validasi Kombinasi (F3.1.3)
│   ├── Atur Threshold (F3.2)
│   ├── Mulai (F3.3)
│   ├── Pause (F3.4)
│   ├── Resume (F3.5)
│   ├── Restart (F3.6)
│   ├── Stop (F3.7)
│   ├── Hapus (F3.8)
│   ├── Detail (F3.9)
│   └── Daftar (F3.10)
│
├── Modul Kolam (F4)
│   ├── Tambah (F4.1)
│   ├── Hapus (F4.2)
│   ├── Daftar (F4.3)
│   ├── Detail (F4.4)
│   ├── History (F4.5)
│   └── Statistik (F4.6)
│
├── Modul Kapal (F5)
│   ├── Tambah (F5.1)
│   ├── Edit (F5.2)
│   ├── Hapus (F5.3)
│   ├── Daftar (F5.4)
│   ├── Status (F5.5)
│   └── Sessions Aktif (F5.6)
│
├── Modul Threshold (F6)
│   ├── Atur (F6.1)
│   ├── Tampilkan (F6.2)
│   ├── Hapus (F6.3)
│   ├── Validasi (F6.4)
│   └── Cek Threshold (F6.5)
│       ├── Cek Min (F6.5.1)
│       ├── Cek Max (F6.5.2)
│       └── Trigger Notif (F6.5.3)
│
├── Modul Notifikasi (F7)
│   ├── Buat Otomatis (F7.1)
│   ├── Daftar (F7.2)
│   ├── Tandai Dibaca (F7.3)
│   ├── Hapus (F7.4)
│   ├── Hapus Semua (F7.5)
│   └── Real-time (F7.6)
│
├── Modul Prediksi (F8)
│   ├── Validasi (F8.1)
│   ├── Ambil Data (F8.2)
│   ├── Panggil ML (F8.3)
│   │   ├── Siapkan Input (F8.3.1)
│   │   ├── Eksekusi Script (F8.3.2)
│   │   ├── Parse Output (F8.3.3)
│   │   └── Fallback (F8.3.4)
│   ├── Tampilkan Hasil (F8.5)
│   └── Daftar Eligible (F8.6)
│
├── Modul Price Tracker (F9)
│   ├── Ambil dari DB (F9.1)
│   ├── Ambil dari API (F9.2)
│   │   ├── Request API (F9.2.1)
│   │   ├── Parse Response (F9.2.2)
│   │   ├── Konversi USD-IDR (F9.2.3)
│   │   └── Error Handling (F9.2.4)
│   ├── Simpan ke DB (F9.3)
│   ├── Tampilkan Harga (F9.4)
│   ├── Hitung Perubahan (F9.5)
│   ├── Refresh (F9.6)
│   └── Cache (F9.7)
│
├── Modul Artikel (F10)
│   ├── Daftar (F10.1)
│   ├── Detail (F10.2)
│   ├── Tambah (F10.3) [Admin]
│   ├── Edit (F10.4) [Admin]
│   ├── Hapus (F10.5) [Admin]
│   └── Pagination (F10.6)
│
├── Modul Forum (F11)
│   ├── Daftar (F11.1)
│   ├── Buat Post (F11.2)
│   ├── Reply (F11.3)
│   ├── Hapus (F11.4) [Admin]
│   └── Pagination (F11.5)
│
├── Modul User Management (F12) [Admin]
│   ├── Daftar Peternak (F12.1)
│   ├── Detail Peternak (F12.2)
│   ├── Hapus User (F12.3)
│   └── Statistik (F12.4)
│
└── Modul Profil (F13)
    ├── Tampilkan (F13.1)
    └── Edit (F13.2) [Future]
```

##### Matriks Fungsi vs Modul

| Fungsi | Controller | Model | Service | View |
|--------|-----------|-------|---------|------|
| F1.1, F1.2, F1.3 | - | - | - | login.blade.php, auth.js |
| F2.1, F2.5 | UserController | SensorData | - | dashboard.blade.php |
| F2.2, F2.4 | UserController | SensorData | - | - |
| F2.6 | UserController | SensorData | - | histori-data.blade.php |
| F3.1-F3.10 | UserController | MonitoringSession | - | manajemen-kapal.blade.php |
| F4.1-F4.6 | UserController | DashboardMonitoring | - | manajemen-kolam.blade.php |
| F5.1-F5.6 | UserController | RobotKapalEshrimp | - | manajemen-kapal.blade.php |
| F6.1-F6.5 | UserController | Threshold | - | dashboard.blade.php |
| F7.1-F7.6 | UserController | Notifikasi | - | dashboard.blade.php |
| F8.1-F8.6 | UserController | - | - | prediksi-pertumbuhan.blade.php |
| F9.1-F9.7 | UserController | HargaUdang | PriceTrackerService | price-tracker.blade.php |
| F10.1-F10.6 | UserController, AdminController | Artikel | - | artikel.blade.php |
| F11.1-F11.5 | UserController, AdminController | Forum | - | forum.blade.php |
| F12.1-F12.4 | AdminController | Peternak | - | kelola-user.blade.php |
| F13.1 | UserController | Peternak | - | profil.blade.php |

#### 2.2.3 Struktur Data dan Relasi

**Relasi Utama**:
1. **Peternak → Robot Kapal**: One-to-Many (1:N)
   - Satu peternak dapat memiliki banyak robot kapal
   
2. **Peternak → Kolam**: One-to-Many (1:N)
   - Satu peternak dapat memiliki banyak kolam
   
3. **Kolam → Data Sensor**: One-to-Many (1:N)
   - Satu kolam menghasilkan banyak data sensor
   
4. **Robot Kapal → Data Sensor**: One-to-Many (1:N)
   - Satu robot kapal mengumpulkan banyak data sensor
   
5. **Kolam → Monitoring Session**: One-to-Many (1:N)
   - Satu kolam dapat memiliki banyak monitoring session
   
6. **Robot Kapal → Monitoring Session**: One-to-Many (1:N)
   - Satu robot kapal dapat digunakan dalam banyak monitoring session
   
7. **Kolam → Threshold**: One-to-Many (1:N)
   - Satu kolam dapat memiliki banyak threshold (satu per sensor type)
   
8. **Kolam → Notifikasi**: One-to-Many (1:N)
   - Satu kolam dapat memiliki banyak notifikasi
   
9. **Peternak → Forum**: One-to-Many (1:N)
   - Satu peternak dapat membuat banyak post forum
   
10. **Forum → Forum (Reply)**: One-to-Many (1:N, Self-referencing)
    - Satu post dapat memiliki banyak reply

**Integritas Referensial**:
- Foreign key constraints dengan `ON DELETE CASCADE` untuk menjaga konsistensi data
- Foreign key constraints dengan `ON DELETE SET NULL` untuk data optional (seperti email_peternak di forum)

### 2.3 Analisis Kebutuhan Non-Fungsional
1. **Performance**: Response time < 2 detik untuk halaman utama
2. **Scalability**: Dapat menangani 100+ concurrent users
3. **Security**: Autentikasi dan otorisasi yang kuat
4. **Usability**: Interface yang user-friendly
5. **Reliability**: Uptime 99%+
6. **Maintainability**: Kode yang mudah di-maintain

### 2.4 Analisis Stakeholder
1. **Peternak Udang**: End user utama
2. **Admin**: Pengelola sistem
3. **Developer**: Tim pengembang
4. **Support Team**: Tim support

---

## 3. PERANCANGAN SISTEM

### 3.1 Arsitektur Sistem

#### 3.1.1 Arsitektur Umum
```
┌─────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                    │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │  Landing     │  │   Dashboard  │  │   Admin      │  │
│  │   Page       │  │   Peternak   │  │   Panel      │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
└─────────────────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│                    APPLICATION LAYER                     │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │ User         │  │ Admin        │  │ API          │  │
│  │ Controller   │  │ Controller  │  │ Endpoints    │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
└─────────────────────────────────────────────────────────┘
                        │
        ┌───────────────┼───────────────┐
        │               │               │
        ▼               ▼               ▼
┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│   Business   │  │   External   │  │      ML      │
│   Logic      │  │   Services   │  │   Service    │
│   (Models)   │  │  (Firebase)  │  │   (Python)   │
└──────────────┘  └──────────────┘  └──────────────┘
        │
        ▼
┌─────────────────────────────────────────────────────────┐
│                      DATA LAYER                         │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │   MySQL      │  │   Firebase   │  │   File       │  │
│  │  Database    │  │   Realtime   │  │   Storage    │  │
│  │              │  │   Database   │  │              │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
└─────────────────────────────────────────────────────────┘
```

#### 3.1.2 Komponen Sistem

**1. Frontend Layer**
- **Technology**: Laravel Blade Templates
- **Styling**: Tailwind CSS 4.0
- **JavaScript**: Vanilla JS + Firebase SDK
- **Build Tool**: Vite

**2. Application Layer**
- **Framework**: Laravel 12.0
- **Controllers**: UserController, AdminController
- **Services**: PriceTrackerService
- **Models**: Eloquent ORM Models

**3. Data Layer**
- **Primary Database**: MySQL
- **Real-time Database**: Firebase Realtime Database
- **Authentication**: Firebase Authentication

**4. External Services**
- **Firebase**: Authentication & Realtime Database
- **Commodities-API**: Price tracking
- **Python ML**: Growth prediction

### 3.2 Desain Modul

#### 3.2.1 Modul Monitoring
- **Fungsi**: Monitoring real-time kualitas air
- **Input**: Data sensor dari IoT
- **Output**: Visualisasi data, notifikasi
- **Komponen**:
  - DashboardController
  - SensorData Model
  - MonitoringSession Model
  - Threshold Model
  - Notifikasi Model

#### 3.2.2 Modul Manajemen
- **Fungsi**: Manajemen kolam dan kapal
- **Input**: User input untuk CRUD operations
- **Output**: Data kolam/kapal
- **Komponen**:
  - DashboardMonitoring Model
  - RobotKapalEshrimp Model
  - UserController methods

#### 3.2.3 Modul Prediksi
- **Fungsi**: Prediksi pertumbuhan udang
- **Input**: Data sensor (pH, suhu, oksigen, salinitas, umur)
- **Output**: Prediksi berat, laju pertumbuhan, kebutuhan pakan
- **Komponen**:
  - Python ML Script
  - Random Forest Model
  - UserController@predictGrowth

#### 3.2.4 Modul Informasi
- **Fungsi**: Artikel dan forum
- **Input**: Konten dari admin/user
- **Output**: Artikel dan forum posts
- **Komponen**:
  - Artikel Model
  - Forum Model
  - UserController & AdminController

#### 3.2.5 Modul Price Tracker
- **Fungsi**: Tracking harga udang
- **Input**: Data dari API atau database
- **Output**: Harga berbagai ukuran udang
- **Komponen**:
  - HargaUdang Model
  - PriceTrackerService
  - UserController@priceTracker

### 3.3 Alur Proses Bisnis

#### 3.3.1 Alur Monitoring
```
1. Peternak login
2. Pilih kolam dan kapal
3. Mulai monitoring session
4. Set threshold untuk setiap parameter
5. Sistem mulai mengumpulkan data sensor
6. Data ditampilkan real-time di dashboard
7. Jika parameter melebihi threshold → Notifikasi
8. Monitoring dapat di-pause/resume/stop
```

#### 3.3.2 Alur Prediksi
```
1. Peternak memilih kolam (minimal umur 14 hari)
2. Sistem mengambil data sensor terbaru
3. Data dikirim ke Python ML script
4. ML model memproses dan predict
5. Hasil prediksi ditampilkan ke user
```

---

## 4. PERANCANGAN DATABASE

### 4.1 Entity Relationship Diagram (ERD)

```
┌─────────────────┐
│    Peternak     │
│─────────────────│
│ email_peternak* │
│ nama            │
│ password        │
│ tracker_id      │
│ role            │
└────────┬────────┘
         │ 1
         │
         │ N
         │
┌────────▼─────────────────┐      ┌──────────────────┐
│ DashboardMonitoring      │      │ RobotKapalEshrimp │
│──────────────────────────│      │──────────────────│
│ kolam_id*                │      │ robot_id*        │
│ email_peternak*          │      │ email_peternak*  │
│ foto_posisi_kapal        │      │ status           │
│ treshold_id              │      │ lokasi           │
│ notif_id                 │      └────────┬─────────┘
│ data_id                  │               │ 1
└──────┬───────────────────┘               │
       │ 1                                  │ N
       │                                    │
       │ N                                  │
       │                                    │
┌──────▼───────────────────┐      ┌────────▼─────────┐
│ SensorData               │      │ MonitoringSession│
│──────────────────────────│      │──────────────────│
│ id*                      │      │ session_id*      │
│ robot_id*                │      │ kolam_id*        │
│ kolam_id*                │      │ nama_kapal*      │
│ umur_budidaya            │      │ umur_budidaya    │
│ waktu                    │      │ threshold_suhu   │
│ ph                       │      │ threshold_ph    │
│ suhu                     │      │ threshold_oksigen│
│ oksigen                  │      │ threshold_salinit│
│ salinitas                │      │ timer_monitoring │
│ kualitas_air             │      │ mulai_monitoring│
└──────────────────────────┘      │ selesai_monitor │
                                   │ is_active       │
                                   │ is_paused       │
                                   └─────────────────┘

┌─────────────────┐      ┌─────────────────┐      ┌─────────────────┐
│   Threshold     │      │   Notifikasi    │      │     Forum       │
│─────────────────│      │─────────────────│      │─────────────────│
│ threshold_id*   │      │ notifikasi_id*  │      │ forum_id*       │
│ kolam_id*       │      │ kolam_id*       │      │ judul           │
│ sensor_tipe     │      │ nama_kapal      │      │ isi             │
│ nilai           │      │ pesan           │      │ tanggal         │
│ timer           │      │ waktu          │      │ email_peternak* │
└─────────────────┘      │ status         │      │ post_peternak_id│
                         └─────────────────┘      └─────────────────┘

┌─────────────────┐      ┌─────────────────┐
│    Artikel      │      │   HargaUdang    │
│─────────────────│      │─────────────────│
│ artikel_id*     │      │ id*             │
│ judul           │      │ ukuran          │
│ konten          │      │ ukuran_display  │
│ tanggal         │      │ harga           │
└─────────────────┘      │ tanggal         │
                         │ sumber          │
                         │ harga_sebelumnya│
                         └─────────────────┘
```

### 4.2 Spesifikasi Tabel

#### 4.2.1 Tabel peternaks
| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| email_peternak | VARCHAR(255) | PRIMARY KEY | Email peternak |
| nama | VARCHAR(255) | NOT NULL | Nama peternak |
| password | VARCHAR(255) | | Password (disimpan di Firebase) |
| tracker_id | VARCHAR(255) | | ID tracker |
| role | VARCHAR(50) | DEFAULT 'peternak' | Role user |

#### 4.2.2 Tabel robot_kapal_eshrimps
| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| robot_id | VARCHAR(255) | PRIMARY KEY | ID robot kapal |
| email_peternak | VARCHAR(255) | FOREIGN KEY | Email pemilik |
| status | VARCHAR(50) | DEFAULT 'idle' | Status kapal |
| lokasi | VARCHAR(255) | | Lokasi kapal |

#### 4.2.3 Tabel dashboard_monitorings
| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| kolam_id | VARCHAR(255) | PRIMARY KEY | ID kolam |
| email_peternak | VARCHAR(255) | FOREIGN KEY | Email pemilik |
| foto_posisi_kapal | TEXT | | Foto posisi kapal |
| treshold_id | VARCHAR(255) | | ID threshold |
| notif_id | VARCHAR(255) | | ID notifikasi |
| data_id | VARCHAR(255) | | ID data |

#### 4.2.4 Tabel sensor_data
| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID data |
| robot_id | VARCHAR(255) | FOREIGN KEY | ID robot kapal |
| kolam_id | VARCHAR(255) | FOREIGN KEY | ID kolam |
| umur_budidaya | INT | | Umur budidaya (hari) |
| waktu | TIMESTAMP | | Timestamp data |
| ph | DECIMAL(5,2) | | Nilai pH |
| suhu | DECIMAL(5,2) | | Suhu (°C) |
| oksigen | DECIMAL(5,2) | | Oksigen terlarut (mg/L) |
| salinitas | DECIMAL(5,2) | | Salinitas (ppt) |
| kualitas_air | VARCHAR(50) | | Kualitas air |

#### 4.2.5 Tabel monitoring_sessions
| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| session_id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID session |
| kolam_id | VARCHAR(255) | FOREIGN KEY | ID kolam |
| nama_kapal | VARCHAR(255) | FOREIGN KEY | ID robot kapal |
| umur_budidaya | INT | | Umur budidaya |
| threshold_suhu | TEXT | | JSON threshold suhu |
| threshold_ph | TEXT | | JSON threshold pH |
| threshold_oksigen | TEXT | | JSON threshold oksigen |
| threshold_salinitas | TEXT | | JSON threshold salinitas |
| timer_monitoring | VARCHAR(50) | | Interval monitoring |
| mulai_monitoring | TIMESTAMP | | Timestamp mulai |
| selesai_monitoring | TIMESTAMP | NULL | Timestamp selesai |
| is_active | BOOLEAN | DEFAULT TRUE | Status aktif |
| is_paused | BOOLEAN | DEFAULT FALSE | Status pause |
| total_paused_seconds | INT | DEFAULT 0 | Total waktu pause |

### 4.3 Relasi Antar Tabel
1. **Peternak → RobotKapalEshrimp**: One-to-Many
2. **Peternak → DashboardMonitoring**: One-to-Many
3. **DashboardMonitoring → SensorData**: One-to-Many
4. **DashboardMonitoring → Threshold**: One-to-Many
5. **DashboardMonitoring → Notifikasi**: One-to-Many
6. **RobotKapalEshrimp → SensorData**: One-to-Many
7. **RobotKapalEshrimp → MonitoringSession**: One-to-Many
8. **DashboardMonitoring → MonitoringSession**: One-to-Many

---

## 5. PERANCANGAN ANTARMUKA

### 5.1 Desain Halaman Utama (Landing Page)
- **Header**: Logo, Navigation, Login button
- **Hero Section**: Judul, deskripsi, CTA buttons
- **Features Section**: IoT Sensor, Alerts, Growth Prediction
- **Monitoring Boat Section**: Deskripsi kapal monitoring
- **Footer**: Contact information, links

### 5.2 Desain Dashboard Peternak
- **Sidebar Navigation**: Menu navigasi
- **Main Content**:
  - Grafik real-time parameter kualitas air
  - Data sensor terbaru
  - Status monitoring sessions
  - Notifikasi terbaru
  - Quick actions

### 5.3 Desain Halaman Monitoring
- **Session List**: Daftar monitoring sessions
- **Session Detail**: Detail session dengan grafik
- **Control Panel**: Start/Pause/Resume/Stop buttons
- **Threshold Settings**: Form untuk set threshold

### 5.4 Desain Halaman Admin
- **Dashboard**: Overview statistics
- **User Management**: Table dengan CRUD operations
- **Content Management**: Artikel dan forum management

### 5.5 Prinsip Desain
1. **Consistency**: Konsisten dalam penggunaan warna, font, spacing
2. **Simplicity**: Interface yang sederhana dan mudah digunakan
3. **Responsiveness**: Responsif untuk berbagai ukuran layar
4. **Accessibility**: Mudah diakses oleh semua user

---

## 6. PERANCANGAN KEAMANAN

### 6.1 Autentikasi
- **Admin**: Firebase Authentication
- **Peternak**: Firebase Realtime Database
- **Session Management**: Browser sessionStorage

### 6.2 Otorisasi
- **Role-based Access Control**: Admin vs Peternak
- **Route Protection**: Middleware untuk proteksi route
- **Frontend Guards**: JavaScript guards untuk halaman

### 6.3 Keamanan Data
- **SQL Injection**: Protected by Eloquent ORM
- **XSS**: Protected by Blade templating
- **CSRF**: Laravel built-in CSRF protection
- **Password**: ⚠️ Perlu implementasi hashing untuk peternak

### 6.4 Rekomendasi Keamanan
1. Implement password hashing untuk peternak
2. Add rate limiting untuk API
3. Implement HTTPS (production)
4. Add input validation
5. Implement audit logging

---

## 7. IMPLEMENTASI

### 7.1 Teknologi yang Digunakan
- **Backend**: Laravel 12.0, PHP 8.2+
- **Frontend**: Blade Templates, Tailwind CSS 4.0
- **Database**: MySQL
- **Authentication**: Firebase
- **ML**: Python 3, Scikit-learn

### 7.2 Struktur Direktori
```
E-shrimp/
├── app/
│   ├── Http/Controllers/
│   │   ├── UserController.php
│   │   └── AdminController.php
│   ├── Models/
│   │   ├── Peternak.php
│   │   ├── RobotKapalEshrimp.php
│   │   ├── SensorData.php
│   │   └── ...
│   └── Services/
│       └── PriceTrackerService.php
├── resources/
│   ├── views/
│   │   ├── user/
│   │   ├── admin/
│   │   └── components/
│   └── js/
│       ├── auth.js
│       └── firebase-rtdb.js
├── database/
│   └── migrations/
├── ml_models/
│   ├── train_model.py
│   ├── predict_growth.py
│   └── random_forest_multi.pkl
└── routes/
    └── web.php
```

### 7.3 Alur Implementasi
1. Setup Laravel project
2. Setup database dan migrations
3. Implement models dan relationships
4. Implement controllers
5. Implement views
6. Integrate Firebase
7. Implement ML prediction
8. Testing
9. Deployment

---

## 8. PENGUJIAN

### 8.1 Unit Testing
- Test models dan relationships
- Test controller methods
- Test service classes

### 8.2 Integration Testing
- Test API endpoints
- Test authentication flow
- Test database operations

### 8.3 User Acceptance Testing
- Test dengan user sebenarnya
- Collect feedback
- Iterate based on feedback

---

## 9. DOKUMENTASI API

### 9.1 Endpoint Authentication
- `POST /api/sync-peternak`: Sync peternak dari Firebase

### 9.2 Endpoint Sensor Data
- `GET /api/sensor-data`: Get sensor data
- `POST /api/simulate-realtime-data`: Simulate realtime data

### 9.3 Endpoint Monitoring
- `POST /mulai-monitoring`: Start monitoring session
- `POST /monitoring-session/{id}/pause`: Pause session
- `POST /monitoring-session/{id}/resume`: Resume session
- `DELETE /monitoring-session/{id}`: Delete session

### 9.4 Endpoint Prediction
- `POST /api/predict-growth`: Predict growth

### 9.5 Endpoint Notifications
- `GET /api/notifications`: Get notifications
- `POST /api/create-notification`: Create notification
- `DELETE /api/notifications/{id}`: Delete notification

---

## 10. LAMPIRAN

### 10.1 Daftar File Penting
- `routes/web.php`: Route definitions
- `app/Http/Controllers/UserController.php`: User controller
- `app/Http/Controllers/AdminController.php`: Admin controller
- `resources/js/auth.js`: Authentication logic
- `ml_models/train_model.py`: ML training script

### 10.2 Diagram Tambahan
- Use Case Diagram
- Sequence Diagram
- Activity Diagram
- Class Diagram

### 10.3 Referensi
- Laravel Documentation: https://laravel.com/docs
- Firebase Documentation: https://firebase.google.com/docs
- Scikit-learn Documentation: https://scikit-learn.org/

---

**Dokumen ini merupakan revisi dan perbaikan dari DPPL_prism.pdf**

**Status**: ✅ Complete  
**Versi**: 2.0  
**Tanggal Revisi**: 2025

