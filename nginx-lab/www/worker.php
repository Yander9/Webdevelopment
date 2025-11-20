<?php
require 'QueueManager.php';

echo "👷 Worker started (Kafka)...\n";
echo "📝 Listening for messages...\n";

$q = new QueueManager();

$q->consume(function($data) {
    echo "📥 Received: " . json_encode($data) . "\n";
    
    // Имитация обработки
    sleep(2);
    
    // Логируем в файл
    $logEntry = date('Y-m-d H:i:s') . " - " . json_encode($data) . PHP_EOL;
    file_put_contents('/var/www/html/processed_kafka.log', $logEntry, FILE_APPEND);
    
    echo "✅ Processed: {$data['name']}\n";
});