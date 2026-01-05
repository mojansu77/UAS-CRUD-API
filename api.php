<?php
header("Content-Type: application/json");
include 'koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM studio");
$data = mysqli_fetch_all($query, MYSQLI_ASSOC);

echo json_encode([
    "status" => "success",
    "message" => "Data Studio Musik",
    "data" => $data
]);
?>