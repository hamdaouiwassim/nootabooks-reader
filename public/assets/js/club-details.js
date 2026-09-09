document.addEventListener('DOMContentLoaded', () => {

  // ---- Tabs ----
  const tabBtns = document.querySelectorAll('.tab-btn');
  const tabPanels = document.querySelectorAll('.tab-panel');

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      tabBtns.forEach(b => b.classList.remove('active'));
      tabPanels.forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById(`tab-${btn.dataset.tab}`)?.classList.add('active');
    });
  });

  // ---- Join club toggle ----
  const joinBtn = document.querySelector('.join-club-btn');
  joinBtn?.addEventListener('click', () => {
    const joined = joinBtn.classList.toggle('joined');
    joinBtn.innerHTML = joined
      ? '<i class="fa-solid fa-check"></i> منضم للنادي'
      : '<i class="fa-solid fa-user-plus"></i> انضمام للنادي';
  });

});
