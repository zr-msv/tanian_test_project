function numberToPersianWords(num) {
    const persianNumbers = [
        "", "یک", "دو", "سه", "چهار", "پنج", "شش", "هفت", "هشت", "نه",
        "ده", "یازده", "دوازده", "سیزده", "چهارده", "پانزده", "شانزده", "هفده", "هجده", "نوزده"
    ];
    const tens = ["", "", "بیست", "سی", "چهل", "پنجاه", "شصت", "هفتاد", "هشتاد", "نود"];
    const hundreds = ["", "صد", "دویست", "سیصد", "چهارصد", "پانصد", "ششصد", "هفتصد", "هشتصد", "نهصد"];
    const thousands = ["", "هزار", "میلیون", "میلیارد"];

    if (num === 0) return "صفر";

    function sectionToWords(section) {
        let words = [];
        let hundred = Math.floor(section / 100);
        let remainder = section % 100;
        if (hundred) words.push(hundreds[hundred]);
        if (remainder < 20) {
            if (remainder) words.push(persianNumbers[remainder]);
        } else {
            words.push(tens[Math.floor(remainder / 10)]);
            if (remainder % 10) words.push(persianNumbers[remainder % 10]);
        }
        return words.join(" و ");
    }

    let parts = [];
    let sectionIndex = 0;
    while (num > 0) {
        let section = num % 1000;
        if (section) {
            let sectionWords = sectionToWords(section);
            if (thousands[sectionIndex]) {
                sectionWords += " " + thousands[sectionIndex];
            }
            parts.unshift(sectionWords);
        }
        num = Math.floor(num / 1000);
        sectionIndex++;
    }

    return parts.join(" و ");
}