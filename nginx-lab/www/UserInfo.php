<?php
class UserInfo {
    public static function getInfo(): array {
        return [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'time' => date('Y-m-d H:i:s'),
            'last_submission' => $_COOKIE['last_submission'] ?? 'Никогда'
        ];
    }
}