<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Studio Musik Jaya | BOOKING</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{font-family:'Montserrat',sans-serif;}

body{
    background:#f4f6f9;
}

/* Header */
.header{
    background:linear-gradient(135deg,#1e3c72,#2a5298);
    color:#fff;
    padding:35px;
    border-radius:16px;
    margin-bottom:30px;
    box-shadow:0 15px 40px rgba(0,0,0,.25);
}

/* Card */
.card-custom{
    border:none;
    border-radius:16px;
    box-shadow:0 12px 35px rgba(0,0,0,.1);
}

/* Form */
.form-control,select{
    border-radius:10px;
    padding:10px 14px;
}

/* Buttons */
.btn-primary{
    background:#2563eb;
    border:none;
    font-weight:600;
}
.btn-success{
    background:#16a34a;
    border:none;
}
.btn-secondary{
    background:#6b7280;
    border:none;
}
.btn-danger{
    background:#dc2626;
    border:none;
}

/* Table */
.table{
    border-radius:14px;
    overflow:hidden;
}
thead{
    background:#2563eb;
    color:#fff;
}
tbody tr:hover{
    background:#eef2ff;
    transition:.25s;
}

/* Progress */
.progress{
    height:8px;
    border-radius:10px;
    background:#e5e7eb;
}
.progress-bar{
    border-radius:10px;
    transition:width .8s ease;
}

.countdown{
    font-size:13px;
    font-weight:500;
}
</style>
</head>

<body>

<div class="container py-5">

<!-- HEADER -->
<div class="header">
    <h3 class="fw-bold mb-1">🎵 Studio Musik Jaya</h3>
    <p class="mb-0">Welcome to Studio Musik Modern & Real-Time</p>
</div>

<!-- FORM -->
<div class="card card-custom mb-4">
<div class="card-body">
<form action="proses.php" method="POST" class="row g-3">
    <div class="col-md-2">
        <input type="text" name="nama" class="form-control" placeholder="Nama Penyewa" required>
    </div>
    <div class="col-md-3">
        <select name="id_studio" id="studio" class="form-control" required>
            <option value="">Pilih Studio</option>
            <?php
            $s=mysqli_query($conn,"SELECT * FROM studio");
            while($d=mysqli_fetch_assoc($s)){
            ?>
            <option value="<?= $d['id_studio']; ?>" data-harga="<?= $d['harga']; ?>">
                <?= $d['nama_studio']; ?> (Rp <?= number_format($d['harga'],0,',','.'); ?>/jam)
            </option>
            <?php } ?>
        </select>
    </div>
    <div class="col-md-2">
        <input type="date" name="tanggal" class="form-control" required>
    </div>
    <div class="col-md-1">
        <input type="number" id="durasi" name="durasi" class="form-control" placeholder="Jam" required>
    </div>
    <div class="col-md-2">
        <input type="text" id="total_harga" class="form-control" placeholder="Total Harga" readonly>
    </div>
    <div class="col-md-2">
        <button type="submit" name="pesan" class="btn btn-primary w-100">
            Booking
        </button>
    </div>
</form>
</div>
</div>

<!-- TABLE -->
<div class="card card-custom">
<div class="card-body table-responsive">

<table class="table align-middle">
<thead>
<tr>
<th>Nama</th>
<th>Studio</th>
<th>Status</th>
<th>Waktu</th>
<th>Total</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>

<?php
$q=mysqli_query($conn,"
    SELECT reservasi.*,studio.nama_studio
    FROM reservasi
    JOIN studio ON reservasi.id_studio=studio.id_studio
");
while($r=mysqli_fetch_assoc($q)){
    $selesai=strtotime($r['waktu_selesai']);
    $mulai=$selesai-($r['durasi']*3600);
?>
<tr>
<td><?= $r['nama_penyewa']; ?></td>
<td><?= $r['nama_studio']; ?></td>

<td>
<?php if($r['status']=='aktif'){ ?>
<a href="proses.php?status=<?= $r['id_reservasi']; ?>&nilai=nonaktif"
   class="btn btn-success btn-sm">Aktif</a>
<?php }else{ ?>
<a href="proses.php?status=<?= $r['id_reservasi']; ?>&nilai=aktif"
   class="btn btn-secondary btn-sm">Nonaktif</a>
<?php } ?>
</td>

<td style="min-width:220px">
<?php if($selesai>time()){ ?>
<small class="countdown"
       data-mulai="<?= $mulai; ?>"
       data-selesai="<?= $selesai; ?>">Loading...</small>
<div class="progress mt-1">
    <div class="progress-bar bg-success progress-waktu"
         data-mulai="<?= $mulai; ?>"
         data-selesai="<?= $selesai; ?>"
         style="width:0%"></div>
</div>
<?php }else{ ?>
<span class="text-danger fw-semibold">Selesai</span>
<?php } ?>
</td>

<td>Rp <?= number_format($r['total_harga'],0,',','.'); ?></td>

<td>
<a href="proses.php?hapus=<?= $r['id_reservasi']; ?>"
   class="btn btn-danger btn-sm">Hapus</a>
</td>
</tr>
<?php } ?>

</tbody>
</table>

</div>
</div>

</div>

<!-- JS HITUNG HARGA -->
<script>
const studio=document.getElementById('studio');
const durasi=document.getElementById('durasi');
const total=document.getElementById('total_harga');

function hitung(){
    const harga=studio.options[studio.selectedIndex]?.dataset.harga;
    const jam=durasi.value;
    total.value=(harga&&jam)?"Rp "+(harga*jam).toLocaleString("id-ID"):"";
}
studio.addEventListener("change",hitung);
durasi.addEventListener("input",hitung);
</script>

<!-- JS COUNTDOWN -->
<script>
function updateWaktu(){
document.querySelectorAll('.countdown').forEach(el=>{
    const mulai=parseInt(el.dataset.mulai);
    const selesai=parseInt(el.dataset.selesai);
    const now=Math.floor(Date.now()/1000);

    const total=selesai-mulai;
    const sisa=selesai-now;

    if(sisa<=0){
        el.innerHTML="<span class='text-danger'>Selesai</span>";
        el.nextElementSibling?.remove();
        return;
    }

    const h=Math.floor(sisa/3600);
    const m=Math.floor((sisa%3600)/60);
    const s=sisa%60;
    el.innerHTML=`${h}j ${m}m ${s}d`;

    let persen=((now-mulai)/total)*100;
    persen=Math.max(0,Math.min(100,persen));

    const bar=el.nextElementSibling.querySelector('.progress-waktu');
    bar.style.width=persen+"%";
});
}
setInterval(updateWaktu,1000);
updateWaktu();
</script>

</body>
</html>
