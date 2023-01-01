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

        let colors = {
            green: {
                backgroundColor: 'hsla(150, 86%, 86% , 0.4)',
                borderColor: 'hsla(150, 75%, 65% , 0.4)',
            },
            red: {
                backgroundColor: 'hsla(0,100%,50%,0.4)',
                borderColor: 'hsla(0, 75%, 65% , 0.4)',
            },
            default:{
                backgroundColor: 'hsla(265, 86%, 86%, 0.4)',
                borderColor: 'hsla(260, 73%, 70%, 0.4)',
            }
        }
        let color = colors[this.color];
        if (!color){
            color = colors.default;
        }
        const $this = this;
        new Chart(this.$refs.canvas, {
            type: 'bar',
            data: {
                labels: $this.chartLabels,
                datasets: [
                    {
                        steppedLine: 'after',
                        pointBorderColor: 'rgba(0, 0, 0, 0)',
                        pointBackgroundColor: 'rgba(0, 0, 0, 0)',
                        label: $this.label,
                        title: $this.label,
                        data: $this.chartData,
                        ...color,
                        borderWidth: 1,
                        barThickness: 'flex',
                        barPercentage: .4,
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
                        // title: function(context){
                        //     return $this.label
                        // },
                        label: function(context, data){
                            return data.datasets[0].label + ': ' + data.datasets[0].data[context.index] + ' ';
                        }
                    },
                },
                scales: {
                    yAxes: [
                        {
                            gridLines: {
                                color: 'rgba(0, 0, 0, .05)',
                                zeroLineColor: 'rgba(0, 0, 0, 0)',
                                drawBorder: true,
                                drawTicks: true,
                                display: true,
                            },
                            ticks: {
                                beginAtZero: true,
                                display: true,
                                suggestedMax: this.suggestedMax,
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
        });
    },

    /**
     * The component's props.
     */
    props: ['label', 'data', 'color', 'height', 'formatTooltipTitle', 'suggestedMax'],
};
</script>
