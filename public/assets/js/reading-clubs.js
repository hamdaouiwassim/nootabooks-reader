document.addEventListener('DOMContentLoaded', () => {

  // ---- Create club form toggle ----
  const toggleBtn = document.getElementById('toggleCreateClub');
  const form = document.getElementById('createClubForm');
  toggleBtn?.addEventListener('click', () => {
    form.hidden = !form.hidden;
    if (!form.hidden) form.querySelector('#clubName')?.focus();
  });

});
