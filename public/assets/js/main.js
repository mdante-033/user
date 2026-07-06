(function () {
    const form = document.querySelector('[data-menu-filter]');
    const results = document.getElementById('menu-results');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    if (!form || !results) {
        return;
    }

    const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    }[char]));

    const money = (value) => `KSh ${Number(value || 0).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    })}`;

    const itemAvailable = (value) => value === true || value === 1 || value === '1' || value === 't' || value === 'true';

    const render = (items) => {
        if (!items.length) {
            results.innerHTML = '<div class="empty-state"><h2>No menu items found</h2><p>Adjust the filters or call 0795 879797.</p></div>';
            return;
        }

        results.innerHTML = items.map((item) => {
            const action = itemAvailable(item.is_available)
                ? `<form action="/cart/add" method="post">
                        <input type="hidden" name="_csrf" value="${escapeHtml(csrf)}">
                        <input type="hidden" name="item_id" value="${escapeHtml(item.id)}">
                        <input type="hidden" name="quantity" value="1">
                        <button class="btn btn-sm btn-primary" type="submit">Add</button>
                   </form>`
                : '<span class="status-pill">Unavailable</span>';

            return `<article class="menu-card">
                <a href="/menu/${escapeHtml(item.id)}">
                    <img src="${escapeHtml(item.image_url)}" alt="${escapeHtml(item.name)}" loading="lazy">
                </a>
                <div class="menu-card-body">
                    <span>${escapeHtml(item.category_name || 'Menu')}</span>
                    <h2><a href="/menu/${escapeHtml(item.id)}">${escapeHtml(item.name)}</a></h2>
                    <p>${escapeHtml(item.description)}</p>
                    <div class="menu-card-actions">
                        <strong>${money(item.price)}</strong>
                        ${action}
                    </div>
                </div>
            </article>`;
        }).join('');
    };

    const load = async () => {
        const params = new URLSearchParams(new FormData(form));
        const response = await fetch(`/api/menu?${params.toString()}`, {
            headers: { Accept: 'application/json' }
        });
        if (!response.ok) {
            return;
        }
        const payload = await response.json();
        render(payload.items || []);
    };

    let timer = null;
    form.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(load, 250);
    });
    form.addEventListener('change', load);
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        load();
    });
})();

(function () {
    const STORAGE_KEY = 'cherynes-theme';
    const root = document.documentElement;

    const getStoredTheme = () => {
        try {
            return localStorage.getItem(STORAGE_KEY);
        } catch (e) {
            return null;
        }
    };

    const setStoredTheme = (theme) => {
        try {
            localStorage.setItem(STORAGE_KEY, theme);
        } catch (e) {
            // localStorage unavailable (private browsing, etc.) - theme just won't persist.
        }
    };

    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const initialTheme = getStoredTheme() || (prefersDark ? 'dark' : 'light');
    root.setAttribute('data-theme', initialTheme);

    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.querySelector('[data-theme-toggle]');
        if (!toggle) {
            return;
        }

        const updateLabel = (theme) => {
            toggle.textContent = theme === 'dark' ? '☀️' : '🌙';
            toggle.setAttribute('aria-label', theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode');
        };

        updateLabel(root.getAttribute('data-theme'));

        toggle.addEventListener('click', () => {
            const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            setStoredTheme(next);
            updateLabel(next);
        });
    });
})();
