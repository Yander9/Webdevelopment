<!DOCTYPE html>
<html>
<head>
    <title>Lab7 - Kafka Queue</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .container { max-width: 800px; margin: 0 auto; }
        form { margin: 20px 0; padding: 20px; border: 1px solid #ddd; }
        input, button { padding: 10px; margin: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔮 Lab7 - Асинхронная обработка через Kafka</h1>
        
        <form method="POST" action="send.php">
            <h3>📤 Отправить сообщение в Kafka</h3>
            <input type="text" name="name" placeholder="Ваше имя" required>
            <input type="email" name="email" placeholder="Email">
            <button type="submit">Отправить в очередь</button>
        </form>

        <div>
            <h3>📊 Статус системы</h3>
            <p>Kafka: localhost:9092</p>
            <p>Zookeeper: localhost:2181</p>
            <p>Web: http://localhost:8080</p>
        </div>

        <div>
            <h3>🎯 Запуск воркера</h3>
            <pre>docker compose exec php php worker.php</pre>
        </div>
    </div>
</body>
</html>