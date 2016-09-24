<?php 
$path = '/usr/lib/pear';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

echo include_path ;
?>