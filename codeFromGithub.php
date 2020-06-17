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

//-------------------------------------------------------------------------------------------

//repo name, remove code at the end
$repo_name = explode("-", $trueDataArray["repoName"]);
unset($repo_name[count($repo_name)-1]);
$repo_name_final = implode("-", $repo_name);

//database connection
$dsn = "mysql:host=localhost;dbname=dupe_comparison";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);

//class includer
spl_autoload_register(
    function ($class_name) {
        include $class_name . '.class.php';
    }
);
$comparer = new Comparison();

//get all the exercises with the same name
$exercises = $pdo->query("SELECT * FROM exercise WHERE exercise_name LIKE '".$repo_name_final."%'");
echo $repo_name_final;

//insert the current exercise
$insert = $pdo->prepare(
    "INSERT INTO exercise
    (username, link, exercise_name, filename,  highest_dupe_percentage, dupe)
    VALUES ('".$trueDataArray["username"]."', '".$trueDataArray["link"]."', '".$trueDataArray["repoName"]."', '".$trueDataArray["fileName"]."',
    0, 0)"
);
$insert->execute();

//check if exercises with same name exist
if ($exercises->rowCount() > 0) {
    $highest_percentage = 0;
    $dupe = 0;
    $result_id = null;

    //get id of current exercise
    $current_exercise_ = $pdo->query(
        "SELECT * FROM exercise
        WHERE exercise_name = '".$trueDataArray["repoName"]."'"
    );
    $current_exercise = $current_exercise_->fetch();

    while ($row = $exercises->fetch()) {
        if ($trueDataArray["username"] != $row["username"]) {
            //get code of second file
            $file2 = fopen("codeData" . "/". $row["exercise_name"] . "-master/" . $row["filename"], "r");
            $fileArray2 = array();
            $index2 = -1;
            while (! feof($file2)) {
                $line2 = fgets($file2);
                $index2++;
                $fileArray2[$index2] = $line2;
            }

            //compare
            $comparer->detectThreshold($fileArray, $fileArray2);
            $result = $comparer->compare($fileArray, $fileArray2);
            echo var_dump($result);

            //save values if higher than the older values
            if ($result[1] > $highest_percentage) {
                $highest_percentage = $result[1];
                $dupe = $result[0];
                $result_id = $row["id"];

                //update current row exercise in the database if value is higher
                if ($highest_percentage > $row["highest_dupe_percentage"]) {
                    $update = $pdo->prepare(
                        "UPDATE exercise
                        SET highest_dupe_percentage = $highest_percentage, 
                        dupe = $dupe,
                        dupe_exercise_id = ".$current_exercise["id"]."
                        WHERE id = ".$row["id"]
                    );
                    $update->execute();
                }
            }
        }
    }

    //final update to the current exercise
    $update_ = $pdo->prepare(
        "UPDATE exercise
        SET highest_dupe_percentage = $highest_percentage, 
        dupe = $dupe,
        dupe_exercise_id = $result_id
        WHERE id = ".$current_exercise["id"]
    );
    $update_->execute();
}


//uncomment to see the output of this page
header("refresh:0; url=index.php");