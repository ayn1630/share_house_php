<?php
$tpl = <<<'EOS'
こんにちは$hello
        
EOS;
$hello = "やっほお";
eval("\$format = \"$tpl\";");
print $format;
