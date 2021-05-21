<?php

include ("adodb5/adodb.inc.php");
$db = NewADOConnection('Mysqli');
$db -> Connect("localhost", "root", "", "pagos");
?>