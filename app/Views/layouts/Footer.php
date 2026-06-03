</main>
    <footer class="bg-white border-t border-gray-100 px-6 py-4 text-center text-xs text-gray-400">
        © <?= date('Y') ?> <?= APP_NAME ?> — Dibuat oleh Tio Alan Kurniawan
    </footer>
</div>

<script>
// Auto dismiss alert
setTimeout(() => {
    document.querySelectorAll('.auto-dismiss').forEach(el => {
        el.style.opacity = '0';
        el.style.transition = 'opacity 0.5s ease';
        setTimeout(() => el.remove(), 500);
    });
}, 3500);

// Animate progress bars
window.addEventListener('load', () => {
    setTimeout(() => {
        document.querySelectorAll('[data-progress]').forEach(bar => {
            bar.style.transition = 'width 1s ease-in-out';
            bar.style.width = bar.getAttribute('data-progress') + '%';
        });
    }, 300);
});
</script>
</body>
</html>