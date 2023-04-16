<?php
session_start();
$error = '';
if (empty($_POST) === false) {
    $pdo = require 'koneksi.php';
    $query = $pdo->prepare('SELECT * FROM users WHERE email=:email');
    $query->execute(['email' => $_POST['email']]);
    $user = $query->fetch();
    try {
        if (!$user) {
            throw new Exception('Username atau password salah');
        }

        if ($user['password'] !== sha1($_POST['password'])) {
            throw new Exception('Username atau password salah');
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'nama' => $user['nama'],
            'role' => $user['tipe'],
        ];

        $backUrl = $_GET['back'] ?? '';
        if (empty($backUrl) == false) {
            header('Location: '.$backUrl);
            exit;
        }

        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="styles.css" rel="stylesheet">
        <title>Login</title>
    </head>
    <body>
        <?php
        include 'menu.php';
        ?>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2>Silahkan Login</h2>
                    <?php
                    if (empty($error) == false) {
                        echo '<p class="alert alert-danger">'.$error.'</p>';
                    }
                    ?>
                <form action="" method="post">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="text-end">
                        <button class="btn btn-primary" type="submit">Login</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </body>
</html>