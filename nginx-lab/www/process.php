<?php
header('Content-Type: text/html; charset=utf-8');

// Если данные пришли через GET (прямой переход), покажем информацию
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<div style="max-width: 500px; margin: 50px auto; padding: 20px; background: #f8d7da; color: #721c24; border-radius: 5px;">';
    echo '<h2>Используйте форму для отправки данных</h2>';
    echo '<p>Эта страница обрабатывает данные формы регистрации.</p>';
    echo '<p><a href="form.html" style="color: #721c24;">← Вернуться к форме</a></p>';
    echo '</div>';
    exit;
}

// Получаем и очищаем данные из формы
$name = trim($_POST['name'] ?? '');
$age = trim($_POST['age'] ?? '');
$faculty = $_POST['faculty'] ?? '';
$educationForm = $_POST['educationForm'] ?? '';
$agree = isset($_POST['agree']) ? 'Да' : 'Нет';

// Массив для ошибок
$errors = [];

// Проверяем обязательные поля
if (empty($name)) {
    $errors[] = "ФИО студента обязательно для заполнения";
}

if (empty($age) || $age < 16 || $age > 60) {
    $errors[] = "Возраст должен быть от 16 до 60 лет";
}

if (empty($faculty)) {
    $errors[] = "Выберите факультет";
}

if (empty($educationForm)) {
    $errors[] = "Выберите форму обучения";
}

if ($agree === 'Нет') {
    $errors[] = "Необходимо согласие с правилами университета";
}

// Если есть ошибки, выводим их
if (!empty($errors)) {
    echo '<div class="error">';
    echo '<h3>Ошибки при заполнении формы:</h3>';
    echo '<ul>';
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo '</ul>';
    echo '<a href="form.html">Вернуться к форме</a>';
    echo '</div>';
    exit;
}

// Преобразуем значения для красивого вывода
$facultyNames = [
    'informatics' => 'Информатика и вычислительная техника',
    'economics' => 'Экономика и управление',
    'linguistics' => 'Лингвистика и межкультурная коммуникация',
    'law' => 'Юриспруденция',
    'engineering' => 'Инженерный факультет'
];

$educationForms = [
    'full-time' => 'Очная форма',
    'part-time' => 'Заочная форма',
    'evening' => 'Вечерняя форма'
];

$facultyDisplay = $facultyNames[$faculty] ?? $faculty;
$educationFormDisplay = $educationForms[$educationForm] ?? $educationForm;

// Выводим результат
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результат регистрации</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 30px;
        }
        .success-message {
            background: #dff0d8;
            color: #3c763d;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #4CAF50;
        }
        .student-info {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 4px;
        }
        .info-item {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .label {
            font-weight: bold;
            color: #333;
        }
        .value {
            color: #666;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #4CAF50;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .timestamp {
            text-align: center;
            color: #999;
            font-size: 14px;
            margin-top: 20px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✅ Регистрация завершена успешно!</h1>
        
        <div class="success-message">
            <strong>Спасибо за регистрацию!</strong> Данные студента успешно обработаны.
        </div>

        <div class="student-info">
            <h3>Информация о студенте:</h3>
            
            <div class="info-item">
                <span class="label">ФИО студента:</span><br>
                <span class="value"><?php echo htmlspecialchars($name); ?></span>
            </div>
            
            <div class="info-item">
                <span class="label">Возраст:</span><br>
                <span class="value"><?php echo htmlspecialchars($age); ?> лет</span>
            </div>
            
            <div class="info-item">
                <span class="label">Факультет:</span><br>
                <span class="value"><?php echo htmlspecialchars($facultyDisplay); ?></span>
            </div>
            
            <div class="info-item">
                <span class="label">Форма обучения:</span><br>
                <span class="value"><?php echo htmlspecialchars($educationFormDisplay); ?></span>
            </div>
            
            <div class="info-item">
                <span class="label">Согласие с правилами:</span><br>
                <span class="value"><?php echo $agree; ?></span>
            </div>
        </div>

        <div class="timestamp">
            Дата регистрации: <?php echo date('d.m.Y H:i:s'); ?>
        </div>

        <a href="form.html" class="back-link">← Вернуться к форме регистрации</a>
    </div>
</body>
</html>