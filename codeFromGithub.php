<?php
//Dit is voor een snelle test
// $link = "https://github.com/LyxurD4/No-More-Errors-54511501/archive/master.zip";

/**
 * Niels test links
 * https://github.com/NielzosFilms/simple_javascript_game
 * script.js
 */

// De "echte" manier
if (isset($_POST["submit"])) {
    $link = $_POST["link"];
    $fileName = $_POST["fileName"];
}

// In de trueDataArray staat alle data van waarde
$dataArray = explode("/", $link);
$trueDataArray = array(
    "username" => $dataArray[3],
    "repoName" => $dataArray[4],
    "fileName" => $fileName,
    "link" => $link,
    "downloadLink" => $link . "/archive/master.zip"
);

// Hier wordt een zip aangemaakt en geunzipt
file_put_contents(
    "master.zip",
    file_get_contents($trueDataArray["downloadLink"])
);

$zip = new ZipArchive;
$res = $zip->open('master.zip');
if ($res === true) {
    $zip->extractTo("codeData");
    $zip->close();
    unlink("master.zip");
} else {
    echo 'Error: No file has been unzipped';
}

// Deze functie laat elke lijn van code een waarde zijn in de fileArray
$file = fopen("codeData" . "/". $trueDataArray["repoName"] . "-master/" . $trueDataArray["fileName"], "r");
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

/*foreach ($fileArray as $value) {
    echo $value . "<br>";
}*/

/*echo var_dump($trueDataArray["username"]);
echo var_dump($trueDataArray["link"]);*/

//Snelle tests
// var_dump($fileArray);


//repo name, remove code at the end
/*$repo_name = explode("-", $trueDataArray["repoName"]);
unset($repo_name[count($repo_name)-1]);
$repo_name_final = implode("-", $repo_name);*/

//Opslaan in de database
$dsn = "mysql:host=localhost;dbname=dupe_comparison";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);

//plagiaat check
spl_autoload_register(
    function ($class_name) {
        include $class_name . '.class.php';
    }
);
$comparer = new Comparison();

$result = null;
$result_id = null;

$exercises = $pdo->query('SELECT * FROM exercise WHERE exercise_name LIKE "'.$trueDataArray["repoName"].'%"');
if ($exercises->rowCount() != 1) {
    while ($row = $exercises->fetch()) {
        if ($trueDataArray["username"] != $row["username"]) {
            //get code of second file
            $file2 = fopen("codeData" . "/". $row["exercise_name"] . "-master/" . $row["fileName"], "r");
            $fileArray2 = array();
            $index2 = -1;
            while (! feof($file2)) {
                $line2 = fgets($file2);
                $index2++;
                $fileArray2[$index2] = $line2;
            }

            $comparer->detectThreshold($fileArray, $fileArray2);
            $result = $comparer->compare($fileArray, $fileArray2);
            $result_id = $row["id"];
            echo var_dump($result);
        }
    }
}

if ($result == null) {
    $stmt = $pdo->prepare(
        "INSERT INTO exercise
        (username, link, exercise_name, filename)
        VALUES ('".$trueDataArray["username"]."', '".$trueDataArray["link"]."', '".$trueDataArray["repoName"]."', '".$trueDataArray["fileName"]."')"
    );
    $stmt->execute();
} else {
    $stmt = $pdo->prepare(
        "INSERT INTO exercise
        (username, link, exercise_name, filename, highest_dupe_percentage, dupe_exercise_id, dupe)
        VALUES ('".$trueDataArray["username"]."', '".$trueDataArray["link"]."', '".$trueDataArray["repoName"]."', '".$trueDataArray["fileName"]."',
        ".$result[1].", ".$result_id.", ".$result[0].")"
    );
    $stmt->execute();
}

header("refresh:0; url=index.php");