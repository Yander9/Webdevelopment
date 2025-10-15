<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все данные</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #333; text-align: center; }
        .data-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .data-table th { background: #f9f9f9; }
        .data-table tr:nth-child(even) { background: #f9f9f9; }
        .links { text-align: center; margin-top: 30px; }
        .links a { color: #4CAF50; text-decoration: none; margin: 0 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Все сохранённые данные</h1>

        <?php if(file_exists("data.txt")): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Возраст</th>
                        <th>Факультет</th>
                        <th>Форма обучения</th>
                        <th>Дата регистрации</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $lines = file("data.txt", FILE_IGNORE_NEW_LINES);
                    foreach($lines as $line):
                        list($name, $email, $age, $faculty, $educationForm, $timestamp) = explode(";", $line);
                        
                        // Преобразуем значения для красивого отображения
                        $facultyNames = [
                            'informatics' => 'Информатика',
                            'economics' => 'Экономика',
                            'linguistics' => 'Лингвистика',
                            'law' => 'Юриспруденция',
                            'engineering' => 'Инженерный'
                        ];
                        
                        $educationForms = [
                            'full-time' => 'Очная',
                            'part-time' => 'Заочная'
                        ];
                        
                        $facultyDisplay = $facultyNames[$faculty] ?? $faculty;
                        $educationFormDisplay = $educationForms[$educationForm] ?? $educationForm;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($name) ?></td>
                        <td><?= htmlspecialchars($email) ?></td>
                        <td><?= htmlspecialchars($age) ?></td>
                        <td><?= $facultyDisplay ?></td>
                        <td><?= $educationFormDisplay ?></td>
                        <td><?= $timestamp ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: #666;">Данных пока нет</p>
        <?php endif; ?>

        <div class="links">
            <a href="index.php">← На главную</a>
            <a href="form.html">📝 Заполнить форму</a>
        </div>
    </div>
</body>
</html>