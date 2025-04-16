<?php
include 'NumberToPersian.php';
include 'jdf.php';
$pdo = new PDO("mysql:host=localhost;dbname=cars", "root", "");

// افزودن ماشین جدید
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create"])) {
    $stmt = $pdo->prepare("INSERT INTO cars (title, status, price, date, time) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['title'], $_POST['status'], $_POST['price'], $_POST['date'], $_POST['time']]);
    header("Location: index.php");
    exit;
}

// حذف
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: index.php");
    exit;
}

// لیست ماشین‌ها
$cars = $pdo->query("SELECT * FROM cars ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <script src="jquery-3.7.1.min.js"></script>

    <script src="persian-date.min.js"></script>

    <script src="persian-datepicker.min.js"></script>
    <link rel="stylesheet" href="persian-datepicker.min.css">

    <script src="numberToWord.js"></script>
    <meta charset="UTF-8">
    <title>مدیریت ماشین‌ها</title>
    <style>
        body {
            font-family: sans-serif;
            direction: rtl;
            background: #f9f9f9;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        form {
            margin-bottom: 30px;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 5px #ccc;
        }

        input, select, button {
            padding: 10px;
            margin: 5px 0;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 100%;
            box-sizing: border-box;
        }

        button.btn {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        button.btn:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 5px #ccc;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        a.btn {
            padding: 5px 10px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        a.btn-danger {
            background: #e74c3c;
        }

        td[data-price]:hover::after {
            content: attr(data-price-text);
            display: block;
            position: absolute;
            background: #eee;
            padding: 5px 10px;
            border-radius: 5px;
            color: #333;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>
<body>

<h2>افزودن ماشین جدید</h2>
<form method="POST">
    <input type="text" name="title" placeholder="نام ماشین" required>
    <select name="status">
        <option value="active">فعال</option>
        <option value="inactive">غیرفعال</option>
    </select>
    <input type="number" step="0.01" name="price" placeholder="قیمت" required>
    <input type="hidden" name="date" id="date">
    <input type="text" id="shamsi_date" name="shamsi_date" placeholder="تاریخ" required>
    <input type="time" name="time" required>
    <button type="submit" name="create" class="btn">افزودن</button>
</form>

<h2>لیست ماشین‌ها</h2>
<table>
    <tr>
        <th>ID</th>
        <th>عنوان</th>
        <th>وضعیت</th>
        <th>قیمت</th>
        <th>تاریخ</th>
        <th>ساعت</th>
        <th>عملیات</th>
    </tr>
    <?php foreach ($cars as $car): ?>
        <tr>
            <td><?= $car['id'] ?></td>
            <td><?= htmlspecialchars($car['title']) ?></td>
            <td><?= $car['status'] ?></td>
            <td class="price-cell"
                data-price="<?= $car['price'] ?>"
                data-price-text="<?= number_format($car['price']) ?> تومان">
                <?= number_format($car['price']) ?> تومان
            </td>
            <td><?= $car['date'] ?></td>
            <td><?= $car['time'] ?></td>
            <td>
                <a href="edit.php?id=<?= $car['id'] ?>" class="btn">ویرایش</a>
                <a href="?delete=<?= $car['id'] ?>" class="btn btn-danger" onclick="return confirm('حذف شود؟')">حذف</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    $(document).ready(function () {
        $("#shamsi_date").persianDatepicker({
            format: 'YYYY/MM/DD',
            initialValue: false,
            onSelect: function (unix) {
                const miladi = new persianDate(unix).toCalendar('gregorian').format("YYYY-MM-DD");
                $("#date").val(miladi);
            }
        });
    });
    $(document).ready(function () {
        $('.price-cell').hover(function () {
            let price = parseInt($(this).data('price'));
            let priceInWords = numberToPersianWords(price);
            $(this).attr('data-price-text', priceInWords + ' تومان');
        });
    });

</script>

</body>
</html>