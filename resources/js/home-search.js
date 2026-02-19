const input = document.querySelector('[data-home-search-input]');
const results = document.querySelector('[data-home-search-results]');
const resultsList = document.querySelector('[data-home-search-list]');
let activeRequest = null;

if (input && results && resultsList) {
  const renderEmpty = (text) => {
    resultsList.innerHTML = `<li class="px-4 py-3 text-sm text-gray-500">${text}</li>`;
  };

  const renderResults = (data) => {
    const items = data.items || [];
    const authors = data.authors || [];
    if (items.length === 0 && authors.length === 0) {
      renderEmpty('Tidak ada hasil yang cocok.');
      return;
    }

    const html = [];
    if (items.length) {
      html.push('<li class="px-4 pt-3 pb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">Buku</li>');
      items.forEach((item) => {
        html.push(
          `<li><a href="/book/${item.slug}" class="block px-4 py-2 hover:bg-gray-50">${item.name}</a></li>`
        );
      });
    }

    if (authors.length) {
      html.push('<li class="px-4 pt-3 pb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">Penulis</li>');
      authors.forEach((author) => {
        html.push(
          `<li><a href="/author/${author.slug}" class="block px-4 py-2 hover:bg-gray-50">${author.name}</a></li>`
        );
      });
    }

    resultsList.innerHTML = html.join('');
  };

  const debounce = (fn, delay) => {
    let timer;
    return (...args) => {
      clearTimeout(timer);
      timer = setTimeout(() => fn(...args), delay);
    };
  };

  const fetchResults = debounce(async (value) => {
    if (!value) {
      results.classList.add('hidden');
      resultsList.innerHTML = '';
      return;
    }

    const controller = new AbortController();
    if (activeRequest) {
      activeRequest.abort();
    }
    activeRequest = controller;

    try {
      const response = await fetch(`/api/search?q=${encodeURIComponent(value)}`, {
        headers: {
          Accept: 'application/json',
        },
        signal: controller.signal,
      });

      if (!response.ok) {
        throw new Error('Search failed');
      }

      const data = await response.json();
      renderResults(data);
      results.classList.remove('hidden');
    } catch (error) {
      if (error.name === 'AbortError') {
        return;
      }
      renderEmpty('Terjadi kesalahan. Coba lagi.');
      results.classList.remove('hidden');
    }
  }, 250);

  input.addEventListener('input', (event) => {
    const value = event.target.value.trim();
    fetchResults(value);
  });

  input.addEventListener('focus', () => {
    if (resultsList.innerHTML.trim()) {
      results.classList.remove('hidden');
    }
  });

  document.addEventListener('click', (event) => {
    if (!results.contains(event.target) && event.target !== input) {
      results.classList.add('hidden');
    }
  });
}
