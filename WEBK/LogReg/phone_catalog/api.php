<?php
header("Access-Control-Allow-Origin: *"); // Разрешить запросы с любого источника
header("Content-Type: application/json"); // Указываем, что возвращаем JSON

$host = 'localhost';
$dbname = 'phone_catalog';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["error" => "Connection failed: " . $e->getMessage()]);
    exit;
}

// Обработка GET-запроса для получения списка телефонов
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM phones";
    $stmt = $conn->query($sql);
    $phones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($phones);
}

// Обработка POST-запроса для добавления нового телефона
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $name = $data['name'];
    $brand = $data['brand'];
    $screen_size = $data['screen_size'];
    $ram = $data['ram'];
    $storage = $data['storage'];
    $battery_capacity = $data['battery_capacity'];
    $price = $data['price'];

    $sql = "INSERT INTO phones (name, brand, screen_size, ram, storage, battery_capacity, price) 
            VALUES (:name, :brand, :screen_size, :ram, :storage, :battery_capacity, :price)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':brand' => $brand,
        ':screen_size' => $screen_size,
        ':ram' => $ram,
        ':storage' => $storage,
        ':battery_capacity' => $battery_capacity,
        ':price' => $price
    ]);

    echo json_encode(["message" => "Phone added successfully!"]);
}
?>