
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Refimm</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/stylesheet.css" rel="stylesheet">
</head>
<body>

<div class="main">
	<div class="container">
    <img src="assets/logos/IPC.png" class="img-fluid" alt="Inala Primary Care Logo">
    <h1>Immunisation Schedules</h1>
		<form class="form-horizontal" method="POST" action="formPreview.php">

			<!-- search field -->
      <div class="container">
  			<div class="form-group">
          <h2>Search for schedule by patient name</h2>
  				<label class="control-label col-sm-2" for="patientName">Patient name:</label>
  				<input type="text" id="patientName" name="patientName" class="content" onkeyup="searchPatient(this.value)" onblur="closeSuggestions()"/><br>
  			</div>
      </div>

			<!-- search results -->
			<div>
				<ul class="list-group" id="livesearch">
					<!--Displays suggestions according to the user typing -->
				</ul>
			</div>

			<input id="patientID" name="patientID" hidden="true"></input>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-standard" id="submitButton">Preview</button>
				</div>
			</div>
		</form>
	</div>

</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

<script>
/*
Does a live search of the database.
*/
function searchPatient(str) {

	if (str.length==0) {
		$("#livesearch").empty();
		$("#livesearch").hide();
		return;
	}
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
			//alert(this.responseText);

			//Convert response text to list and pass to
			//"displaySearch" method

			var list = JSON.parse(this.responseText);
			displaySearch(list);

		}
	};
	xmlhttp.open("GET","searchPatients.php?q="+str,true);
	xmlhttp.send();
};

/*
Display the livesearch box with a list
*/
function displaySearch(list) {

	$("#livesearch").empty();
	$("#livesearch").show();

	for (i=0; i<list.length; i++) {

		var result = list[i];
		var listItem = "<li class='list-group-item' onclick='selectResult(this)'>" +
			result + "</li>";
		$("#livesearch").append(listItem);

	}
}

/*
Sets the patientName field to the text contained in the clickedElement.
Copies the patient ID to the hidden ID field.
*/
function selectResult(clickedElement) {

	$("#patientName").val(clickedElement.innerText);
	var patientString = $("#patientName").val();
	var idIndexStart = patientString.indexOf("#");
	var idIndexEnd = patientString.indexOf(")");
	var patientID = patientString.substr(idIndexStart+1, (idIndexEnd - idIndexStart - 1));
	$("#patientID").val(patientID);
	//$("#submitButton").prop("disabled", false);

}

/*closes the livesearch results box when the user clicks out of the Company field
hide the livesearch element (after a delay, to give time for the user to select an item
from the livesearch box).
*/
function closeSuggestions() {

	setTimeout(function(){$("#livesearch").slideUp();}, 300);
}


</script>

</body>
</html>
