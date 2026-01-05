<?php
header("Content-Type: application/json");

// API KEY
$API_KEY = "STUDIO_API_KEY_2026";

// ambil api key dari header (aman untuk Apache)
$clientKey = null;

// cara 1: getallheaders
$headers = getallheaders();
if (isset($headers['X-API-KEY'])) {
    $clientKey = $headers['X-API-KEY'];
} elseif (isset($headers['X-Api-Key'])) {
    $clientKey = $headers['X-Api-Key'];
}

// cara 2: fallback server variable
if (!$clientKey && isset($_SERVER['HTTP_X_API_KEY'])) {
    $clientKey = $_SERVER['HTTP_X_API_KEY'];
}

if ($clientKey !== $API_KEY) {
    http_response_code(401);
    echo json_encode([
        "status" => false,
        "message" => "API Key tidak valid"
    ]);
    exit;
}


// koneksi database
$conn = mysqli_connect("localhost", "root", "", "db_studio");
if (!$conn) {
    echo json_encode([
        "status" => false,
        "message" => "Koneksi database gagal"
    ]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getmethod($conn);
        break;

    case 'POST':
        postmethod($conn);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        putmethod($conn, $data);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        deletemethod($conn, $data);
        break;

    default:
        echo json_encode([
            "status" => false,
            "message" => "Method tidak diizinkan"
        ]);
}

// ================== GET ==================
function getmethod($conn)
{
    $query = "SELECT * FROM reservasi order by nama_penyewa";
    $result = mysqli_query($conn, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode([
        "status" => true,
        "data" => $data
    ]);
}


// ================== POST ==================
function postmethod($conn)
{
    if (
        !isset(
            $_POST['nama_penyewa'],
            $_POST['id_studio'],
            $_POST['tanggal'],
            $_POST['jam_mulai'],
            $_POST['durasi'],
            $_POST['total_harga']
        )
    ) {
        echo json_encode([
            "status" => false,
            "message" => "Parameter POST tidak lengkap"
        ]);
        return;
    }

    $nama = $_POST['nama_penyewa'];
    $studio = $_POST['id_studio'];
    $tanggal = $_POST['tanggal'];
    $jam = $_POST['jam_mulai'];
    $durasi = $_POST['durasi'];
    $harga = $_POST['total_harga'];

    $query = "INSERT INTO reservasi
        (nama_penyewa, id_studio, tanggal, jam_mulai, durasi, total_harga, status)
        VALUES
        ('$nama', '$studio', '$tanggal', '$jam', '$durasi', '$harga', 'aktif')";

    if (mysqli_query($conn, $query)) {
        echo json_encode([
            "status" => true,
            "message" => "Reservasi berhasil ditambahkan"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => mysqli_error($conn)
        ]);
    }
}


// ================== PUT ==================
function putmethod($conn, $data)
{
    $id = $data['id_reservasi'];
    $nama = $data['nama_penyewa'];
    $durasi = $data['durasi'];
    $harga = $data['total_harga'];
    $status = $data['status'];

    $query = "UPDATE reservasi SET
        nama_penyewa='$nama',
        durasi='$durasi',
        total_harga='$harga',
        status='$status'
        WHERE id_reservasi='$id'";

    if (mysqli_query($conn, $query)) {
        echo json_encode([
            "status" => true,
            "message" => "Reservasi berhasil diupdate"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Gagal update reservasi"
        ]);
    }
}


// ================== DELETE ==================
function deletemethod($conn, $data)
{
    $id = $data['id_reservasi'];

    $query = "DELETE FROM reservasi WHERE id_reservasi='$id'";

    if (mysqli_query($conn, $query)) {
        echo json_encode([
            "status" => true,
            "message" => "Reservasi berhasil dihapus"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Gagal hapus reservasi"
        ]);
    }
}
