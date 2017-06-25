<!DOCTYPE html>
<html>
<body>

<?php

$serverName = "DANIEL-PC\BPSINSTANCE";
echo  $serverName."<br/><br/>";

$connectionInfo = array("Database"=>"BPSSamples","UID"=>"bpsrawdata","PWD"=>"samples");
echo "Connection String: DATABASE=".$connectionInfo["Database"].";UID=".$connectionInfo["UID"].";PWD=".$connectionInfo["PWD"]."<br/>";

$conn = sqlsrv_connect($serverName,$connectionInfo);

if ($conn)
{
	echo "Connection established<br><br>";
}
else
{
	echo "Connection could not be established";
	exit(print_r(sqlsrv_errors(),true));
}

$sql = "select * from PATIENTS";

$stmt = sqlsrv_query($conn,$sql);

if ($stmt === false)
{
	exit(print_r(sqlsrv_errors(),true));
}

while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC))
{
	echo $row ["FIRSTNAME"]." ".$row["MIDDLENAME"]." ".$row["SURNAME"]."<br/>";
}

 ?>

</body>
</html> 