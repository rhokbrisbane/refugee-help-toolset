<?php

$searchterm = $_REQUEST["q"];

require_once ('dbaccess.php');


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