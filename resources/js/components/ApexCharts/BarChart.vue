<template>
    <div id="chart" class="relative" :style="{ height: height + 'px' }">
        <apexchart v-if="loaded" type="bar" :options="chartOptions" :series="single ? [{ name: label, data: chartData }] : series"></apexchart>
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts"
function numberFormat(value) {
    return value
        ? value.toLocaleString(
              undefined, // leave undefined to use the visitor's browser locale or a string like 'en-US' to override it.
              { minimumFractionDigits: 2 },
          )
        : "0"
}

export default {
    components: {
        apexchart: VueApexCharts,
    },
    /**
     * The component's props.
     */
    props: ["label", "data", "labels", "series", "color", "colors", "height", "formatTooltipTitle", "suggestedMax", "single"],
    data() {
        return {
            loaded: false,
            chartOptions: {
                // colors: [],
                chart: {
                    height: this.height,
                    type: "bar",
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        dataLabels: {
                            position: "top", // top, center, bottom
                        },
                    },
                },
                dataLabels: {
                    enabled: false,
                    formatter: function (val) {
                        return val + "%"
                    },
                    offsetY: -20,
                    style: {
                        fontSize: "12px",
                        colors: ["#304758"],
                    },
                },

                xaxis: {
                    categories: [],
                    position: "bottom",
                    axisBorder: {
                        show: true,
                    },
                    axisTicks: {
                        show: true,
                    },
                    // crosshairs: {
                    //     fill: {
                    //         type: 'gradient',
                    //         gradient: {
                    //             colorFrom: '#D8E3F0',
                    //             colorTo: '#BED1E6',
                    //             stops: [0, 100],
                    //             opacityFrom: 0.4,
                    //             opacityTo: 0.5,
                    //         }
                    //     }
                    // },
                    tooltip: {
                        enabled: true,
                        y: {
                            formatter: function (value, { series, seriesIndex, dataPointIndex, w }) {
                                return this.label + ": " + value
                            },
                        },
                    },
                },
                yaxis: {
                    axisBorder: {
                        show: true,
                    },
                    axisTicks: {
                        show: true,
                    },
                    labels: {
                        show: true,
                        formatter: function (val) {
                            return numberFormat(val)
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
            green: {
                backgroundColor: "rgba(244, 67, 54, 0.7)",
                borderColor: "hsla(0, 75%, 65% , 0.4)",
            },
            red: {
                backgroundColor: "#FF8A8A",
                borderColor: "hsla(0, 75%, 65% , 0.4)",
            },
            default: {
                backgroundColor: "#D6BDFA",
                borderColor: "hsla(260, 73%, 70%, 0.4)",
            },
        }
        if (this.colors) {
            this.chartOptions.colors = this.colors
        } else {
            let color = colors[this.color]
            if (!color) {
                color = colors.default
            }
            this.chartOptions.colors = [color.backgroundColor, ...Object.values(colors).map((_) => _.backgroundColor)]
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
