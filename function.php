<?php

//koneksi Database
$conn = mysqli_connect("localhost", "root", "","db_kampus");


//cara ambil data
//mysqli_fetch_row($result) //mengembalikan array numerik
//mysqli_fetch_assoc($result); //mengembalikan array asosiatif
//mysqli_fetch_array($result); //mengembalikan array numerik dan asosiatif
//mysqli_fetch_object($result); //mengembalikan object

//query dari index
function query($query){
    global $conn;
    $result = mysqli_query($conn,$query);
        if(!$result){
            //jika query tidak ada / error
            echo mysqli_error();
        }
    $rows = [];
    while($row = mysqli_fetch_assoc($result)){
     $rows[] = $row;
    }
    //mengembalikan nilai row dengan array assosiasi
    return $rows;
}

function tambah($data){
    global $conn;
    $nama = htmlspecialchars($data["nama"]);
    $nrp = htmlspecialchars($data["nrp"]);
    $email = htmlspecialchars($data["email"]);
    $jurusan = htmlspecialchars($data["jurusan"]);
    //mengambil fungsi upload
    $gambar = upload();
    if(!$gambar){
        return false;
    }

    $query = "INSERT INTO mahasiswa VALUES('','$nama','$nrp','$email','$jurusan','$gambar')";
    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function upload(){
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    //cek apakah gambar di upload atau tidak
    if($error === 4){
        echo "<script>
                alert('pilih gambar terlebih dahulu');
                </script>";
        return false;
    }
    //cek yang di upload gambar atau bukan
    $ekstensiGambarValid = ['jpg','jpeg','png','gif'];
    $ekstensiGambar = explode('.',$namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if(!in_array($ekstensiGambar,$ekstensiGambarValid)){
        echo "<script>
                alert('yang anda upload bukan gambar');
             </script>";
        return false;
    }
    //cek jika ukuran terlalu besar
    if($ukuranFile > 3000000){
        echo "<script>
                alert('Ukuran gambar terlalu besar');
             </script>";
        return false;
    }
    //gambar siap di upload
    //generate nama baru
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;
    move_uploaded_file($tmpName,'img/' . $namaFileBaru);
    return $namaFileBaru;

}

function hapus($id){
    global $conn;
    mysqli_query($conn,"DELETE FROM mahasiswa WHERE id = $id");
    return mysqli_affected_rows($conn);
}

function ubah($data){
    global $conn;
    $id = $data["id"];
    $nama = htmlspecialchars($data["nama"]);
    $nrp = htmlspecialchars($data["nrp"]);
    $email = htmlspecialchars($data["email"]);
    $jurusan = htmlspecialchars($data["jurusan"]);
    $gambarLama = htmlspecialchars($data["gambarLama"]);
    //cek user upload gambar
    if($_FILES['gambar']['error'] === 4 ){
        $gambar = $gambarLama;
    }else{
        $gambar = upload();
    }
    $query = "UPDATE mahasiswa SET nrp = '$nrp',nama = '$nama',
    email = '$email',jurusan = '$jurusan',gambar = '$gambar' WHERE id = $id";

    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}
function cari($keyword){
    $query = "SELECT * FROM mahasiswa WHERE nama LIKE '%$keyword%' OR nrp
    LIKE '%$keyword%' OR email LIKE '%$keyword%' OR jurusan LIKE '%$keyword%' ";
    return query($query);
}
function registrasi($data){
    global $conn;
    $username = strtolower(htmlspecialchars(stripslashes($data["username"])));
    $password = mysqli_real_escape_string($conn,htmlspecialchars($data["password"]));
    $password2 = mysqli_real_escape_string($conn,htmlspecialchars($data["password2"]));

    if($password != $password2){
            echo "<script>
                alert('Paswword tidak sesuai');
             </script>";
             return false;
    }
    //cek username sudah ada atau belum
    $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");
    if( mysqli_fetch_assoc($result)){
        echo "<script>
                alert('Username Sudah Terdaftar');
             </script>";
             return false;
    }
    //enksipsi password
    $password = password_hash($password,PASSWORD_DEFAULT);
    $query = "INSERT INTO user VALUES('','$username','$password')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}

?>