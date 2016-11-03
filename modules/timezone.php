<?php

array_shift($argv);
$req = implode(' ', $argv);

$req = urlencode($req);

$url = "http://maps.googleapis.com/maps/api/geocode/json?address=$req&sensor=false";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$dataRaw = curl_exec($ch);
$data = json_decode($dataRaw, true);

if ($data) {
    $results = $data['results'];
    $result = current($results);
    $geometry = $result['geometry'];
    $location = $geometry['location'];
    $lat = $location['lat'];
    $lng = $location['lng'];
    if ($lat && $lng) {
        $time = time();
        $url = "https://maps.googleapis.com/maps/api/timezone/json?location=$lat,$lng&timestamp=$time&sensor=false";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $dataRaw = curl_exec($ch);
        $data = json_decode($dataRaw, true);
        if (isset($data['rawOffset'])) {
            $dstOffset = (int) $data['dstOffset'];
            $offset = (int) $data['rawOffset'];
            $offset += $dstOffset;
            $hours = $offset / 3600;
            $hoursAms = $hours - 1;
            echo "Difference with GMT: $hours hours\n";
            echo "Difference with Amsterdam: $hoursAms hours\n";
        } else {
            echo $dataRaw . PHP_EOL;
            echo "No time data\n";
        }
    }
} else {
    echo $dataRaw . PHP_EOL;
    echo "No Geo data\n";
}