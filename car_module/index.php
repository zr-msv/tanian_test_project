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
    <script src="numberToWord.js"></script>
    <meta charset="UTF-8">
    <title>مدیریت ماشین‌ها</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 40px;
            background: #f9f9f9;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
            background: #fff;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }

        form {
            background: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            max-width: 500px;
            margin-bottom: 30px;
        }

        input, select {
            padding: 8px;
            margin: 5px 0;
            width: 100%;
        }

        .btn {
            padding: 8px 12px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn:hover {
            opacity: 0.9;
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
    <input type="date" name="date" required>
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
            <td data-price="<?= $car['price'] ?>"
                onmouseover="showPriceInWords(this)">
                <?= number_format($car['price']) ?>
            </td>
            <!--            <td data-price=" -->
            <?php //= $pric_to_word = NumberToPersian::convert($car['price']); ?><!--"-->
            <!--                onmouseover="showPriceInWords(this)">-->
            <!--                --><?php //= number_format($car['price']) ?>
            <!--            </td>-->

            <td>
                <?php
                list($gy, $gm, $gd) = explode('-', $car['date']);
                echo gregorian_to_jalali($gy, $gm, $gd, '/'); 
                ?>
            </td>
            <td><?= $car['time'] ?></td>
            <td>
                <a href="edit.php?id=<?= $car['id'] ?>" class="btn">ویرایش</a>
                <a href="?delete=<?= $car['id'] ?>" class="btn btn-danger" onclick="return confirm('حذف شود؟')">حذف</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>