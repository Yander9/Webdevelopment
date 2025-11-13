<?php
require_once 'vendor/autoload.php';


use App\Services\RedisUserService;

$redisService = new RedisUserService();

// Обработка форм
if ($_POST['action'] ?? '' === 'add_user') {
    $id = uniqid();
    $userData = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'age' => $_POST['age'],
        'city' => $_POST['city'],
        'created_at' => date('Y-m-d H:i:s')
    ];
    $redisService->addUser($id, $userData);
    header('Location: ?success=1');
    exit;
}

if ($_GET['action'] ?? '' === 'delete') {
    $redisService->deleteUser($_GET['id']);
    header('Location: ?deleted=1');
    exit;
}

if ($_POST['action'] ?? '' === 'update_user') {
    $redisService->updateUser($_POST['id'], $_POST['field'], $_POST['value']);
    header('Location: ?updated=1');
    exit;
}

// Получение данных
$allUsers = $redisService->getAllUsers();
$usersCount = $redisService->getUsersCount();
$ageStats = $redisService->getAgeStatistics();
$cities = ['Москва', 'Санкт-Петербург', 'Казань', 'Новосибирск', 'Екатеринбург'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redis - Управление пользователями</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial; background: #1e272e; color: white; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { text-align: center; color: #ff6b6b; margin-bottom: 30px; }
        .card { background: #2d3436; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #ff6b6b; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #dfe6e9; }
        input, select { width: 100%; padding: 10px; border: 1px solid #636e72; border-radius: 4px; background: #2d3436; color: white; }
        .btn { background: #ff6b6b; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        .btn:hover { background: #ff5252; }
        .btn-danger { background: #d63031; }
        .btn-success { background: #00b894; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #636e72; }
        th { background: #34495e; color: #ff6b6b; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
        .stat-card { background: #34495e; padding: 15px; border-radius: 4px; text-align: center; }
        .success { background: #00b894; color: white; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .user-form { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        @media (max-width: 768px) {
            .user-form { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔴 Redis - Система управления пользователями</h1>

        <?php if ($_GET['success'] ?? ''): ?>
            <div class="success">✅ Пользователь успешно добавлен!</div>
        <?php endif; ?>

        <?php if ($_GET['deleted'] ?? ''): ?>
            <div class="success">✅ Пользователь успешно удален!</div>
        <?php endif; ?>

        <?php if ($_GET['updated'] ?? ''): ?>
            <div class="success">✅ Данные пользователя обновлены!</div>
        <?php endif; ?>

        <!-- Статистика -->
        <div class="stats">
            <div class="stat-card">
                <h3>👥 Всего пользователей</h3>
                <p style="font-size: 2em; color: #ff6b6b;"><?= $usersCount ?></p>
            </div>
            <div class="stat-card">
                <h3>📊 Статистика по возрастам</h3>
                <p>До 20: <?= $ageStats['under_20'] ?></p>
                <p>20-30: <?= $ageStats['20_30'] ?></p>
                <p>30-40: <?= $ageStats['30_40'] ?></p>
                <p>40+: <?= $ageStats['over_40'] ?></p>
            </div>
            <div class="stat-card">
                <h3>⚡ Redis</h3>
                <p>Порт: 6379</p>
                <p>Web UI: 8081</p>
            </div>
        </div>

        <!-- Форма добавления пользователя -->
        <div class="card">
            <h2>➕ Добавить нового пользователя</h2>
            <form method="POST" class="user-form">
                <input type="hidden" name="action" value="add_user">
                <div class="form-group">
                    <label>Имя пользователя:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Возраст:</label>
                    <input type="number" name="age" min="1" max="120" required>
                </div>
                <div class="form-group">
                    <label>Город:</label>
                    <select name="city" required>
                        <option value="">Выберите город</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= $city ?>"><?= $city ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Добавить пользователя</button>
                </div>
            </form>
        </div>

        <!-- Список пользователей -->
        <div class="card">
            <h2>👥 Список пользователей</h2>
            <?php if (empty($allUsers)): ?>
                <p>Нет пользователей в базе данных</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Возраст</th>
                            <th>Город</th>
                            <th>Дата создания</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allUsers as $key => $user): ?>
                            <tr>
                                <td><?= substr($key, 5) ?></td>
                                <td><?= htmlspecialchars($user['name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['age'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['city'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['created_at'] ?? '') ?></td>
                                <td>
                                    <a href="?action=delete&id=<?= substr($key, 5) ?>" 
                                       class="btn btn-danger" 
                                       onclick="return confirm('Удалить пользователя?')">Удалить</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Поиск пользователей -->
        <div class="card">
            <h2>🔍 Поиск пользователей</h2>
            <form method="GET" class="user-form">
                <div class="form-group">
                    <label>Поиск по email:</label>
                    <input type="text" name="search_email" placeholder="Введите email">
                </div>
                <div class="form-group">
                    <label>Поиск по городу:</label>
                    <select name="search_city">
                        <option value="">Все города</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= $city ?>"><?= $city ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">Найти</button>
                    <a href="?" class="btn">Сбросить</a>
                </div>
            </form>

            <?php
            // Поиск пользователей
            if ($_GET['search_email'] ?? '') {
                $foundUser = $redisService->findUserByEmail($_GET['search_email']);
                if ($foundUser) {
                    echo "<div class='success'>Найден пользователь: " . htmlspecialchars($foundUser['name']) . "</div>";
                } else {
                    echo "<div style='background: #d63031; color: white; padding: 10px; border-radius: 4px;'>Пользователь не найден</div>";
                }
            }

            if ($_GET['search_city'] ?? '') {
                $cityUsers = $redisService->getUsersByCity($_GET['search_city']);
                if (!empty($cityUsers)) {
                    echo "<h3>Пользователи из города " . htmlspecialchars($_GET['search_city']) . ":</h3>";
                    echo "<ul>";
                    foreach ($cityUsers as $user) {
                        echo "<li>" . htmlspecialchars($user['name']) . " (" . htmlspecialchars($user['email']) . ")</li>";
                    }
                    echo "</ul>";
                }
            }
            ?>
        </div>

        <!-- Информация о Redis -->
        <div class="card">
            <h2>ℹ️ О Redis</h2>
            <ul>
                <li><strong>Тип:</strong> База данных в памяти (in-memory)</li>
                <li><strong>Использование:</strong> Кэширование, сессии, быстрый доступ к данным</li>
                <li><strong>Преимущества:</strong> Высокая скорость, простота использования</li>
                <li><strong>Web интерфейс:</strong> <a href="http://localhost:8081" target="_blank" style="color: #ff6b6b;">Redis Commander</a></li>
            </ul>
        </div>
    </div>
</body>
</html>