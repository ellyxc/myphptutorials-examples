<?php
require_once 'cek-akses.php';
if (!empty($_POST)) {
    $pdo = require 'koneksi.php';
    try {
        $pdo->beginTransaction();
        $query = $pdo->prepare("INSERT INTO pertanyaan (deskripsi, skor)
        VALUES (:deskripsi, :skor)");
        $query->execute(array(
            'deskripsi' => $_POST['deskripsi'],
            'skor' => $_POST['skor']
        ));
        $idPertanyaan = $pdo->lastInsertId();
        foreach($_POST['jawaban'] as $index => $jawaban) {
            $queryJawaban = $pdo->prepare('INSERT INTO jawaban
            (deskripsi, benar, id_pertanyaan)
            VALUES (:deskripsi, :benar, :id_pertanyaan)');
            $benar = 0;
            if ($index == $_POST['benar']) {
                $benar = 1;
            }
            $queryJawaban->execute(array(
                'deskripsi' => $jawaban,
                'id_pertanyaan' => $idPertanyaan,
                'benar' => $benar
            ));
        }
        $pdo->commit();
        header("Location: pertanyaan.php");
        exit;
    } catch(Exception $e) {
        $pdo->rollBack();
        echo $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Tambah Pertanyaan</title>
    </head>
    <body>
        <h2>Tambah Pertanyaan</h2>
        <form action="" method="POST">
            <p>
                <label>Deskripsi</label><br>
                <textarea cols="50" rows="6" name="deskripsi" required></textarea>
            </p>
            <p>
                <label>Skor</label><br>
                <input type="number" name="skor" required>
            </p>
            <strong>Jawaban</strong>
            <table>
                <thead>
                    <tr>
                        <td>Jawaban</td>
                        <td>Benar</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody id="table-jawaban">
                    <tr>
                        <td><input type="text" required name="jawaban[1]"></td>
                        <td><input type="radio" name="benar" value="1"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><input type="text" required name="jawaban[2]"></td>
                        <td><input type="radio" name="benar" value="2"></td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><button type="button" onclick="tambah()">+</button></td>
                    </tr>
                </tfoot>
            </table>
            <button type="submit">Simpan</button>
            <script>
                let jumlah = 2;
                function tambah() {
                    jumlah ++;
                    let baris = '<td><input type="text" required name="jawaban['+jumlah+']"></td>' +
                        '<td><input type="radio" name="benar" value="'+jumlah+'"></td>' +
                        '<td><button type="button" onclick="hapus('+jumlah+')">&times;</button></td>';

                    const tr = document.createElement('tr');
                    tr.innerHTML = baris;
                    tr.setAttribute('id', 'jawaban-' + jumlah);
                    document.getElementById('table-jawaban').append(tr);
                }

                function hapus(id) {
                    document.getElementById('jawaban-' + id).remove();
                }
            </script>
        </form>
    </body>
</html>