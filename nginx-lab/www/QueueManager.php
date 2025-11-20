<?php
class QueueManager {
    private $topic = 'lab7_topic';
    private $broker = 'kafka:9092';

    public function publish($data) {
        try {
            $conf = new RdKafka\Conf();
            $conf->set('client.id', 'lab7_producer');
            $conf->set('metadata.broker.list', $this->broker);
            $conf->set('message.timeout.ms', '5000');
            
            $producer = new RdKafka\Producer($conf);
            
            // Ждем пока Kafka станет доступна
            $this->waitForKafka($producer);
            
            $topic = $producer->newTopic($this->topic);
            $topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($data));
            
            // Ожидаем отправки
            for ($i = 0; $i < 10; $i++) {
                $result = $producer->poll(100);
                if ($result === 0) {
                    break;
                }
            }
            
            $producer->flush(5000);
            
            echo "✅ Сообщение отправлено в Kafka: " . json_encode($data) . "\n";
            return true;
        } catch (Exception $e) {
            echo "❌ Ошибка Kafka при отправке: " . $e->getMessage() . "\n";
            $this->saveToFile($data, 'publish');
            return false;
        }
    }

    public function consume(callable $callback) {
        echo "👷 Worker запущен (Kafka)...\n";
        
        try {
            $conf = new RdKafka\Conf();
            $conf->set('group.id', 'lab7_group');
            $conf->set('metadata.broker.list', $this->broker);
            $conf->set('auto.offset.reset', 'earliest');
            $conf->set('session.timeout.ms', '10000');
            
            $consumer = new RdKafka\KafkaConsumer($conf);
            
            echo "📡 Подписываемся на топик: {$this->topic}\n";
            $consumer->subscribe([$this->topic]);
            
            echo "⏳ Ожидание сообщений...\n";
            
            $retryCount = 0;
            $maxRetries = 10;
            
            while (true) {
                try {
                    $message = $consumer->consume(10000);
                    
                    if ($message->err === RD_KAFKA_RESP_ERR_NO_ERROR) {
                        $data = json_decode($message->payload, true);
                        if ($data) {
                            echo "📥 Получено сообщение: " . json_encode($data) . "\n";
                            $callback($data);
                            echo "✅ Обработано\n";
                            $retryCount = 0; // Сброс счетчика при успехе
                        }
                    } elseif ($message->err === RD_KAFKA_RESP_ERR__TIMED_OUT) {
                        // Таймаут - нормально
                        continue;
                    } else {
                        echo "⚠️ Ошибка Kafka [{$message->err}]: {$message->errstr()}\n";
                        
                        // Если топик не найден, ждем и переподписываемся
                        if ($message->err === 3) { // Unknown topic or partition
                            $retryCount++;
                            if ($retryCount < $maxRetries) {
                                echo "🔄 Попытка переподписки {$retryCount}/{$maxRetries}...\n";
                                sleep(3);
                                $consumer->subscribe([$this->topic]);
                            } else {
                                throw new Exception("Не удалось подключиться к топику после {$maxRetries} попыток");
                            }
                        }
                    }
                } catch (RdKafka\Exception $e) {
                    echo "❌ Ошибка RdKafka: " . $e->getMessage() . "\n";
                    sleep(2);
                }
            }
        } catch (Exception $e) {
            echo "❌ Критическая ошибка Kafka: " . $e->getMessage() . "\n";
            echo "🔄 Переключаемся на файловую систему...\n";
            $this->consumeFromFile($callback);
        }
    }

    private function waitForKafka($producer, $maxRetries = 10) {
        for ($i = 0; $i < $maxRetries; $i++) {
            try {
                $metadata = $producer->getMetadata(true, null, 5000);
                echo "✅ Kafka доступна\n";
                return;
            } catch (Exception $e) {
                echo "⏳ Ожидание Kafka... ({$i}/{$maxRetries})\n";
                sleep(2);
            }
        }
        throw new Exception("Kafka недоступна после {$maxRetries} попыток");
    }

    private function saveToFile($data, $type) {
        $logEntry = date('Y-m-d H:i:s') . " - $type: " . json_encode($data) . PHP_EOL;
        file_put_contents('queue_fallback.log', $logEntry, FILE_APPEND);
        echo "📁 Сообщение сохранено в файл: " . json_encode($data) . "\n";
    }

    private function consumeFromFile(callable $callback) {
        echo "📁 Чтение из файловой очереди...\n";
        
        while (true) {
            if (file_exists('queue_fallback.log')) {
                $lines = file('queue_fallback.log', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                
                foreach ($lines as $i => $line) {
                    if (strpos($line, 'publish:') !== false) {
                        $json = substr($line, strpos($line, 'publish:') + 9);
                        $data = json_decode(trim($json), true);
                        
                        if ($data) {
                            echo "📥 Получено из файла: " . json_encode($data) . "\n";
                            $callback($data);
                            echo "✅ Обработано\n";
                            
                            unset($lines[$i]);
                            file_put_contents('queue_fallback.log', implode(PHP_EOL, array_values($lines)) . PHP_EOL);
                            break;
                        }
                    }
                }
            }
            sleep(3);
        }
    }
}