<?php
require_once 'cek-akses.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Hasil Kuis</title>
    </head>
    <body>
        <h1>Hasil Kuis Anda</h1>
<?php
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $pdo = require 'koneksi.php';
    $query = $pdo->prepare("SELECT * FROM hasil where id=:id");
    $query->execute(array('id' => $_GET['id']));
    $hasil = $query->fetch();
    if (!$hasil) {
        echo '<p style="color:red">Hasil tidak ditemukan</p>';
    } else {
        echo '<h1>Selamat Skor Anda: '.$hasil['nilai'].'</h1>';
        echo '<h2>Detil Hasil Anda</h2>';
        echo '<ol>';
        $query2 = $pdo->prepare("SELECT h.*,j.deskripsi as jawab, 
        p.deskripsi as tanya
        FROM hasil_jawaban as h
        INNER JOIN jawaban as j ON h.id_jawaban = j.id
        INNER JOIN pertanyaan as p ON j.id_pertanyaan = p.id
        WHERE h.id_hasil=:id_hasil");
        $query2->execute(array(
            'id_hasil' => $hasil['id']
        ));
        while($data = $query2->fetch()) {
            echo '<li>';
            echo htmlentities($data['tanya']);
            echo '<p>Jawaban: '. htmlentities($data['jawab']).'</p>';
            if ($data['benar']) {
                echo '<p style="color:green">Benar</p>';
            } else {
                echo '<p style="color:red">Salah</p>';
            }
            echo '</li>';
        }
        echo '</ol>';
    }
}
?>
    </body>
</html>