<script>
(function () {
  var stored = localStorage.getItem('nootabooks-theme');
  var theme = stored || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  document.documentElement.dataset.theme = theme;
})();
</script>
