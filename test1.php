<?php

$content = file_get_contents("over.txt");


var_dump(substr($content, 0, 10000));
