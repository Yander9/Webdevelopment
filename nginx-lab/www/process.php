<?php
// process.php - обработка данных формы с использованием сессий
session_start();

// Получаем и очищаем данные из формы
$name = htmlspecialchars(trim($_POST['name'] ?? ''));
$age = htmlspecialchars(trim($_POST['age'] ?? ''));
$faculty = htmlspecialchars($_POST['faculty'] ?? '');
$educationForm = htmlspecialchars($_POST['educationForm'] ?? '');
$agree = isset($_POST['agree']) ? 'Да' : 'Нет';

// Сохраняем данные в сессию
$_SESSION['form_data'] = [
    'name' => $name,
    'age' => $age,
    'faculty' => $faculty,
    'educationForm' => $educationForm,
    'agree' => $agree,
    'timestamp' => date('Y-m-d H:i:s')
];

// Сохраняем также отдельные переменные для удобства
$_SESSION['username'] = $name;
$_SESSION['user_age'] = $age;
$_SESSION['user_faculty'] = $faculty;
$_SESSION['education_form'] = $educationForm;
$_SESSION['agreement'] = $agree;

// Перенаправляем обратно на главную страницу
header("Location: form.html");
exit();