<?php require APP_PATH . 'Views/layouts/header.php'; ?>

<?php if (isset($flash) && $flash): ?>
<div class="auto-dismiss mb-5 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2
    <?= $flash['type']==='success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-blue-50 text-blue-700 border border-blue-200' ?>">
    <i class="fas <?= $flash['type']==='success' ? 'fa-check-circle' : 'fa-info-circle' ?>"></i>
    <?= e($flash['msg']) ?>
</div>
<?php endif; ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Manajemen Panen</h1>
    <p class="text-sm text-gray-500">Pantau seluruh tanaman dari semua user, lacak estimasi sisa hari panen, dan kelola tindakan penyesuaian prediksi.</p>
</div>

<?php
$totalTanaman = count($allTanamanUser ?? []);
$siapPanenCount = 0;
$terlambatCount = 0;
$prosesCount = 0;

$today = new \DateTime('today');

foreach ($allTanamanUser ?? [] as $t) {
    if (($t['status'] ?? '') === 'aktif' && isset($t['estimasi_panen'], $t['tanggal_tanam']) && $t['estimasi_panen'] > 0) {
        $tglTanam = new \DateTime($t['tanggal_tanam']);
        $interval = (int)$t['estimasi_panen'];
        $tglPanen = (clone $tglTanam)->modify("+$interval days");
        $sisa = (int)$today->diff($tglPanen)->format('%r%a');
        
        if ($sisa < 0) {
            $terlambatCount++;
        } elseif ($sisa == 0) {
            $siapPanenCount++;
        } else {
            $prosesCount++;
        }
    }
}
?>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-forest-600 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
            <i class="fas fa-leaf text-white text-lg"></i>
        </div>
        <div>
            <div class="text-2xl font-bold text-gray-800"><?= $totalTanaman ?></div>
            <div class="text-xs text-gray-500 font-medium">Total Terdaftar</div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-100 border border-green-200 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-basket-shopping text-green-600 text-lg"></i>
        </div>
        <div>
            <div class="text-2xl font-bold text-green-600"><?= $siapPanenCount ?></div>
            <div class="text-xs text-gray-500 font-medium">Siap Panen (Hari H)</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-red-100 border border-red-200 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-triangle-exclamation text-red-500 text-lg"></i>
        </div>
        <div>
            <div class="text-2xl font-bold text-red-500"><?= $terlambatCount ?></div>
            <div class="text-xs text-gray-500 font-medium">Terlambat Panen</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 border border-blue-200 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-hourglass-half text-blue-600 text-lg"></i>
        </div>
        <div>
            <div class="text-2xl font-bold text-blue-600"><?= $prosesCount ?></div>
            <div class="text-xs text-gray-500 font-medium">Masa Pertumbuhan</div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
        <h2 class="font-bold text-gray-700 flex items-center gap-2">
            <i class="fas fa-table-list text-forest-600"></i> Monitoring Status & Analisis Panen Seluruh User
        </h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse table-auto">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                    <th class="px-6 py-3.5">ID / Pemilik</th>
                    <th class="px-6 py-3.5">Nama Tanaman & Lokasi</th>
                    <th class="px-6 py-3.5">Tanggal Tanam</th>
                    <th class="px-6 py-3.5">Status Sistem</th>
                    <th class="px-6 py-3.5">Analisis Waktu Panen</th>
                    <th class="px-6 py-3.5 text-center">Aksi Manajemen Admin</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                <?php if (empty($allTanamanUser)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium">
                        <i class="fas fa-seedling text-4xl text-gray-200 mb-3 block"></i>
                        Belum ada data tanaman dari user manapun.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($allTanamanUser as $t): 
                    $statusColor = ['aktif'=>'bg-green-50 text-green-600','panen'=>'bg-amber-50 text-amber-600','mati'=>'bg-red-50 text-red-500'];
                    $badge = $statusColor[$t['status']] ?? 'bg-gray-100 text-gray-500';

                    // Logika Penentuan Badge & Informasi Analisis Panen
                    $estimasiSet = isset($t['estimasi_panen']) && $t['estimasi_panen'] > 0;
                    $statusPanenBadge = "bg-gray-100 text-gray-500 border border-gray-200";
                    $statusPanenText = "Belum Diatur Prediksi";

                    if ($t['status'] === 'panen') {
                        $statusPanenText = "Selesai Panen ✅";
                        $statusPanenBadge = "bg-gray-100 text-gray-600 border border-gray-200";
                    } elseif ($t['status'] === 'mati') {
                        $statusPanenText = "Tanaman Mati ❌";
                        $statusPanenBadge = "bg-red-50 text-red-400 border border-red-100";
                    } elseif ($estimasiSet && isset($t['tanggal_tanam'])) {
                        $tglTanam = new \DateTime($t['tanggal_tanam']);
                        $interval = (int)$t['estimasi_panen'];
                        $tglPanen = (clone $tglTanam)->modify("+$interval days");
                        $sisaHari = (int)$today->diff($tglPanen)->format('%r%a');

                        if ($sisaHari < 0) {
                            $statusPanenText = "Terlambat " . abs($sisaHari) . " Hari ⚠️";
                            $statusPanenBadge = "bg-red-50 text-red-600 border border-red-200 font-semibold";
                        } elseif ($sisaHari == 0) {
                            $statusPanenText = "SIAP PANEN (Hari H) 🌟";
                            $statusPanenBadge = "bg-green-100 text-green-700 border border-green-300 font-bold animate-pulse";
                        } else {
                            $statusPanenText = "H - " . $sisaHari . " Menuju Panen";
                            $statusPanenBadge = "bg-blue-50 text-blue-600 border border-blue-200 font-medium";
                        }
                    }
                ?>
                <tr class="hover:bg-gray-50/70 transition-colors">
                    <td class="px-6 py-4 font-semibold text-forest-600">
                        User ID: <?= e($t['user_id'] ?? $t['uid'] ?? '-') ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800 flex items-center gap-2">
                            <?= e($t['nama_tanaman']) ?>
                            <?php if (isset($t['ada_laporan_baru']) && $t['ada_laporan_baru'] == 1): ?>
                                <span class="inline-flex items-center bg-amber-50 text-amber-700 text-[10px] px-2 py-0.5 rounded-full font-bold border border-amber-200 animate-pulse flex-shrink-0">
                                    ⚠️ Laporan Baru
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="text-[11px] text-gray-400"><span class="font-medium text-gray-500"><?= e($t['jenis']) ?></span> · <i class="fas fa-location-dot text-[10px]"></i> <?= e($t['lokasi']) ?></div>
                    </td>
                    <td class="px-6 py-4 text-xs font-medium text-gray-500">
                        <?= date('d M Y', strtotime($t['tanggal_tanam'])) ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="<?= $badge ?> text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider"><?= $t['status'] ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="<?= $statusPanenBadge ?> text-xs px-2.5 py-1 rounded-xl shadow-sm inline-block">
                            <?= $statusPanenText ?>
                        </span>
                        <?php if($estimasiSet && $t['status'] === 'aktif'): ?>
                            <div class="text-[10px] text-gray-400 mt-1">
                                Est. Tanggal: <?= date('d M Y', strtotime($t['tanggal_prediksi_panen'] ?? $t['tanggal_tanam'])) ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="<?= url('jadwal') ?>?action=tambah&tanaman_id=<?= $t['id'] ?>" 
                               class="px-3 py-1.5 bg-forest-600 hover:bg-forest-700 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-1">
                                <i class="fas fa-calendar-plus text-[10px]"></i> Tentukan Jadwal
                            </a>
                            <button type="button" onclick="bukaModalPrediksi(<?= $t['id'] ?>, '<?= e($t['nama_tanaman']) ?>')" 
                               class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-1">
                                <i class="fas fa-magic text-[10px]"></i> Atur Prediksi
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modalAturPrediksi" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="px-6 py-4 bg-indigo-600 flex items-center justify-between">
            <h3 class="font-bold text-white flex items-center gap-2">
                <i class="fas fa-magic"></i> Atur Prediksi Waktu Panen
            </h3>
            <button type="button" onclick="tutupModalPrediksi()" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="<?= url('prediksi') ?>" class="p-6 space-y-4">
            <input type="hidden" name="tanaman_id" id="modal_tanaman_id">

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Tanaman</label>
                <input type="text" id="modal_nama_tanaman" disabled 
                    class="w-full bg-gray-50 border border-gray-200 text-gray-500 rounded-xl px-3 py-2.5 text-sm font-semibold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Estimasi Panen (Jumlah Hari) *</label>
                <input type="number" name="estimasi_panen" min="1" required placeholder="Contoh: 30"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-indigo-300">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Suhu (°C)</label>
                    <input type="number" step="0.1" name="suhu_ideal" placeholder="21" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">pH Ideal</label>
                    <input type="number" step="0.1" name="ph_ideal" placeholder="6" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Lembab (%)</label>
                    <input type="number" step="0.1" name="kelembaban_ideal" placeholder="65" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Saran Perawatan dari Admin</label>
                <textarea name="catatan_admin" rows="3" placeholder="Contoh: Penyiraman 2 kali sehari..."
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-indigo-300 resize-none"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="tutupModalPrediksi()" class="flex-1 py-2.5 text-sm font-semibold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="flex-1 py-2.5 text-sm bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-md">Simpan & Kirim</button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaModalPrediksi(id, namaTanaman) {
    document.getElementById('modal_tanaman_id').value = id;
    document.getElementById('modal_nama_tanaman').value = namaTanaman;
    document.getElementById('modalAturPrediksi').classList.remove('hidden');
}
function tutupModalPrediksi() {
    document.getElementById('modalAturPrediksi').classList.add('hidden');
}
</script>

<?php require APP_PATH . 'Views/layouts/footer.php'; ?>