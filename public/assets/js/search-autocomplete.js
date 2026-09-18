document.addEventListener('DOMContentLoaded', () => {

  const MIN_CHARS = 2;
  const DEBOUNCE_MS = 280;

  function initAutocomplete(inputId) {
    const input = document.getElementById(inputId);
    const wrapper = input?.closest('.writers-search, .hero-search');
    if (!input || !wrapper) return;

    const panelId = `searchSuggestions-${inputId}`;
    const panel = document.createElement('div');
    panel.className = 'search-suggestions dropdown-panel';
    panel.id = panelId;
    panel.setAttribute('role', 'listbox');
    wrapper.appendChild(panel);

    input.setAttribute('role', 'combobox');
    input.setAttribute('aria-autocomplete', 'list');
    input.setAttribute('aria-expanded', 'false');
    input.setAttribute('aria-controls', panelId);
    input.setAttribute('autocomplete', 'off');

    let debounceTimer = null;
    let abortController = null;
    let activeIndex = -1;
    let items = [];

    function escapeHtml(str) {
      const div = document.createElement('div');
      div.textContent = str ?? '';
      return div.innerHTML;
    }

    function closePanel() {
      panel.classList.remove('open');
      input.setAttribute('aria-expanded', 'false');
      input.removeAttribute('aria-activedescendant');
      activeIndex = -1;
    }

    // position: fixed, so coordinates are relative to the viewport, not the
    // wrapper — recomputed on open and kept in sync on scroll/resize so the
    // panel tracks the search box instead of the page scrolling out from
    // under a stale absolute position.
    function positionPanel() {
      const rect = wrapper.getBoundingClientRect();
      const top = rect.bottom + 10;
      panel.style.left = `${rect.left}px`;
      panel.style.top = `${top}px`;
      panel.style.width = `${rect.width}px`;
      // Cap height to whatever room is left below the search box, so on a
      // short mobile viewport the panel's own scrollbar kicks in instead of
      // its bottom rows landing off-screen.
      panel.style.maxHeight = `${Math.max(160, Math.min(360, window.innerHeight - top - 12))}px`;
    }

    function renderResults(results) {
      items = results;
      activeIndex = -1;

      if (!results.length) {
        closePanel();
        return;
      }

      panel.innerHTML = results.map((item, i) => `
        <a href="${item.url}" class="suggestion-item" role="option" id="${panelId}-${i}" data-index="${i}">
          <img class="suggestion-thumb ${item.type === 'author' ? 'is-author' : 'is-book'}" src="${item.thumbnail ?? ''}" alt="" loading="lazy">
          <span class="suggestion-text">
            <span class="suggestion-title">${escapeHtml(item.title)}</span>
            ${item.subtitle ? `<span class="suggestion-subtitle">${escapeHtml(item.subtitle)}</span>` : ''}
          </span>
        </a>
      `).join('');

      positionPanel();
      panel.classList.add('open');
      input.setAttribute('aria-expanded', 'true');
    }

    window.addEventListener('scroll', () => {
      if (panel.classList.contains('open')) positionPanel();
    }, true);
    window.addEventListener('resize', () => {
      if (panel.classList.contains('open')) positionPanel();
    });

    async function fetchSuggestions(query) {
      abortController?.abort();
      abortController = new AbortController();

      try {
        const res = await fetch(`/search/autocomplete?q=${encodeURIComponent(query)}`, {
          headers: { Accept: 'application/json' },
          signal: abortController.signal,
        });

        if (!res.ok) throw new Error('bad response');

        const data = await res.json();
        renderResults(data);
      } catch (err) {
        if (err.name !== 'AbortError') closePanel();
      }
    }

    input.addEventListener('input', () => {
      const query = input.value.trim();
      clearTimeout(debounceTimer);

      if (query.length < MIN_CHARS) {
        abortController?.abort();
        closePanel();
        return;
      }

      debounceTimer = setTimeout(() => fetchSuggestions(query), DEBOUNCE_MS);
    });

    function setActive(index) {
      const options = panel.querySelectorAll('.suggestion-item');
      options.forEach(el => el.classList.remove('is-active'));

      if (index >= 0 && options[index]) {
        options[index].classList.add('is-active');
        input.setAttribute('aria-activedescendant', `${panelId}-${index}`);
        options[index].scrollIntoView({ block: 'nearest' });
      } else {
        input.removeAttribute('aria-activedescendant');
      }

      activeIndex = index;
    }

    input.addEventListener('keydown', (e) => {
      const isOpen = panel.classList.contains('open');

      if (e.key === 'ArrowDown' && isOpen) {
        e.preventDefault();
        setActive(activeIndex < items.length - 1 ? activeIndex + 1 : 0);
        return;
      }

      if (e.key === 'ArrowUp' && isOpen) {
        e.preventDefault();
        setActive(activeIndex > 0 ? activeIndex - 1 : items.length - 1);
        return;
      }

      if (e.key === 'Escape' && isOpen) {
        closePanel();
        return;
      }

      if (e.key === 'Enter' && isOpen && activeIndex >= 0 && items[activeIndex]) {
        e.preventDefault();
        window.location.href = items[activeIndex].url;
      }
      // Enter with nothing highlighted falls through to whatever normal
      // submit behavior this input already has (discover's own separate
      // Enter-to-submit listener, or the home hero's native <form> submit).
    });

    document.addEventListener('click', (e) => {
      if (!wrapper.contains(e.target)) closePanel();
    });

    panel.addEventListener('mousedown', (e) => e.preventDefault());
  }

  initAutocomplete('discoverSearch');
  initAutocomplete('homepage-search');

});
