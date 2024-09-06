let smallNumFormatter = new Intl.NumberFormat(
    "en-EN",
    {
        maximumFractionDigits: 0,
        roundingMode: "trunc",
    }
)

let bigNumFormatter = new Intl.NumberFormat(
    "en-EN",
    {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        notation: "scientific",
        roundingMode: "trunc",
    }
)

export default function formatNum(number) {
    if (number < 1e9) {
        return smallNumFormatter.format(number);
    }
    else {
        return bigNumFormatter.format(number).toLowerCase();
    }
}