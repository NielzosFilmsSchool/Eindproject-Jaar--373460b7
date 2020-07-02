<?php
header('Content-Type: application/json');
//class includer
spl_autoload_register(
    function ($class_name) {
        include $class_name . '.class.php';
    }
);

//database connection
$dsn = "mysql:host=localhost;dbname=dupe_comparison";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);

$comparer = new Comparison();

// De "echte" manier
$link = $_GET["repo_link"];

// In de trueDataArray staat alle data van waarde
$dataArray = explode("/", $link);
$trueDataArray = array(
    "username" => $dataArray[3],
    "repoName" => $dataArray[4],
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
    //echo 'Error: No file has been unzipped';
}


$directory = "codeData/".$trueDataArray["repoName"]."-master";
$files = array_diff(scandir($directory), array('..', '.', 'README.md', '.gitignore'));
//echo var_dump($files);

//insert the current exercise
$insert_exercise = $pdo->prepare(
    "INSERT INTO exercise
    (username, link, exercise_name)
    VALUES ('".$trueDataArray["username"]."', '".$trueDataArray["link"]."', '".$trueDataArray["repoName"]."')"
);
$insert_exercise->execute();

//get id of current exercise
$current_exercise_ = $pdo->query(
    "SELECT * FROM exercise
    WHERE exercise_name = '".$trueDataArray["repoName"]."'"
);
$current_exercise = $current_exercise_->fetch();

$final_json_files = array();

// Deze functie laat elke lijn van code een waarde zijn in de fileArray
foreach ($files as $filename) {
    $final_file = array(
        "filename" => $filename,
        "dupe_percentage" => 0,
        "dupe" => 0,
    );
    //$final_json_files[] = $final_file;
    
    $insert_file = $pdo->prepare(
        "INSERT INTO files
        (filename, exercise_id)
        VALUES ('$filename', '".$current_exercise["id"]."')"
    );
    $insert_file->execute();
    
    $file = fopen("codeData" . "/". $trueDataArray["repoName"] . "-master/" . $filename, "r");
    $fileArray = array();
    $index = -1;
    while (! feof($file)) {
        $line = fgets($file);
        $index++;
        $fileArray[$index] = $line;
    }
    
    $repo_name = explode("-", $trueDataArray["repoName"]);
    unset($repo_name[count($repo_name)-1]);
    $repo_name_final = implode("-", $repo_name);

    //get all the exercises with the same name
    $exercises = $pdo->query("SELECT * FROM exercise WHERE exercise_name LIKE '".$repo_name_final."%'");
    //echo $repo_name_final;

    //check if exercises with same name exist
    if ($exercises->rowCount() > 0) {
        $highest_percentage = 0;
        $dupe = 0;
        $result_id = null;

        while ($row = $exercises->fetch()) {
            if ($trueDataArray["username"] != $row["username"]) {
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
                    //echo 'Error: No file has been unzipped';
                }
                
                //get code of second file
                $file2 = fopen("codeData" . "/". $row["exercise_name"] . "-master/" . $filename, "r");
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
                //echo var_dump($result);

                //save values if higher than the older values
                if ($result[1] > $highest_percentage) {
                    $highest_percentage = $result[1];
                    $dupe = $result[0];
                    $result_id = $row["id"];

                    $db_file_ = $pdo->query(
                        "SELECT * FROM files WHERE filename LIKE '$filename'
                        AND exercise_id = ".$row["id"]
                    );
                    $db_file = $db_file_->fetch();

                    if ($db_file != null) {
                        if ($highest_percentage > $db_file["dupe_percentage"]) {
                            $update = $pdo->prepare(
                                "UPDATE files
                                SET dupe_percentage = $highest_percentage,
                                dupe = $dupe, dupe_exercise_id = ".$current_exercise["id"]."
                                WHERE id = ".$db_file["id"]
                            );
                            $update->execute();
                            
                            $final_file["dupe_percentage"] = $highest_percentage;
                            $final_file["dupe"] = $dupe;
                        }
                    } else {
                        $insert = $pdo->prepare(
                            "INSERT INTO files
                            (filename, exercise_id, dupe_percentage, dupe, dupe_exercise_id)
                            VALUES ('$filename', '".$row["id"]."', $highest_percentage,
                            $dupe, '".$current_exercise["id"]."')"
                        );
                        $insert->execute();
                    }

                    $db_file_current = $pdo->query(
                        "SELECT * FROM files WHERE filename LIKE '$filename'
                        AND exercise_id = ".$current_exercise["id"]
                    );
                    $db_file_current = $db_file_current->fetch();

                    if ($highest_percentage > $db_file_current["dupe_percentage"]) {
                        $update = $pdo->prepare(
                            "UPDATE files
                            SET dupe_percentage = $highest_percentage,
                            dupe = $dupe, dupe_exercise_id = ".$row["id"]."
                            WHERE id = ".$db_file_current["id"]
                        );
                        $update->execute();
                        
                        $final_file["dupe_percentage"] = $highest_percentage;
                        $final_file["dupe"] = $dupe;
                    }
                }
            }
        }
    }
    array_push($final_json_files, $final_file);
}

$json = array(
    "username" => $trueDataArray["username"],
    "repo_link" => $trueDataArray["link"],
    "repo_name" => $trueDataArray["repoName"],
    "files" => $final_json_files,
);
$json = json_encode($json);
$json = str_replace("\\", "", $json);
echo $json;
//header("refresh:0; url=results.php");