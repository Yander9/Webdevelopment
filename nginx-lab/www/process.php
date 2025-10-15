<?php
session_start();

// Получаем данные
$name = htmlspecialchars(trim($_POST['name'] ?? ''));
$email = htmlspecialchars(trim($_POST['email'] ?? ''));
$age = htmlspecialchars(trim($_POST['age'] ?? ''));
$faculty = htmlspecialchars($_POST['faculty'] ?? '');
$educationForm = htmlspecialchars($_POST['educationForm'] ?? '');

// Валидация
$errors = [];

if (empty($name)) {
    $errors[] = "Имя не может быть пустым";
}

if (empty($email)) {
    $errors[] = "Email не может быть пустым";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Некорректный email";
}

if (empty($age) || $age < 16 || $age > 60) {
    $errors[] = "Возраст должен быть от 16 до 60 лет";
}

if (empty($faculty)) {
    $errors[] = "Выберите факультет";
}

if (empty($educationForm)) {
    $errors[] = "Выберите форму обучения";
}

// Если есть ошибки - сохраняем в сессию и перенаправляем
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

// Сохраняем в сессию
$_SESSION['username'] = $name;
$_SESSION['email'] = $email;
$_SESSION['age'] = $age;
$_SESSION['faculty'] = $faculty;
$_SESSION['education_form'] = $educationForm;

// Сохраняем в файл
$line = $name . ";" . $email . ";" . $age . ";" . $faculty . ";" . $educationForm . ";" . date('Y-m-d H:i:s') . "\n";
file_put_contents("data.txt", $line, FILE_APPEND);

// Перенаправляем на главную
header("Location: index.php");
exit();
?>