<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Immunisation Schedule Preview</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/stylesheet.css" rel="stylesheet">
</head>
<?php
require_once ('dbaccess.php');

// //query to get all patients
// $sqlx = "SELECT PATIENTS.SURNAME + ' ' + PATIENTS.FIRSTNAME + ' DOB ' + CONVERT(VARCHAR,CAST(PATIENTS.DOB AS DATE)) + ' ID ' + ':' + CONVERT(varchar,PATIENTS.INTERNALID) AS Result FROM PATIENTS ORDER BY dbo.PATIENTS.SURNAME";

// $stmtx = sqlsrv_query($conn,$sqlx);

// if ($stmtx === false)
// {
// 	exit(print_r(sqlsrv_errors(),true));
// }

// while ($row = sqlsrv_fetch_array($stmtx,SQLSRV_FETCH_ASSOC))
// {
// 	//echo $row ["FIRSTNAME"]." ".$row["MIDDLENAME"]." ".$row["SURNAME"]."<br/>";
// 	echo $row ["Result"]."<br/>";

// }


//query to get all of a patients
// $sql = "SELECT * FROM BPSPatients.dbo.IMMUNISATIONS";

$sql = "SELECT
  BPSPatients.dbo.IMMUNISATIONS.INTERNALID,
  BPSPatients.dbo.IMMUNISATIONS.RECORDID,
  BPSPatients.dbo.IMMUNISATIONS.RECORDSTATUS,
  BPSPatients.dbo.IMMUNISATIONS.VACCINEID,
  BPSPatients.dbo.IMMUNISATIONS.GIVENDATE
FROM
  BPSPatients.dbo.IMMUNISATIONS
WHERE
  BPSPatients.dbo.IMMUNISATIONS.RECORDSTATUS <> 0 AND BPSPatients.dbo.IMMUNISATIONS.INTERNALID = 10;
";

$stmt = sqlsrv_query($conn,$sql);

if ($stmt === false)
{
	exit(print_r(sqlsrv_errors(),true));
}

while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC))
{
	//echo $row ["FIRSTNAME"]." ".$row["MIDDLENAME"]." ".$row["SURNAME"]."<br/>";
	echo ("internalID: " . $row ["INTERNALID"] .  //int
	", RecordID: " . $row["RECORDID"] .    //int
	", RecordStatus: " . $row["RECORDSTATUS"] .   //int
	", VaccineID: " . $row["VACCINEID"] .   //int  
	", Given Date: " . $row["GIVENDATE"]->format('d/m/Y') . "<br/>"); //string
}



$drug = "SELECT
  BPSDrugs.dbo.VACCINES.VACCINEID,
  BPSDrugs.dbo.VACCINES.VACCINENAME,
  BPSDrugs.dbo.VAXDISEASES.DISEASENAME
FROM
  BPSDrugs.dbo.VACCINES
  INNER JOIN BPSDrugs.dbo.VACCINE_DISEASE ON BPSDrugs.dbo.VACCINE_DISEASE.VACCINEID = BPSDrugs.dbo.VACCINES.VACCINEID
  INNER JOIN BPSDrugs.dbo.VAXDISEASES ON BPSDrugs.dbo.VACCINE_DISEASE.DISEASECODE = BPSDrugs.dbo.VAXDISEASES.DISEASECODE
ORDER BY
  VAXDISEASES.DISEASENAME
";

$stmtDrug = sqlsrv_query($conn,$drug);

$HepBVaccineID = array();
$ADTdToaVaccineID = array();
$IPVVaccineID = array();
$MMRVaccineID = array();
$VZVVaccineID = array();
$HPVVaccineID = array();
$BCGVaccineID = array();
$MenACWYVaccineID = array();

if ($stmtDrug === false)
{
	exit(print_r(sqlsrv_errors(),true));
}

