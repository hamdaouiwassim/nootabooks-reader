(() => {
  const loader = document.getElementById('pageLoader');
  if (!loader) return;

  const hideLoader = () => {
    loader.classList.add('hidden');
    setTimeout(() => loader.remove(), 500);
  };

  window.addEventListener('load', () => {
    setTimeout(hideLoader, 300);
  });

  // Fallback in case the load event never fires (e.g. a stalled asset)
  setTimeout(hideLoader, 4000);
})();
