<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:ital,wght@0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Fraunces', serif; }
        .bg-pattern {
            background-color: #0e380e;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='%232d9e2d' fill-opacity='0.15'%3E%3Cpath d='M40 0C18 0 0 18 0 40s18 40 40 40 40-18 40-40S62 0 40 0zm0 70c-16.5 0-30-13.5-30-30S23.5 10 40 10s30 13.5 30 30-13.5 30-30 30z'/%3E%3C/g%3E%3C/svg%3E");
        }
        .input-field { background:rgba(255,255,255,0.08);border:1.5px solid rgba(255,255,255,0.15);color:white;transition:all 0.2s; }
        .input-field::placeholder { color:rgba(255,255,255,0.4); }
        .input-field:focus { background:rgba(255,255,255,0.12);border-color:#4db84d;outline:none;box-shadow:0 0 0 3px rgba(77,184,77,0.2); }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl p-8 shadow-2xl">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-700 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-seedling text-white text-2xl"></i>
                </div>
                <h1 class="font-display text-3xl text-white font-bold"><?= APP_NAME ?></h1>
                <p class="text-green-300 text-sm mt-1">Monitoring System</p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="bg-red-500/20 border border-red-400/40 text-red-200 rounded-xl px-4 py-3 mb-5 text-sm flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i> <?= e($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?= url('auth/login') ?>" class="space-y-4">
                <div>
                    <label class="text-green-200 text-sm font-medium block mb-1.5">Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-green-400 text-sm"></i>
                        <input type="email" name="email" placeholder="email@kamu.com"
                            class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-sm"
                            value="<?= e($_POST['email'] ?? '') ?>" required>
                    </div>
                </div>
                <div>
                    <label class="text-green-200 text-sm font-medium block mb-1.5">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-green-400 text-sm"></i>
                        <input type="password" name="password" id="password" placeholder="••••••••"
                            class="input-field w-full pl-10 pr-10 py-3 rounded-xl text-sm" required>
                        <button type="button" onclick="togglePw()" class="absolute right-3 top-1/2 -translate-y-1/2 text-green-400 hover:text-green-200">
                            <i class="fas fa-eye text-sm" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>
                <button type="submit"
                    class="w-full bg-gradient-to-r from-green-500 to-green-700 hover:from-green-400 hover:to-green-600 text-white font-semibold py-3 rounded-xl transition shadow-lg shadow-green-900/40 mt-2">
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-green-300/70 text-sm">Belum punya akun?
                    <a href="<?= url('auth/register') ?>" class="text-green-300 hover:text-white font-semibold transition">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
    <script>
    function togglePw() {
        const pw = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        pw.type = pw.type === 'password' ? 'text' : 'password';
        icon.className = pw.type === 'password' ? 'fas fa-eye text-sm' : 'fas fa-eye-slash text-sm';
    }
    </script>
</body>
</html>