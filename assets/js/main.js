// e-Profile System Frontend JS

// Auto-dismiss flash messages
document.addEventListener('DOMContentLoaded', function() {
    const flash = document.querySelector('[data-flash]');
    if (flash) {
        setTimeout(() => {
            flash.style.transition = 'opacity 0.5s';
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500);
        }, 4000);
    }
});

// Confirm before delete
function confirmAction(message) {
    return confirm(message || 'Adakah anda pasti?');
}

// Toggle profile active status via AJAX
function toggleStatus(id, currentStatus, row) {
    if (!confirm('Tukar status profil ini?')) return;
    fetch('/api/toggle_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + id + '&status=' + (currentStatus ? '0' : '1')
    })
    .then(r => r.json())
    .then(data => { if (data.success) location.reload(); else alert('Gagal menukar status.'); })
    .catch(() => alert('Ralat rangkaian.'));
}

// Copy to clipboard
function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check text-green-500"></i>';
        setTimeout(() => { btn.innerHTML = orig; }, 2000);
    });
}