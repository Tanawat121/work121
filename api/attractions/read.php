<?php
// ตั้งค่าให้ API รองรับการเรียกใช้งานจากทุกโดเมน (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include '../db.php';

try {
    $attractions = array(); // สร้างอาร์เรย์เปล่าสำหรับเก็บข้อมูล

    $stmt = $dbh->query('SELECT * FROM attractions'); // ดึงข้อมูลจากฐานข้อมูล
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $attraction = array(
            'id' => $row['id'],
            'title' => $row['name'], // เปลี่ยน key ให้ตรงกับ JavaScript
            'image' => $row['coverimage'],
            'description' => $row['detail'],
        );
        array_push($attractions, $attraction); // เพิ่มรายการลงใน array
    }

    echo json_encode($attractions, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); // แสดงผล JSON

    $dbh = null; // ปิดการเชื่อมต่อฐานข้อมูล
} catch (PDOException $e) {
    echo json_encode(array("error" => "Database error: " . $e->getMessage()));
    die();
}
?>