while ($rowX = sqlsrv_fetch_array($stmtDrug,SQLSRV_FETCH_ASSOC))
{
	// echo ("Vaccine ID: " . $rowX ["VACCINEID"] .  //int
	// ", Vaccine Name: " . $rowX["VACCINENAME"] .    //int
	// ", Disease Name: " . $rowX["DISEASENAME"]) . '<br>';
	if($rowX["DISEASENAME"] == "Hepatitis B                   "){
		array_push($HepBVaccineID, $rowX["VACCINEID"]);
	} elseif ($rowX["DISEASENAME"] == "Diphtheria                    " || $rowX["DISEASENAME"] == "Tetnus                        "){
		array_push($ADTdToaVaccineID, $rowX['VACCINEID']);
	} elseif ($rowX["DISEASENAME"] == "Poliomyelitis                 ") {
		array_push($IPVVaccineID, $rowX['VACCINEID']);
	} elseif ($rowX["DISEASENAME"] == "Measels                       " || $rowX["DISEASENAME"] == "Mumps                         " || $rowX["DISEASENAME"] == "Rubella                                 ") {
		array_push($MMRVaccineID, $rowX["VACCINEID"]);
	} elseif ($rowX["DISEASENAME"] == "Varicella-Zoster              ") {
		array_push($VZVVaccineID, $rowX["VACCINEID"]);
	} elseif ($rowX["DISEASENAME"] == "HPV                           ") {
		array_push($HPVVaccineID, $rowX["VACCINEID"]);
	} elseif ($rowX["DISEASENAME"] == "Tuberculosis                  ") {
		array_push($BCGVaccineID, $rowX["VACCINEID"]);
	} elseif ($rowX["DISEASENAME"] == "Meningococcus ACWY            ") {
		array_push($MenACWYVaccineID, $rowX['VACCINEID']);
	}
}

echo "Hep B: ";
print_r($HepBVaccineID);

echo "<br> ADT/dTpa: ";

print_r($ADTdToaVaccineID);

echo "<br> IPV: ";

print_r($IPVVaccineID);

echo "<br> MMR: ";

print_r($MMRVaccineID);

echo "<br> VZV: ";

print_r($VZVVaccineID);

echo "<br> HPV: ";

print_r($HPVVaccineID);

echo "<br> BCG: ";

print_r($BCGVaccineID);

echo "<br> MenACWY: ";

print_r($MenACWYVaccineID);








?>


<body>

<!--header for form -->
<div id="header">
	<p id="companyName">Inala Primary Care</p>
	<p id="companyAddress">64 Wirraway Pde INALA 4064</p>
	<p id="companyPhone">07 3275 5444</p>
</div>

<!--page heading for form-->
<div id="headingBox">
	<p id="formTitle">REFUGEE IMMUNISATION CATCH-UP ADVISORY SCHEDULE – </p>
	<p id="ageGroup">10 - 19 YEARS</p>
</div>

<!--patient info section-->
<div id="patientInfo">
	<label>NAME:</label>
	<input id="PtFullName"></input>
	
	<label>AGE:</label>
	<input id="PtAge"></input>
	
	<label>ADDRESS:</label>
	<input id="PtAddress"></input>
	
	<label>DOB:</label>
	<input id="PtDoB"></input>
	
	<label>PATIENT RECORD NO:</label>
	<input id="PtRecordNo"></input>

	<label>SEX:</label>
	<input id="PtSex"></input>
</div>

<br>

