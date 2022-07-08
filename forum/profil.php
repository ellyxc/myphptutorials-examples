<?php
include_once 'cek-akses.php';
$pdo = include 'koneksi.php';
$sql = "SELECT * FROM users WHERE id=:id";
$query = $pdo->prepare($sql);
$query->execute(array('id' => $_SESSION['user']['id']));
$user = $query->fetch();

$error = '';
if (!empty($_POST)) {
    // Validasi email harus unik
    $sqlEmail = "SELECT count(*) FROM users
        WHERE email=:email and id!=:id";
    $queryEmail = $pdo->prepare($sqlEmail);
    $queryEmail->execute(array(
        'email' => $_POST['email'],
        'id' => $_SESSION['user']['id']
    ));
    $count = $queryEmail->fetchColumn();
    if ($count > 0) {
        $error = 'Email telah digunakan, silahkan pakai email lain';
    } else {
        $sqlUpdate = "UPDATE users SET nama=:nama, email=:email
        WHERE id=:id";
        $querUpdate = $pdo->prepare($sqlUpdate);
        $querUpdate->execute(array(
            'nama' => $_POST['nama'],
            'email' => $_POST['email'],
            'id' => $_SESSION['user']['id']
        ));

        // Update data session
        $_SESSION['user']['nama'] = $_POST['nama'];
        $_SESSION['user']['email'] = $_POST['email'];

        if (!empty($_POST['password_lama']) && !empty($_POST['password_baru'])){
            if (sha1($_POST['password_lama']) != $user['password']) {
                $error = 'Password lama salah';
            } else {
                if ($_POST['password_baru'] != $_POST['password_baru2']) {
                    $error = 'Password Baru dan Ketik Ulang Password Baru harus sama';
                } else {
                    $sqlPassword = "UPDATE users set password = :password
                    WHERE id = :id";
                    $queryPassword = $pdo->prepare($sqlPassword);
                    $queryPassword->execute(array(
                        'password' => sha1($_POST['password_baru']),
                        'id' => $_SESSION['user']['id']
                    ));
                    header("Location: profil.php");
                }
            }
        } else {
            header("Location: profil.php");
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Profil User</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php
         $__menuAktif = 'profil';
         include 'menu.php';
         ?>
         <div class="container">
            <div class="row mb-3 align-items-center">
                <div class="col-auto">
                 <img src="//www.gravatar.com/avatar/<?php echo md5($user['email']);?>?s=48&d=monsterid" class="rounded-circle"/>
                </div>
                <div class="col">
                    <h2 class="mb-0"><?php echo htmlentities($user['nama']);?></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <?php
                    if ($error != '') {
                        echo '<p class="text-danger">'.$error.'</p>';
                    }
                    ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="nama"
                                value="<?php echo isset($_POST['nama']) ? $_POST['nama'] : $user['nama'];?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email"
                                value="<?php echo isset($_POST['email']) ? $_POST['email'] : $user['email'];?>" required>
                        </div>
                        <hr/>
                        <h5>Ganti Password</h5>
                        <p class="text-info">Kosongkan jika tidak diganti</p>
                        <div class="mb-3">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="password_lama" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password_baru" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ketik Ulang Password Baru</label>
                            <input type="password" name="password_baru2" class="form-control">
                        </div>
                        <div class="text-end mb-5">
                            <button class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
         </div>
    </body>
</html>