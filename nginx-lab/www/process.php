<?php
require_once 'db.php';
require_once 'Student.php';
require_once 'ApiClient.php';

session_start();

// Создаем объект студента
$student = new Student($pdo);
$student->createTable();

// Получаем данные
$name = htmlspecialchars(trim($_POST['name'] ?? ''));
$email = htmlspecialchars(trim($_POST['email'] ?? ''));
$age = intval($_POST['age'] ?? 0);
$region_id = $_POST['region'] ?? '';
$city_id = $_POST['city'] ?? '';
$faculty = htmlspecialchars($_POST['faculty'] ?? '');
$education_form = htmlspecialchars($_POST['educationForm'] ?? '');
$agree_rules = isset($_POST['agree']) ? 1 : 0;

// Валидация
$errors = [];

if (empty($name)) $errors[] = "Имя не может быть пустым";
if (empty($email)) $errors[] = "Email не может быть пустым";
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Некорректный email";
if (empty($age) || $age < 16 || $age > 60) $errors[] = "Возраст должен быть от 16 до 60 лет";
if (empty($region_id)) $errors[] = "Выберите регион";
if (empty($city_id)) $errors[] = "Выберите город";
if (empty($faculty)) $errors[] = "Выберите факультет";
if (empty($education_form)) $errors[] = "Выберите форму обучения";
if (!$agree_rules) $errors[] = "Необходимо согласие с правилами университета";

// Если есть ошибки
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

// Сохраняем в базу данных
$result = $student->add($name, $email, $age, $regionName, $cityName, $faculty, $education_form, $agree_rules);

if ($result) {
    // Сохраняем в сессию для отображения
    $_SESSION['username'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['age'] = $age;
    $_SESSION['region'] = $regionName;
    $_SESSION['city'] = $cityName;
    $_SESSION['faculty'] = $faculty;
    $_SESSION['education_form'] = $education_form;
    $_SESSION['agree'] = $agree_rules ? 'Да' : 'Нет';
    
    // Получаем общие данные API для отображения
    $apiData = $api->getRussianRegions();
    $_SESSION['api_data'] = $apiData;
    
    $_SESSION['success'] = "Данные успешно сохранены в MySQL!";
} else {
    $_SESSION['errors'] = ["Ошибка сохранения в базу данных"];
}

// Устанавливаем куку
setcookie("last_submission", date('Y-m-d H:i:s'), time() + 3600, "/");

// Перенаправляем на главную
header("Location: index.php");
exit();
?>