<!--Dosage section-->
<table id="dosageTable" class="table">
	<tr>
		<th>Dose</th>
		<th>1</th>
		<th>2</th>
		<th>3</th>
		<th>COMMENTS</th>
	</tr>
	<tr>
		<td>Antigen</td>
		<td colspan="5">For recording previously given vaccinations enter date given and leave blank for ones still required</td>
	</tr>
	<tr>
		<td>Hep B</td>
		<td><input id="hepBdate1" type="date"></input></td>
		<td><input id="hepBdate2" type="date"></input></td>
		<td><input id="hepBdate3" type="date"></input></td>
		<td></td>
	</tr>
	<tr>
		<td>ADT/dTpa</td>
		<td><input id="ADTdate1" type="date"></input></td>
		<td><input id="ADTdate2" type="date"></input></td>
		<td><input id="ADTdate3" type="date"></input></td>
		<td></td>
	</tr>
	<tr>
		<td>IPV</td>
		<td><input id="IPVdate1" type="date"></input></td>
		<td><input id="IPVdate2" type="date"></input></td>
		<td><input id="IPVdate3" type="date"></input></td>
		<td></td>
	</tr>
	<tr>
		<td>MMR</td>
		<td><input id="MMRdate1" type="date"></input></td>
		<td><input id="MMRdate2" type="date"></input></td>
		<td><input id="MMRdate3" type="date"></input></td>
		<td>Refer to TB clinicafter 2nd MMR</td>
	</tr>
	<tr>
		<td>VZV*</td>
		<td><input id="VZVdate1" type="date"></input></td>
		<td><input id="VZVdate2" type="date"></input></td>
		<td><input id="VZVdate3" type="date"></input></td>
		<td>School based vaccination program only</td>
	</tr>
	<tr>
		<td>HPV#</td>
		<td><input id="HPVdate1" type="date"></input></td>
		<td><input id="HPVdate2" type="date"></input></td>
		<td><input id="HPVdate3" type="date"></input></td>
		<td>School based vaccination program only</td>
	</tr>
	<tr>
		<td>BCG</td>
		<td><input id="BCGdate1" type="date"></input></td>
		<td><input id="BCGdate2" type="date"></input></td>
		<td><input id="BCGdate3" type="date"></input></td>
		<td>Refer to Metro South Clinical TB Service</td>
	</tr>
	<tr>
		<td>MenACWY</td>
		<td><input id="MenACWYdate1" type="date"></input></td>
		<td><input id="MenACWYdate2" type="date"></input></td>
		<td><input id="MenACWYdate3" type="date"></input></td>
		<td>School based vaccination (Yr10) OR catch up for 15-19 year olds with GP</td>
	</tr>
</table>

<br>

<!--timeline table-->
<table id="timelineTable" class="table">
	<tr>
		<th>Time Due</th>
		<th>Date</th>
		<th>Vaccinations Needed</th>
		<th>Batch</th>
		<th>Site</th>
		<th>Vaccinator</th>
	</tr>
	<!--Now row-->
	<tr>
		<td>Now</td>
		<td><input id="timelineDate1" type="date"></input></td>
		<td>
			<ul>
				<li>Paediatric Hep B</li>
				<li>Boostrix</li>
				<li>IPV</li>
				<li>MMRV</li>
			</ul>
		</td>
		<td><input id="batch1" type="text"></input></td>
		<td><input id="site1" type="text"></input></td>
		<td>Checked:</td>
	</tr>
	<!--4 month row-->
	<tr>
		<td>1 month from last</td>
		<td><input id="timelineDate2" type="date"></input></td>
		<td>
			<ul>
				<li>Paediatric Hep B</li>
				<li>ADT</li>
				<li>IPV</li>
				<li>MMR</li>
			</ul>
		</td>
		<td><input id="batch2" type="text"></input></td>
		<td><input id="site2" type="text"></input></td>
		<td>Checked:</td>
	</tr>
	<!--6 months row-->
	<tr>
		<td>3 months from last</td>
		<td><input id="timelineDate3" type="date"></input></td>
		<td>
			<ul>
				<li>Paediatric Hep B</li>
				<li>ADT</li>
				<li>IPV</li>
				<li>MenC</li>
			</ul>
		</td>
		<td><input id="batch3" type="text"></input></td>
		<td><input id="site3" type="text"></input></td>
		<td>Checked:</td>
	</tr>
	<!--table notes row 1-->
	<tr>
		<td colspan="6" id="notes1">#  HPV can be ordered from QHIP for males and females, if year 8 school dose has been missed.  * At least 1 dose Varicella if child aged <14 years however if child is ≥14 years at first dose, give second dose, 4 weeks after 1st dose  -    as per Australian Standard Vaccination Schedule- 10th Edition</td>
	</tr>
	<tr>
		<td colspan="6" id="notes2">Record on VIVAS as Refugee Catch-up</td>
	</tr>
</table>

</body>
</html>
