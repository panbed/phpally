<?php

namespace CidiLabs\PhpAlly;

use DOMDocument;

// include autoload so that the namespace actually works
require_once 'vendor/autoload.php';


// detect no html file
if (empty($argv[1])) {
    print("Empty argument! Specify an HTML file as an argument please...\n");
    return;
}

// stop all the warnings (maybe bad practice? some of the warnings are about
// html parsing errors when HtmlService is run)
error_reporting(E_ERROR | E_PARSE);

// create HtmlService so that we can get the html document,
// then get the dom of the html
$HtmlService = new HtmlService();
$html = $HtmlService->dom(file_get_contents($argv[1]));

// debug: print html file that php gets
// print($html);


// check if html is empty
if (empty($html)) {
    print("HTML file is empty! Either it actually is empty, or an invalid file was passed in, please try checking it!\n");
    return;
}

// create instance of phpally, and get all the rules from src/Rule
$PhpAlly = new PhpAlly();
$ruleIds = $PhpAlly->getRuleIds();

// generate report using phpally, output is in json
$report = $PhpAlly->checkMany($html, $ruleIds);
print($report);

?>