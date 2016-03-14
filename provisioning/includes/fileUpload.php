<?php

/* 
 * Simple script to receive a file and echo the content
 * Only text files are allowed
 */


$str = file_get_contents($_FILES["fileupload"]["tmp_name"]);

echo $str;