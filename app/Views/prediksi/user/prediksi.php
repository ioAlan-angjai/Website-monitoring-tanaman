<?php 
// 1. PANGGIL LAYOUT HEADER DI BARIS PALING ATAS
require APP_PATH . 'Views/layouts/header.php'; 
?>

<!-- HEADER HALAMAN USER -->
<div class="mb-8 p-6 bg-gradient-to-r from-emerald-800 to-teal-900 rounded-2xl text-white shadow-md">
    <div class="flex items-center gap-2 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-1">
        <i class="fas fa-satellite-dish animate-pulse"></i> Monitoring Terkalibrasi Admin
    </div>
    <h1 class="text-2xl font-black tracking-tight">Prediksi & Estimasi Panen</h1>
    <p class="text-emerald-100/80 text-xs mt-1">Halaman ini memuat kalkulasi sisa waktu panen dan rekomendasi ambang batas lingkungan yang telah divalidasi oleh tim agro-klimat admin.</p>
</div>

<!-- GRID DATA PREDIKSI UNTUK USER -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php if (empty($tanamanMilikUser)): ?>
    <div class="col-span-3 bg-white rounded-2xl border-2 border-dashed border-slate-200 py-16 text-center">
        <i class="fas fa-seedling text-4xl text-slate-300 mb-2 block"></i>
        <h3 class="text-slate-500 font-bold text-sm">Tidak Ada Tanaman Aktif</h3>
        <p class="text-slate-400 text-xs mt-0.5">Silakan tambahkan data tanaman Anda terlebih dahulu di menu Utama.</p>
    </div>
    <?php else: ?>
    <?php 
    foreach ($tanamanMilikUser as $t):
        $estimasi = (int)($t['estimasi_panen'] ?? 0);
        $tglTanam = new DateTime($t['tanggal_tanam']);
        $tglSekarang = new DateTime('today');

        $diff = $tglSekarang->diff($tglTanam);
        $hariBerjalan = $diff->invert ? $diff->days : -$diff->days; 

        $sisa = $estimasi - $hariBerjalan;
        $prog = ($estimasi > 0) ? round(($hariBerjalan / $estimasi) * 100) : 0;
        if ($prog > 100) $prog = 100;
        if ($prog < 0) $prog = 0;

        $tglPanen = (clone $tglTanam)->modify('+' . $estimasi . ' days');
    ?>
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-black text-slate-800 text-lg tracking-tight"><?= htmlspecialchars($t['nama_tanaman']) ?></h3>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5"><i class="fas fa-calendar mr-1"></i>Ditanam: <?= date('d M Y', strtotime($t['tanggal_tanam'])) ?></p>
                </div>
                <div class="px-2.5 py-1 rounded-lg font-black text-xs <?= $sisa <= 0 ? 'bg-rose-500 text-white' : 'bg-slate-900 text-white' ?>">
                    <?= $sisa <= 0 ? 'Siap Panen' : $sisa . ' Hari Lagi' ?>
                </div>
            </div>

            <div class="mb-4">
                <div class="flex justify-between text-[11px] mb-1 font-semibold text-slate-500">
                    <span>Estimasi Kematangan</span>
                    <span><?= $prog ?>%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full bg-emerald-500" style="width: <?= $prog ?>%"></div>
                </div>
            </div>

            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100/70 mb-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2"><i class="fas fa-temperature-high text-emerald-500 mr-1"></i>Target Lingkungan Ideal (Admin)</p>
                <div class="grid grid-cols-3 gap-1.5 text-center">
                    <div class="p-1.5 bg-white rounded-lg border border-slate-100">
                        <span class="text-[9px] block text-slate-400">Suhu</span>
                        <span class="text-xs font-bold text-slate-700"><?= !empty($t['suhu_ideal']) ? $t['suhu_ideal'] . '°C' : '--' ?></span>
                    </div>
                    <div class="p-1.5 bg-white rounded-lg border border-slate-100">
                        <span class="text-[9px] block text-slate-400">pH Air</span>
                        <span class="text-xs font-bold text-slate-700"><?= !empty($t['ph_ideal']) ? $t['ph_ideal'] : '--' ?></span>
                    </div>
                    <div class="p-1.5 bg-white rounded-lg border border-slate-100">
                        <span class="text-[9px] block text-slate-400">Kelembaban</span>
                        <span class="text-xs font-bold text-slate-700"><?= !empty($t['kelembaban_ideal']) ? $t['kelembaban_ideal'] . '%' : '--' ?></span>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-dashed border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1"><i class="fas fa-comment-medical text-indigo-500 mr-1"></i>Catatan Intervensi Agro</p>
                <?php if (!empty($t['catatan_admin'])): ?>
                    <div class="p-2.5 bg-indigo-50/50 border border-indigo-100/50 rounded-xl text-xs text-indigo-950 leading-relaxed italic">
                        "<?= htmlspecialchars($t['catatan_admin']) ?>"
                    </div>
                <?php else: ?>
                    <p class="text-xs text-slate-400 italic">Belum ada catatan atau tindakan intervensi khusus dari admin.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="px-6 py-3 bg-slate-50/80 border-t border-slate-100 text-right">
            <span class="text-[10px] text-slate-400 font-medium">Perkiraan Tanggal Panen: </span>
            <span class="text-xs font-bold text-slate-700"><?= date('d F Y', strtotime($tglPanen->format('Y-m-d'))) ?></span>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php 
// 2. PANGGIL LAYOUT FOOTER DI BARIS PALING BAWAH
require APP_PATH . 'Views/layouts/footer.php'; 
?>