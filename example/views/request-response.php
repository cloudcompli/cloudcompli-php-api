<h2><?php echo $title; ?></h2>

<h3>Request</h3>

<pre><?php echo $request; ?></pre>

<h3>Response Headers</h3>

<pre><?php

echo 'HTTP/'.$response->getProtocolVersion().' '.$response->getStatusCode().' '.$response->getReasonPhrase().PHP_EOL;

foreach($response->getHeaders() as $headerName => $headerValues)
    foreach($headerValues as $headerValue)
        echo $headerName.': '.$headerValue.PHP_EOL;

?></pre>

<h3>Response Data</h3>

<div style="max-width:100%;overflow-x:scroll"><?php var_dump(json_decode($response->getBody(), true)); ?></div>