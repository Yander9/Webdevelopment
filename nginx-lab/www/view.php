<?php
require_once 'db.php';
require_once 'Student.php';

session_start();

// Создаем объект студента и получаем данные
$student = new Student($pdo);
$allStudents = $student->getAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все данные из MySQL</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #333; text-align: center; }
        .stats { background: #e8f4fd; padding: 15px; margin: 20px 0; border-radius: 4px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #f9f9f9; }
        .links { text-align: center; margin-top: 30px; }
        .links a { color: #4CAF50; text-decoration: none; margin: 0 10px; }
        .no-data { text-align: center; color: #666; padding: 40px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Все данные из MySQL</h1>
        
        <div class="stats">
            <strong>Всего записей в базе:</strong> <?= count($allStudents) ?>
        </div>

        <?php if (empty($allStudents)): ?>
            <div class="no-data">Нет данных в базе</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
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
                    <?php foreach($allStudents as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['id']) ?></td>
                        <td><?= htmlspecialchars($student['name']) ?></td>
                        <td><?= htmlspecialchars($student['email']) ?></td>
                        <td><?= htmlspecialchars($student['age']) ?></td>
                        <td><?= htmlspecialchars($student['region']) ?></td>
                        <td><?= htmlspecialchars($student['city']) ?></td>
                        <td><?= htmlspecialchars($student['faculty']) ?></td>
                        <td><?= htmlspecialchars($student['education_form']) ?></td>
                        <td><?= $student['agree_rules'] ? 'Да' : 'Нет' ?></td>
                        <td><?= htmlspecialchars($student['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="links">
            <a href="index.php">← На главную</a>
            <a href="form.html">📝 Заполнить форму</a>
        </div>
        
        <div style="text-align: center; margin-top: 20px; color: #666;">
            <small>База данных: MySQL | Adminer: <a href="http://localhost:8081" target="_blank">http://localhost:8081</a></small>
        </div>
    </div>
</body>
</html>