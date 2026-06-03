<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' — ' : '' ?><?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:ital,wght@0,300;0,700;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Fraunces', 'serif'],
                    },
                    colors: {
                        forest: {
                            50:'#f0faf0',100:'#d9f2d9',200:'#b3e6b3',
                            300:'#7dd17d',400:'#4db84d',500:'#2d9e2d',
                            600:'#1f7a1f',700:'#165716',800:'#0e380e',900:'#071c07'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-link { transition: all 0.2s ease; border-left: 3px solid transparent; }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(45,158,45,0.1); color: #2d9e2d;
            border-left: 3px solid #2d9e2d;
        }
        .leaf-bg {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%232d9e2d' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .notif-dot { animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }
        #profileDropdown { transition: opacity 0.18s ease, transform 0.18s ease; transform-origin: top right; }
        #profileDropdown.dd-open { opacity:1!important; transform:scale(1) translateY(0)!important; pointer-events:auto!important; }
        .dd-item { display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;font-size:13px;color:#475569;text-decoration:none;transition:background .12s,color .12s; }
        .dd-item:hover { background:#f0faf0; color:#1f7a1f; }
        .dd-icon { width:28px;height:28px;border-radius:7px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;transition:background .12s; }
        .dd-item:hover .dd-icon { background:#d9f2d9;color:#1f7a1f; }
        .dd-item.dd-danger { color:#ef4444; }
        .dd-item.dd-danger .dd-icon { background:#fef2f2;color:#ef4444; }
        .dd-item.dd-danger:hover { background:#fef2f2;color:#dc2626; }
        .dd-item.card-hover { transition: all 0.2s; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
    </style>
</head>
<body class="bg-gray-50 leaf-bg min-h-screen flex">

<?php
use App\Core\Auth;

// Sinkronisasi variabel penentu URL agar rute Admin tidak melempar 404
$fullUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$roleUser = Auth::role();
$actionUrl = ($roleUser === 'admin' || strpos($fullUri, '/admin/') !== false) ? 'admin/notifikasi' : 'notifikasi';

// Mengambil sisa nilai unreadNotif yang dikirim dari Controller atau fungsi helper
$unreadNotif = $unreadNotif ?? (function_exists('countUnreadNotif') ? countUnreadNotif() : 0);

$currentPage = basename($fullUri);
$namaUser    = Auth::user()['nama'];
$fotoUser    = Auth::user()['foto'] ?? null; 
$initials    = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', array_slice(explode(' ', $namaUser), 0, 2))));

if (!function_exists('navLink')) {
    function navLink(string $path, string $icon, string $label, string $current): string {
        $active = (str_contains($current, explode('/', $path)[0]) || ($path === 'admin/notifikasi' && $current === 'notifikasi')) ? 'active' : '';
        return "<a href='".url($path)."' class='sidebar-link $active flex items-center gap-3 px-4 py-3 text-gray-600 rounded-lg text-sm font-medium'>
            <i class='$icon w-5 text-center'></i> $label</a>";
    }
}
?>

<div id="sidebarBackdrop" onclick="toggleSidebar()"
     class="hidden fixed inset-0 bg-black/40 z-40 lg:hidden backdrop-blur-sm"></div>

<aside id="mainSidebar"
       class="w-64 min-h-screen bg-white border-r border-gray-100 shadow-sm flex-shrink-0
              fixed top-0 left-0 h-full z-50 flex flex-col
              transition-transform duration-300 transform -translate-x-full lg:translate-x-0">

    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-forest-500 to-forest-700 rounded-xl flex items-center justify-center shadow-md">
                <i class="fas fa-seedling text-white text-lg"></i>
            </div>
            <div>
                <div class="font-display font-bold text-forest-700 leading-tight text-base">Urban</div>
                <div class="text-xs text-gray-400 tracking-widest uppercase">Farming Monitor</div>
            </div>
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-500 hover:bg-gray-100">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="px-4 py-4 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 relative flex-shrink-0">
                <?php if (!empty($fotoUser)): ?>
                    <img src="<?= url('uploads/' . $fotoUser) ?>" 
                         onerror="this.style.display='none'; document.getElementById('fb-side').classList.remove('hidden');"
                         class="w-9 h-9 rounded-full object-cover border border-gray-200 shadow-sm absolute top-0 left-0" 
                         alt="Foto">
                <?php endif; ?>
                <div id="fb-side" class="<?= !empty($fotoUser) ? 'hidden' : '' ?> w-9 h-9 bg-gradient-to-br from-forest-400 to-forest-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                    <?= $initials ?>
                </div>
            </div>
            <div>
                <div class="font-semibold text-gray-700 text-sm truncate w-32"><?= e($namaUser) ?></div>
                <div class="text-xs text-gray-400 capitalize"><?= $roleUser ?></div>
            </div>
        </div>
    </div>

    <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
        <div class="text-xs text-gray-400 uppercase tracking-wider font-semibold px-4 py-2">Menu Utama</div>
        <?= navLink('dashboard',    'fas fa-chart-pie',      'Dashboard',         $currentPage) ?>
        
        <?php if ($roleUser !== 'admin'): ?>
            <?= navLink('tanaman',       'fas fa-leaf',           'Data Tanaman',      $currentPage) ?>
            <?= navLink('perkembangan', 'fas fa-chart-line',     'Perkembangan',      $currentPage) ?>
        <?php endif; ?>

        <?= navLink('jadwal',       'fas fa-calendar-check', 'Jadwal Perawatan',  $currentPage) ?>

        <div class="text-xs text-gray-400 uppercase tracking-wider font-semibold px-4 py-2 mt-3">Analisis</div>
        <?= navLink('prediksi',    'fas fa-clock',    'Prediksi Panen', $currentPage) ?>
        
        <?= navLink($actionUrl, 'fas fa-bell', 'Notifikasi', $currentPage) ?>
        
        <?php if ($roleUser !== 'admin'): ?>
            <?= navLink('konsultasi',  'fas fa-comments', 'Konsultasi',     $currentPage) ?>
        <?php endif; ?>

        <?php if ($roleUser === 'admin'): ?>
        <div class="text-xs text-gray-400 uppercase tracking-wider font-semibold px-4 py-2 mt-3">Admin</div>
        <?= navLink('admin/konsultasi', 'fas fa-headset', 'Jawab Konsultasi', $currentPage) ?>
        <?php endif; ?>
    </nav>

    <div class="p-3 border-t border-gray-100">
        <a href="<?= url('auth/logout') ?>"
           class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-lg text-sm font-medium transition">
            <i class="fas fa-sign-out-alt"></i> Keluar
        </a>
    </div>
</aside>

<div class="flex-1 lg:ml-64 flex flex-col min-h-screen w-full min-w-0">
    <header class="bg-white border-b border-gray-100 px-4 sm:px-6 py-3 flex items-center justify-between sticky top-0 z-20 shadow-sm">
        <div class="flex items-center gap-3">
            <button onclick="toggleSidebar()" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <div>
                <h1 class="font-display font-bold text-gray-800 text-base sm:text-xl truncate max-w-[160px] sm:max-w-none">
                    <?= $pageTitle ?? 'Dashboard' ?>
                </h1>
                <p class="text-[10px] sm:text-xs text-gray-400 mt-0.5"><?= date('l, d F Y') ?></p>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-3">
            <a href="<?= url($actionUrl) ?>" class="relative w-9 h-9 bg-gray-100 hover:bg-forest-50 rounded-xl flex items-center justify-center transition">
                <i class="fas fa-bell text-gray-500 text-sm"></i>
                
                <?php if (isset($unreadNotif) && $unreadNotif > 0): ?>
                <span class="notif-dot absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center font-bold">
                    <?= $unreadNotif ?>
                </span>
                <?php endif; ?>
            </a>

            <div class="relative" id="profileWrap">
                <button id="profileBtn" onclick="toggleProfileDropdown()" aria-haspopup="true" aria-expanded="false"
                    class="flex items-center gap-2 pl-1 pr-2 sm:pr-3 py-1.5 rounded-xl border border-transparent hover:bg-gray-50 hover:border-gray-200 transition-all">
                    
                    <div class="w-8 h-8 relative flex-shrink-0">
                        <?php if (!empty($fotoUser)): ?>
                            <img src="<?= url('uploads/' . $fotoUser) ?>" 
                                 onerror="this.style.display='none'; document.getElementById('fb-head').classList.remove('hidden');"
                                 class="w-8 h-8 rounded-lg object-cover border border-gray-200 shadow absolute top-0 left-0" 
                                 alt="Avatar">
                        <?php endif; ?>
                        <div id="fb-head" class="<?= !empty($fotoUser) ? 'hidden' : '' ?> w-8 h-8 bg-gradient-to-br from-forest-400 to-forest-600 rounded-lg flex items-center justify-center shadow text-white text-xs font-bold">
                            <?= $initials ?>
                        </div>
                    </div>

                    <div class="hidden sm:block text-left">
                        <div class="text-sm font-semibold text-gray-700 leading-tight truncate max-w-[100px]"><?= e($namaUser) ?></div>
                        <div class="text-[10px] text-gray-400 capitalize"><?= $roleUser ?></div>
                    </div>
                    <i id="profileChevron" class="fas fa-chevron-down text-[10px] text-gray-400 hidden sm:block transition-transform duration-200"></i>
                </button>

                <div id="profileDropdown" role="menu"
                     class="absolute right-0 top-[calc(100%+8px)] w-56 bg-white rounded-2xl z-50 border border-green-100 shadow-xl shadow-black/10 opacity-0 scale-95 pointer-events-none">
                    <div class="p-4 rounded-t-2xl border-b border-green-100" style="background:linear-gradient(135deg,#f0faf0,#e8f5e8)">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 relative flex-shrink-0">
                                <?php if (!empty($fotoUser)): ?>
                                    <img src="<?= url('uploads/' . $fotoUser) ?>" 
                                         onerror="this.style.display='none'; document.getElementById('fb-dd').classList.remove('hidden');"
                                         class="w-11 h-11 rounded-xl object-cover border border-gray-200 shadow-md absolute top-0 left-0" 
                                         alt="Profil">
                                <?php endif; ?>
                                <div id="fb-dd" class="<?= !empty($fotoUser) ? 'hidden' : '' ?> w-11 h-11 bg-gradient-to-br from-forest-400 to-forest-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-md">
                                    <?= $initials ?>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-forest-800 truncate"><?= e($namaUser) ?></div>
                                <span class="inline-block text-[10px] font-semibold bg-forest-100 text-forest-700 px-2 py-0.5 rounded-full capitalize"><?= $roleUser ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="p-2">
                        <a href="<?= url('profil') ?>" class="dd-item"><span class="dd-icon"><i class="fas fa-user-circle"></i></span>Profil Saya</a>
                        
                        <a href="<?= url($actionUrl) ?>" class="dd-item">
                            <span class="dd-icon"><i class="fas fa-bell"></i></span>
                            Notifikasi
                            <?php if (isset($unreadNotif) && $unreadNotif > 0): ?>
                            <span class="ml-auto text-[10px] font-bold bg-red-100 text-red-500 px-1.5 py-0.5 rounded-full"><?= $unreadNotif ?></span>
                            <?php endif; ?>
                        </a>
                        
                        <?php if ($roleUser === 'admin'): ?>
                        <a href="<?= url('admin/konsultasi') ?>" class="dd-item"><span class="dd-icon"><i class="fas fa-users-cog"></i></span>Kelola Konsultasi</a>
                        <?php endif; ?>
                        <div class="my-1.5 border-t border-gray-100"></div>
                        <a href="<?= url('auth/logout') ?>" class="dd-item dd-danger"><span class="dd-icon"><i class="fas fa-sign-out-alt"></i></span>Keluar</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 p-4 sm:p-6 min-w-0">

<script>
function toggleSidebar() {
    const sidebar  = document.getElementById('mainSidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const hidden   = sidebar.classList.contains('-translate-x-full');
    sidebar.classList.toggle('-translate-x-full', !hidden);
    backdrop.classList.toggle('hidden', !hidden);
}
function toggleProfileDropdown() {
    const btn = document.getElementById('profileBtn');
    const dd  = document.getElementById('profileDropdown');
    const open = dd.classList.contains('dd-open');
    dd.classList.toggle('dd-open', !open);
    btn.setAttribute('aria-expanded', String(!open));
}
document.addEventListener('click', e => {
    const wrap = document.getElementById('profileWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('profileDropdown').classList.remove('dd-open');
        document.getElementById('profileBtn').setAttribute('aria-expanded','false');
    }
});
document.addEventListener('keydown', e => { if(e.key==='Escape') document.getElementById('profileDropdown').classList.remove('dd-open'); });
</script>