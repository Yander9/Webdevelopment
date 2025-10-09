<?php
// process.php - обработка данных формы регистрации студента
header('Content-Type: text/html; charset=utf-8');

// Получаем данные из формы
$name = $_POST['name'] ?? '';
$age = $_POST['age'] ?? '';
$faculty = $_POST['faculty'] ?? '';
$educationForm = $_POST['educationForm'] ?? '';
$agree = isset($_POST['agree']) ? 'Да' : 'Нет';

// Проверяем обязательные поля
if (empty($name) || empty($age) || empty($faculty) || empty($educationForm) || $agree === 'Нет') {
    echo "<h2>Ошибка!</h2>";
    echo "<p>Заполните все обязательные поля</p>";
    echo "<a href='form.html'>Вернуться к форме</a>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результат регистрации</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
        h1 { color: #4CAF50; text-align: center; }
        .info { background: #f9f9f9; padding: 15px; margin: 15px 0; }
        .back { display: block; text-align: center; margin-top: 20px; color: #4CAF50; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Регистрация завершена</h1>
        
        <div class="info">
            <p><strong>ФИО:</strong> <?php echo $name; ?></p>
            <p><strong>Возраст:</strong> <?php echo $age; ?> лет</p>
            <p><strong>Факультет:</strong> <?php echo $faculty; ?></p>
            <p><strong>Форма обучения:</strong> <?php echo $educationForm; ?></p>
            <p><strong>Согласие с правилами:</strong> <?php echo $agree; ?></p>
        </div>

        <p>Дата регистрации: <?php echo date('d.m.Y H:i:s'); ?></p>
        
        <a href="form.html" class="back">Вернуться к форме</a>
    </div>
</body>
</html>