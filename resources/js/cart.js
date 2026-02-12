// Simple AJAX add-to-cart handler using Fetch

function getCsrfToken() {
    const el = document.querySelector('meta[name="csrf-token"]');
    return el ? el.getAttribute('content') : null;
}

async function postAddToCart(url, form) {
    const token = getCsrfToken();
    const headers = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    };
    if (token) headers['X-CSRF-TOKEN'] = token;

    const res = await fetch(url, {
        method: 'POST',
        headers,
        body: new URLSearchParams(new FormData(form))
    });

    return res.json();
}

function flashMessage(message, el) {
    const msg = document.createElement('div');
    msg.className = 'fixed bottom-6 right-6 bg-green-600 text-white px-4 py-2 rounded shadow-lg';
    msg.textContent = message;
    document.body.appendChild(msg);
    setTimeout(() => msg.remove(), 2500);
}

document.addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-add-to-cart]');
    if (!btn) return;
    e.preventDefault();

    const form = btn.closest('form');
    if (!form) return;

    const url = form.getAttribute('action');
    postAddToCart(url, form)
        .then(data => {
            if (data && data.success) {
                // update any cart count indicators
                document.querySelectorAll('[data-cart-count]').forEach(el => {
                    el.textContent = data.cart_count ?? Object.values(data.cart ?? {}).reduce((s,i)=>s+(i.quantity||0),0);
                });

                flashMessage(data.message || 'Produk ditambahkan ke keranjang');
            } else {
                flashMessage(data.message || 'Gagal menambahkan ke keranjang');
            }
        })
        .catch(() => flashMessage('Terjadi kesalahan, coba lagi'));
});
