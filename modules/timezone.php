<?php

const FORMAT = 'Y-m-d H:i:s';

date_default_timezone_set('UTC');
function detect($place, $time)
{
    $req = urlencode($place);
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
            $url = "https://maps.googleapis.com/maps/api/timezone/json?location=$lat,$lng&timestamp=$time&sensor=false";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataRaw = curl_exec($ch);
            $data = json_decode($dataRaw, true);
            if (isset($data['rawOffset'])) {
                $dstOffset = (int) $data['dstOffset'];
                $offset = (int) $data['rawOffset'];
                $offset += $dstOffset;
                return $offset;
            }
        }
    }
    return null;
}

function getDateTZ($timestamp, $timezone)
{
    return date(FORMAT, strtotime(
        sprintf(
            '%s %s %s',
            date(FORMAT, $timestamp),
            $timezone < 0 ? '-' : '+',
            sprintf('%u seconds', abs($timezone))
        )
    ));
}

$targetResult = null;
$compareResult = null;
$timeSpec = null;
$compare = null;
if (preg_match('#^/timezone/(.*)$#', $requestUri, $matches)) {
    $params = array_map('urldecode', array_filter(explode('/', $matches[1])));
    $target = $params[0] ?? null;
    $timeSpec = $params[1] ?? null;
    $compare = $params[2] ?? null;
    $time = null;

    if ($target) {
        if ($timeSpec) {
            $time = strtotime($timeSpec);
        }
        if (!$time) {
            $timeSpec = 'now';
            $time = time();
        }
        $targetTZ = detect($target, $time);
        if ($targetTZ) {

            $targetResult = [
                'tz' => $targetTZ / 3600,
                'time' => getDateTZ($time, $targetTZ),
            ];
            if ($targetResult && $compare) {
                $compareTZ = detect($compare, $time);
                if ($compareTZ) {
                    $compareResult = [
                        'diff' => ($targetTZ - $compareTZ) / 3600,
                        'time' => getDateTZ($time, $compareTZ),
                    ];
                }
            }
        }
    }
}
?>
<html>
<head>
<title>Timezone check - QASK</title>
</head>
<body>
<h1>Timezone check</h1>
<div>
    <p><b>Usage:</b> <p style="font-family:'Lucida Console', monospace">/timezone/&lt;target&gt;/[&lt;time&gt;]/[&lt;compare&gt;]</p>
    <p>where</p>
    <p>&lt;target&gt; - a place where you want to check the timezone,</p>
    <p>&lt;time&gt; - when do you want to check timezone (e.g. <i>now</i>, <i>2 weeks</i> (meaning in two weeks), <i>yesterday</i> etc. This is optional.</p>
    <p>&lt;compare&gt; - a place you want to know the time difference with the target. This is optional.</p>
    <br clear="all" />
    <p><i>Examples:</i></p>
    <p><a href="/timezone/Moscow">/timezone/Moscow</a></p>
    <p><a href="/timezone/Amsterdam/2+weeks+ago">/timezone/Amsterdam/2+weeks+ago</a></p>
    <p><a href="/timezone/New+York/1+week">/timezone/New+York/1+week</a></p>
    <p><a href="/timezone/Shanghai/now/Cary,+NC">/timezone/Shanghai/now/Cary,+NC</a></p>
</div>

<?php
if ($targetResult) {
?>
    <h2>Results: </h2>
    <div>
        <p><b>Difference with GMT: </b><?=$targetResult['tz']?> hour(s)</p>
        <p><b>Time <?=$timeSpec?>: <?=$targetResult['time']?></b></p>
    </div>
<?php
    if ($compareResult) {
?>
        <div>
            <p><b>Difference with <?=$compare?>: </b><?=$compareResult['diff']?> hour(s)</p>
            <p><b>Time <?=$timeSpec?>: <?=$compareResult['time']?></b></p>
        </div>
<?php
    }
}
?>

</body>
</html>
