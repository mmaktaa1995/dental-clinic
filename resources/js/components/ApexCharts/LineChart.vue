<template>
    <div id="chart" class="relative" :style="{ height: height + 'px' }">
        <apexchart v-if="loaded" type="area" :options="chartOptions" :series="series ? series : [{ name: '', data: chartData }]"></apexchart>
    </div>
</template>

<script>
function numberFormat(value) {
    return value
        ? value.toLocaleString(
              undefined, // leave undefined to use the visitor's browser locale or a string like 'en-US' to override it.
              { minimumFractionDigits: 2 },
          )
        : "0"
}

import VueApexCharts from "vue3-apexcharts"

export default {
    components: {
        apexchart: VueApexCharts,
    },
    /**
     * The component's props.
     */
    props: ["label", "data", "labels", "series", "color", "colors", "height", "formatTooltipTitle", "suggestedMax"],
    data() {
        const label = this.label
        const series = this.series
        return {
            loaded: false,
            chartOptions: {
                // forecastDataPoints: {
                //     count: 7
                // },
                chart: {
                    type: "area",
                    stacked: false,
                    height: this.height,
                    zoom: {
                        type: "x",
                        enabled: true,
                        autoScaleYaxis: true,
                    },
                    toolbar: {
                        autoSelected: "zoom",
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                markers: {
                    size: 5,
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        inverseColors: false,
                        opacityFrom: 0.8,
                        opacityTo: 0,
                        stops: [0, 90, 100],
                    },
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return numberFormat(val)
                        },
                    },
                    axisTicks: {
                        show: true,
                    },
                },
                xaxis: {},
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return series ? numberFormat(val) : "  " + label + ": " + numberFormat(val)
                        },
                    },
                },
            },
        }
    },
    /**
     * The component's computed properties.
     */
    computed: {
        chartLabels() {
            return this.data.map((dataPoint) => " " + dataPoint.label)
        },

        chartData() {
            return this.data.map((dataPoint) => dataPoint.value)
        },
    },

    /**
     * Prepare the component.
     */
    mounted() {
        this.chartOptions.xaxis.categories = this.chartLabels
        const colors = {
            red: "#FF8A8A",
            green: "#BDFADB",
            blue: "#36A2EB",
            pink: "#e36dde",
            yellow: "#FFCD56",
            default: "#D6BDFA",
        }
        let color = colors[this.color]
        if (!color) {
            color = colors.default
        }
        if (!this.series || (this.series && !this.colors)) {
            this.chartOptions.colors = [color, ...Object.values(colors)]
        } else {
            this.chartOptions.colors = []
            this.colors.forEach((c) => {
                this.chartOptions.colors.push(colors[c])
            })
        }
        setTimeout(() => (this.loaded = true))
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
