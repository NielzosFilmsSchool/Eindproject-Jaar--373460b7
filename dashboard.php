<head>
    <meta charset="utf-8">
    <title>Jarvis | Bit Academy</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/dashboard.css" rel="stylesheet">
</head>

<body>
    <header>
        <img src="img/logo.png" alt="Bit Logo" class="logo">
    </header>
    <main>
        <h1 style="padding: 20px;">Dashboard</h1>
        <div class="table">
            <table>
                <tr>
                    <td>Username</td>
                    <td>Exercise Name</td>
                    <td>Repo Link</td>
                    <td>Plagiaat</td>
                </tr>
                <?php
                $dsn = "mysql:host=localhost;dbname=dupe_comparison";
                $user = "root";
                $passwd = "";
                $pdo = new PDO($dsn, $user, $passwd);

                $exercises = $pdo->query("SELECT * FROM exercise");
                while ($row = $exercises->fetch()) {
                    $files = $pdo->query("SELECT * FROM files WHERE exercise_id = ".$row["id"]);
                    $dupe = "Nee";
                    while ($file = $files->fetch()) {
                        if ($file["dupe"] == 1) {
                            $dupe = "Ja";
                        }
                    } ?>
                <tr style="border:0">
                    <td><?= $row["username"]?></td>
                    <td><?= $row["exercise_name"]?></td>
                    <td><a href="<?= $row["link"]?>"><?= $row["link"]?></a></td>
                    <td><?= $dupe?></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <details>
                            <summary>File Details</summary>
                            <table class="files_table">
                                <tr>
                                    <td>Filename</td>
                                    <td>Percentage</td>
                                    <td>Plagiaat</td>
                                    <td>Persoon</td>
                                </tr>
                                <?php
                               $files = $pdo->query("SELECT * FROM files WHERE exercise_id = ".$row["id"]);
                    while ($file = $files->fetch()) {
                        ?>
                                <tr>
                                    <td><?= $file["filename"]?></td>
                                    <td><?= $file["dupe_percentage"]?></td>
                                    <td><?= $file["dupe"]?"Ja":"Nee" ?></td>
                                    <td>
                                        <?php
                                        $person = $pdo->query("SELECT * FROM exercise WHERE id = ".$file["dupe_exercise_id"]);
                        $person = $person->fetch();
                        echo $person["username"]; ?>
                                    </td>
                                </tr>
                                <?php
                    } ?>
                            </table>
                        </details>
                    </td>
                </tr>
                <?php
                }
                ?>
            </table>
        </div>
    </main>
</body>