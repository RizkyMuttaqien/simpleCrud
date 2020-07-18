<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}


require 'function.php';

//pagenation
//konfigurasi
$junlahDataPerhalaman = 1;
$jumlahData = count(query("SELECT * FROM mahasiswa"));
$jumlahHalaman = ceil($jumlahData/$junlahDataPerhalaman);
$halamanAktif = (isset($_GET["halaman"])) ? $_GET["halaman"] :1;
$awalData = ($junlahDataPerhalaman * $halamanAktif)- $junlahDataPerhalaman;

$mahasiswa = query("SELECT * FROM mahasiswa LIMIT $awalData,$junlahDataPerhalaman");
//tombol cari ditekan
if(isset($_POST["cari"])){
    $mahasiswa = cari($_POST["keyword"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>
</head>
<body>
    <a href="logout.php">Logout</a>
    <h1>Daftar Mahasiswa</h1>
    <a href="tambah.php">Tambah Data</a><br><br>
<form action="" method="post">
    <input type="text" name="keyword" size="35" autofocus placeholder="Masukan Keyword Pencarian..." autocomplete="off">
    <button type="submit" name="cari">Cari</button>
</form>
<?php if($halamanAktif>1): ?>
<a href="?halaman=<?= $halamanAktif-1; ?>">&laquo;</a>
<?php endif;?>
<?php for($i = 1; $i<=$jumlahHalaman; $i++):?>
    <?php if($i == $halamanAktif):?>
        <a href="?halaman=<?= $i; ?>" style="font-weight:bold;color:red;"><?= $i; ?></a>
    <?php else:?>
        <a href="?halaman=<?= $i; ?>"><?= $i; ?></a>
    <?php endif; ?>
<?php endfor;?>

<?php if($halamanAktif<$jumlahHalaman): ?>
<a href="?halaman=<?= $halamanAktif+1; ?>">&raquo;</a>
<?php endif;?>

<br>
<table border="1" cellpadding="10" cellspacing="0">
<thead>
    <tr>
        <th>No.</th>
        <th>Aksi</th>
        <th>Gambar</th>
        <th>Nrp</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Jurusan</th>
    </tr>
</thead>
<tbody>
<?php $i =1;  foreach($mahasiswa as $row): ?>
    <tr>
        <td><?= $i++ ?></td>
        <td>
            <a href="ubah.php?id=<?= $row["id"];?>">Edit</a> ||
            <a href="hapus.php?id=<?= $row["id"];?>" onclick="return confirm('yakin akan menghapus?');">Delete</a>
        </td>
        <td><img src="img/<?= $row["gambar"]; ?>" width="100" alt=""></td>
        <td><?= $row["nrp"];?></td>
        <td><?= $row["nama"];?></td>
        <td><?= $row["email"];?></td>
        <td><?= $row["jurusan"];?></td>
    </tr>
<?php endforeach;?>
</tbody>
</table>
<script src="js/script.js"></script>
</body>
</html>