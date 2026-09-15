document.addEventListener('DOMContentLoaded', () => {
  if (typeof Chart === 'undefined') return;

  const rootStyle = getComputedStyle(document.documentElement);
  const cssVar = (name, fallback) => (rootStyle.getPropertyValue(name).trim() || fallback);

  const colors = {
    gold: cssVar('--gold-dark', '#c2933f'),
    teal: cssVar('--teal', '#12433f'),
    navy: cssVar('--navy', '#0d2b30'),
  };

  document.querySelectorAll('.admin-chart-canvas').forEach((canvas) => {
    let labels = [];
    let values = [];
    let tooltipLabels = [];

    try {
      labels = JSON.parse(canvas.dataset.chartLabels || '[]');
      values = JSON.parse(canvas.dataset.chartValues || '[]');
      tooltipLabels = JSON.parse(canvas.dataset.chartTooltips || '[]');
    } catch (e) {
      return;
    }

    const color = colors[canvas.dataset.chartColor] || colors.gold;
    const unit = canvas.dataset.chartUnit || '';

    new Chart(canvas, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          data: values,
          backgroundColor: color,
          borderRadius: 4,
          maxBarThickness: 34,
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            rtl: true,
            titleFont: { family: 'Tajawal' },
            bodyFont: { family: 'Tajawal' },
            callbacks: {
              title: (items) => tooltipLabels[items[0].dataIndex] || labels[items[0].dataIndex],
              label: (item) => `${item.parsed.y}${unit ? ' ' + unit : ''}`,
            },
          },
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { font: { family: 'Tajawal', size: 11 } },
          },
          y: {
            beginAtZero: true,
            ticks: { precision: 0, font: { family: 'Tajawal', size: 11 } },
          },
        },
      },
    });
  });
});
