document.addEventListener('DOMContentLoaded', () => {

  // ---- Tabs (visual only demo) ----
  const tabs = document.querySelectorAll('.filter-tab');
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
    });
  });

});
