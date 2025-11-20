<?php
require 'QueueManager.php';

if ($_POST) {
    $q = new QueueManager();
    $data = [
        'name' => $_POST['name'] ?? 'Без имени',
        'email' => $_POST['email'] ?? '',
        'timestamp' => date('Y-m-d H:i:s'),
        'type' => 'user_registration'
    ];
    
    $q->publish($data);
    
    echo "<h2>✅ Сообщение отправлено в Kafka!</h2>";
    echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";
    echo '<a href="/">Назад</a>';
}