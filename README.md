# Лабораторная работа №1: Nginx + Docker

## 👩‍💻 Автор
ФИО: Афонькин Георгий Константинович
Группа: 3МО-3

---

## 📌 Описание задания
Создать веб-сервер в Docker с использованием Nginx и подключить HTML-страницу.  
Результат доступен по адресу [http://localhost:3000](http://localhost:3000).

---

## ⚙️ Как запустить проект

1. Клонировать репозиторий:
   ```bash
   git clone <https://github.com/Yander9/Webdevelopment>
   cd nginx-lab
Запустить контейнеры:
```bash
docker-compose up -d --build
```
Открыть в браузере:
```http://localhost:3000```
📂 Содержимое проекта

```docker-compose.yml``` — описание сервиса Nginx

```code/index.html``` — главная HTML-страница

```code/about.html``` — вторая HTML-страница

```screenshotes/``` — все скриншоты

📸 Скриншоты работы

✅ Результат
Сервер в Docker успешно запущен, Nginx отдаёт мою HTML-страницу.