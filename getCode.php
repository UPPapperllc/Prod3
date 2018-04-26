<?php
$jTableResult = array();
$jTableResult['Result'] = "OK";
require('xml2array.php');

$xmlDoc = new DOMDocument();
$xmlDoc->load('SchedCode.xml');
$xml = XML2Array::createArray($xmlDoc);

//var_dump($xml['cat']);
foreach ($xml['codes'] as $cat) {
    $jTableResult['Options'] = $cat;
}

print json_encode($jTableResult);