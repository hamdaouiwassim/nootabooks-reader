document.addEventListener('DOMContentLoaded', () => {

  const tabBtns = document.querySelectorAll('.tab-btn');
  const tabPanels = document.querySelectorAll('.tab-panel');

  function activateTab(tab) {
    const btn = document.querySelector(`.tab-btn[data-tab="${tab}"]`);
    if (!btn) return;
    tabBtns.forEach(b => b.classList.remove('active'));
    tabPanels.forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById(`tab-${tab}`)?.classList.add('active');
  }

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => activateTab(btn.dataset.tab));
  });

  // ---- Activate tab from URL hash (e.g. #tab-favorites) ----
  if (location.hash.startsWith('#tab-')) {
    activateTab(location.hash.replace('#tab-', ''));
  }

  // ---- Avatar edit ----
  document.querySelector('.avatar-edit-btn')?.addEventListener('click', (e) => {
    window.location.href = e.currentTarget.dataset.href;
  });

});
