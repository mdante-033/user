(function () {
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const selector = toggle.getAttribute('data-bs-target') || toggle.getAttribute('href');
            if (!selector) {
                return;
            }

            const target = document.querySelector(selector);
            if (!target) {
                return;
            }

            const expanded = target.classList.toggle('show');
            toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        });
    });

    document.querySelectorAll('[data-bs-dismiss="alert"]').forEach((button) => {
        button.addEventListener('click', () => {
            const alert = button.closest('.alert');
            if (alert) {
                alert.remove();
            }
        });
    });
})();
