<?php
if (empty($_FILES) === false) {
    foreach($_FILES['files']['name'] as $index => $fileName) {
        if (empty($fileName) || $_FILES['files']['size'][$index] <= 0 && $_FILES['files']['error'][$index] != 0) {
            continue;
        }

        move_uploaded_file($_FILES['files']['tmp_name'][$index], __DIR__.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$fileName);
    }

    echo 'File telah di upload';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Upload Multiple File</title>
    </head>
    <body>
        <form action="" method="POST" enctype="multipart/form-data">
            File: <input type="file" name="files[]" multiple="true" accept=".png,.jpg"/>
            <p>
                <button type="submit">Upload</button>
            </p>
        </form>
    </body>
</html>