# Dokumentasi Sistem Login E-SHRIMP

## 📋 Ringkasan

Sistem login E-SHRIMP menggunakan **dual authentication system**:

-   **Admin**: Login melalui **Firebase Authentication**
-   **Peternak**: Login melalui **Firebase Realtime Database (RDTB)**

Kedua sistem menggunakan **email dan password**, tetapi disimpan di tempat yang berbeda.

---

## 🔐 Flow Login

### 1. User Mengisi Form Login

-   User memasukkan **email** dan **password** di halaman `/login`
-   Form submit ditangani oleh JavaScript di `resources/js/auth.js`

### 2. Proses Autentikasi (2 Langkah)

#### **Langkah 1: Cek Firebase Authentication (Admin)**

```javascript
// Coba login sebagai Admin
await signInWithEmailAndPassword(auth, email, password);
```

-   Jika **berhasil** → User adalah **Admin**
-   Jika **gagal** → Lanjut ke Langkah 2

#### **Langkah 2: Cek Firebase Realtime Database (Peternak)**

```javascript
// Cek di RDTB untuk Peternak
const peternak = await checkPeternakLogin(email, password);
```

-   Fungsi `checkPeternakLogin()`:
    1. Mengambil data peternak dari RDTB path: `peternaks/{encodedEmail}`
    2. Membandingkan password yang diinput dengan password di RDTB
    3. Jika cocok → return data peternak (tanpa password)
    4. Jika tidak cocok → return `null`

### 3. Set Session Storage

Setelah login berhasil, data disimpan di **browser sessionStorage**:

**Untuk Admin:**

```javascript
sessionStorage.setItem("userRole", "admin");
sessionStorage.setItem("userEmail", email);
```

**Untuk Peternak:**

```javascript
sessionStorage.setItem("userRole", "peternak");
sessionStorage.setItem("userEmail", email);
sessionStorage.setItem("userName", peternak.nama || "");
```

### 4. Redirect

-   **Admin** → `/admin/dashboard`
-   **Peternak** → `/dashboard`

---

## 🗄️ Penyimpanan Data

### Admin (Firebase Authentication)

-   **Lokasi**: Firebase Authentication
-   **Cara Daftar**: Manual di Firebase Console → Authentication → Users → Add User
-   **Data yang Disimpan**:
    -   Email
    -   Password (ter-hash otomatis oleh Firebase)
    -   UID (User ID)

### Peternak (Firebase Realtime Database)

-   **Lokasi**: Firebase Realtime Database
-   **Path**: `peternaks/{encodedEmail}`
-   **Cara Daftar**: Admin menambahkan melalui halaman `/admin/kelola-user`
-   **Data yang Disimpan**:
    ```json
    {
        "email_peternak": "peternak@example.com",
        "nama": "Nama Peternak",
        "password": "plaintext_password", // ⚠️ Belum di-hash (perlu perbaikan)
        "tracker_id": "TRACKER-001",
        "role": "peternak",
        "created_at": 1234567890,
        "created_at_iso": "2024-01-01T00:00:00.000Z"
    }
    ```

**Catatan Penting**: Email di-encode untuk path karena Firebase RDTB tidak mengizinkan karakter khusus (`.`, `#`, `$`, `[`, `]`) dalam path.

-   Contoh: `reino@gmail.com` → `reino_DOT_gmail_DOT_com`

---

## 🔒 Proteksi Halaman

### Halaman yang Memerlukan Autentikasi

Halaman dengan attribute `data-requires-auth="true"` akan dicek autentikasinya.

### Proses Proteksi

1. **Cek sessionStorage**:

    - Jika ada `userRole` dan `userEmail` → Lanjut
    - Jika tidak ada → Cek Firebase Auth

2. **Cek Firebase Auth** (untuk Admin):

    - Jika ada user → Set sessionStorage sebagai admin
    - Jika tidak ada → Redirect ke `/login`

