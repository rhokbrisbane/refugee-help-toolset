<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Immunisation Schedule Preview</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/stylesheet.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- print function -->
    <script>
      function printForm() {
          window.print();
      }
    </script>
</head>
<?php
require_once ('dbaccess.php');

$pID = $_POST["patientID"];

//query to get all patients data
$sqlPatient = "SELECT PATIENTS.SURNAME, 
PATIENTS.FIRSTNAME, 
PATIENTS.ADDRESS1, 
PATIENTS.DOB, 
PATIENTS.SEXCODE, 
PATIENTS.RECORDNO 
FROM BPSPatients.dbo.PATIENTS 
WHERE PATIENTS.INTERNALID = " . $pID;// . $_POST["patientID"];

$stmtPatient = sqlsrv_query($conn,$sqlPatient);

if ($stmtPatient === false)
{
	exit(print_r(sqlsrv_errors(),true));
}

while ($rowPatient = sqlsrv_fetch_array($stmtPatient,SQLSRV_FETCH_ASSOC))
{
	echo $rowPatient["FIRSTNAME"]." ".$rowPatient["SURNAME"]."<br/>";
	
}


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
  BPSPatients.dbo.IMMUNISATIONS.RECORDSTATUS <> 0 AND BPSPatients.dbo.IMMUNISATIONS.INTERNALID = " . $pID;

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

// require_once ('dbaccess.php');
//
// $sql = "SELECT PATIENTS.SURNAME + ' ' + PATIENTS.FIRSTNAME + ' DOB ' + CONVERT(VARCHAR,CAST(PATIENTS.DOB AS DATE)) + ' ID ' + ':' + CONVERT(varchar,PATIENTS.INTERNALID) AS Result FROM PATIENTS ORDER BY dbo.PATIENTS.SURNAME";
//
// $stmt = sqlsrv_query($conn,$sql);
//
// if ($stmt === false)
// {
// 	exit(print_r(sqlsrv_errors(),true));
// }
//
// while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC))
// {
// 	//echo $row ["FIRSTNAME"]." ".$row["MIDDLENAME"]." ".$row["SURNAME"]."<br/>";
// 	echo $row ["Result"]."<br/>";
//
// }


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

<!-- action buttons -->
<div class="container">
  <div class="table" id="tools">
    <button type="button" class="btn btn-upload" data-toggle="modal" data-target="#myModal">Upload to Best Practice</button>
    <button onclick="printForm()" type="button" class="btn btn-print">Print</button>
  </div>
</div>

<!-- pop-up confirm modal -->
<!-- Example from w3schools.com -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <h3>Are you sure you want to upload this form to Best Practice?</h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Yes</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>

<!--header for form -->
<div class="container">
  <div class="table" id="header">
    <img src="assets/logos/IPC.png" class="img-fluid" alt="Inala Primary Care Logo">
  	<p id="companyName">Inala Primary Care</p>
  	<p id="companyAddress">64 Wirraway Pde INALA 4064</p>
  	<p id="companyPhone">07 3275 5444</p>
  </div>
</div>

<!--page heading for form-->
<div class="container">
  <div class="table" id="headingBox">
  	<h2 id="formTitle">Refugee Immunisation Catch-up Advisory Schedule</h2>
  	<h3 id="ageGroup">10 - 19 Years</h3>
  </div>
</div>

<!--patient info section-->
<?php
	echo('<div class="container">
  <div class="table" id="patientInfo">
    <h2>Patient Details</h2>
    <div class=".col-md-6">
        <label>Name:</label>
        <input id="PtFullName"></input>
        <label>Age:</label>
        <input id="PtAge"></input>
        <label>Address:</label>
        <input id="PtAddress"></input>
    </div>
    <div class=".col-md-6">
        <label>DOB:</label>
        <input id="PtDoB"></input>
        <label>Patient Record No.:</label>
        <input id="PtRecordNo"></input>
        <label>Sex:</label>
        <input id="PtSex"></input>
    </div>
  </div>
</div')
?>
	

<!--Dosage section-->
<div class="container">
  <div class="table">
    <table class="table" id="dosageTable">
      <h2>Dosage Record</h2>
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
  </div>
</div>


<!--timeline table-->
<div class="container">
  <table class="table" id="timelineTable">
    <h2>Vaccination Schedule</h2>
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
  		<td colspan="6" id="notes1">#  HPV can be ordered from QHIP for males and females, if year 8 school dose has been missed.  * At least 1 dose Varicella if child aged < 14 years however if child is ≥ 14 years at first dose, give second dose, 4 weeks after 1st dose  -    as per Australian Standard Vaccination Schedule- 10th Edition</td>
  	</tr>
  	<tr>
  		<td colspan="6" id="notes2">Record on VIVAS as Refugee Catch-up</td>
  	</tr>
  </table>
</container>

<div class="container">
  <table class="table" id="additional-notes">
    <h2>Additional Notes</h2>
    <form>
      <div class="form-group">
        <textarea class="form-control" rows="5" id="comment"></textarea>
      </div>
    </form>
  </table
</div>

</body>
</html>
