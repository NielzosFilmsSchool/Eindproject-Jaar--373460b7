<?php
//Dit is voor de test
$link = "https://github.com/LyxurD4/No-More-Errors-54511501/archive/master.zip";

//Dit moet de echte lijn worden 
if (isset($_POST["submit"])) {
    $link = $_POST["link"];
    $fileName = $_POST["fileName"];
    $completeLink = $link . "/archive/master.zip";
}


file_put_contents("master.zip",
    file_get_contents($link)
);

system('unzip master.zip');
unlink("master.zip");

$file = fopen("No-More-Errors-54511501-master\wisselgeld.php", "r");
while (!feof($file)) {
    $line = fgets($file);
    $fileArray = array($line);

    // setcookie("fileArray", $fileArray, time() + 3600);
    // header("refresh:0; url=index.php");
    
    // foreach ($fileArray as $value) {
    //     echo $value;
    // } 
}

?>