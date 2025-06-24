/**
 * 折れ線グラフに影
 * @type {{id: string, beforeDatasetDraw(*, *, *): void, afterDatasetDraw(*, *, *): void}}
 */
const shadowPlugin = {
  id: 'shadowLine',
  beforeDatasetDraw(chart, args, options) {
    const { ctx } = chart;
    const datasetIndex = args.index;

    ctx.save();
    ctx.shadowColor = 'rgba(255,255,255,1)';
    ctx.shadowBlur = 5;
    ctx.shadowOffsetX = 0;
    ctx.shadowOffsetY = 0;
  },
  afterDatasetDraw(chart, args, options) {
    const { ctx } = chart;
    ctx.restore(); // シャドウ設定を戻す
  }
};
/**
 * ガントチャートの表示
 * @param ganttCtx
 * @param ganttCanvas
 * @param chartParams
 * @returns {{id: string, afterDraw(*): void}}
 */
const createGanttPlugin = (ganttCtx, ganttCanvas, chartParams) => {
  return {
    id: 'draw-gantt-bars',
    afterDraw(chart) {
      ganttCtx.clearRect(0, 0, ganttCanvas.width, ganttCanvas.height);

      const chartArea = chart.chartArea;
      const scaleX = chart.scales.x;
      const tasks = chartParams.tasks;

      const barHeight = 18;
      const barGap = 4;
      const rowCount = 7; // 工程数
      const height = chartArea.height / rowCount;
      const baseHeight = chartArea.top + (height - barHeight * 2 - barGap) / 2;

      for (const i in tasks) {
        const index = Number(i);
        const startLabel = chartParams.labels[index];
        const endLabel = chartParams.labels[index + 1];
        const start = scaleX.getPixelForValue(startLabel);
        const end = scaleX.getPixelForValue(endLabel);

        const task = tasks[i];

        // 計画（黒）
        if (task.plan) {
          for (const y of task.plan) {
            const topY = baseHeight + (y - 1) * height;
            ganttCtx.fillStyle = 'black';
            ganttCtx.fillRect(start, topY, end - start, barHeight);
          }
        }

        // 実施（赤）
        if (task.result) {
          for (const y of task.result) {
            const topY = baseHeight + (y - 1) * height + barHeight + barGap;
            ganttCtx.fillStyle = 'red';
            ganttCtx.fillRect(start, topY, end - start, barHeight);
          }
        }
      }
    }
  };
}

document.addEventListener('DOMContentLoaded', function() {
  const lineCanvas = document.getElementById('lineCanvas');
  const ganttCanvas = document.getElementById('ganttCanvas');
  lineCanvas.height = ganttCanvas.height = ganttCanvas.offsetHeight;
  const ctx = lineCanvas.getContext('2d');
  const ganttCtx = ganttCanvas.getContext('2d');
  const ganttPlugin = createGanttPlugin(ganttCtx, ganttCanvas, chartParams);

  const config = {
    type: 'line',
    data: {
      labels: chartParams.labels,
      datasets: chartParams.datasets
    },
    options: chartParams.options,
    plugins: [
      shadowPlugin,
      ganttPlugin
    ]
  }
  // callback設定
  config.options.scales.y1.ticks.callback = value => value + '%';

  const comboChart = new Chart(ctx, config);

  // フッター余白
  const footerMargin = comboChart.height - comboChart.chartArea.bottom + 10;
  // 左ラベルの設定
  const chartLeft = document.querySelector('.js-chart-left');
  chartLeft.style.height = `${comboChart.chartArea.height}px`;
  chartLeft.style.marginTop = `${comboChart.chartArea.top}px`;
  chartLeft.style.marginBottom = `${footerMargin}px`;
  const chartFoot = document.querySelector('.js-chart-foot');
  chartFoot.style.width = `${comboChart.chartArea.width}px`;
  chartFoot.style.paddingTop = '10px';
  chartFoot.style.marginLeft = `${comboChart.chartArea.left}px`;
});