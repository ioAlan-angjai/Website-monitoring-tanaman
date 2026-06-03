<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang — <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:ital,wght@0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>tailwind.config={theme:{extend:{colors:{forest:{50:'#f0faf0',100:'#d9f2d9',500:'#2d9e2d',600:'#1f7a1f',700:'#165716'}}}}}</script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Fraunces', serif; }
        .bg-pattern { background-color:#f0faf0;background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%232d9e2d' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-xl">

    <!-- Header -->
    <div class="text-center mb-8">
        <div class="w-20 h-20 bg-gradient-to-br from-forest-500 to-forest-700 rounded-3xl flex items-center justify-center mx-auto mb-5 shadow-xl shadow-forest-200">
            <i class="fas fa-seedling text-white text-3xl"></i>
        </div>
        <h1 class="font-display text-3xl text-forest-800 font-bold mb-2">Selamat Datang! 🌱</h1>
        <p class="text-gray-500 text-sm max-w-sm mx-auto">
            Halo <strong class="text-forest-700"><?= e(\App\Core\Auth::user()['nama']) ?></strong>! Sebelum mulai, tambahkan tanaman pertamamu dulu ya.
        </p>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-3xl shadow-xl border border-forest-100 overflow-hidden">
        <div class="bg-gradient-to-r from-forest-600 to-forest-700 px-6 py-4">
            <h2 class="text-white font-bold text-lg flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> Data Tanaman Pertama
            </h2>
            <p class="text-forest-200 text-xs mt-0.5">Kamu bisa menambah tanaman lebih banyak nanti</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="mx-6 mt-5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> <?= e($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('onboarding') ?>" class="p-6 space-y-4">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Nama Tanaman *</label>
                    <input type="text" name="nama_tanaman" placeholder="Contoh: Tomat Cherry" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-forest-300 outline-none transition focus:bg-white bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Jenis Tanaman</label>
                    <input type="text" name="jenis" placeholder="Sayuran, Buah, Rempah..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-forest-300 outline-none transition focus:bg-white bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Lokasi Tanam</label>
                    <input type="text" name="lokasi" placeholder="Pot, Kebun Belakang..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-forest-300 outline-none transition focus:bg-white bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Tanggal Tanam *</label>
                    <input type="date" name="tanggal_tanam" value="<?= date('Y-m-d') ?>" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-forest-300 outline-none transition focus:bg-white bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Estimasi Panen (hari) *</label>
                    <input type="number" name="estimasi_panen" placeholder="Contoh: 60" required min="1"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-forest-300 outline-none transition focus:bg-white bg-gray-50">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Catatan</label>
                    <textarea name="catatan" rows="2" placeholder="Catatan awal tentang tanaman ini..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-forest-300 outline-none transition focus:bg-white bg-gray-50 resize-none"></textarea>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-forest-600 to-forest-700 hover:from-forest-500 hover:to-forest-600 text-white font-bold py-4 rounded-2xl transition shadow-lg shadow-forest-200 mt-2">
                <i class="fas fa-rocket mr-2"></i> Mulai Kebun Digital Saya!
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-gray-400 mt-5">
        <a href="<?= url('auth/logout') ?>" class="hover:text-gray-600 transition">Keluar dari akun</a>
    </p>
</div>
</body>
</html>