export default {
    percentage(value: number, decimals = 0): string {
        return `${(value * 100).toFixed(decimals)}%`
    },
}
