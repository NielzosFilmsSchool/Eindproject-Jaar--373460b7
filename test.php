<?php
$json = file_get_contents("http://localhost/Eindproject-Jaar--373460b7/codeFromGithub.php?repo_link=https://github.com/NielzosFilmsSchool/Wie-is-daar-c5779a2d");
var_dump(json_decode($json));