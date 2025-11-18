<template>
  <div class="container mb-4 analytics">
    <div class="row justify-content-center">

      <!-- --- Completion Rate (円グラフ) --- -->
      <div class="col-5 text-center">
        <h3 class="h5 mb-4">Completion Rate</h3>
        <canvas ref="pieCanvas" class="mt-2" style="max-width: 175px; margin: auto;"></canvas>
      </div>

      <!-- --- Completion by Priority (横棒グラフ) --- -->
      <div class="col-5 text-center">
        <h3 class="h5 mb-4">Completion by Priority</h3>

        <div v-for="level in ['low','medium','high']" :key="level" class="text-start mx-auto w-75 mt-3">

          <div class="mb-2">
            <span class="text-capitalize">
                {{ level }} {{ priorityData[level].completed }} / {{ priorityData[level].total }} ({{ getPercent(level) }}%)
            </span>
          </div>

          <div class="bar-bg">
              <div
              class="bar-fill"
              :style="{ width: getPercent(level) + '%', backgroundColor: getBarColor(level) }"
            ></div>
          </div>

        </div>

      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { api } from "@/plugins/axios";
import { Chart } from "chart.js/auto";

const pieCanvas = ref(null);

const total = ref(0);
const completed = ref(0);
const percent = ref(0);

const priorityData = ref({
  low: { completed: 0, total: 0 },
  medium: { completed: 0, total: 0 },
  high: { completed: 0, total: 0 }
});

const fetchStats = async () => {
  const res = await api.get("/tasks/stats");
  total.value = res.data.total;
  completed.value = res.data.completed;
  percent.value = total.value ? Math.round((completed.value / total.value) * 100) : 0;
  priorityData.value = res.data.priority;
};

const getPercent = (level) => {
  const data = priorityData.value[level];
  return data.total ? Math.round((data.completed / data.total) * 100) : 0;
};

const getBarColor = (level) => {
  return {
    low: getComputedStyle(document.documentElement)
           .getPropertyValue('--bs-primary').trim(),
    medium: getComputedStyle(document.documentElement)
           .getPropertyValue('--bs-warning').trim(),
    high: getComputedStyle(document.documentElement)
           .getPropertyValue('--bs-danger').trim()
  }[level];
};

// 円グラフ + 中央テキスト
const drawPie = () => {
const ctx = pieCanvas.value.getContext("2d");
const cx = pieCanvas.value.width / 2;
const cy = pieCanvas.value.height / 2;

// 円周に沿った虹色
const gradient = ctx.createConicGradient(-0.5 * Math.PI, cx, cy);
gradient.addColorStop(0.00, "#ff0000"); // 赤
gradient.addColorStop(0.14, "#ffa500"); // オレンジ
gradient.addColorStop(0.28, "#ffff00"); // 黄
gradient.addColorStop(0.42, "#00ff00"); // 緑
gradient.addColorStop(0.57, "#0000ff"); // 青
gradient.addColorStop(0.71, "#4b0082"); // 藍
gradient.addColorStop(0.85, "#8a2be2"); // 紫
gradient.addColorStop(1.00, "#ff0000"); // 赤

  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: ["Completed", "Remaining"],
      datasets: [
        {
          data: [completed.value, total.value - completed.value],
          backgroundColor: [
            gradient,   // ✅ 完了タスクのみ虹色
            "#d0d0d0"   // ❗ 残タスクは灰色のまま
          ],
          borderWidth: 0,
          hoverOffset: 4
        }
      ]
    },
    options: {
      cutout: "70%", // ✅ 真ん中が広めで文字が映える
      plugins: {
        legend: { display: false },
        tooltip: { enabled: true }
      }
    },
    plugins: [
      {
        id: "centerText",
        afterDraw(chart) {
          const { ctx, width, height } = chart;
          ctx.save();
          ctx.fillStyle = "#333";
          ctx.font = "600 14px sans-serif";
          ctx.textBaseline = "middle";
          ctx.textAlign = "center";
          ctx.fillText(`${completed.value}/${total.value} (${percent.value}%)`, width / 2, height / 2);
        }
      }
    ]
  });
};

onMounted(async () => {
  await fetchStats();
  drawPie();
});
</script>

<style scoped>
.analytics {
  height: 250px;
}

.bar-bg {
  width: 100%;
  background: #eee;
  height: 12px;
  border-radius: 4px;
  overflow: hidden;
}

.bar-fill {
  height: 100%;
  transition: width 0.4s ease;
}
</style>