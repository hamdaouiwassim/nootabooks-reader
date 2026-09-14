(() => {
  const loader = document.getElementById('pageLoader');
  if (!loader) return;

  const percentEl = document.getElementById('loaderPercent');
  let percent = 0;

  const setPercent = (value) => {
    percent = value;
    if (percentEl) percentEl.textContent = Math.round(percent) + '%';
  };

  // The browser doesn't expose real bytes-loaded progress for a full page
  // navigation, so this eases toward 90% as a visual cue that things are
  // still happening — it only actually reaches 100% once `load` (or the
  // fallback below) fires, i.e. once the page is truly ready.
  const progressTimer = percentEl ? setInterval(() => {
    setPercent(percent + (90 - percent) * 0.1);
  }, 150) : null;

  const hideLoader = () => {
    if (progressTimer) clearInterval(progressTimer);
    setPercent(100);

    setTimeout(() => {
      loader.classList.add('hidden');
      setTimeout(() => loader.remove(), 500);
    }, 150);
  };

  window.addEventListener('load', () => {
    setTimeout(hideLoader, 300);
  });

  // Fallback in case the load event never fires (e.g. a stalled asset)
  setTimeout(hideLoader, 4000);
})();
