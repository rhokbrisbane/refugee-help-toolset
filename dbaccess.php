<?php
$title = "SQL Server DB access";


// $serverName = "CHRISTOPHER4B3E\BPSINSTANCE";
// echo  $serverName."<br/><br/>";

// $connectionInfo = array("Database"=>"BPSPatients","UID"=>"bpsrawdata","PWD"=>"123456");
// echo "Connection String: DATABASE=".$connectionInfo["Database"].";UID=".$connectionInfo["UID"].";PWD=".$connectionInfo["PWD"]."<br/>";

$serverName = "DANIEL-PC\BPSINSTANCE";


$connectionInfo = array("Database"=>"BPSSamples","UID"=>"bpsrawdata","PWD"=>"samples");


$conn = sqlsrv_connect($serverName,$connectionInfo);

if ($conn)
{
//	echo "Connection established<br><br>";
}
else
{
//	echo "Connection could not be established";
	exit(print_r(sqlsrv_errors(),true));
}

//$sql = "select * from PATIENTS";

// $sql = "SELECT PATIENTS.SURNAME + ' ' + PATIENTS.FIRSTNAME + ' DOB ' + CONVERT(VARCHAR,CAST(PATIENTS.DOB AS DATE)) + ' ID ' + ':' + CONVERT(varchar,PATIENTS.INTERNALID) AS Result FROM PATIENTS ORDER BY dbo.PATIENTS.SURNAME";

// $stmt = sqlsrv_query($conn,$sql);

// if ($stmt === false)
// {
// 	exit(print_r(sqlsrv_errors(),true));
// }

// while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC))
// {
// 	//echo $row ["FIRSTNAME"]." ".$row["MIDDLENAME"]." ".$row["SURNAME"]."<br/>";
// 	echo $row ["Result"]."<br/>";

// }

 ?>