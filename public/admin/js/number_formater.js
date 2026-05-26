function parseNumber(num) {
    if (isNaN(num)) {
        return "0";
    }

    // Ép kiểu về float để xử lý phần thập phân
    num = parseFloat(num);

    // Nếu là số nguyên
    if (num % 1 === 0) {
        return num.toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 0});
    }

    // Tách phần thập phân
    const parts = num.toString().split('.');
    let decimalPart = parts[1] || '';

    // Loại bỏ số 0 ở cuối phần thập phân
    decimalPart = decimalPart.replace(/0+$/, '');

    // Đếm số chữ số thập phân còn lại (tối đa 3)
    const decimalLength = Math.min(decimalPart.length, 3);

    return num.toLocaleString(undefined, {
        minimumFractionDigits: decimalLength,
        maximumFractionDigits: decimalLength
    });
}

function roundDecimal(number, decimals = 2) {
    const factor = Math.pow(10, decimals);
    return Math.round(number * factor) / factor;
}

function formatNumber(num) {
    if (!num) {
        return 0;
    }

    return num.replace(/[^0-9.-]/g, '');
}

function convert_number_to_number(num) {
    if (isNaN(num)) {
        return "0";
    }

    num = parseFloat(num);

    if (Number.isInteger(num)) {
        return num;
    }

    let rounded = num.toFixed(3);
    rounded = rounded.replace(/\.?0+$/, '');

    return parseFloat(rounded);
}

function convert_string_to_number(num) {
    if (!num) {
        return 0;
    }

    num = num.replaceAll(',', '');
    return num;
}
