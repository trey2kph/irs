<?php

$trans_order = html_entity_decode('a:1:{s:32:&quot;09c2c9f5314d4bf719f40ed758b3ddb7&quot;;a:7:{s:5:&quot;rowid&quot;;s:32:&quot;09c2c9f5314d4bf719f40ed758b3ddb7&quot;;s:2:&quot;id&quot;;s:2:&quot;14&quot;;s:3:&quot;qty&quot;;s:1:&quot;5&quot;;s:5:&quot;price&quot;;s:3:&quot;200&quot;;s:4:&quot;name&quot;;s:13:&quot;Sledge Hammer&quot;;s:7:&quot;options&quot;;a:1:{s:4:&quot;unit&quot;;s:5:&quot;piece&quot;;}s:9:&quot;subtottal&quot;;N;}}', ENT_QUOTES);


$trans_order = unserialize($trans_order);

var_dump($trans_order);

?>