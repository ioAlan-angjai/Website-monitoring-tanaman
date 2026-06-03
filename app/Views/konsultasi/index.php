<?php require APP_PATH . 'Views/layouts/header.php'; ?>

<?php if ($flash): ?>
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

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <p class="text-sm text-gray-500">Tanyakan masalah tanamanmu langsung pada ahlinya.</p>
    <button onclick="document.getElementById('modalKonsultasi').classList.remove('hidden')"
        class="bg-forest-600 hover:bg-forest-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg transition flex items-center gap-2">
        <i class="fas fa-plus-circle"></i> Buat Konsultasi Baru
    </button>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <div class="<?= $detail ? 'xl:col-span-1' : 'xl:col-span-3' ?> space-y-3 max-h-[70vh] overflow-y-auto pr-1">
        <?php if (empty($konsultasi)): ?>
        <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 p-10 text-center">
            <i class="fas fa-comment-slash text-4xl text-gray-200 mb-3 block"></i>
            <p class="text-gray-400 font-medium text-sm">Belum ada riwayat konsultasi.</p>
        </div>
        <?php else: ?>
        <?php foreach ($konsultasi as $k): ?>
        <?php 
            // Proteksi: Jika status di database NULL atau tidak terdaftar di array, fallback ke 'menunggu'
            $currentStatus = (!empty($k['status']) && isset($statusStyle[$k['status']])) ? $k['status'] : 'menunggu';
        ?>
        <a href="<?= url('konsultasi') ?>?detail=<?= $k['id'] ?>"
           class="block bg-white rounded-xl border-2 p-4 transition-all
           <?= $detailId === $k['id'] ? 'border-forest-500 shadow-md' : 'border-white hover:border-gray-200 shadow-sm' ?>">
            <div class="flex justify-between items-start gap-2 mb-1.5">
                <h3 class="font-bold text-gray-800 text-sm truncate flex-1"><?= e($k['judul']) ?></h3>
                <span class="<?= $statusStyle[$currentStatus] ?> border text-[9px] font-black px-2 py-0.5 rounded-lg uppercase flex-shrink-0">
                    <?= $currentStatus ?>
                </span>
            </div>
            <p class="text-xs text-gray-500 line-clamp-1 mb-2"><?= e($k['pesan']) ?></p>
            <p class="text-[10px] text-gray-400"><i class="far fa-calendar-alt mr-1"></i><?= date('d M Y', strtotime($k['created_at'])) ?></p>
        </a>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if ($detail): ?>
    <?php 
        // Proteksi untuk status room yang sedang aktif dibuka
        $detailStatus = (!empty($detail['status']) && isset($statusStyle[$detail['status']])) ? $detail['status'] : 'menunggu';
    ?>
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden flex flex-col" style="height:70vh">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3 bg-white flex-shrink-0">
                <div class="w-10 h-10 bg-forest-100 text-forest-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-leaf"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-sm"><?= e($detail['judul']) ?></h3>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold"><?= $detailStatus ?></p>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-5 space-y-4 bg-gray-50/50">
                <div class="flex justify-end">
                    <div class="bg-forest-600 text-white rounded-2xl rounded-tr-none px-4 py-3 max-w-[85%] shadow-sm">
                        <p class="text-[10px] font-bold text-forest-200 mb-1 uppercase tracking-tighter">Pertanyaan Anda</p>
                        <p class="text-sm leading-relaxed"><?= nl2br(e($detail['pesan'])) ?></p>
                        <p class="text-[9px] text-forest-200 mt-1.5 text-right"><?= date('H:i', strtotime($detail['created_at'])) ?></p>
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
                            <?= $isMe ? 'Anda' : '👩‍💼 Admin (' . e($b['nama']) . ')' ?>
                        </p>
                        <p class="text-sm leading-relaxed"><?= nl2br(e($b['pesan'])) ?></p>
                        <p class="text-[9px] <?= $isMe ? 'text-forest-200' : 'text-gray-400' ?> mt-1.5 text-right"><?= date('H:i', strtotime($b['created_at'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($detailStatus !== 'ditutup'): ?>
            <div class="p-4 bg-white border-t border-gray-100 flex-shrink-0">
                <form method="POST" action="<?= url('konsultasi') ?>" class="flex gap-2 bg-gray-50 p-2 rounded-xl border border-gray-200 focus-within:border-forest-400 transition">
                    <input type="hidden" name="action" value="balas">
                    <input type="hidden" name="konsultasi_id" value="<?= $detail['id'] ?>">
                    <input type="text" name="pesan" placeholder="Tulis balasan..." required autocomplete="off"
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

<div id="modalKonsultasi" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-5 bg-forest-600 text-white flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg">Tanya Ahli</h3>
                <p class="text-xs text-forest-200">Konsultasikan tanamanmu sekarang</p>
            </div>
            <button onclick="document.getElementById('modalKonsultasi').classList.add('hidden')"
                class="w-8 h-8 flex items-center justify-center rounded-full bg-forest-700 hover:bg-forest-800 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="<?= url('konsultasi') ?>" class="p-6 space-y-4">
            <input type="hidden" name="action" value="buat">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Topik / Judul *</label>
                <input type="text" name="judul" placeholder="Contoh: Daun Aglonema Menguning" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-forest-300 focus:bg-white outline-none transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Detail Masalah *</label>
                <textarea name="pesan" rows="5" placeholder="Jelaskan kondisi tanaman atau pertanyaan Anda secara detail..." required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-forest-300 focus:bg-white outline-none resize-none transition"></textarea>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" onclick="document.getElementById('modalKonsultasi').classList.add('hidden')"
                    class="flex-1 px-4 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition">Batal</button>
                <button type="submit"
                    class="flex-[2] px-4 py-3 text-sm font-bold bg-forest-600 text-white rounded-xl hover:bg-forest-700 transition">
                    <i class="fas fa-paper-plane mr-1"></i> Kirim Pertanyaan
                </button>
            </div>
        </form>
    </div>
</div>

<?php require APP_PATH . 'Views/layouts/footer.php'; ?>