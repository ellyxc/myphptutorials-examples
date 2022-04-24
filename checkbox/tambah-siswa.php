<?php
if(!empty($_POST)) {
    $pdo = include "koneksi.php";
    $query = $pdo->prepare("insert into siswa (nis, nama, jenis_kelamin, tgl_lahir) 
    values (:nis, :nama, :jenis_kelamin, :tgl_lahir)");
    $query->execute(array(
        'nis' => $_POST['nis'],
        'nama' => $_POST['nama'],
        'jenis_kelamin' => $_POST['jenis_kelamin'],
        'tgl_lahir' => $_POST['tgl_lahir'],
    ));
    header("Location: tambah-siswa.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Tambah Siswa</title>
    </head>
    <body>
        <h1>Tambah Siswa</h1>
        <form method="POST" action="">
            <div>
                <div>NIS</div>
                <p>
                    <input type="text" name="nis" autocomplete="off"/>
                </p>
            </div>
            <div>
                <div>Nama</div>
                <p>
                    <input type="text" name="nama" autocomplete="off"/>
                </p>
            </div>
            <div>
                <div>Jenis Kelamin</div>
                <p>
                    <select name="jenis_kelamin">
                        <option value="L">Laki Laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </p>
            </div>
            <div>
                <div>Tanggal Lahir</div>
                <p>
                    <input type="date" name="tgl_lahir" autocomplete="off"/>
                </p>
            </div>
            <p>
                <button type="submit">Simpan</button>
            </p>
        </form>
    </body>
</html>