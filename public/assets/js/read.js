document.addEventListener('DOMContentLoaded', () => {

  const fullscreenBtn = document.getElementById('readerFullscreenBtn');
  const frameWrap = document.getElementById('readerFrameWrap');
  if (!fullscreenBtn || !frameWrap) return;

  fullscreenBtn.addEventListener('click', () => {
    if (!document.fullscreenElement) {
      frameWrap.requestFullscreen?.();
    } else {
      document.exitFullscreen?.();
    }
  });

  document.addEventListener('fullscreenchange', () => {
    const icon = fullscreenBtn.querySelector('i');
    icon.classList.toggle('fa-expand', !document.fullscreenElement);
    icon.classList.toggle('fa-compress', !!document.fullscreenElement);
  });

});
