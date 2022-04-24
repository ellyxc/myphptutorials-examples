<?php
$pdo = include "koneksi.php";
if(!empty($_POST)) {
    foreach($_POST['siswa'] as $idSiswa) {
        $query = $pdo->prepare("delete from siswa where id=:id");
        $query->execute(array('id' => $idSiswa));
    }

    header("Location: checkbox.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Hapus Data Dengan Checkbox</title>
        <style>
            table {
                border: 1px solid #ddd;
                border-collapse: collapse;
            }
            table td, table th {
                padding: 8px;
                border: 1px solid #ddd;
            }
        </style>
    </head>
    <body>
        <h1>Data Siswa</h1>
        <form action="" method="POST" onsubmit="return konfirmasi()">
            <p><button type="submit">Hapus</button> | <a href="tambah-siswa.php">Tambah Siswa</a></p>
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" onchange="selectAll(this)"></th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Jenis Kelamis</th>
                        <th>Tanggal Lahir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = $pdo->prepare("select * from siswa");
                    $query->execute();
                    while($siswa = $query->fetch()) {
                    ?>
                    <tr>
                        <td><input type="checkbox" class="siswa" name="siswa[]" value="<?php echo $siswa['id'];?>"></td>
                        <td><?php echo htmlentities($siswa['nis']);?></td>
                        <td><?php echo htmlentities($siswa['nama']);?></td>
                        <td><?php echo htmlentities($siswa['jenis_kelamin']);?></td>
                        <td><?php echo htmlentities($siswa['tgl_lahir']);?></td>
                    </tr>
                    <?php 
                    }
                    ?>
                </tbody>
            </table>
            <p><button type="submit">Hapus</button></p>
        </form>
        <script>
            function konfirmasi() {
                return confirm('Apakah ada yakin menghapus data siswa tersebut?');
            }

            function selectAll(input) {
                let checkboxes = document.querySelectorAll('.siswa');
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = input.checked;
                });
            }
        </script>
    </body>
</html>