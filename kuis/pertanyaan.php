<?php
require_once 'cek-akses.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Daftar Pertanyaan</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }

            table td, table th {
                border: 1px solid #ddd;
                vertical-align: top;
                padding: 8px;
            }

            table th {
                text-align: left;
            }
        </style>
    </head>
    <body>
        <h2>Daftar Pertanyaan</h2>
        <p>
            <a href="tambah-pertanyaan.php">Tambah Pertanyaan</a>
        </p>
        <table>
            <thead>
                <tr>
                    <th>Pertanyaan</th>
                    <th>Skor</th>
                    <th>Jawaban</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $pdo = require 'koneksi.php';
                $query = $pdo->prepare("SELECT * FROM pertanyaan");
                $query->execute();
                while($pertanyaan = $query->fetch()) {
                ?>
                <tr>
                    <td><?php echo htmlentities($pertanyaan['deskripsi']);?></td>
                    <td><?php echo htmlentities($pertanyaan['skor']);?></td>
                    <td>
                        <?php
                        $queryJawaban = $pdo->prepare("SELECT * FROM jawaban
                            WHERE id_pertanyaan=:id");
                        $queryJawaban->execute(array('id' => $pertanyaan['id']));
                        echo '<ol type="A">';
                        while($jawaban = $queryJawaban->fetch()) {
                            $style = 'style="color:green"';
                            if ($jawaban['benar'] != 1) {
                                $style = '';
                            }
                            echo '<li '.$style.'>'.htmlentities($jawaban['deskripsi']).'</li>';
                        }
                        echo '</ol>';
                        ?>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </body>
</html>