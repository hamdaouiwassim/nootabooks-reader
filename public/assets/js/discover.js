document.addEventListener('DOMContentLoaded', () => {

  const searchInput = document.getElementById('discoverSearch');
  const clearBtn = document.getElementById('clearFilters');

  // All filtering/sorting (category, language, rating, format, sort) now
  // happens server-side against the full dataset — search submits its form
  // on Enter rather than re-fetching on every keystroke.
  searchInput?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      searchInput.form?.submit();
    }
  });

  clearBtn?.addEventListener('click', () => {
    if (clearBtn.dataset.clearUrl) window.location.href = clearBtn.dataset.clearUrl;
  });

});
