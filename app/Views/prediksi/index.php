<?php require APP_PATH . 'Views/layouts/header.php'; ?>

<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-indigo-900 to-slate-900 p-6 rounded-2xl text-white shadow-lg">
    <div>
        <div class="flex items-center gap-2 text-indigo-300 text-xs font-bold uppercase tracking-wider mb-1">
            <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-ping"></span>
            Laboratorium Analisis & Estimasi
        </div>
        <h1 class="text-2xl font-black tracking-tight">Pusat Prediksi Panen</h1>
        <p class="text-slate-300 text-xs mt-1 max-w-xl">Kelola algoritma waktu tunggu, kalkulasi sisa hari, dan sesuaikan parameter lingkungan ideal untuk optimalisasi hasil panen seluruh user.</p>
    </div>
    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md p-3 rounded-xl border border-white/10 self-start md:self-center">
        <i class="fas fa-calculator text-2xl text-indigo-300"></i>
        <div>
            <p class="text-[10px] text-slate-400 font-bold uppercase">Metode Hitung</p>
            <p class="text-xs font-bold text-white">Masa Tanam + Estimasi Hari</p>
        </div>
    </div>
</div>

<?php 
$segeraPanenCount = 0;
$terlambatCount = 0;
$normalCount = 0;

if (!empty($allTanamanUser)) {
    foreach ($allTanamanUser as $t) {
        if (($t['status'] ?? 'aktif') !== 'aktif') continue;
        $sisa = sisaHariPanen($t['tanggal_tanam'], (int)($t['estimasi_panen'] ?? 0));
        if ($sisa < 0) $terlambatCount++;
        elseif ($sisa <= 7) $segeraPanenCount++;
        else $normalCount++;
    }
}
?>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-amber-50 border border-amber-200/60 p-4 rounded-xl flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-600"><i class="fas fa-hourglass-half text-lg"></i></div>
        <div>
            <h4 class="text-2xl font-black text-slate-800"><?= $segeraPanenCount ?></h4>
            <p class="text-[11px] text-slate-500 font-semibold">Segera Panen (≤ 7 Hari)</p>
        </div>
    </div>
    <div class="bg-rose-50 border border-rose-200/60 p-4 rounded-xl flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-rose-500/10 flex items-center justify-center text-rose-600"><i class="fas fa-calendar-times text-lg"></i></div>
        <div>
            <h4 class="text-2xl font-black text-slate-800"><?= $terlambatCount ?></h4>
            <p class="text-[11px] text-slate-500 font-semibold">Melebihi Est. Waktu</p>
        </div>
    </div>
    <div class="bg-indigo-50 border border-indigo-200/60 p-4 rounded-xl flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-600"><i class="fas fa-chart-line text-lg"></i></div>
        <div>
            <h4 class="text-2xl font-black text-slate-800"><?= $normalCount ?></h4>
            <p class="text-[11px] text-slate-500 font-semibold">Fase Pertumbuhan Stabil</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php if (empty($allTanamanUser)): ?>
    <div class="col-span-3 bg-white rounded-2xl border-2 border-dashed border-slate-200 py-20 text-center">
        <i class="fas fa-chart-pie text-5xl text-slate-300 mb-3 block"></i>
        <h3 class="text-slate-500 font-bold">Belum Ada Data Komparasi</h3>
        <p class="text-slate-400 text-xs mt-1">Sistem belum membaca data tanaman aktif untuk dikalkulasi.</p>
    </div>
    <?php else: ?>
    <?php 
    foreach ($allTanamanUser as $t):
        if (($t['status'] ?? 'aktif') !== 'aktif') continue;

        $estimasi = (int)($t['estimasi_panen'] ?? 0);
        $sisa = sisaHariPanen($t['tanggal_tanam'], $estimasi);
        $prog = progressTanaman($t['tanggal_tanam'], $estimasi);
        $tglPanen = (new DateTime($t['tanggal_tanam']))->modify('+' . $estimasi . ' days');

        // Pengkondisian Tema Card Berdasarkan Prioritas Prediksi
        if ($sisa < 0) {
            $themeBg = 'border-rose-100 bg-gradient-to-b from-rose-50/30 to-white';
            $countdownBadge = 'bg-rose-500 text-white';
            $barColor = 'bg-rose-500';
        } elseif ($sisa <= 7) {
            $themeBg = 'border-amber-100 bg-gradient-to-b from-amber-50/30 to-white';
            $countdownBadge = 'bg-amber-500 text-slate-900';
            $barColor = 'bg-amber-500';
        } else {
            $themeBg = 'border-slate-100 bg-white';
            $countdownBadge = 'bg-slate-900 text-white';
            $barColor = 'bg-emerald-500';
        }
    ?>
    <div class="border rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col justify-between <?= $themeBg ?>">
        
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded uppercase tracking-wider">UID: <?= e($t['user_id'] ?? '-') ?></span>
                    <h3 class="font-black text-slate-800 text-xl mt-1 tracking-tight"><?= e($t['nama_tanaman']) ?></h3>
                </div>
                <div class="px-3 py-1.5 rounded-xl font-black text-xs text-center shadow-sm uppercase tracking-wide <?= $countdownBadge ?>">
                    <?php if ($sisa < 0): ?>
                        Maju <?= abs($sisa) ?> H
                    <?php elseif ($sisa == 0): ?>
                        Hari H Panen
                    <?php else: ?>
                        <?= $sisa ?> Hari Lagi
                    <?php endif; ?>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 text-xs text-slate-500 mb-4 bg-slate-50 p-2.5 rounded-xl">
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase">Varietas/Jenis</p>
                    <p class="font-bold text-slate-700 truncate"><?= e($t['jenis'] ?? 'Kategori Umum') ?></p>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase">Zona Tanam</p>
                    <p class="font-semibold text-slate-700 truncate"><i class="fas fa-location-dot text-slate-400 mr-1"></i><?= e($t['lokasi'] ?? 'Area Monitor') ?></p>
                </div>
            </div>

            <div class="mb-5">
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-slate-400 font-medium">Akurasi Kematangan</span>
                    <span class="font-black text-slate-700"><?= $prog ?>%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="h-2 rounded-full <?= $barColor ?> transition-all duration-500" style="width: <?= $prog ?>%"></div>
                </div>
            </div>

            <div class="border-t border-dashed border-slate-100 pt-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2"><i class="fas fa-flask text-indigo-500 mr-1"></i>Kondisi Lingkungan Target</p>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="p-2 bg-slate-50 rounded-lg">
                        <span class="text-[9px] block text-slate-400 font-medium">Suhu</span>
                        <span class="text-xs font-bold text-slate-700"><?= isset($t['suhu_ideal']) ? $t['suhu_ideal'].'°C' : '--' ?></span>
                    </div>
                    <div class="p-2 bg-slate-50 rounded-lg">
                        <span class="text-[9px] block text-slate-400 font-medium">pH Air</span>
                        <span class="text-xs font-bold text-slate-700"><?= isset($t['ph_ideal']) ? $t['ph_ideal'] : '--' ?></span>
                    </div>
                    <div class="p-2 bg-slate-50 rounded-lg">
                        <span class="text-[9px] block text-slate-400 font-medium">Kelembaban</span>
                        <span class="text-xs font-bold text-slate-700"><?= isset($t['kelembaban_ideal']) ? $t['kelembaban_ideal'].'%' : '--' ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100/80 grid grid-cols-2 gap-3">
            <div class="text-left">
                <p class="text-[9px] font-bold text-slate-400 uppercase">Est. Panen Masuk</p>
                <p class="text-xs font-black text-slate-700"><?= date('d/m/Y', strtotime($tglPanen->format('Y-m-d'))) ?></p>
            </div>
            <button type="button" 
                    onclick="bukaModalPrediksi(<?= $t['id'] ?>, '<?= e($t['nama_tanaman']) ?>', '<?= $t['estimasi_panen'] ?? '' ?>', '<?= $t['suhu_ideal'] ?? '' ?>', '<?= $t['ph_ideal'] ?? '' ?>', '<?= $t['kelembaban_ideal'] ?? '' ?>', '<?= e($t['catatan_admin'] ?? '') ?>')" 
                    class="w-full py-2 px-3 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 transition-colors shadow-sm flex items-center justify-center gap-1.5">
                <i class="fas fa-sliders text-[10px]"></i> Kalibrasi Data
            </button>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<div id="modalAturPrediksi" class="hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden border border-slate-100 animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 bg-slate-900 flex items-center justify-between">
            <h3 class="font-black text-white text-sm tracking-tight"><i class="fas fa-calculator text-indigo-400 mr-2"></i>Kalibrator Algoritma Panen</h3>
            <button type="button" onclick="tutupModalPrediksi()" class="text-slate-400 hover:text-white transition-colors"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="<?= url('prediksi') ?>" class="p-6 space-y-4">
            <input type="hidden" name="tanaman_id" id="modal_tanaman_id">
            
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanaman Target</label>
                <input type="text" id="modal_nama_tanaman" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-500 rounded-xl px-3 py-2.5 text-sm font-bold outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Durasi Siklus Hidup (Hari) *</label>
                <div class="relative">
                    <input type="number" name="estimasi_panen" id="modal_estimasi_panen" min="1" required class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-semibold focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                    <span class="absolute right-3 top-2.5 text-xs text-slate-400 font-medium">Hari</span>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-3">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Penyesuaian Ambang Batas Ideal</label>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <span class="text-[10px] block text-slate-500 font-semibold mb-1">Suhu (°C)</span>
                        <input type="number" step="0.1" name="suhu_ideal" id="modal_suhu_ideal" class="w-full border border-slate-200 rounded-xl p-2 text-xs font-bold text-center">
                    </div>
                    <div>
                        <span class="text-[10px] block text-slate-500 font-semibold mb-1">Kadar pH</span>
                        <input type="number" step="0.1" name="ph_ideal" id="modal_ph_ideal" class="w-full border border-slate-200 rounded-xl p-2 text-xs font-bold text-center">
                    </div>
                    <div>
                        <span class="text-[10px] block text-slate-500 font-semibold mb-1">Lembab (%)</span>
                        <input type="number" step="0.1" name="kelembaban_ideal" id="modal_kelembaban_ideal" class="w-full border border-slate-200 rounded-xl p-2 text-xs font-bold text-center">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Rekomendasi Intervensi Agro-Klimat</label>
                <textarea name="catatan_admin" id="modal_catatan_admin" rows="3" placeholder="Saran tindakan penyiraman, pemberian nutrisi khusus, atau pengaturan naungan..." class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all resize-none"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="tutupModalPrediksi()" class="flex-1 py-2.5 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Batalkan</button>
                <button type="submit" class="flex-1 py-2.5 text-xs bg-indigo-600 text-white rounded-xl font-black hover:bg-indigo-700 shadow-md shadow-indigo-200 transition-colors">Terapkan Analisis</button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaModalPrediksi(id, nama, estimasi, suhu, ph, lembab, catatan) {
    document.getElementById('modal_tanaman_id').value = id;
    document.getElementById('modal_nama_tanaman').value = nama;
    document.getElementById('modal_estimasi_panen').value = estimasi;
    document.getElementById('modal_suhu_ideal').value = suhu;
    document.getElementById('modal_ph_ideal').value = ph;
    document.getElementById('modal_kelembaban_ideal').value = lembab;
    document.getElementById('modal_catatan_admin').value = catatan;
    
    document.getElementById('modalAturPrediksi').classList.remove('hidden');
}

function tutupModalPrediksi() {
    document.getElementById('modalAturPrediksi').classList.add('hidden');
}
</script>

<?php require APP_PATH . 'Views/layouts/footer.php'; ?>\