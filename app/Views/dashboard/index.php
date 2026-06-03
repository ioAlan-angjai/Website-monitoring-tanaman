<?php require APP_PATH . 'Views/layouts/header.php'; ?>

<?php if ($flash): ?>
<div class="auto-dismiss mb-5 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2
    <?= $flash['type']==='success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-blue-50 text-blue-700 border border-blue-200' ?>">
    <i class="fas <?= $flash['type']==='success' ? 'fa-check-circle' : 'fa-info-circle' ?>"></i>
    <?= e($flash['msg']) ?>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">
    <?php
    $stats = [
        ['label'=>'Tanaman Aktif',      'value'=>$totalTanaman,  'icon'=>'fas fa-leaf',             'color'=>'from-forest-500 to-forest-700'],
        ['label'=>'Jadwal Hari Ini',    'value'=>$jadwalHariIni, 'icon'=>'fas fa-calendar-check',  'color'=>'from-blue-400 to-blue-600'],
        ['label'=>'Sudah Panen',        'value'=>$tanamPanen,    'icon'=>'fas fa-basket-shopping',  'color'=>'from-amber-400 to-amber-600'],
        ['label'=>'Notif Belum Dibaca', 'value'=>$unread,        'icon'=>'fas fa-bell',             'color'=>'from-purple-400 to-purple-600'],
    ];
    foreach ($stats as $s): ?>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 card-hover flex items-center gap-4">
        <div class="w-12 h-12 bg-gradient-to-br <?= $s['color'] ?> rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
            <i class="<?= $s['icon'] ?> text-white text-lg"></i>
        </div>
        <div>
            <div class="text-2xl font-bold text-gray-800"><?= $s['value'] ?></div>
            <div class="text-xs text-gray-500 font-medium"><?= $s['label'] ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-700 flex items-center gap-2"><i class="fas fa-leaf text-forest-500"></i> Tanaman Aktif</h2>
                <a href="<?= url('tanaman') ?>" class="text-xs text-forest-600 hover:text-forest-800 font-semibold">Lihat semua →</a>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php if (empty($tanamanAktif)): ?>
                <div class="col-span-2 text-center py-10 text-gray-400">
                    <i class="fas fa-seedling text-4xl mb-3 block text-gray-200"></i>
                    Belum ada tanaman. <a href="<?= url('tanaman') ?>" class="text-forest-500 font-semibold">Tambah sekarang →</a>
                </div>
                <?php else: ?>
                <?php foreach ($tanamanAktif as $t):
                    $sisa = sisaHariPanen($t['tanggal_tanam'], $t['estimasi_panen']);
                    $prog = progressTanaman($t['tanggal_tanam'], $t['estimasi_panen']);
                    $badge = $sisa <= 7 ? 'bg-amber-50 text-amber-600' : 'bg-forest-50 text-forest-600';
                ?>
                <div class="border border-gray-100 rounded-xl p-4 hover:border-forest-200 transition card-hover">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="font-bold text-gray-700 text-sm"><?= e($t['nama_tanaman']) ?></h3>
                            <p class="text-xs text-gray-400"><?= e($t['jenis']) ?> · <?= e($t['lokasi']) ?></p>
                        </div>
                        <span class="<?= $badge ?> text-xs font-semibold px-2 py-0.5 rounded-full">
                            <?= $sisa > 0 ? $sisa.' hr lagi' : 'Siap panen!' ?>
                        </span>
                    </div>
                    <div class="mt-3">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Progress</span><span><?= $prog ?>%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-gradient-to-r from-forest-400 to-forest-600 h-2 rounded-full transition-all duration-500" style="width:<?= $prog ?>%"></div>
                        </div>
                    </div>
                    <div class="mt-2 flex items-center gap-1 text-xs text-gray-400">
                        <i class="fas fa-calendar text-gray-300"></i> Tanam: <?= formatTanggal($t['tanggal_tanam']) ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-700 flex items-center gap-2"><i class="fas fa-calendar-day text-blue-500"></i> Jadwal Hari Ini</h2>
                <a href="<?= url('jadwal') ?>" class="text-xs text-blue-500 font-semibold">Semua →</a>
            </div>
            <div class="divide-y divide-gray-50">
                <?php if (empty($jadwalHari)): ?>
                <div class="text-center py-8 text-gray-400 text-sm">
                    <i class="fas fa-check-circle text-3xl mb-2 block text-gray-200"></i>Tidak ada jadwal perawatan hari ini
                </div>
                <?php else: ?>
                <?php
                $iconJadwal = ['penyiraman'=>'fas fa-droplet text-blue-400','pemupukan'=>'fas fa-flask text-green-400','pemangkasan'=>'fas fa-scissors text-orange-400','pengendalian_hama'=>'fas fa-bug text-red-400','lainnya'=>'fas fa-star text-gray-400'];
                foreach ($jadwalHari as $j):
                    $icon = $iconJadwal[$j['jenis_perawatan']] ?? 'fas fa-star text-gray-400';
                ?>
                <div class="px-5 py-3 flex items-center gap-3 hover:bg-gray-50 transition">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="<?= $icon ?> text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate"><?= e($j['nama_tanaman']) ?></p>
                        <p class="text-xs text-gray-400 capitalize"><?= str_replace('_',' ',$j['jenis_perawatan']) ?> · <?= substr($j['waktu'],0,5) ?></p>
                    </div>
                    <form method="POST" action="<?= url('jadwal') ?>">
                        <input type="hidden" name="action" value="selesai">
                        <input type="hidden" name="id" value="<?= $j['id'] ?>">
                        <button type="submit" class="text-xs bg-forest-50 text-forest-600 hover:bg-forest-100 px-2 py-1 rounded-lg font-semibold transition">Selesai</button>
                    </form>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-700 flex items-center gap-2"><i class="fas fa-bell text-amber-500"></i> Notifikasi</h2>
                <a href="<?= url('notifikasi') ?>" class="text-xs text-amber-500 font-semibold">Semua →</a>
            </div>
            <div class="divide-y divide-gray-50">
                <?php
                $iconNotif = ['penyiraman'=>'fas fa-droplet text-blue-400','panen'=>'fas fa-basket-shopping text-amber-400','jadwal'=>'fas fa-calendar text-green-400','konsultasi'=>'fas fa-comments text-purple-400','sistem'=>'fas fa-info-circle text-gray-400'];
                foreach ($notifTerbaru as $n):
                    $icon = $iconNotif[$n['tipe']] ?? 'fas fa-bell text-gray-400';
                ?>
                <div class="px-5 py-3 flex items-start gap-3 <?= !$n['is_read'] ? 'bg-amber-50/30' : '' ?> hover:bg-gray-50 transition">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="<?= $icon ?> text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700"><?= e($n['judul']) ?></p>
                        <p class="text-xs text-gray-400 mt-0.5 line-clamp-1"><?= e($n['pesan']) ?></p>
                    </div>
                    <?php if (!$n['is_read']): ?><div class="w-2 h-2 bg-amber-400 rounded-full mt-1.5 flex-shrink-0"></div><?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . 'Views/layouts/footer.php'; ?>