<?php
include 'koneksi.php';

/* SIMPAN DATA */
if(isset($_POST['pesan'])){
    $nama      = $_POST['nama'];
    $id_studio = $_POST['id_studio'];
    $tanggal   = $_POST['tanggal'];
    $durasi    = $_POST['durasi'];

    $q = mysqli_query($conn,"SELECT harga FROM studio WHERE id_studio='$id_studio'");
    $s = mysqli_fetch_assoc($q);

    $total = $s['harga'] * $durasi;

    $mulai   = "$tanggal ".date("H:i:s");
    $selesai = date("Y-m-d H:i:s", strtotime("+$durasi hours", strtotime($mulai)));

    mysqli_query($conn,"INSERT INTO reservasi
    (nama_penyewa,id_studio,tanggal,durasi,waktu_selesai,status,total_harga)
    VALUES
    ('$nama','$id_studio','$tanggal','$durasi','$selesai','aktif','$total')");

    header("Location:index.php");
    exit;
}

/* UPDATE STATUS */
if(isset($_GET['status'])){
    mysqli_query($conn,"
        UPDATE reservasi 
        SET status='$_GET[nilai]' 
        WHERE id_reservasi='$_GET[status]'
    ");
    header("Location:index.php");
    exit;
}

/* HAPUS */
if(isset($_GET['hapus'])){
    mysqli_query($conn,"DELETE FROM reservasi WHERE id_reservasi='$_GET[hapus]'");
    header("Location:index.php");
    exit;
}
