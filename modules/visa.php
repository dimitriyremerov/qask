<html>
<head>
<title>Visa check - QASK</title>
</head>
<body>
<?php

if (preg_match('#^/visa/(\w{2})/(\w{2})/?$#', $requestUri, $matches)) {
	$url = sprintf('https://www.timaticweb.com/cgi-bin/tim_website_client.cgi?SpecData=1&VISA=&page=visa&NA=%s&AR=00&PASSTYPES=PASS&DE=%s&user=KLMB2C&subuser=KLMB2C', $matches[1], $matches[2]);
?>

<iframe src="<?=$url?>" style="position:fixed; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;">
    Your browser doesn't support iframes
</iframe>

<?php
} else {
?>

<h1>Visa check</h1>
<div>
<p><b>Usage:</b> <p style="font-family:'Lucida Console', monospace">/visa/&lt;nat&gt;/&lt;dest&gt;</p></p>
<p>where</p>
<p>&lt;nat&gt; - your nationality,</p>
<p>&lt;dest&gt; - your destination.</p>
<br clear="all" />
<p><i>Examples:</i></p>
<p><a href="/visa/IL/IR">/IL/IR</a></p>
<p><a href="/visa/RU/BY">/RU/BY</a></p>
<p><a href="/visa/RU/JP">/RU/JP</a></p>
<p><a href="/visa/NL/JP">/NL/JP</a></p>
</div>

<?php
}
?>

</body>
</html>
