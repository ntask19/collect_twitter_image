<?php

require 'download.php';

$keyword = isset($argv[1]) ? $argv[1] : null;

if (empty($keyword)) {
    echo '$keywordを引数に指定してください。';
    exit();
}

$dl = new DownloadTwitter;
$dl->searchImage($keyword);