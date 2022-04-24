<?php
include_once "../cek-akses.php";
$error = '';
if (!empty($_POST)) {
    if ($_POST['password'] != $_POST['password2']) {
        $error = 'Password dan Ulang Password harus sama';
    } else {
        try {
            $pdo = include "../koneksi.php";
            $query = $pdo->prepare("insert into users (username, password, salt, nama, aktif, tipe) values(:username, :password, :salt, :nama, :aktif, :tipe)");
            $string = str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
            $salt = sha1(substr($string, 0, 8).time().rand());
            $query->execute(array(
                'username' => $_POST['username'],
                'password' => sha1($_POST['password'].$salt),
                'salt' => $salt,
                'nama' => $_POST['nama'],
                'aktif' => $_POST['aktif'],
                'tipe' => $_POST['tipe'],
            ));
            header("Location: admin.php");
        } catch(Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Tambah User</title>
    </head>
    <body>
        <h1>Tambah User</h1>
        <?php
        if ($error) {
            echo '<h5 style="color:red">'.$error.'</h5>';
        }
        ?>
        <form method="POST" action="">
            <div>
                <div>Nama</div>
                <p>
                    <input type="text" name="nama"/>
                </p>
            </div>
            <div>
                <div>Username</div>
                <p>
                    <input type="text" name="username" autocomplete="off"/>
                </p>
            </div>
            <div>
                <div>Password</div>
                <p>
                    <input type="password" name="password" autocomplete="off"/>
                </p>
            </div>
            <div>
                <div>Ulang Password</div>
                <p>
                    <input type="password" name="password2" autocomplete="off"/>
                </p>
            </div>
            <div>
                <div>Tipe</div>
                <p>
                    <select name="tipe">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </p>
            </div>
            <div>
                <div>Aktif</div>
                <p>
                    <input type="hidden" name="aktif" value="0"/>
                    <input type="checkbox" name="aktif" value="1"/>
                </p>
            </div>
            <button type="submit">Simpan</button>
        </form>
    </body>
</html>