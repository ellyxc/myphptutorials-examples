<?php
session_start();
$gagal = false;
if (!empty($_POST)) {
    $pdo = include "koneksi.php";
    $query = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $query->execute(array('username' => $_POST['username']));
    $user = $query->fetch();
    if (!$user) {
        $gagal = true;
    } elseif($user['aktif'] != 1 || $user['password'] != sha1($_POST['password'].$user['salt'])) {
        $gagal = true;
    } else {
        $_SESSION['browser'] = md5($_SERVER['HTTP_USER_AGENT']);
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user'] = array(
            'id' => $user['id'],
            'nama' => $user['nama'],
        );
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <h1>Please login</h1>
        <?php
        if ($gagal) {
            echo '<h5 style="color:red">Username atau password salah</h5>';
        }
        ?>
        <form method="POST" action="">
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
            <button type="submit">Login</button>
        </form>
    </body>
</html>