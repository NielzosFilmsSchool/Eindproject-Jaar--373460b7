<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparison Test</title>
</head>

<body>
    <h1>Comparison Test</h1>
    <form action="codeFromGithub.php" method="POST">
        <input type="text" name="link" placeholder="Link...">
        <input type="text" name="fileName" placeholder="Filename...">
        <input type="submit" name="submit" value="Submit">
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Repo Link</th>
            <th>Exercise Name</th>
            <th>Filename</th>
            <th>Dupe Percentage</th>
            <th>Dupe ID</th>
            <th>Dupe</th>
        </tr>
        <?php
        $dsn = "mysql:host=localhost;dbname=dupe_comparison";
        $user = "root";
        $passwd = "";
        $pdo = new PDO($dsn, $user, $passwd);

        $exercises = $pdo->query("SELECT * FROM exercise");
        while ($row = $exercises->fetch()) {
            ?>
        <tr>
            <td><?= $row["id"]?></td>
            <td><?= $row["username"]?></td>
            <td><?= $row["link"]?></td>
            <td><?= $row["exercise_name"]?></td>
            <td><?= $row["filename"]?></td>
            <td><?= $row["highest_dupe_percentage"]?></td>
            <td><?= $row["dupe_exercise_id"]?></td>
            <td><?= $row["dupe"]?></td>
        </tr>
        <?php
        }
        ?>
    </table>

</body>

</html>