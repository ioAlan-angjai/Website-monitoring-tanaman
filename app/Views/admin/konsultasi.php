<?php require APP_PATH . 'Views/layouts/header.php'; ?>

<?php if (!empty($flash)): ?>
<div class="auto-dismiss mb-5 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2 bg-green-50 text-green-700 border border-green-200">
    <i class="fas fa-check-circle"></i> <?= e($flash['msg']) ?>
</div>
<?php endif; ?>

<?php
$statusStyle = [
    'menunggu' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
    'dijawab'  => 'bg-green-50 text-green-600 border-green-100',
    'ditutup'  => 'bg-gray-100 text-gray-500 border-gray-200',
];
?>

<div class="flex gap-2 flex-wrap mb-6">
    <?php foreach (['semua'=>'Semua','menunggu'=>'Menunggu','dijawab'=>'Dijawab','ditutup'=>'Ditutup'] as $k=>$v): ?>
    <a href="<?= url('admin/konsultasi') ?>?status=<?= $k ?>"
       class="px-3 py-1.5 rounded-lg text-sm font-medium transition
       <?= $filter===$k ? 'bg-forest-600 text-white' : 'bg-white text-gray-600 hover:bg-forest-50 border border-gray-200' ?>">
         <?= $v ?>
    </a>
    <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <div class="<?= $detail ? 'xl:col-span-1' : 'xl:col-span-3' ?> space-y-3 max-h-[72vh] overflow-y-auto pr-1">
    <?php if (empty($konsultasi)): ?>
    <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 p-10 text-center">
        <i class="fas fa-inbox text-4xl text-gray-200 mb-3 block"></i>
        <p class="text-gray-400 text-sm font-medium">Tidak ada konsultasi.</p>
    </div>
    <?php else: ?>
    <?php foreach ($konsultasi as $k): ?>
    <?php 
        // Proteksi Admin List: Jika status NULL atau tidak dikenali, fallback aman ke 'menunggu'
        $currentStatus = (!empty($k['status']) && isset($statusStyle[$k['status']])) ? $k['status'] : 'menunggu';

        // Proteksi ID: Jika ID bernilai NULL di database, ubah ke 0 agar parameter URL ?detail= tidak kosong/rusak
        $safeId = !empty($k['id']) ? $k['id'] : 0;
    ?>
    <a href="<?= url('admin/konsultasi') ?>?status=<?= $filter ?>&detail=<?= $safeId ?>"
       class="block bg-white rounded-xl border-2 p-4 transition-all
       <?= $detailId == $k['id'] ? 'border-forest-500 shadow-md' : 'border-white hover:border-gray-200 shadow-sm' ?>">
        <div class="flex justify-between items-start gap-2 mb-1">
            <h3 class="font-bold text-gray-800 text-sm truncate flex-1"><?= e($k['judul']) ?></h3>
            <span class="<?= $statusStyle[$currentStatus] ?> border text-[9px] font-black px-2 py-0.5 rounded-lg uppercase flex-shrink-0">
                <?= $currentStatus ?>
            </span>
        </div>
        <p class="text-xs text-gray-500 mb-1">oleh <span class="font-semibold text-gray-700"><?= e($k['nama']) ?></span></p>
        <p class="text-xs text-gray-400 line-clamp-1"><?= e($k['pesan']) ?></p>
        <p class="text-[10px] text-gray-300 mt-2"><i class="far fa-clock mr-1"></i><?= date('d M Y H:i', strtotime($k['created_at'])) ?></p>
    </a>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

    <?php if ($detail): ?>
    <?php 
        // Proteksi Admin Detail Pane: Menjaga stabilitas jika panel chat dibuka
        $detailStatus = (!empty($detail['status']) && isset($statusStyle[$detail['status']])) ? $detail['status'] : 'menunggu';
    ?>
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden flex flex-col" style="height:72vh">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center font-bold text-sm">
                        <?= strtoupper(substr($detail['user_nama'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm"><?= e($detail['judul']) ?></h3>
                        <p class="text-[10px] text-gray-400">dari <span class="font-semibold"><?= e($detail['user_nama']) ?></span></p>
                    </div>
                </div>
                <?php if ($detailStatus !== 'ditutup'): ?>
                <form method="POST" action="<?= url('admin/konsultasi') ?>" onsubmit="return confirm('Tutup konsultasi ini?')">
                    <input type="hidden" name="action" value="tutup">
                    <input type="hidden" name="konsultasi_id" value="<?= $detail['id'] ?>">
                    <button class="text-xs px-3 py-1.5 bg-gray-100 text-gray-500 hover:bg-red-50 hover:text-red-500 rounded-lg font-bold transition">
                        <i class="fas fa-lock mr-1"></i> Tutup
                    </button>
                </form>
                <?php endif; ?>
            </div>

            <div class="flex-1 overflow-y-auto p-5 space-y-4 bg-gray-50/50">
                <div class="flex">
                    <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-none px-4 py-3 max-w-[85%] shadow-sm">
                        <p class="text-[10px] font-bold text-amber-600 mb-1 uppercase tracking-tighter">
                            👤 <?= e($detail['user_nama']) ?>
                        </p>
                        <p class="text-sm text-gray-700 leading-relaxed"><?= nl2br(e($detail['pesan'])) ?></p>
                        <p class="text-[9px] text-gray-400 mt-1.5"><?= date('H:i', strtotime($detail['created_at'])) ?></p>
                    </div>
                </div>

                <?php
                $myId = \App\Core\Auth::id();
                foreach ($balasan as $b):
                    $isMe = $b['user_id'] == $myId;
                ?>
                <div class="flex <?= $isMe ? 'justify-end' : '' ?>">
                    <div class="<?= $isMe ? 'bg-forest-600 text-white rounded-tr-none' : 'bg-white border border-gray-200 rounded-tl-none text-gray-700' ?> rounded-2xl px-4 py-3 max-w-[85%] shadow-sm">
                        <p class="text-[10px] font-bold <?= $isMe ? 'text-forest-200' : 'text-amber-600' ?> mb-1 uppercase tracking-tighter">
                            <?= $isMe ? '👩‍💼 Admin (Anda)' : '👤 ' . e($b['nama']) ?>
                        </p>
                        <p class="text-sm leading-relaxed"><?= nl2br(e($b['pesan'])) ?></p>
                        <p class="text-[9px] <?= $isMe ? 'text-forest-200' : 'text-gray-400' ?> mt-1.5 text-right"><?= date('H:i', strtotime($b['created_at'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($detailStatus !== 'ditutup'): ?>
            <div class="p-4 bg-white border-t border-gray-100 flex-shrink-0">
                <form method="POST" action="<?= url('admin/konsultasi') ?>" class="flex gap-2 bg-gray-50 p-2 rounded-xl border border-gray-200 focus-within:border-forest-400 transition">
                    <input type="hidden" name="action" value="balas">
                    <input type="hidden" name="konsultasi_id" value="<?= $detail['id'] ?>">
                    <input type="text" name="pesan" placeholder="Tulis jawaban admin..." required autocomplete="off"
                        class="flex-1 bg-transparent px-3 py-2 text-sm outline-none">
                    <button type="submit" class="bg-forest-600 text-white w-10 h-10 rounded-lg flex items-center justify-center hover:bg-forest-700 transition">
                        <i class="fas fa-paper-plane text-sm"></i>
                    </button>
                </form>
            </div>
            <?php else: ?>
            <div class="p-4 bg-gray-50 text-center text-xs text-gray-400 font-bold uppercase tracking-widest flex-shrink-0">
                <i class="fas fa-lock mr-2"></i> Konsultasi Ditutup
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php require APP_PATH . 'Views/layouts/footer.php'; ?>