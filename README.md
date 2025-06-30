# 🍽️ IndoRecipes - Modul Latihan LKS

Selamat datang di **IndoRecipes**, sebuah proyek latihan berbasis Laravel yang dibuat sebagai simulasi sistem backend untuk aplikasi resep masakan.  
Proyek ini dikembangkan sebagai **modul latihan LKS (Lomba Kompetensi Siswa)** jurusan Rekayasa Perangkat Lunak (RPL).

---

## 🧩 Fitur Utama

- ✅ Autentikasi menggunakan UUID Token
- ✅ Register & Login User
- ✅ Middleware Role: `User` & `Administrator`
- ✅ CRUD Kategori (khusus admin)
- ✅ CRUD Resep
- ✅ Komentar & Rating
- ✅ Rata-rata rating tiap resep
- ✅ 3 besar resep terbaik (best recipes)
- ✅ Eloquent Relationship: User, Recipe, Rating, Comment, Category

---

## 🚀 Teknologi

- **Framework**: Laravel 10.x
- **Database**: MySQL (PhpMyAdmin)
- **Autentikasi**: UUID Token (tanpa Sanctum)
- **Postman**: untuk testing endpoint

---

## 🎯 Endpoints Penting

### 🔓 Tanpa Login

| Method | Endpoint                    | Deskripsi                      |
|--------|-----------------------------|--------------------------------|
| POST   | `/v1/register`              | Registrasi user                |
| POST   | `/v1/login`                 | Login user                     |
| GET    | `/v1/categories`            | Ambil semua kategori           |
| GET    | `/v1/recipes`               | Ambil semua resep              |
| GET    | `/v1/recipes/{slug}`        | Detail resep berdasarkan slug  |
| GET    | `/v1/best-recipes`          | Ambil 3 resep rating tertinggi |

### 🔐 Login User

| Method | Endpoint                           | Deskripsi                         |
|--------|------------------------------------|-----------------------------------|
| GET    | `/v1/profile`                      | Lihat profil + resep yang dibuat  |
| POST   | `/v1/recipes`                      | Tambah resep                      |
| DELETE | `/v1/recipes/{slug}`              | Hapus resep milik sendiri         |
| POST   | `/v1/recipes/{slug}/comment`       | Komentar pada resep               |
| POST   | `/v1/recipes/{slug}/rating`        | Beri rating pada resep            |
| POST   | `/v1/logout`                       | Logout & hapus token              |

### 🛡️ Login Admin (Role: Administrator)

| Method | Endpoint                     | Deskripsi                      |
|--------|------------------------------|--------------------------------|
| POST   | `/v1/categories`             | Tambah kategori baru           |
| DELETE | `/v1/categories/{slug}`      | Hapus kategori berdasarkan slug|

---

## 🔐 Autentikasi UUID

Setelah login berhasil, akan didapatkan token UUID yang dipakai di setiap request sebagai:

```http
Authorization: Bearer {token}
```

Contoh:
```
Authorization: Bearer 12ab34cd-ef56-78gh-ij90-klmn123456op
```

---

## 🎓 Tujuan Modul

Modul ini ditujukan untuk siswa SMK dan peserta LKS RPL yang ingin:

- Memahami autentikasi token tanpa Sanctum
- Membuat API CRUD dengan Laravel
- Mengelola relasi antar model
- Membangun sistem login dengan role-based access
- Melatih kemampuan problem solving backend

---

## 🧠 Kontributor

> Modul ini dikembangkan sebagai bagian dari simulasi lomba.  
> Untuk pelatihan, evaluasi mandiri, atau pembelajaran di jurusan RPL SMK.

