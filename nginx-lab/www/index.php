<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #333; text-align: center; }
        .session-data { background: #f9f9f9; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin: 20px 0; }
        .links { text-align: center; margin-top: 30px; }
        .links a { margin: 0 10px; color: #4CAF50; text-decoration: none; }
        .links a:hover { text-decoration: underline; }
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

        <!-- Вывод данных из сессии -->
        <div class="session-data">
            <?php if(isset($_SESSION['username'])): ?>
                <h3>Данные из сессии:</h3>
                <ul>
                    <li><strong>Имя:</strong> <?= $_SESSION['username'] ?></li>
                    <li><strong>Email:</strong> <?= $_SESSION['email'] ?? 'Не указан' ?></li>
                    <li><strong>Возраст:</strong> <?= $_SESSION['age'] ?? 'Не указан' ?></li>
                    <li><strong>Факультет:</strong> <?= $_SESSION['faculty'] ?? 'Не указан' ?></li>
                    <li><strong>Форма обучения:</strong> <?= $_SESSION['education_form'] ?? 'Не указан' ?></li>
                </ul>
            <?php else: ?>
                <p>Данных пока нет.</p>
            <?php endif; ?>
        </div>

        <!-- Ссылки -->
        <div class="links">
            <a href="form.html">📝 Заполнить форму</a> |
            <a href="view.php">📊 Посмотреть все данные</a> |
            <a href="clear_session.php">🗑️ Очистить сессию</a>
        </div>
    </div>
</body>
</html>