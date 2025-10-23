<?php
class ApiClient {
    
    public function request(string $url): array {
        try {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => 'User-Agent: StudentRegistrationApp/1.0',
                    'timeout' => 10
                ]
            ]);
            
            $response = file_get_contents($url, false, $context);
            
            if ($response === false) {
                return ['error' => 'Не удалось получить данные от API'];
            }
            
            $data = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['error' => 'Ошибка декодирования JSON: ' . json_last_error_msg()];
            }
            
            return $data;
            
        } catch (\Exception $e) {
            return ['error' => 'Произошла ошибка: ' . $e->getMessage()];
        }
    }

    /**
     * Получить список регионов России
     */
    public function getRussianRegions(): array {
        return $this->request('https://api.hh.ru/areas/113');
    }

    /**
     * Получить регион по ID
     */
    public function getRegionById(int $regionId): array {
        return $this->request("https://api.hh.ru/areas/{$regionId}");
    }
}