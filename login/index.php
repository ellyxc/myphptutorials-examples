<?php
include_once "cek-akses.php";
$pdo = include "koneksi.php";
if (!empty($_POST)) {
    if ($_POST['aksi'] == 'Tambah') {
        $query = $pdo->prepare('INSERT INTO todo (judul, selesai, id_user) VALUES (:judul, 0, :id_user)');
        $query->execute(array(
            'judul' => $_POST['judul'],
            'id_user' => $_SESSION['user']['id']
        ));
        header("Location: index.php");
        exit;
    }

    if ($_POST['aksi'] == 'Update' && !empty($_POST['todo'])) {
        foreach($_POST['todo'] as $id) {
            $query = $pdo->prepare('UPDATE todo SET selesai=1 WHERE id=:id AND id_user=:id_user');
            $query->execute(array(
                'id' => $id,
                'id_user' => $_SESSION['user']['id']
            ));
        }
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Todo List</title>
        <style>
           ul {
               list-style: none;
               margin-left: 0;
               padding-left: 0;
           }
        </style>
    </head>
    <body>
        <h3>Selamat Datang <?php echo htmlentities($_SESSION['user']['nama']);?></h3>
        <p>
        <a href="logout.php">Logout</a>
        </p>
        <h1>Todo List</h1>
        <form method="POST" accept="">
            <input type="text" name="judul" required placeholder="Judul">
            <input type="submit" name="aksi" value="Tambah">
        </form>
        <form method="POST" action="">
            <ul>
                <?php
                $query = $pdo->prepare("SELECT * FROM todo WHERE id_user=:id_user");
                $query->execute(array(
                    'id_user' => $_SESSION['user']['id']
                ));
                while($todo = $query->fetch()) {
                    echo '<li>';
                    $checked = '';
                    if ($todo['selesai'] == 1) {
                        $checked = 'checked';
                    }
                    echo '<input type="checkbox" value="'.$todo['id'].'" name="todo[]" '.$checked.'/>';
                    echo htmlentities($todo['judul']);
                    echo '</li>';
                }
                ?>
            </ul>
            <input type="submit" name="aksi" value="Update"/>
        </form>
    </body>
</html>