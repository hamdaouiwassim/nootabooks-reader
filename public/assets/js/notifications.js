document.addEventListener('DOMContentLoaded', () => {

  const filterTabs = document.querySelectorAll('.notif-filter-tab');
  const items = document.querySelectorAll('.notif-page-item');
  const groups = document.querySelectorAll('.notif-group');
  const emptyState = document.getElementById('notifEmpty');
  const markAllReadPageBtn = document.getElementById('markAllReadPageBtn');

  function applyFilter(filter) {
    let visibleCount = 0;

    items.forEach(item => {
      const matches =
        filter === 'all' ||
        (filter === 'unread' && item.classList.contains('unread')) ||
        item.dataset.filter === filter;
      item.hidden = !matches;
      if (matches) visibleCount++;
    });

    groups.forEach(group => {
      const hasVisible = group.querySelectorAll('.notif-page-item:not([hidden])').length > 0;
      group.hidden = !hasVisible;
    });

    if (emptyState) emptyState.hidden = visibleCount !== 0;
  }

  filterTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      filterTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      applyFilter(tab.dataset.filter);
    });
  });

  markAllReadPageBtn?.addEventListener('click', () => {
    items.forEach(item => item.classList.remove('unread'));
    const badge = document.getElementById('notifBadge');
    if (badge) badge.style.display = 'none';
  });

  document.querySelectorAll('.notif-dismiss-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      const item = btn.closest('.notif-page-item');
      const group = btn.closest('.notif-group');
      item.remove();
      if (group && group.querySelectorAll('.notif-page-item').length === 0) {
        group.hidden = true;
      }
      const activeFilter = document.querySelector('.notif-filter-tab.active')?.dataset.filter || 'all';
      applyFilter(activeFilter);
    });
  });

});
