<?php
session_start();
$error = '';
if (!empty($_POST)) {
    $pdo = require 'koneksi.php';
    $query = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $query->execute(array('email' => $_POST['email']));
    $user = $query->fetch();
    if (!$user) {
        $error = 'Email atau password salah';
    } elseif (sha1($_POST['password']) == $user['password']) {
        $_SESSION['user'] = array(
            'id' => $user['id'],
            'nama' => $user['name']
        );
        header("Location: index.php");
        exit;
    } else {
        $error = 'Email atau password salah';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <h1>Silahkan Login</h1>
        <?php
        if ($error) {
            echo '<p styles="color:red">'.$error.'</p>';
        }
        ?>
        <form action="" method="POST">
            <p>
                <label>Email</label>
                <p>
                    <input type="email" name="email" required/>
                </p>
            </p>
            <p>
                <label>Password</label>
                <p>
                    <input type="password" name="password" required/>
                </p>
            </p>
            <p>
                <button type="submit">Login</button>
            </p>
        </form>
    </body>
</html>