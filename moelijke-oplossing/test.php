<?php
require_once(__DIR__ . '/github-php-client-master/client/GitHubClient.php');

$owner = 'LyxurD4';
$repo = 'No-More-Errors-54511501';

$client = new GitHubClient();

// $client->setPage();
// $client->setPageSize(2);
$issues = $client->issues->listIssues($owner, $repo);

foreach ($issues as $issue)
{
    /* @var $issue GitHubIssue */
    echo get_class($issue) . "[" . $issue->getNumber() . "]: " . $issue->getTitle() . $issue->getBody() . "\n";
}


?>