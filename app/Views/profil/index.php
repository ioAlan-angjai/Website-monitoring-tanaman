<?php require APP_PATH . 'Views/layouts/header.php'; ?>

<?php if ($flash): ?>
<div class="auto-dismiss mb-5 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2
    <?= $flash['type']==='success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
    <i class="fas <?= $flash['type']==='success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
    <?= e($flash['msg']) ?>
</div>
<?php endif; ?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-xl font-black text-gray-800 mb-6">Pengaturan Profil</h2>

        <form method="POST" action="<?= url('profil') ?>" enctype="multipart/form-data" class="space-y-6">

            <!-- Foto Profil -->
            <div class="flex flex-col items-center mb-2">
                <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-gray-100 mb-4 bg-gray-100 shadow-inner">
                    <?php
                    $fotoPath = ROOT_PATH . 'public/uploads/' . ($user['foto'] ?? '');
                    if (!empty($user['foto']) && file_exists($fotoPath)):
                    ?>
                        <img src="<?= url('uploads/' . e($user['foto'])) ?>" class="w-full h-full object-cover" alt="Foto Profil">
                    <?php else: ?>
                        <div class="flex items-center justify-center h-full">
                            <i class="fas fa-user text-5xl text-gray-300"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <label class="cursor-pointer bg-forest-600 text-white px-5 py-2 rounded-xl text-xs font-bold hover:bg-forest-700 transition shadow-md">
                    <i class="fas fa-camera mr-1.5"></i> Ganti Foto Profil
                    <input type="file" name="foto_profil" class="hidden" accept="image/jpg,image/jpeg,image/png">
                </label>
                <p class="text-[10px] text-gray-400 mt-2">Format JPG atau PNG, maks. 2MB</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= e($user['nama']) ?>"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-forest-300 outline-none transition text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Email</label>
                    <input type="email" name="email" value="<?= e($user['email']) ?>"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-forest-300 outline-none transition text-sm" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Password Baru</label>
                <input type="password" name="new_pass" placeholder="Kosongkan jika tidak ingin mengubah password"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-forest-300 outline-none transition text-sm">
            </div>

            <div class="pt-2 grid grid-cols-2 gap-3">
                <a href="<?= url('dashboard') ?>"
                   class="py-3 text-center text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                    Kembali
                </a>
                <button type="submit"
                    class="py-3 bg-forest-600 text-white font-bold rounded-xl hover:bg-forest-700 transition shadow-lg text-sm">
                    <i class="fas fa-save mr-1.5"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<?php require APP_PATH . 'Views/layouts/footer.php'; ?>