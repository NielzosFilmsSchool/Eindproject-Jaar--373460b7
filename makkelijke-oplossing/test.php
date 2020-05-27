<?php
file_put_contents("master.zip",
    file_get_contents("https://github.com/LyxurD4/No-More-Errors-54511501/master.zip")
);
$zip = zip_open("master.zip");
zip_read($zip);

zip_close($zip);
?>