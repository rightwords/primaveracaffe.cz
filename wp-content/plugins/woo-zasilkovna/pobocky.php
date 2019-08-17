<?php 


error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('max_execution_time', 0);
//ini_set('memory_limit', '512M');



$dir =  dirname(dirname(dirname(dirname(__FILE__))));

require_once($dir.'/wp-load.php');   
require_once($dir.'/wp-includes/option.php');

Zasilkovna_Pobocky::get_branches();

exit();    