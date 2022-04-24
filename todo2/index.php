<?php
include_once "cek-akses.php";
$pdo = include "koneksi.php";
if (!empty($_POST)) {
    if ($_POST['aksi'] == 'Tambah') {
        $query = $pdo->prepare("insert into todo (judul, selesai, id_user) values (:judul, 0, :id_user)");
        $query->execute(array(
            'judul' => $_POST['judul'],
            'id_user' => $_SESSION['user']['id']
        ));
        header("Location: index.php");
        exit;
    }

    if ($_POST['aksi'] == 'Update' && !empty($_POST['todo'])) {
        foreach($_POST['todo'] as $idTodo) {
            $query = $pdo->prepare("update todo set selesai=1 where id=:id and id_user=:id_user");
            $query->execute(array(
                'id' => $idTodo,
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
        <form action="" method="POST">
            <input type="text" name="judul" placeholder="Judul" required/>
            <input type="submit" name="aksi" value="Tambah"/>
        </form>
        <?php
        if ($_SESSION['user']['tipe'] == 'admin') {?>
        <form method="GET" action="" id="select-user">
            <p>
            <strong>Todo List: </strong>
            <select name="idUser" onchange="document.getElementById('select-user').submit()">
                <option value=""><?php echo htmlentities($_SESSION['user']['nama']);?></option>
                <?php
                $query = $pdo->prepare("select * from users where id !=:id_user && tipe != 'admin'");
                $query->execute(array('id_user' => $_SESSION['user']['id']));
                while($user = $query->fetch()) {
                    $selected = '';
                    if (isset($_GET['idUser']) && $_GET['idUser'] == $user['id']) {
                        $selected = 'selected';
                    }
                    echo '<option value="'.$user['id'].'" '.$selected.'>'. htmlentities($user['nama']).'</option>';
                }
                ?>
            </select>
            </p>
        </form>
        <?php }?>
        <form action="" method="POST">
            <ul>
                <?php
                $query = $pdo->prepare("select * from todo where id_user=:id_user");
                $idUser = $_SESSION['user']['id'];
                if ($_SESSION['user']['tipe'] == 'admin' && isset($_GET['idUser']) && !empty($_GET['idUser'])) {
                    $idUser = $_GET['idUser'];
                }
                $query->execute(array('id_user' => $idUser));
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
            <?php
            if ($idUser == $_SESSION['user']['id']) {?>
            <input type="submit" name="aksi" value="Update"/>
            <?php }?>
        </form>
    </body>
</html>