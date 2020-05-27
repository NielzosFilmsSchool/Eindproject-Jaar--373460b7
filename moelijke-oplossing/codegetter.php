<?php
//https://github.com/LyxurD4/No-More-Errors-54511501

if (isset($_POST["submit"])) {
    $link = $_POST["inputLink"];
    echo $link; 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Codegetter</h1>
    <form action="codegetter.php" method="POST">
        <input type="text" name="inputLink">
        <input type="submit" name="submit" value="Submit">
    </form>
    <?php
    if (isset($link)) {
        $test = file_get_contents($link);
        echo $test; 
    }
    ?>
</body>
</html>