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
        <?php foreach (['semua'=>'Semua','hari_ini'=>'Hari Ini','pending'=>'Belum Selesai','selesai'=>'Selesai'] as $k=>$v): ?>
        <a href="<?= url('jadwal') ?>?filter=<?= $k ?>"
           class="px-3 py-1.5 rounded-lg text-sm font-medium transition
           <?= $filter===$k ? 'bg-forest-600 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-forest-50 border border-gray-200' ?>">
             <?= $v ?>
        </a>
        <?php endforeach; ?>
    </div>
   <?php if (($_SESSION['role'] ?? 'user') === 'admin'): ?>
    <a href="<?= url('jadwal/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-forest-600 hover:bg-forest-750 text-white text-sm font-bold rounded-xl shadow-sm transition-all duration-300">
        <i class="fas fa-plus"></i> Tambah Jadwal
    </a>
<?php endif; ?>
</div>

<?php
$iconJadwal = [
    'penyiraman'       => ['icon'=>'fas fa-droplet',  'color'=>'text-blue-400',  'bg'=>'bg-blue-50'],
    'pemupukan'        => ['icon'=>'fas fa-flask',    'color'=>'text-green-500', 'bg'=>'bg-green-50'],
    'pemangkasan'      => ['icon'=>'fas fa-scissors', 'color'=>'text-orange-400','bg'=>'bg-orange-50'],
    'pengendalian_hama'=> ['icon'=>'fas fa-bug',      'color'=>'text-red-400',   'bg'=>'bg-red-50'],
    'lainnya'          => ['icon'=>'fas fa-star',     'color'=>'text-gray-400',  'bg'=>'bg-gray-50'],
];
$statusStyle = [
    'pending'  => 'bg-yellow-50 text-yellow-600',
    'selesai'  => 'bg-green-50 text-green-600',
    'terlewat' => 'bg-red-50 text-red-500',
];
?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Jenis</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Tanaman</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Tanggal & Waktu</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($jadwal)): ?>
                <tr>
                    <td colspan="5" class="text-center py-12 text-gray-400">
                        <i class="fas fa-calendar text-4xl mb-2 block text-gray-200"></i>Tidak ada jadwal.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($jadwal as $j):
                    $ji = $iconJadwal[$j['jenis_perawatan']] ?? $iconJadwal['lainnya'];
                ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 <?= $ji['bg'] ?> rounded-lg flex items-center justify-center">
                                <i class="<?= $ji['icon'] ?> <?= $ji['color'] ?> text-sm"></i>
                            </div>
                            <span class="font-medium text-gray-700 capitalize"><?= str_replace('_',' ',$j['jenis_perawatan']) ?></span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-600">
                        <?= e($j['nama_tanaman']) ?>
                        <?php if (isset($j['nama_user']) && ($_SESSION['role'] ?? 'user') === 'admin'): ?>
                            <span class="text-xs block text-gray-400">Owner: <?= e($j['nama_user']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-gray-600">
                        <div class="font-medium"><?= date('d M Y', strtotime($j['tanggal_jadwal'])) ?></div>
                        <div class="text-xs text-gray-400"><?= substr($j['waktu'],0,5) ?> WIB</div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="<?= $statusStyle[$j['status']] ?> text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                            <?= $j['status'] ?>
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex justify-center gap-2">
                            <?php if ($j['status'] === 'pending'): ?>
                            <form method="POST" action="<?= url('jadwal') ?>">
                                <input type="hidden" name="action" value="selesai">
                                <input type="hidden" name="id" value="<?= $j['id'] ?>">
                                <button title="Tandai Selesai" class="w-8 h-8 bg-green-50 text-green-600 hover:bg-green-500 hover:text-white rounded-lg transition flex items-center justify-center">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                            <form method="POST" action="<?= url('jadwal') ?>" onsubmit="return confirm('Hapus jadwal ini?')">
                                <input type="hidden" name="action" value="hapus">
                                <input type="hidden" name="id" value="<?= $j['id'] ?>">
                                <button title="Hapus" class="w-8 h-8 bg-red-50 text-red-400 hover:bg-red-500 hover:text-white rounded-lg transition flex items-center justify-center">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modalJadwal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 bg-forest-600 flex items-center justify-between">
            <h3 class="font-bold text-white flex items-center gap-2"><i class="fas fa-calendar-plus"></i> Tambah Jadwal</h3>
            <button onclick="document.getElementById('modalJadwal').classList.add('hidden')" class="text-white/80 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="<?= url('jadwal') ?>" class="p-6 space-y-4">
            <input type="hidden" name="action" value="tambah">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Tanaman</label>
                <select name="tanaman_id" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-forest-300 outline-none">
                    <option value="">-- Pilih Tanaman --</option>
                    <?php foreach ($tanamanDropdown as $t): ?>
                        <?php 
                            $isSelected = (isset($_GET['tanaman_id']) && (int)$_GET['tanaman_id'] === (int)$t['id']) ? 'selected' : ''; 
                        ?>
                        <option value="<?= $t['id'] ?>" <?= $isSelected ?>>
                            <?= e($t['nama_tanaman']) ?>
                            <?php if (isset($t['nama_user'])): ?>
                                (Pemilik: <?= e($t['nama_user']) ?>)
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Perawatan</label>
                <select name="jenis_perawatan" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-forest-300 outline-none">
                    <option value="penyiraman">💧 Penyiraman</option>
                    <option value="pemupukan">🌱 Pemupukan</option>
                    <option value="pemangkasan">✂️ Pemangkasan</option>
                    <option value="pengendalian_hama">🐛 Pengendalian Hama</option>
                    <option value="lainnya">⭐ Lainnya</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal_jadwal" value="<?= date('Y-m-d') ?>" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-forest-300 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Waktu</label>
                    <input type="time" name="waktu" value="07:00"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-forest-300 outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan</label>
                <textarea name="catatan" rows="2" placeholder="Catatan tambahan..."
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-forest-300 outline-none resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modalJadwal').classList.add('hidden')"
                    class="flex-1 py-2.5 text-sm font-semibold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition">Batal</button>
                <button type="submit"
                    class="flex-1 py-2.5 text-sm bg-forest-600 text-white rounded-xl font-bold hover:bg-forest-700 transition">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Jika link mengandung parameter 'tanaman_id', sistem mendeteksi panggilan dari dashboard admin
    // dan otomatis menampilkan jendela Modal secara instan.
    if (urlParams.has('tanaman_id')) {
        const modal = document.getElementById('modalJadwal');
        if (modal) {
            modal.classList.remove('hidden');
            
            // REVISI OPTIMASI URL: Menghapus parameter ?tanaman_id dari address bar browser 
            // tanpa memicu reload halaman, agar modal tidak terus-menerus auto-open saat direfresh.
            urlParams.delete('tanaman_id');
            const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
            window.history.replaceState({}, document.title, newUrl);
        }
    }
});
</script>

<?php require APP_PATH . 'Views/layouts/footer.php'; ?>