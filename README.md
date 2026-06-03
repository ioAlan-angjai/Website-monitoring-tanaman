# Urban Farming Monitor 🌱

Aplikasi berbasis web monitoring pertanian urban (*urban farming*) terintegrasi yang dirancang untuk membantu pengelolaan, penjadwalan, serta analisis prediksi masa panen komoditas tanaman secara efisien.

## ✨ Fitur Utama

- **Pusat Analisis Prediksi Panen (Sisi Admin):** Monitoring terpusat berbasis data (*data-driven*) dengan formula kalkulasi masa tumbuh ($Masa\ Tanam + Estimasi\ Hari$). Dilengkapi dengan dasbor manajemen risiko (*Summary Cards*) untuk mendeteksi tanaman rawan atau keterlambatan panen.
- **Estimasi & Prediksi Panen Dinamis (Sisi User):** Visualisasi kartu pertumbuhan individual (*User Crop Tracking*) yang dilengkapi indikator sisa hari real-time, persentase kematangan, serta ambang batas kondisi ideal (Suhu, pH Air, Kelembaban).
- **Sistem Intervensi & Notifikasi Terintegrasi:** Komunikasi dua arah melalui fitur catatan intervensi agro dari admin serta lonceng notifikasi real-time yang tersinkronisasi.

## 🛠️ Arsitektur & Teknologi

Aplikasi ini dibangun menggunakan arsitektur kustom **Model-View-Controller (MVC)** berbasis PHP native tanpa framework berat demi efisiensi performa eksekusi dan struktur kode yang bersih.

- **Backend:** PHP v8.x (Custom Core MVC Architecture)
- **Frontend Framework:** Tailwind CSS & FontAwesome Icons
- **Database Wrapper:** Custom Database Core Handler (Mendukung Driver PDO & MySQLi)

## 📁 Struktur Direktori

```text
URBAN/
├── app/
│   ├── Core/          # Engine Utama Framework (Model.php, Router.php)
│   ├── Helpers/       # Fungsi bantu global (functions.php)
│   ├── Models/        # Logika entitas data database
│   └── Views/         # File antarmuka / Layouting (admin, layouts, dsb.)
├── public/            # Dokumen root (index.php, assets, uploads)
└── .gitignore