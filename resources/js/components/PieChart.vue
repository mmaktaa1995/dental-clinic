<template>
    <div class="relative" :style="{ height: height }">
        <canvas ref="canvas"></canvas>
    </div>
</template>

<script>
import Chart from "chart.js"

export default {
    /**
     * The component's props.
     */
    props: ["label", "data", "color", "height", "formatTooltipTitle", "suggestedMax"],
    /**
     * The component's computed properties.
     */
    computed: {
        chartLabels() {
            return this.data.map((dataPoint) => dataPoint.label)
        },

        chartData() {
            return this.data.map((dataPoint) => dataPoint.value)
        },
    },

    /**
     * Prepare the component.
     */
    mounted() {
        Chart.defaults.global.defaultFontFamily = "Nunito, sans-serif"

        const colors = []
        for (let i = 0; i < this.data.length; i++) {
            colors.push(this.dynamicColors(i))
        }
        const $this = this
        new Chart(this.$refs.canvas, {
            type: "pie",
            data: {
                labels: this.chartLabels,
                datasets: [
                    {
                        label: this.label,
                        title: this.label,
                        data: this.chartData,
                        backgroundColor: colors,
                        hoverOffset: 4,
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
                        title: function (context, data) {
                            return data.labels[context[0].index]
                        },
                        label: function (context, data) {
                            return data.datasets[0].data[context.index] + " " + data.datasets[0].label
                        },
                    },
                },
                plugins: {},
            },
        })
    },
    methods: {
        dynamicColors(index) {
            const CHART_COLORS = {
                red1: "rgb(244, 67, 54)",
                red: "rgb(255, 99, 132)",
                orange: "rgb(255, 159, 64)",
                yellow: "rgb(255, 205, 86)",
                green: "rgb(75, 192, 192)",
                blue: "rgb(54, 162, 235)",
                green1: "rgb(76, 175, 80)",
                grey: "rgb(201, 203, 207)",
                grey_blue: "rgb(96, 125, 139)",
                purple1: "rgb(103, 58, 183)",
                purple: "rgb(153, 102, 255)",
                purple2: "rgb(255, 100, 248)",
            }
            const colorIndex = index % Object.keys(CHART_COLORS).length
            return Object.values(CHART_COLORS)[colorIndex]
        },
    },
}
</script>
