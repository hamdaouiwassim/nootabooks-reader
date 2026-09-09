document.addEventListener('DOMContentLoaded', () => {

  // ---- Tabs (visual only demo) ----
  const tabs = document.querySelectorAll('.filter-tab');
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
    });
  });

  // ---- Like buttons ----
  document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const countEl = btn.querySelector('.count');
      const icon = btn.querySelector('i');
      let count = parseInt(countEl.textContent, 10);
      const liked = btn.classList.toggle('liked');
      countEl.textContent = liked ? count + 1 : count - 1;
      icon.classList.toggle('fa-regular');
      icon.classList.toggle('fa-solid');
    });
  });

  // ---- Join club buttons ----
  document.querySelectorAll('.join-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const joined = btn.classList.toggle('joined');
      btn.textContent = joined ? 'منضم' : 'انضمام';
    });
  });

  // ---- New post form ----
  const form = document.getElementById('newPostForm');
  const feed = document.getElementById('discussionFeed');

  form?.addEventListener('submit', (e) => {
    e.preventDefault();
    const input = document.getElementById('newPostInput');
    const text = input.value.trim();
    if (!text) return;

    const card = document.createElement('article');
    card.className = 'discussion-card';
    card.innerHTML = `
      <div class="discussion-head">
        <img src="https://i.pravatar.cc/64?img=13" alt="أحمد محمد">
        <div>
          <strong>أحمد محمد</strong>
          <span class="discussion-time">الآن</span>
        </div>
      </div>
      <p class="discussion-text"></p>
      <div class="discussion-footer">
        <button class="engage-btn like-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">0</span></button>
        <button class="engage-btn"><i class="fa-regular fa-comment"></i> <span class="count">0</span></button>
        <button class="engage-btn"><i class="fa-solid fa-share-nodes"></i> مشاركة</button>
      </div>
    `;
    card.querySelector('.discussion-text').textContent = text;
    card.querySelector('.like-btn').addEventListener('click', function () {
      const countEl = this.querySelector('.count');
      const icon = this.querySelector('i');
      let count = parseInt(countEl.textContent, 10);
      const liked = this.classList.toggle('liked');
      countEl.textContent = liked ? count + 1 : count - 1;
      icon.classList.toggle('fa-regular');
      icon.classList.toggle('fa-solid');
    });

    feed.prepend(card);
    input.value = '';
  });

});
