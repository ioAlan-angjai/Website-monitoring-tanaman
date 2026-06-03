<?php require APP_PATH . 'Views/layouts/header.php'; ?>

<?php if ($flash): ?>
<div class="auto-dismiss mb-5 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2 bg-green-50 text-green-700 border border-green-200">
    <i class="fas fa-check-circle"></i> <?= e($flash['msg']) ?>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
            <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-plus-circle text-forest-500"></i> Catat Pertumbuhan
            </h3>
            <form method="POST" action="<?= url('perkembangan') ?>" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Pilih Tanaman</label>
                    <select name="tanaman_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-forest-300 text-sm" required>
                        <option value="">-- Pilih Tanaman --</option>
                        <?php foreach ($tanamanDropdown as $t): ?>
                        <option value="<?= $t['id'] ?>"><?= e($t['nama_tanaman']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>"
                            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-forest-300 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Tinggi (cm)</label>
                        <input type="number" step="0.1" name="tinggi_cm" placeholder="0.0"
                            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-forest-300 text-sm" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Kondisi</label>
                    <select name="kondisi" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-forest-300 text-sm">
                        <option value="sangat_baik">🌟 Sangat Baik</option>
                        <option value="baik" selected>✅ Baik</option>
                        <option value="cukup">⚠️ Cukup</option>
                        <option value="buruk">❌ Buruk</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Catatan</label>
                    <textarea name="catatan" rows="3" placeholder="Tulis catatan pengamatan..."
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-forest-300 text-sm resize-none"></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-forest-600 text-white font-bold py-3 rounded-xl hover:bg-forest-700 transition text-sm">
                    <i class="fas fa-save mr-2"></i> Simpan Log
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="<?= (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'lg:col-span-3' : 'lg:col-span-2' ?>">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-chart-line text-forest-500"></i> Riwayat Perkembangan
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-5 py-3 text-xs font-bold text-gray-400 uppercase">Tanggal</th>
                            <th class="text-left px-5 py-3 text-xs font-bold text-gray-400 uppercase">Tanaman</th>
                            <th class="text-left px-5 py-3 text-xs font-bold text-gray-400 uppercase">Tinggi</th>
                            <th class="text-left px-5 py-3 text-xs font-bold text-gray-400 uppercase">Kondisi</th>
                            <th class="text-left px-5 py-3 text-xs font-bold text-gray-400 uppercase">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if (empty($riwayat)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-400">
                                <i class="fas fa-chart-line text-4xl mb-2 block text-gray-200"></i>
                                Belum ada log perkembangan.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php
                        $kondisiStyle = [
                            'sangat_baik' => 'bg-green-50 text-green-600',
                            'baik'        => 'bg-blue-50 text-blue-600',
                            'cukup'       => 'bg-yellow-50 text-yellow-600',
                            'buruk'       => 'bg-red-50 text-red-500',
                        ];
                        foreach ($riwayat as $r):
                            $ks = $kondisiStyle[$r['kondisi']] ?? 'bg-gray-100 text-gray-500';
                        ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 text-gray-600 text-sm"><?= formatTanggal($r['tanggal']) ?></td>
                            <td class="px-5 py-3 font-bold text-gray-800"><?= e($r['nama_tanaman']) ?></td>
                            <td class="px-5 py-3 text-gray-700 font-medium"><?= $r['tinggi_cm'] ?> cm</td>
                            <td class="px-5 py-3">
                                <span class="<?= $ks ?> px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase">
                                    <?= str_replace('_',' ',$r['kondisi']) ?>
                                </span>
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-600 max-w-[220px] whitespace-normal break-words"><?= e($r['catatan'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . 'Views/layouts/footer.php'; ?>