<template>
    <div id="chart" class="relative" :style="{ height: height+'px' }">
        <apexchart v-if="loaded" type="polarArea" :options="chartOptions" :series="chartData"></apexchart>
    </div>
</template>

<script>

export default {
    data() {
        return {
            loaded: false,
            chartOptions: {
                chart: {
                    type: 'polarArea',
                    height: 350,
                },
                stroke: {
                    colors: ['#FFF']
                },
                fill: {
                    opacity: 0.8
                },
                labels: [],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
            },

        }
    },
    /**
     * The component's computed properties.
     */
    computed: {
        chartLabels() {
            return this.data.map((dataPoint) => ' ' + dataPoint.label);
        },

        chartData() {
            return this.data.map((dataPoint) => dataPoint.value);
        },
    },

    /**
     * Prepare the component.
     */
    mounted() {
        this.chartOptions.labels = this.chartLabels;
        setTimeout(() => this.loaded = true);
    },
    methods: {
        dynamicColors(index) {
            const CHART_COLORS = {
                red1: 'rgb(244, 67, 54)',
                red: 'rgb(255, 99, 132)',
                orange: 'rgb(255, 159, 64)',
                yellow: 'rgb(255, 205, 86)',
                green: 'rgb(75, 192, 192)',
                blue: 'rgb(54, 162, 235)',
                green1: 'rgb(76, 175, 80)',
                grey: 'rgb(201, 203, 207)',
                grey_blue: 'rgb(96, 125, 139)',
                purple1: 'rgb(103, 58, 183)',
                purple: 'rgb(153, 102, 255)',
                purple2: 'rgb(255, 100, 248)',
            };
            const colorIndex = index % Object.keys(CHART_COLORS).length;
            return Object.values(CHART_COLORS)[colorIndex]
        }
    },

    /**
     * The component's props.
     */
    props: ['label', 'data', 'labels', 'series', 'color', 'height', 'formatTooltipTitle', 'suggestedMax'],
};
</script>
