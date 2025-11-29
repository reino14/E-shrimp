# Analisis Sistem E-SHRIMP
## Dokumentasi Lengkap Website E-SHRIMP

---

## 📋 DAFTAR ISI

1. [Ringkasan Eksekutif](#ringkasan-eksekutif)
2. [Arsitektur Sistem](#arsitektur-sistem)
3. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
4. [Struktur Database](#struktur-database)
5. [Fitur dan Modul](#fitur-dan-modul)
6. [Alur Autentikasi](#alur-autentikasi)
7. [API dan Integrasi](#api-dan-integrasi)
8. [Machine Learning](#machine-learning)
9. [Keamanan](#keamanan)
10. [Kesimpulan dan Rekomendasi](#kesimpulan-dan-rekomendasi)

---

## 1. RINGKASAN EKSEKUTIF

### 1.1 Deskripsi Sistem
E-SHRIMP adalah sistem monitoring berbasis web untuk budidaya udang yang menggunakan teknologi IoT (Internet of Things) dengan robot kapal monitoring. Sistem ini dirancang untuk membantu peternak udang dalam memantau kualitas air kolam secara real-time, memprediksi pertumbuhan udang, dan mengoptimalkan proses budidaya.

### 1.2 Tujuan Sistem
- **Monitoring Real-time**: Memantau parameter kualitas air (pH, suhu, oksigen terlarut, salinitas) secara real-time
- **Prediksi Pertumbuhan**: Menggunakan machine learning untuk memprediksi pertumbuhan udang dan kebutuhan pakan
- **Manajemen Kolam**: Mengelola multiple kolam dan kapal monitoring
- **Notifikasi**: Memberikan alert ketika parameter melebihi threshold
- **Price Tracker**: Melacak harga udang di pasar
- **Komunitas**: Forum diskusi dan artikel edukasi

### 1.3 User Roles
1. **Admin**: Mengelola user, artikel, dan forum
2. **Peternak**: Menggunakan fitur monitoring, prediksi, dan manajemen kolam

---

## 2. ARSITEKTUR SISTEM

### 2.1 Arsitektur Umum
```
┌─────────────────┐
│   Web Browser   │
│  (Frontend UI)  │
└────────┬────────┘
         │
         ├─────────────────┐
         │                 │
         ▼                 ▼
┌─────────────────┐  ┌─────────────────┐
│  Laravel Backend│  │  Firebase       │
│  (PHP)          │  │  - Auth         │
│  - API          │  │  - Realtime DB  │
│  - Controllers  │  └─────────────────┘
│  - Models       │
└────────┬────────┘
         │
         ├─────────────────┐
         │                 │
         ▼                 ▼
┌─────────────────┐  ┌─────────────────┐
│  MySQL Database │  │  Python ML       │
│  - Data Storage │  │  - Prediction    │
└─────────────────┘  └─────────────────┘
```

### 2.2 Komponen Utama

#### Frontend
- **Framework**: Laravel Blade Templates
- **Styling**: Tailwind CSS 4.0
- **JavaScript**: Vanilla JS + Firebase SDK
- **Build Tool**: Vite

#### Backend
- **Framework**: Laravel 12.0
- **PHP Version**: 8.2+
- **Database**: MySQL
- **Authentication**: Firebase Authentication (Admin) + Firebase Realtime Database (Peternak)

#### External Services
- **Firebase**: Authentication & Realtime Database
- **Commodities-API**: Price tracking (optional)
- **Python ML**: Growth prediction model

---

## 3. TEKNOLOGI YANG DIGUNAKAN

### 3.1 Backend Technologies
- **Laravel 12.0**: PHP framework
- **MySQL**: Relational database
- **Firebase SDK**: Authentication & Realtime Database
- **Python 3**: Machine Learning model execution

### 3.2 Frontend Technologies
- **Tailwind CSS 4.0**: Utility-first CSS framework
- **Vite**: Build tool and dev server
- **Firebase JavaScript SDK**: Client-side Firebase integration
- **Axios**: HTTP client (via Laravel)

### 3.3 Machine Learning
- **Scikit-learn**: Random Forest Multi-output Regressor
- **Pandas**: Data manipulation
- **NumPy**: Numerical computing
- **Joblib**: Model serialization

### 3.4 Development Tools
- **Composer**: PHP dependency management
- **NPM**: JavaScript package management
- **Git**: Version control

---

## 4. STRUKTUR DATABASE

### 4.1 Entity Relationship Diagram (ERD)

```
┌──────────────┐         ┌──────────────────┐
│   Peternak   │────────<│ RobotKapalEshrimp│
└──────┬───────┘         └────────┬─────────┘
       │                          │
       │                          │
       │         ┌────────────────┴─────────┐
       │         │                          │
       ▼         ▼                          ▼
┌──────────────┐         ┌──────────────┐  ┌──────────────┐
│Dashboard     │         │SensorData    │  │Monitoring    │
│Monitoring    │────────<│              │  │Session       │
└──────┬───────┘         └──────────────┘  └──────────────┘
       │
       ├──────────┬──────────┬──────────┐
       │          │          │          │
       ▼          ▼          ▼          ▼
┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐
│Threshold │ │Notifikasi│ │  Forum   │ │  Artikel │
└──────────┘ └──────────┘ └──────────┘ └──────────┘

┌──────────────┐
│  HargaUdang  │ (Standalone)
└──────────────┘
```

### 4.2 Tabel Database

#### 4.2.1 Tabel `peternaks`
- `email_peternak` (PK): Email peternak
- `nama`: Nama peternak
- `password`: Password (disimpan di Firebase RDTB)
- `tracker_id`: ID tracker
- `role`: Role user (peternak)

#### 4.2.2 Tabel `admins`
- `admin_id` (PK): ID admin
- `nama`: Nama admin
- `email`: Email admin
- `password`: Password (disimpan di Firebase Auth)
- `role`: Role user (admin)

#### 4.2.3 Tabel `robot_kapal_eshrimps`
- `robot_id` (PK): ID robot kapal
- `email_peternak` (FK): Email pemilik kapal
- `status`: Status kapal (idle, active, maintenance)
- `lokasi`: Lokasi kapal

#### 4.2.4 Tabel `dashboard_monitorings`
- `kolam_id` (PK): ID kolam
- `email_peternak` (FK): Email pemilik kolam
- `foto_posisi_kapal`: Foto posisi kapal
- `treshold_id`: ID threshold
- `notif_id`: ID notifikasi
- `data_id`: ID data

#### 4.2.5 Tabel `sensor_data`
- `id` (PK): Auto increment
- `robot_id` (FK): ID robot kapal
- `kolam_id` (FK): ID kolam
- `umur_budidaya`: Umur budidaya (hari)
- `waktu`: Timestamp data
- `ph`: Nilai pH
- `suhu`: Suhu air (°C)
- `oksigen`: Oksigen terlarut (mg/L)
- `salinitas`: Salinitas (ppt)
- `kualitas_air`: Kualitas air (Sangat Baik, Baik, Cukup, Buruk)

#### 4.2.6 Tabel `monitoring_sessions`
- `session_id` (PK): Auto increment
- `kolam_id` (FK): ID kolam
- `nama_kapal` (FK): ID robot kapal
- `umur_budidaya`: Umur budidaya saat monitoring dimulai
- `threshold_suhu`: JSON threshold suhu
- `threshold_ph`: JSON threshold pH
- `threshold_oksigen`: JSON threshold oksigen
- `threshold_salinitas`: JSON threshold salinitas
- `timer_monitoring`: Interval monitoring (detik)
- `mulai_monitoring`: Timestamp mulai
- `selesai_monitoring`: Timestamp selesai
- `is_active`: Status aktif
- `is_paused`: Status pause
- `total_paused_seconds`: Total waktu pause

#### 4.2.7 Tabel `thresholds`
- `threshold_id` (PK): Auto increment
- `kolam_id` (FK): ID kolam
- `sensor_tipe`: Tipe sensor (ph, suhu, oksigen, salinitas)
- `nilai`: Nilai threshold
- `timer`: Timestamp update

#### 4.2.8 Tabel `notifikasis`
- `notifikasi_id` (PK): Auto increment
- `kolam_id` (FK): ID kolam
- `nama_kapal`: Nama kapal yang trigger notifikasi
- `pesan`: Pesan notifikasi
- `waktu`: Timestamp notifikasi
- `status`: Status read/unread

#### 4.2.9 Tabel `forums`
- `forum_id` (PK): ID forum
- `judul`: Judul post
- `isi`: Isi post
- `tanggal`: Timestamp post
- `email_peternak` (FK): Email pembuat post
- `post_peternak_id`: ID post parent (untuk reply)

#### 4.2.10 Tabel `artikels`
- `artikel_id` (PK): ID artikel
- `judul`: Judul artikel
- `konten`: Konten artikel
- `tanggal`: Tanggal publikasi

#### 4.2.11 Tabel `harga_udang`
- `id` (PK): Auto increment
- `ukuran`: Ukuran udang (Size 100, Size 80, etc.)
- `ukuran_display`: Display ukuran (Size 100 (10g))
- `harga`: Harga per kg (decimal)
- `tanggal`: Tanggal harga
- `sumber`: Sumber data (Database, Commodities-API, Manual)
- `harga_sebelumnya`: Harga sebelumnya untuk kalkulasi perubahan

---

## 5. FITUR DAN MODUL

### 5.1 Modul Peternak

#### 5.1.1 Dashboard Monitoring
- **Fungsi**: Menampilkan data sensor real-time
- **Fitur**:
  - Grafik real-time parameter kualitas air
  - Data sensor terbaru
  - Status monitoring session
  - Notifikasi terbaru
  - Threshold settings
- **Route**: `/dashboard`
- **Controller**: `UserController@dashboard`

#### 5.1.2 Manajemen Kapal
- **Fungsi**: Mengelola robot kapal monitoring
- **Fitur**:
  - Tambah kapal baru
  - Edit informasi kapal
  - Hapus kapal
  - Lihat status kapal
  - Lihat monitoring sessions aktif
- **Route**: `/manajemen-kapal`
- **Controller**: `UserController@manajemenKapal`

#### 5.1.3 Manajemen Kolam
- **Fungsi**: Mengelola kolam budidaya
- **Fitur**:
  - Tambah kolam baru
  - Hapus kolam
  - Lihat history monitoring per kolam
  - Lihat statistik kolam
- **Route**: `/manajemen-kolam`
- **Controller**: `UserController@manajemenKolam`

#### 5.1.4 Monitoring Session
- **Fungsi**: Mengelola session monitoring
- **Fitur**:
  - Mulai monitoring baru
  - Pause/Resume monitoring
  - Restart monitoring
  - Hentikan monitoring
  - Lihat detail session
- **Routes**:
  - `POST /mulai-monitoring`
  - `POST /monitoring-session/{id}/pause`
  - `POST /monitoring-session/{id}/resume`
  - `POST /monitoring-session/{id}/restart`
  - `DELETE /monitoring-session/{id}`
- **Controller**: `UserController`

#### 5.1.5 Prediksi Pertumbuhan
- **Fungsi**: Memprediksi pertumbuhan udang menggunakan ML
- **Fitur**:
  - Input: Umur budidaya, pH, Salinitas, DO, Suhu
  - Output: Berat udang, Laju pertumbuhan, Feed rate, Pakan per hari, Akumulasi pakan
  - Validasi: Minimal umur budidaya 14 hari
- **Route**: `/prediksi-pertumbuhan`
- **Controller**: `UserController@prediksiPertumbuhan`
- **ML Model**: Random Forest Multi-output Regressor

#### 5.1.6 Histori Data
- **Fungsi**: Menampilkan history data sensor
- **Fitur**:
  - Pagination (10, 50, 100 per page)
  - Filter berdasarkan kolam
  - Export data (future)
- **Route**: `/histori-data`
- **Controller**: `UserController@historiData`

#### 5.1.7 Price Tracker
- **Fungsi**: Melacak harga udang di pasar
- **Fitur**:
  - Tampilkan harga berbagai ukuran
  - Perubahan harga (naik/turun)
  - Refresh data dari API
  - Cache data (6 jam)
- **Route**: `/price-tracker`
- **Controller**: `UserController@priceTracker`
- **Service**: `PriceTrackerService`

#### 5.1.8 Artikel
- **Fungsi**: Membaca artikel edukasi
- **Fitur**:
  - List artikel
  - Detail artikel
  - Pagination
- **Routes**:
  - `/artikel`
  - `/artikel/{id}`
- **Controller**: `UserController@bacaArtikel`

#### 5.1.9 Forum
- **Fungsi**: Diskusi komunitas
- **Fitur**:
  - Post baru
  - Reply post
  - List post terbaru
- **Routes**:
  - `/forum`
  - `POST /forum`
  - `POST /forum/{id}/reply`
- **Controller**: `UserController@forum`

#### 5.1.10 Profil
- **Fungsi**: Mengelola profil user
- **Fitur**:
  - Lihat profil
  - Edit profil (future)
- **Route**: `/profil`
- **Controller**: `UserController@profil`

### 5.2 Modul Admin

#### 5.2.1 Dashboard Admin
- **Fungsi**: Overview sistem
- **Fitur**:
  - Total users
  - Total artikel
  - Total forum posts
- **Route**: `/admin/dashboard`
- **Controller**: `AdminController@dashboard`

#### 5.2.2 Kelola User
- **Fungsi**: Mengelola user peternak
- **Fitur**:
  - List semua peternak
  - Detail peternak
  - Hapus user
- **Routes**:
  - `/admin/kelola-user`
  - `/admin/peternak/{email}`
  - `DELETE /admin/user/{email}`
- **Controller**: `AdminController@kelolaUser`

#### 5.2.3 Kelola Artikel
- **Fungsi**: Mengelola artikel
- **Fitur**:
  - Tambah artikel
  - Edit artikel
  - Hapus artikel
  - List artikel
- **Routes**:
  - `/admin/kelola-artikel`
  - `POST /admin/artikel`
  - `PUT /admin/artikel/{id}`
  - `DELETE /admin/artikel/{id}`
- **Controller**: `AdminController@kelolaArtikel`

#### 5.2.4 Kelola Forum
- **Fungsi**: Mengelola forum posts
- **Fitur**:
  - List semua posts
  - Hapus post
- **Routes**:
  - `/admin/kelola-forum`
  - `DELETE /admin/forum/{id}`
- **Controller**: `AdminController@kelolaForum`

---

## 6. ALUR AUTENTIKASI

### 6.1 Dual Authentication System

Sistem menggunakan dua metode autentikasi:

1. **Firebase Authentication** (untuk Admin)
2. **Firebase Realtime Database** (untuk Peternak)

### 6.2 Flow Login

```
User Input Email & Password
         │
         ▼
┌────────────────────┐
│ Cek Firebase Auth  │
│ (Admin)            │
└─────────┬──────────┘
          │
          ├─── Success ───> Set sessionStorage (admin) ───> Redirect /admin/dashboard
          │
          └─── Failed ───> Cek Firebase RDTB (Peternak)
                            │
                            ├─── Success ───> Set sessionStorage (peternak) ───> Redirect /dashboard
                            │
                            └─── Failed ───> Show Error
```

### 6.3 Session Management

- **Storage**: Browser `sessionStorage`
- **Data Stored**:
  - `userRole`: "admin" atau "peternak"
  - `userEmail`: Email user
  - `userName`: Nama user (untuk peternak)

### 6.4 Proteksi Halaman

- Halaman dengan `data-requires-auth="true"` akan dicek autentikasinya
- Admin pages: Cek role === "admin"
- User pages: Cek role === "peternak" atau "admin"

---

## 7. API DAN INTEGRASI

### 7.1 Internal API Endpoints

#### 7.1.1 Sensor Data API
- `GET /api/sensor-data?kolam_id={id}&limit={n}`
- **Response**: JSON array of sensor data

#### 7.1.2 Thresholds API
- `GET /api/thresholds?kolam_id={id}`
- **Response**: JSON object of thresholds

#### 7.1.3 Notifications API
- `GET /api/notifications?kolam_id={id}`
- `POST /api/create-notification`
- `DELETE /api/notifications/{id}`
- `DELETE /api/notifications/clear-all?kolam_id={id}`

#### 7.1.4 Prediction API
- `POST /api/predict-growth`
- **Request**: `{kolam_id: string}`
- **Response**: Prediction results

#### 7.1.5 Price Tracker API
- `POST /api/refresh-price-tracker`
- **Response**: Updated price data

#### 7.1.6 Training Progress API
- `GET /api/training-progress`
- **Response**: ML model training progress

#### 7.1.7 Realtime Data Simulation
- `POST /api/simulate-realtime-data`
- **Request**: `{kolam_id, umur_budidaya, session_id}`
- **Response**: Simulated sensor data

### 7.2 External Integrations

#### 7.2.1 Firebase
- **Authentication**: Admin login
- **Realtime Database**: Peternak data, Forum posts
- **SDK**: Firebase JavaScript SDK v11.0.0

#### 7.2.2 Commodities-API
- **Purpose**: Price tracking
- **Optional**: Fallback ke database jika API tidak tersedia
- **Rate Limit**: 100 requests/month (free tier)

### 7.3 Data Flow

#### 7.3.1 Sensor Data Flow
```
IoT Sensor → Robot Kapal → Firebase RDTB → Laravel Backend → Database → Frontend Display
```

#### 7.3.2 Prediction Flow
```
User Request → Laravel Controller → Python Script → ML Model → Prediction Results → Frontend Display
```

---

## 8. MACHINE LEARNING

### 8.1 Model Overview
- **Algorithm**: Random Forest Multi-output Regressor
- **Library**: Scikit-learn
- **Model File**: `ml_models/random_forest_multi.pkl`

### 8.2 Input Features
1. `Umur_Budidaya`: Umur budidaya (hari)
2. `pH`: Nilai pH air
3. `Salinitas_ppt`: Salinitas (parts per thousand)
4. `DO_mgL`: Dissolved Oxygen (mg/L)
5. `Suhu_C`: Suhu air (°C)

### 8.3 Output Targets
1. `Berat_udang_gr`: Berat udang (gram)
2. `Laju_pertumbuhan_harian_gr`: Laju pertumbuhan harian (gram/hari)
3. `Feed_Rate_persen`: Feed rate (%)
4. `Pakan_per_hari_kg`: Pakan per hari (kg)
5. `Akumulasi_pakan_kg`: Akumulasi pakan (kg)

### 8.4 Training Process
1. Load dataset dari Excel (`dataset_e-shrimp.xlsx`)
2. Preprocessing data
3. Split train/test (80/20)
4. Train Random Forest model
5. Evaluate model
6. Save model sebagai `.pkl` file

### 8.5 Prediction Process
1. User input data sensor terbaru
2. Laravel controller memanggil Python script
3. Python script load model dan predict
4. Return hasil prediksi ke Laravel
5. Laravel return JSON response ke frontend

---

## 9. KEAMANAN

### 9.1 Authentication
- ✅ Dual authentication system
- ✅ Firebase Authentication untuk admin
- ✅ Session-based authentication
- ⚠️ Password peternak disimpan plaintext di Firebase RDTB (perlu perbaikan)

### 9.2 Authorization
- ✅ Role-based access control
- ✅ Route protection berdasarkan role
- ✅ Frontend route guards

### 9.3 Data Security
- ✅ SQL injection protection (Laravel Eloquent)
- ✅ XSS protection (Blade templating)
- ✅ CSRF protection (Laravel built-in)
- ⚠️ Password hashing perlu diimplementasikan untuk peternak

### 9.4 Recommendations
1. Implement password hashing untuk peternak
2. Add rate limiting untuk API endpoints
3. Implement HTTPS (production)
4. Add input validation yang lebih ketat
5. Implement audit logging

---

## 10. KESIMPULAN DAN REKOMENDASI

### 10.1 Kekuatan Sistem
1. ✅ Arsitektur yang baik dengan separation of concerns
2. ✅ Real-time monitoring capabilities
3. ✅ Machine learning integration
4. ✅ User-friendly interface
5. ✅ Comprehensive features

### 10.2 Area Perbaikan
1. ⚠️ **Security**: Password hashing untuk peternak
2. ⚠️ **Error Handling**: Perlu improvement
3. ⚠️ **Testing**: Unit tests dan integration tests
4. ⚠️ **Documentation**: API documentation
5. ⚠️ **Performance**: Database indexing optimization
6. ⚠️ **Monitoring**: Application monitoring dan logging

### 10.3 Rekomendasi Pengembangan
1. **Short-term**:
   - Implement password hashing
   - Add comprehensive error handling
   - Improve API documentation
   - Add unit tests

2. **Medium-term**:
   - Implement caching strategy
   - Add export functionality untuk data
   - Improve mobile responsiveness
   - Add push notifications

3. **Long-term**:
   - Mobile app development
   - Advanced analytics dashboard
   - Integration dengan hardware IoT
   - Multi-language support

---

## 11. DIAGRAM SISTEM

### 11.1 Use Case Diagram
```
┌─────────────┐
│   Peternak  │
└──────┬──────┘
       │
       ├─── Monitor Kualitas Air
       ├─── Kelola Kolam
       ├─── Kelola Kapal
       ├─── Prediksi Pertumbuhan
       ├─── Lihat Histori Data
       ├─── Atur Threshold
       ├─── Baca Artikel
       ├─── Forum Diskusi
       └─── Price Tracker

┌─────────────┐
│    Admin    │
└──────┬──────┘
       │
       ├─── Kelola User
       ├─── Kelola Artikel
       └─── Kelola Forum
```

### 11.2 Sequence Diagram - Monitoring Flow
```
Peternak → Frontend → Laravel Controller → Database
                │
                ├─── Firebase RDTB (Real-time)
                │
                └─── Python ML (Prediction)
```

---

**Dokumen ini dibuat untuk keperluan dokumentasi dan perbaikan DPPL_prism.pdf**

**Versi**: 1.0  
**Tanggal**: 2025  
**Status**: Complete Analysis



