<?php

$searchterm = $_REQUEST["q"];

$title = "SQL Server DB access";
//echo  $title."<br>";

$serverName = "TOBY-SURFACE\BPSINSTANCE";
//echo  $serverName."<br/><br/>";

$connectionInfo = array("Database"=>"BPSSamples","UID"=>"bpsrawdata","PWD"=>"samples");
//echo "Connection String: DATABASE=".$connectionInfo["Database"].";UID=".$connectionInfo["UID"].";PWD=".$connectionInfo["PWD"]."<br/>";

$conn = sqlsrv_connect($serverName,$connectionInfo);
if ($conn) {

	//echo "Connection established<br><br>";

} else {

	//echo "Connection could not be established";
	exit();

}

$sql = "SELECT * FROM PATIENTS WHERE SURNAME LIKE '%$searchterm%' OR FIRSTNAME LIKE '%$searchterm%'";
//echo $sql;
$result = sqlsrv_query($conn, $sql);
if ($result == false) {
	sqlsrv_close($conn);
	//echo "query error";
	exit();
}

$resultArray = array();

while ($row = sqlsrv_fetch_array($result)) {
	//echo $row['EXTERNALID']." AND ".$row['FIRSTNAME'];
	$firstname = $row['FIRSTNAME'];
	$lastname = $row['SURNAME'];
	$internalID = $row['INTERNALID'];
	$dob = $row['DOB']->format("y-m-d");
	$string = $lastname.", ".$firstname." (patient ID#" .$internalID.")";
	array_push($resultArray, $string);
}

echo json_encode($resultArray);

sqlsrv_close($conn);

 ?>