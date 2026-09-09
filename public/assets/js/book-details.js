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

  // ---- Wishlist toggle ----
  const wishlistBtn = document.querySelector('.wishlist-btn');
  if (wishlistBtn) {
    wishlistBtn.addEventListener('click', () => {
      wishlistBtn.classList.toggle('active');
      const icon = wishlistBtn.querySelector('i');
      icon.classList.toggle('fa-regular');
      icon.classList.toggle('fa-solid');
    });
  }

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
