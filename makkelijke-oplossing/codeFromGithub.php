<?php
//Dit is voor de test
// $link = "https://github.com/LyxurD4/No-More-Errors-54511501/archive/master.zip";

// Dit moet de echte lijn worden 
if (isset($_POST["submit"])) {
    $link = $_POST["link"];
    $fileName = $_POST["fileName"];
}

$dataArray = explode ("/", $link);

$trueDataArray = array (
    "username" => $dataArray[3],
    "repoName" => $dataArray[4],
    "fileName" => $fileName,
    "link" => $link,
    "downloadLink" => $link . "/archive/master.zip"
);

file_put_contents("master.zip",
    file_get_contents($trueDataArray["downloadLink"])
);

$zip = new ZipArchive;
$res = $zip->open('master.zip');
if ($res === TRUE) {
    $zip->extractTo("code");
    $zip->close();
    unlink("master.zip");
} else {
    echo 'Error: No file has been unzipped';
}

// $file = fopen($trueDataArray["repoName"] . "\wisselgeld.php", "r");
$file = fopen("code" . "/". $trueDataArray["repoName"] . "-master/" . $trueDataArray["fileName"], "r");
$fileArray = array();
$index = -1;
while (! feof($file)) {
    $line = fgets($file);
    $index++;
    $fileArray[$index] = $line;
}

//Voor als we het willen wegsturen
// setcookie("fileArray", $fileArray, time() + 3600);
// header("refresh:0; url=index.php");
    
//Voor in de pagina
foreach ($fileArray as $value) {
    echo $value . "<br>";
} 

//Snelle tests
// var_dump($fileArray);
?>