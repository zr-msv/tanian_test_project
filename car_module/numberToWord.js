function convertToWords(num) {
    const yakan = ["", "یک", "دو", "سه", "چهار", "پنج", "شش", "هفت", "هشت", "نه"];
    const dahgan = ["", "ده", "بیست", "سی", "چهل", "پنجاه", "شصت", "هفتاد", "هشتاد", "نود"];
    const dahyek = ["ده", "یازده", "دوازده", "سیزده", "چهارده", "پانزده", "شانزده", "هفده", "هجده", "نوزده"];
    const sadgan = ["", "یکصد", "دویست", "سیصد", "چهارصد", "پانصد", "ششصد", "هفتصد", "هشتصد", "نهصد"];
    const basex = ["", "هزار", "میلیون", "میلیارد"];

    if (num == 0) return "صفر";

    let result = "";
    let s = num.toString();
    let groups = [];
    while (s.length > 0) {
        let end = s.length;
        let start = Math.max(0, end - 3);
        groups.unshift(parseInt(s.slice(start, end)));
        s = s.slice(0, start);
    }

    for (let i = 0; i < groups.length; i++) {
        let group = groups[i];
        if (group === 0) continue;

        let groupStr = "";

        let s = Math.floor(group / 100);
        let d = Math.floor((group % 100) / 10);
        let y = group % 10;

        if (s) groupStr += sadgan[s];
        if (d > 1) {
            groupStr += (groupStr ? " و " : "") + dahgan[d];
            if (y) groupStr += " و " + yakan[y];
        } else if (d === 1) {
            groupStr += (groupStr ? " و " : "") + dahyek[y];
        } else if (y) {
            groupStr += (groupStr ? " و " : "") + yakan[y];
        }

        if (basex[groups.length - i - 1]) {
            groupStr += " " + basex[groups.length - i - 1];
        }

        result += (result ? " و " : "") + groupStr;
    }

    return result;
}

function showPriceInWords(element) {
    const price = parseInt(element.dataset.price);
    const toman = price * 10;
    const words = convertToWords(toman);
    element.title = words + " تومان";
}