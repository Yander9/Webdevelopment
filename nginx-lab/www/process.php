<?php
require_once 'ApiClient.php';
session_start();

// Получаем данные
$name = htmlspecialchars(trim($_POST['name'] ?? ''));
$email = htmlspecialchars(trim($_POST['email'] ?? ''));
$age = htmlspecialchars(trim($_POST['age'] ?? ''));
$region_id = htmlspecialchars($_POST['region'] ?? '');
$city_id = htmlspecialchars($_POST['city'] ?? '');
$faculty = htmlspecialchars($_POST['faculty'] ?? '');
$educationForm = htmlspecialchars($_POST['educationForm'] ?? '');
$agree = isset($_POST['agree']) ? 'Да' : 'Нет';

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

if (empty($region_id)) {
    $errors[] = "Выберите регион";
}

if (empty($city_id)) {
    $errors[] = "Выберите город";
}

if (empty($faculty)) {
    $errors[] = "Выберите факультет";
}

if (empty($educationForm)) {
    $errors[] = "Выберите форму обучения";
}

if ($agree === 'Нет') {
    $errors[] = "Необходимо согласие с правилами университета";
}

// Если есть ошибки - сохраняем в сессию и перенаправляем
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

// Получаем названия регионов и городов из API
$api = new ApiClient();
$regionData = $api->getRegionById($region_id);
$cityData = $api->getRegionById($city_id);

$regionName = $regionData['name'] ?? 'Неизвестно';
$cityName = $cityData['name'] ?? 'Неизвестно';

// Сохраняем в сессию
$_SESSION['username'] = $name;
$_SESSION['email'] = $email;
$_SESSION['age'] = $age;
$_SESSION['region'] = $regionName;
$_SESSION['city'] = $cityName;
$_SESSION['faculty'] = $faculty;
$_SESSION['education_form'] = $educationForm;
$_SESSION['agree'] = $agree;

// Получаем общие данные API для отображения
$apiData = $api->getRussianRegions();
$_SESSION['api_data'] = $apiData;

// Сохраняем в файл
$line = $name . ";" . $email . ";" . $age . ";" . $regionName . ";" . $cityName . ";" . $faculty . ";" . $educationForm . ";" . $agree . ";" . date('Y-m-d H:i:s') . "\n";
file_put_contents("data.txt", $line, FILE_APPEND);

// Устанавливаем куку
setcookie("last_submission", date('Y-m-d H:i:s'), time() + 3600, "/");

// Перенаправляем на главную
header("Location: index.php");
exit();
?>