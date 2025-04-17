<?php
// نمایش خطاها
ini_set('display_errors', 1);
error_reporting(E_ALL);

// اتصال به پایگاه داده
$pdo = new PDO("mysql:host=localhost;dbname=cars", "root", "");

// گرفتن اطلاعات فعلی ماشین
$id = $_GET['id'] ?? null;
if (!$id) {
    die("Invalid ID");
}

$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    die("Car not found");
}

// اگر فرم ارسال شده
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $status = $_POST['status'];
    $price = $_POST['price'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $stmt = $pdo->prepare("UPDATE cars SET title = ?, status = ?, price = ?, date = ?, time = ? WHERE id = ?");
    $stmt->execute([$title, $status, $price, $date, $time, $id]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Car</title>
</head>
<body>
<h1>Edit Car</h1>
<form method="POST">
    <input type="text" name="title" value="<?= htmlspecialchars($car['title']) ?>" required>
    <select name="status">
        <option value="active" <?= $car['status'] === 'active' ? 'selected' : '' ?>>Active</option>
        <option value="inactive" <?= $car['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
    </select>
    <input type="number" step="0.01" name="price" value="<?= $car['price'] ?>" required>
    <input type="date" name="date" value="<?= $car['date'] ?>" required>
    <input type="time" name="time" value="<?= $car['time'] ?>" required>
    <button type="submit">Save</button>
</form>
</body>
</html>