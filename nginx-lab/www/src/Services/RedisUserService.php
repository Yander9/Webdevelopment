<?php

namespace App\Services;

use Predis\Client;

class RedisUserService
{
    private $redis;

    public function __construct()
    {
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host'   => 'redis',
            'port'   => 6379,
        ]);
    }

    // Добавление пользователя
    public function addUser($id, $userData)
    {
        $key = "user:$id";
        return $this->redis->hmset($key, $userData);
    }

    // Получение пользователя по ID
    public function getUser($id)
    {
        $key = "user:$id";
        return $this->redis->hgetall($key);
    }

    // Обновление данных пользователя
    public function updateUser($id, $field, $value)
    {
        $key = "user:$id";
        return $this->redis->hset($key, $field, $value);
    }

    // Удаление пользователя
    public function deleteUser($id)
    {
        $key = "user:$id";
        return $this->redis->del([$key]);
    }

    // Получение всех пользователей
    public function getAllUsers()
    {
        $keys = $this->redis->keys('user:*');
        $users = [];
        
        foreach ($keys as $key) {
            $users[$key] = $this->redis->hgetall($key);
        }
        
        return $users;
    }

    // Поиск пользователей по email
    public function findUserByEmail($email)
    {
        $keys = $this->redis->keys('user:*');
        
        foreach ($keys as $key) {
            $userEmail = $this->redis->hget($key, 'email');
            if ($userEmail === $email) {
                return $this->redis->hgetall($key);
            }
        }
        
        return null;
    }

    // Установка TTL для пользователя (автоудаление через время)
    public function setUserTTL($id, $seconds)
    {
        $key = "user:$id";
        return $this->redis->expire($key, $seconds);
    }

    // Получение количества пользователей
    public function getUsersCount()
    {
        $keys = $this->redis->keys('user:*');
        return count($keys);
    }

    // Получение пользователей по городу
    public function getUsersByCity($city)
    {
        $keys = $this->redis->keys('user:*');
        $users = [];
        
        foreach ($keys as $key) {
            $userCity = $this->redis->hget($key, 'city');
            if ($userCity === $city) {
                $users[$key] = $this->redis->hgetall($key);
            }
        }
        
        return $users;
    }

    // Получение статистики по возрастам
    public function getAgeStatistics()
    {
        $keys = $this->redis->keys('user:*');
        $stats = [
            'total' => 0,
            'under_20' => 0,
            '20_30' => 0,
            '30_40' => 0,
            'over_40' => 0
        ];
        
        foreach ($keys as $key) {
            $age = (int)$this->redis->hget($key, 'age');
            $stats['total']++;
            
            if ($age < 20) $stats['under_20']++;
            elseif ($age <= 30) $stats['20_30']++;
            elseif ($age <= 40) $stats['30_40']++;
            else $stats['over_40']++;
        }
        
        return $stats;
    }
}