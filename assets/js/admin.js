// File: assets/js/admin.js

document.addEventListener('DOMContentLoaded', function () {
    // Sidebar toggle
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar   = document.getElementById('sidebar-wrapper');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    }

    // Auto-dismiss alerts after 4s
    setTimeout(() => {
        document.querySelectorAll('.alert-dismissible').forEach(el => {
            const bsAlert = bootstrap.Alert.getInstance(el);
            if (bsAlert) bsAlert.close();
            else { el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }
        });
    }, 4000);

    // Confirm delete
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', e => {
            if (!confirm(btn.dataset.confirm)) e.preventDefault();
        });
    });

    // Character counters for textareas
    document.querySelectorAll('textarea[maxlength]').forEach(ta => {
        const counter = document.createElement('small');
        counter.className = 'text-muted float-end';
        ta.parentNode.appendChild(counter);
        const update = () => counter.textContent = `${ta.value.length}/${ta.maxLength}`;
        ta.addEventListener('input', update);
        update();
    });
});