3. **Cek Role untuk Halaman Admin**:

    - Jika user bukan admin → Redirect ke `/dashboard`

4. **Cek Role untuk Halaman User**:
    - Jika user bukan peternak/admin → Redirect ke `/login`

---

## 🚪 Logout

### Proses Logout

1. Hapus data dari `sessionStorage`:

    ```javascript
    sessionStorage.removeItem("userRole");
    sessionStorage.removeItem("userEmail");
    sessionStorage.removeItem("userName");
    ```

2. Sign out dari Firebase Auth (jika admin):

    ```javascript
    await signOut(auth);
    ```

3. Redirect ke landing page (`/`)

### Trigger Logout

-   Klik link/element dengan attribute `data-logout`
-   Contoh: `<a href="/" data-logout>Logout</a>`

---

## 🔄 Sinkronisasi dengan Laravel DB

### Untuk Peternak

Setelah login berhasil, saat membuka dashboard:

1. JavaScript mengambil email dari `sessionStorage`
2. Memanggil endpoint `/api/sync-peternak` untuk menyinkronkan data ke Laravel DB
3. Data peternak dibuat/diupdate di tabel `peternaks` di Laravel
4. Email disimpan di Laravel session untuk request selanjutnya

**Tujuan**: Memastikan data peternak ada di Laravel DB untuk relasi database (foreign key).

---

## 📝 File-file Penting

### Frontend

-   `resources/js/auth.js` - Logic autentikasi dan session management
-   `resources/js/firebase-rtdb.js` - Fungsi untuk akses Firebase RDTB
-   `resources/js/firebase-config.js` - Konfigurasi Firebase
-   `resources/views/login.blade.php` - Halaman login

### Backend

-   `app/Http/Controllers/UserController.php` - Controller untuk dashboard user
-   `app/Http/Controllers/AdminController.php` - Controller untuk dashboard admin
-   `routes/web.php` - Definisi routes

---

## ⚠️ Catatan Keamanan

1. **Password Peternak**: Saat ini disimpan sebagai **plaintext** di RDTB. Untuk production, sebaiknya di-hash menggunakan bcrypt atau hash function lainnya.

2. **CSRF Protection**: Endpoint `/api/sync-peternak` menggunakan CSRF token untuk proteksi.

3. **Session Storage**: Data di `sessionStorage` akan hilang saat browser ditutup. Untuk persistensi, bisa menggunakan `localStorage` (tapi kurang aman).

4. **Firebase Rules**: Pastikan Firebase Realtime Database Rules sudah dikonfigurasi dengan benar untuk mencegah akses tidak sah.

---

## 🧪 Testing Login

### Test Admin

1. Buat user admin di Firebase Console → Authentication
2. Login dengan email dan password admin
3. Harus redirect ke `/admin/dashboard`

### Test Peternak

1. Login sebagai admin
2. Tambahkan peternak di `/admin/kelola-user`
3. Logout
4. Login dengan email dan password peternak
5. Harus redirect ke `/dashboard`

---

## 🔧 Troubleshooting

### Error: "Email atau password salah"

-   Pastikan email dan password benar
-   Untuk admin: cek di Firebase Console → Authentication
-   Untuk peternak: cek di Firebase Console → Realtime Database → `peternaks`

### Error: "Permission denied"

-   Cek Firebase Realtime Database Rules
-   Pastikan rules mengizinkan read/write untuk path `peternaks`

### Error: "FOREIGN KEY constraint failed"

-   Pastikan peternak sudah di-sync ke Laravel DB
-   Cek apakah email di `dashboard_monitorings` ada di tabel `peternaks`

---

## 📚 Referensi

-   [Firebase Authentication Documentation](https://firebase.google.com/docs/auth)
-   [Firebase Realtime Database Documentation](https://firebase.google.com/docs/database)
-   [Laravel Session Documentation](https://laravel.com/docs/session)

