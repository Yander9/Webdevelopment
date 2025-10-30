<?php
class Student {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Создание таблицы
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            age INT NOT NULL,
            region VARCHAR(100) NOT NULL,
            city VARCHAR(100) NOT NULL,
            faculty VARCHAR(100) NOT NULL,
            education_form VARCHAR(50) NOT NULL,
            agree_rules TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $this->pdo->exec($sql);
    }

    // Добавление студента
    public function add($name, $email, $age, $region, $city, $faculty, $education_form, $agree_rules) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO students (name, email, age, region, city, faculty, education_form, agree_rules) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$name, $email, $age, $region, $city, $faculty, $education_form, $agree_rules]);
    }

    // Получение всех студентов
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM students ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    // Обновление студента
    public function update($id, $name, $email) {
        $stmt = $this->pdo->prepare("UPDATE students SET name=?, email=? WHERE id=?");
        $stmt->execute([$name, $email, $id]);
    }

    // Удаление студента
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM students WHERE id=?");
        $stmt->execute([$id]);
    }

    // Получение количества студентов
    public function getCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM students");
        return $stmt->fetch()['count'];
    }
}
?>