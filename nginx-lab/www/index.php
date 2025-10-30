<?php
require_once 'db.php';
require_once 'Student.php';
require_once 'UserInfo.php';

session_start();

// Создаем объект студента и получаем данные
$student = new Student($pdo);
$allStudents = $student->getAll();
$totalStudents = $student->getCount();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
        }
        
        .session-data {
            background: #f9f9f9;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        
        .api-data {
            background: #e8f4fd;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .user-info {
            background: #f0f9ff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .db-data {
            background: #fff3cd;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .links {
            text-align: center;
            margin-top: 20px;
        }
        
        .links a {
            color: #4CAF50;
            text-decoration: none;
            margin: 0 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .stats {
            background: #e8f4fd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Главная страница</h1>

        <!-- Вывод ошибок -->
        <?php if(isset($_SESSION['errors'])): ?>
            <div class="error">
                <h3>Ошибки:</h3>
                <ul>
                    <?php foreach($_SESSION['errors'] as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <!-- Вывод успеха -->
        <?php if(isset($_SESSION['success'])): ?>
            <div class="success">
                ✅ <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Статистика -->
        <div class="stats">
            <strong>Всего студентов в базе:</strong> <?= $totalStudents ?>
        </div>

        <!-- Данные из сессии -->
        <div class="session-data">
            <h3>Данные из сессии:</h3>
            <?php if(isset($_SESSION['username'])): ?>
                <ul>
                    <li><strong>Имя:</strong> <?= $_SESSION['username'] ?></li>
                    <li><strong>Email:</strong> <?= $_SESSION['email'] ?? 'Не указан' ?></li>
                    <li><strong>Возраст:</strong> <?= $_SESSION['age'] ?? 'Не указан' ?></li>
                    <li><strong>Регион:</strong> <?= $_SESSION['region'] ?? 'Не указан' ?></li>
                    <li><strong>Город:</strong> <?= $_SESSION['city'] ?? 'Не указан' ?></li>
                    <li><strong>Факультет:</strong> <?= $_SESSION['faculty'] ?? 'Не указан' ?></li>
                    <li><strong>Форма обучения:</strong> <?= $_SESSION['education_form'] ?? 'Не указан' ?></li>
                    <li><strong>Согласие с правилами:</strong> <?= $_SESSION['agree'] ?? 'Нет' ?></li>
                </ul>
            <?php else: ?>
                <p>Данных пока нет.</p>
            <?php endif; ?>
        </div>

        <!-- Данные из базы данных -->
        <div class="db-data">
            <h3>Сохранённые данные из MySQL:</h3>
            <?php if(!empty($allStudents)): ?>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($allStudents as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['age']) ?></td>
                            <td><?= htmlspecialchars($row['region']) ?></td>
                            <td><?= htmlspecialchars($row['city']) ?></td>
                            <td><?= htmlspecialchars($row['faculty']) ?></td>
                            <td><?= htmlspecialchars($row['education_form']) ?></td>
                            <td><?= $row['agree_rules'] ? 'Да' : 'Нет' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Нет данных в базе.</p>
            <?php endif; ?>
        </div>

        <!-- Данные из API -->
        <?php if(isset($_SESSION['api_data'])): ?>
            <div class="api-data">
                <h3>Данные из API (Регионы России):</h3>
                <?php if(isset($_SESSION['api_data']['areas'])): ?>
                    <div style="max-height: 200px; overflow-y: auto;">
                        <?php 
                        $regions = $_SESSION['api_data']['areas'];
                        foreach(array_slice($regions, 0, 8) as $region):
                            $cities = $region['areas'] ?? [];
                            $cityNames = array_slice(array_column($cities, 'name'), 0, 2);
                        ?>
                            <div style="margin-bottom: 10px; padding: 8px; background: white; border-radius: 4px;">
                                <strong><?= htmlspecialchars($region['name']) ?></strong>
                                <?php if(!empty($cityNames)): ?>
                                    <br>
                                    <small>Города: <?= htmlspecialchars(implode(', ', $cityNames)) ?>
                                    <?php if(count($cities) > 2): ?>...<?php endif; ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php elseif(isset($_SESSION['api_data']['error'])): ?>
                    <p style="color: red;">Ошибка API: <?= $_SESSION['api_data']['error'] ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Информация о пользователе -->
        <div class="user-info">
            <h3>Информация о пользователе:</h3>
            <?php
            $info = UserInfo::getInfo();
            foreach ($info as $key => $val) {
                echo htmlspecialchars($key) . ': ' . htmlspecialchars($val) . '<br>';
            }
            ?>
        </div>

        <!-- Ссылки -->
        <div class="links">
            <a href="form.html">Заполнить форму</a> |
            <a href="view.php">Посмотреть все данные</a> |
            <a href="clear_session.php">Очистить сессию</a>
        </div>
    </div>
</body>
</html>