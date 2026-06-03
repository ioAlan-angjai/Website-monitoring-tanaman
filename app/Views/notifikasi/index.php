<?php require APP_PATH . 'Views/layouts/header.php'; ?>

<?php
// Mendeteksi halaman aktif secara dinamis untuk query string bypass routing statis
$fullUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$actionUrl = (strpos($fullUri, '/admin/notifikasi') !== false) ? 'admin/notifikasi' : 'notifikasi';
?>

<?php if (isset($flash) && $flash): ?>
<div class="auto-dismiss mb-5 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2 bg-white border border-gray-100 shadow-sm">
    <div class="w-2 h-2 rounded-full <?= $flash['type'] === 'success' ? 'bg-green-500' : 'bg-blue-500' ?>"></div>
    <?= e($flash['msg']) ?>
</div>
<?php endif; ?>

<?php
$iconNotif = [
    'penyiraman' => ['icon' => 'fas fa-faucet-drip',      'bg' => 'bg-blue-50',   'color' => 'text-blue-500'],
    'panen'      => ['icon' => 'fas fa-seedling',         'bg' => 'bg-amber-50',  'color' => 'text-amber-600'],
    'jadwal'     => ['icon' => 'fas fa-calendar-check',    'bg' => 'bg-emerald-50','color' => 'text-emerald-600'],
    'konsultasi' => ['icon' => 'fas fa-comment-medical',   'bg' => 'bg-purple-50', 'color' => 'text-purple-600'],
    'sistem'     => ['icon' => 'fas fa-robot',             'bg' => 'bg-gray-50',   'color' => 'text-gray-500'],
];
?>

<div class="max-w-3xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <p class="text-sm text-gray-500"><?= count($notifikasi) ?> pemberitahuan masuk.</p>
        <?php if (!empty($notifikasi)): ?>
        <a href="<?= url($actionUrl) ?>?baca_semua=1"
           class="inline-flex items-center gap-2 text-xs font-bold text-forest-600 bg-forest-50 border border-forest-100 px-4 py-2.5 rounded-xl hover:bg-forest-600 hover:text-white transition-all">
            <i class="fas fa-check-double text-[10px]"></i> Tandai Semua Dibaca
        </a>
        <?php endif; ?>
    </div>

    <div class="space-y-3">
        <?php if (empty($notifikasi)): ?>
        <div class="bg-white rounded-2xl border-2 border-dashed border-gray-100 py-20 text-center">
            <i class="fas fa-bell-slash text-5xl text-gray-200 mb-3 block"></i>
            <h3 class="text-gray-400 font-bold">Kotak Masuk Kosong</h3>
            <p class="text-gray-300 text-xs mt-1">Belum ada notifikasi saat ini.</p>
        </div>
        <?php else: ?>
        <?php foreach ($notifikasi as $n):
            $ni     = $iconNotif[$n['tipe']] ?? $iconNotif['sistem'];
            $isRead = (bool)$n['is_read'];
        ?>
        <div class="group relative flex items-start gap-4 px-5 py-4 bg-white rounded-2xl border-2 transition-all duration-200
            <?= !$isRead ? 'border-forest-100 shadow-md' : 'border-white hover:border-gray-100 shadow-sm' ?>">

            <div class="w-11 h-11 <?= $ni['bg'] ?> rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                <i class="<?= $ni['icon'] ?> <?= $ni['color'] ?> text-lg"></i>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <h4 class="font-bold text-sm <?= !$isRead ? 'text-gray-900' : 'text-gray-600' ?>"><?= e($n['judul']) ?></h4>
                    <?php if (!$isRead): ?>
                    <span class="relative flex h-2 w-2 flex-shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-forest-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-forest-500"></span>
                    </span>
                    <?php endif; ?>
                </div>
                <p class="text-xs leading-relaxed <?= !$isRead ? 'text-gray-600 font-medium' : 'text-gray-400' ?>"><?= e($n['pesan']) ?></p>
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-[10px] font-bold text-gray-300 uppercase tracking-tighter">
                        <i class="far fa-clock mr-1"></i> <?= date('d M Y • H:i', strtotime($n['created_at'])) ?>
                    </span>
                    <span class="text-[10px] font-black text-gray-200">· <?= $n['tipe'] ?></span>
                </div>
            </div>

            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                <?php if (!$isRead): ?>
                <a href="<?= url($actionUrl) ?>?baca=<?= $n['id'] ?>" title="Tandai dibaca"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-forest-50 text-forest-600 hover:bg-forest-600 hover:text-white transition">
                    <i class="fas fa-check text-xs"></i>
                </a>
                <?php endif; ?>
                <a href="<?= url($actionUrl) ?>?hapus=<?= $n['id'] ?>" onclick="return confirm('Hapus notifikasi ini?')" title="Hapus"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition">
                    <i class="fas fa-trash-alt text-[10px]"></i>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require APP_PATH . 'Views/layouts/footer.php'; ?>