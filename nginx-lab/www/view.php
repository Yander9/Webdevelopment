<?php
// view.php - Просмотр сохраненных данных

// Функция для безопасного вывода строк
function safeString($value) {
    if ($value === null || $value === '') {
        return '';
    }
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Чтение данных из файла
$dataFile = 'data.txt';
$dataArray = [];

if (file_exists($dataFile)) {
    $lines = file($dataFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $dataArray[] = explode(';', $line); // Исправлено на разделитель ;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все сохранённые данные</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .no-data {
            text-align: center;
            color: #666;
            padding: 40px;
            font-style: italic;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-link:hover {
            background-color: #0056b3;
        }
        .links {
            text-align: center;
            margin-top: 30px;
        }
        .links a {
            margin: 0 10px;
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Все сохранённые данные</h1>

        <?php if (empty($dataArray)): ?>
            <div class="no-data">
                Нет сохраненных данных для отображения.
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Возраст</th>
                        <th>Регион</th>
                        <th>Город</th>
                        <th>Факультет</th>
                        <th>Форма обучения</th>
                        <th>Согласие</th>
                        <th>Дата регистрации</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataArray as $row): ?>
                        <tr>
                            <td><?php echo safeString($row[0] ?? ''); ?></td>
                            <td><?php echo safeString($row[1] ?? ''); ?></td>
                            <td><?php echo safeString($row[2] ?? ''); ?></td>
                            <td><?php echo safeString($row[3] ?? ''); ?></td>
                            <td><?php echo safeString($row[4] ?? ''); ?></td>
                            <td><?php echo safeString($row[5] ?? ''); ?></td>
                            <td><?php echo safeString($row[6] ?? ''); ?></td>
                            <td><?php echo safeString($row[7] ?? ''); ?></td>
                            <td><?php echo safeString($row[8] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="links">
            <a href="form.html">📝 Вернуться к форме регистрации</a> |
            <a href="index.php">🏠 На главную</a>
        </div>
    </div>
</body>
</html>