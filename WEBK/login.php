<?php
$host = 'localhost';
$dbname = 'user_auth';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ищем пользователя по имени
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        echo "Login successful!";
        // Здесь можно начать сессию и перенаправить пользователя
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: http://localhost/WEBK/profil.html"); // Перенаправление на защищенную страницу
    }  if ($username === 'admin' && $password === 'admin') {
        // Перенаправляем на другую страницу
        header("Location: LogReg/phone_catalog/index.html");
        exit(); // Останавливаем выполнение скрипта
    } 
    else {
        echo "Invalid username or password!";
    }
}
?>