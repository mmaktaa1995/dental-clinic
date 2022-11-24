<template>
    <div class="relative" :style="{ height: height }">
        <canvas ref="canvas"></canvas>
    </div>
</template>

<script>
import Chart from 'chart.js';

export default {
    /**
     * The component's computed properties.
     */
    computed: {
        chartLabels() {
            return this.data.map((dataPoint) => dataPoint.label);
        },

        chartData() {
            return this.data.map((dataPoint) => dataPoint.value);
        },
    },

    /**
     * Prepare the component.
     */
    mounted() {
        Chart.defaults.global.defaultFontFamily = 'Nunito, sans-serif';

        let colors = []
        for (let i = 0; i < this.data.length; i++) {
            colors.push(this.dynamicColors(i));
        }
        const $this = this;
        new Chart(this.$refs.canvas, {
            type: 'pie',
            data: {
                labels: this.chartLabels,
                datasets: [
                    {
                        label: this.label,
                        title: this.label,
                        data: this.chartData,
                        backgroundColor: colors,
                        hoverOffset: 4
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
                        // title: function(context){
                        //     return $this.label
                        // },
                        label: function(context, data){
                            return data.datasets[0].label + ': ' + data.datasets[0].data[context.index];
                        }
                    },
                },
                plugins:{
                }
            },
        });
    },
    methods: {
        dynamicColors(index) {
            let r = Math.floor(Math.random() * 255);
            let g = Math.floor(Math.random() * 255);
            let b = Math.floor(Math.random() * 255);

            const CHART_COLORS = {
                red: 'rgb(255, 99, 132)',
                orange: 'rgb(255, 159, 64)',
                yellow: 'rgb(255, 205, 86)',
                green: 'rgb(75, 192, 192)',
                blue: 'rgb(54, 162, 235)',
                purple: 'rgb(153, 102, 255)',
                grey: 'rgb(201, 203, 207)'
            };
            const colorIndex = index % Object.keys(CHART_COLORS).length;
            return  Object.values(CHART_COLORS)[colorIndex]
            return "rgba(" + r + "," + g + "," + b + ", .3)";
        }
    },

    /**
     * The component's props.
     */
    props: ['label', 'data', 'color', 'height', 'formatTooltipTitle', 'suggestedMax'],
};
</script>
