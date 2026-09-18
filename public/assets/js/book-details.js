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

  // ---- Bookmark toggle ----
  // Bookmark state is handled server-side (see BookBookmarkController) via a
  // real form submission, so no client-side toggle is needed here.

  // ---- Add review form toggle ----
  const toggleReviewBtn = document.getElementById('toggleReviewForm');
  const reviewForm = document.getElementById('addReviewForm');
  if (toggleReviewBtn && reviewForm) {
    toggleReviewBtn.addEventListener('click', () => {
      reviewForm.hidden = !reviewForm.hidden;
    });
  }

  // ---- Follow author button ----
  // Follow state is handled server-side (see WriterFollowController) via a
  // real form submission, so no client-side toggle is needed here anymore.

});
