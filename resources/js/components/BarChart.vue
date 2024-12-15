<template>
    <div class="relative" :style="{ height: height }">
        <canvas ref="canvas"></canvas>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue"
import Chart from "chart.js"

// Props
const props = defineProps({
    label: String,
    data: Array,
    color: String,
    height: String,
    formatTooltipTitle: Function,
    suggestedMax: Number,
})

// Refs
const canvas = ref(null)

// Computed properties
const chartLabels = computed(() => {
    return props.data.map((dataPoint) => dataPoint.label)
})

const chartData = computed(() => {
    return props.data.map((dataPoint) => dataPoint.value)
})

// Chart colors based on color prop
const chartColors = {
    green: {
        backgroundColor: "hsla(150, 86%, 86% , 0.4)",
        borderColor: "hsla(150, 75%, 65% , 0.4)",
    },
    red: {
        backgroundColor: "hsla(0,100%,50%,0.4)",
        borderColor: "hsla(0, 75%, 65% , 0.4)",
    },
    default: {
        backgroundColor: "hsla(265, 86%, 86%, 0.4)",
        borderColor: "hsla(260, 73%, 70%, 0.4)",
    },
}

const color = chartColors[props.color] || chartColors.default

// Chart initialization
onMounted(() => {
    Chart.defaults.global.defaultFontFamily = "Nunito, sans-serif"

    new Chart(canvas.value, {
        type: "bar",
        data: {
            labels: chartLabels.value,
            datasets: [
                {
                    steppedLine: "after",
                    pointBorderColor: "rgba(0, 0, 0, 0)",
                    pointBackgroundColor: "rgba(0, 0, 0, 0)",
                    label: props.label,
                    title: props.label,
                    data: chartData.value,
                    ...color,
                    borderWidth: 1,
                    barThickness: "flex",
                    barPercentage: 0.4,
                    categoryPercentage: 0.6,
                    lineTension: 0.2,
                },
            ],
        },
        options: {
            maintainAspectRatio: true,
            legend: {
                display: true,
            },
            tooltips: {
                displayColors: true,
                callbacks: {
                    label: (context, data) => {
                        return data.datasets[0].label + ": " + data.datasets[0].data[context.index] + " "
                    },
                },
            },
            scales: {
                yAxes: [
                    {
                        gridLines: {
                            color: "rgba(0, 0, 0, .05)",
                            zeroLineColor: "rgba(0, 0, 0, 0)",
                            drawBorder: true,
                            drawTicks: true,
                            display: true,
                        },
                        ticks: {
                            beginAtZero: true,
                            display: true,
                            suggestedMax: props.suggestedMax,
                            padding: 15,
                        },
                    },
                ],
                xAxes: [
                    {
                        gridLines: {
                            drawBorder: true,
                            display: true,
                            drawTicks: true,
                            offsetGridLines: true,
                        },
                        ticks: {
                            display: true,
                            maxTicksLimit: 10,
                            maxRotation: 0,
                            padding: 5,
                        },
                    },
                ],
            },
        },
    })
})
</script>
