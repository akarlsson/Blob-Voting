<?php	
$queries = DB::getQueryLog();	
$last_query = end($queries);
var_dump($last_query);
 ?>