<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include '../db.php';

try {
    // เช็คว่า 'id' ถูกส่งมาหรือไม่
    if (!isset($_GET['id'])) {
        echo json_encode(array("error" => "Missing id parameter"));
        exit;
    }

    // เตรียมคำสั่ง SQL และ bind ค่า
    $stmt = $dbh->prepare("SELECT * FROM attractions WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();

    // ดึงข้อมูลแถวเดียว
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $attraction = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'coverimage' => $row['coverimage'],
            'detail' => $row['detail'],
            'latitude' => $row['latitude'],
            'longitude' => $row['longitude'],
        );

        echo json_encode($attraction, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("error" => "Attraction not found"));
    }

    $dbh = null;
} catch (PDOException $e) {
    echo json_encode(array("error" => "Database error: " . $e->getMessage()));
    die();
}
?>
