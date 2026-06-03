<?php require APP_PATH . 'Views/layouts/header.php'; ?>

<?php if ($flash): ?>
<div class="auto-dismiss mb-5 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2
    <?= $flash['type']==='success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-blue-50 text-blue-700 border border-blue-200' ?>">
    <i class="fas <?= $flash['type']==='success' ? 'fa-check-circle' : 'fa-info-circle' ?>"></i>
    <?= e($flash['msg']) ?>
</div>
<?php endif; ?>

<div class="flex flex-wrap gap-3 items-center justify-between mb-6">
    <div class="flex gap-2 flex-wrap">
        <?php foreach (['all'=>'Semua','aktif'=>'Aktif','panen'=>'Panen','mati'=>'Mati'] as $k=>$v): ?>
        <a href="<?= url('tanaman') ?>?status=<?= $k ?>"
           class="px-3 py-1.5 rounded-lg text-sm font-medium transition
           <?= $filter===$k ? 'bg-forest-600 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-forest-50 border border-gray-200' ?>">
            <?= $v ?>
        </a>
        <?php endforeach; ?>
    </div>
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
        class="bg-gradient-to-r from-forest-500 to-forest-700 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md hover:opacity-90 flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Tanaman
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
<?php if (empty($tanaman)): ?>
    <div class="col-span-3 bg-white rounded-2xl border-2 border-dashed border-gray-200 py-16 text-center">
        <i class="fas fa-seedling text-5xl text-gray-200 mb-3 block"></i>
        <p class="text-gray-400 font-medium">Belum ada tanaman.</p>
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
            class="mt-4 text-forest-600 font-semibold text-sm hover:underline">+ Tambah Tanaman</button>
    </div>
<?php else: ?>
<?php foreach ($tanaman as $t):
    $sisa = sisaHariPanen($t['tanggal_tanam'], $t['estimasi_panen']);
    $prog = progressTanaman($t['tanggal_tanam'], $t['estimasi_panen']);
    $statusColor = ['aktif'=>'bg-green-50 text-green-600','panen'=>'bg-amber-50 text-amber-600','mati'=>'bg-red-50 text-red-500'];
    $badge = $statusColor[$t['status']] ?? 'bg-gray-100 text-gray-500';
?>
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden">
    <div class="h-1.5 w-full bg-gray-100">
        <div class="h-full bg-gradient-to-r from-forest-400 to-forest-600" style="width:<?= $prog ?>%"></div>
    </div>
    <div class="p-5">
        <div class="flex justify-between items-start mb-3">
            <div>
                <h3 class="font-bold text-gray-800"><?= e($t['nama_tanaman']) ?></h3>
                <p class="text-xs text-gray-400 mt-0.5"><?= e($t['jenis']) ?> · <?= e($t['lokasi']) ?></p>
            </div>
            <span class="<?= $badge ?> text-[10px] font-bold px-2 py-0.5 rounded-full uppercase"><?= $t['status'] ?></span>
        </div>

        <div class="grid grid-cols-2 gap-2 mb-4">
            <div class="bg-gray-50 rounded-xl p-2.5">
                <p class="text-[9px] text-gray-400 uppercase font-bold mb-0.5">Tanam</p>
                <p class="text-xs font-bold text-gray-700"><?= date('d M Y', strtotime($t['tanggal_tanam'])) ?></p>
            </div>
            <div class="bg-gray-50 rounded-xl p-2.5">
                <p class="text-[9px] text-gray-400 uppercase font-bold mb-0.5">Sisa Hari</p>
                <p class="text-xs font-bold <?= $sisa <= 7 ? 'text-amber-500' : 'text-forest-600' ?>">
                    <?php if ($t['estimasi_panen'] <= 0): ?>
                        <span class="text-gray-400 font-normal italic text-[11px]">Menunggu prediksi admin</span>
                    <?php else: ?>
                        <?= $sisa > 0 ? $sisa.' hari' : 'Siap panen!' ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mb-3">
            <a href="<?= url('perkembangan') ?>?tanaman_id=<?= $t['id'] ?>" 
               class="py-2 text-center text-xs font-bold bg-forest-50 text-forest-700 hover:bg-forest-600 hover:text-white rounded-xl transition flex items-center justify-center gap-1.5 border border-forest-100">
                <i class="fas fa-chart-line text-[11px]"></i> Perkembangan
            </a>
            <a href="<?= url('prediksi') ?>?tanaman_id=<?= $t['id'] ?>" 
               class="py-2 text-center text-xs font-bold bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white rounded-xl transition flex items-center justify-center gap-1.5 border border-indigo-100">
                <i class="fas fa-magic text-[11px]"></i> Prediksi
            </a>
        </div>

        <div class="flex gap-2">
            <?php if ($t['status'] === 'aktif'): ?>
            <form method="POST" action="<?= url('tanaman') ?>" class="flex-1">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                <input type="hidden" name="status" value="panen">
                <button onclick="return confirm('Tandai tanaman ini sudah dipanen?')"
                    class="w-full py-2 text-xs font-bold bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-xl transition">
                    <i class="fas fa-check-circle mr-1"></i> Panen
                </button>
            </form>
            <?php endif; ?>
            <form method="POST" action="<?= url('tanaman') ?>">
                <input type="hidden" name="action" value="hapus">
                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                <button onclick="return confirm('Hapus tanaman ini?')"
                    class="w-9 h-9 bg-red-50 text-red-400 hover:bg-red-500 hover:text-white rounded-xl transition flex items-center justify-center">
                    <i class="fas fa-trash-alt text-xs"></i>
                </button>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>

<div id="modalTambah" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg my-4">
        <div class="px-6 py-4 bg-forest-600 rounded-t-2xl flex items-center justify-between">
            <h3 class="font-bold text-white flex items-center gap-2"><i class="fas fa-seedling"></i> Tambah Tanaman Baru</h3>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-white/80 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="<?= url('tanaman') ?>" class="p-6 space-y-4">
            <input type="hidden" name="action" value="tambah">
            
            <input type="hidden" name="estimasi_panen" value="0">

            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Tanaman *</label>
                    <input type="text" name="nama_tanaman" required placeholder="Contoh: Tomat Cherry"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-forest-300">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jenis</label>
                    <input type="text" name="jenis" placeholder="Sayuran, Buah..."
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-forest-300">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Lokasi</label>
                    <input type="text" name="lokasi" placeholder="Pot, Kebun..."
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-forest-300">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Tanam *</label>
                    <input type="date" name="tanggal_tanam" value="<?= date('Y-m-d') ?>" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-forest-300">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Catatan</label>
                    <textarea name="catatan" rows="2" placeholder="Catatan tambahan..."
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-forest-300 resize-none"></textarea>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="flex-1 py-2.5 text-sm font-semibold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition">Batal</button>
                <button type="submit"
                    class="flex-1 py-2.5 text-sm bg-forest-600 text-white rounded-xl font-bold hover:bg-forest-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php require APP_PATH . 'Views/layouts/footer.php'; ?>