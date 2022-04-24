<?php
if (empty($_FILES) === false) {
    foreach ($_FILES as $key => $file) {
        if (empty($_FILES[$key]['name']) || $_FILES[$key]['size'] <= 0 && $_FILES[$key]['error'] != 0) {
            continue;
        }

        move_uploaded_file($_FILES[$key]['tmp_name'], __DIR__.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$_FILES[$key]['name']);
    }

    echo 'Files telah diupload';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Upload Multiple File</title>
    </head>
    <body>
        <form action="" method="POST" enctype="multipart/form-data">
            <div id="file-input">
                <p>File 1: <input type="file" name="file0"/></p>
            </div>
            <p>
                <button type="button" onclick="tambahFile()">Tambah</button>
            </p>
            <p>
                <button type="submit">Upload</button>
            </p>
        </form>
        <script>
            let jumlah = 1;
            function tambahFile() {
                let fileInput = `File ${jumlah+1}: <input type="file" name="file${jumlah}"/>`;
                let p = document.createElement('p');
                p.innerHTML = fileInput;
                document.getElementById('file-input').append(p);
            }
        </script>
    </body>
</html>