
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
		<div class="Jumbotron">
          <h1>Create immunisation schedule</h1>
          <h2>Enter patient name:</h2>
        </div>
		<form class="form-horizontal" method="POST" action="retrievePatient.php">

			<!-- search field -->
			<div class="form-group">
				<label class="control-label col-sm-2" for="patientName">Patient name:</label> 
				<div class="col-sm-10">  
					<input type="text" id="patientName" name="patientName" class="content" onblur="searchPatient(this.value)"/><br>
				</div>
			</div>
			
			<!-- search results -->
			<div>
				<ul class="list-group" id="livesearch">
					<!--Displays suggestions according to the user typing -->
				</ul>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">  
					<button type="submit" class="btn btn-default">Preview</button>
				</div>
			</div>
		</form>
	</div>

</div>

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
		
			//Convert response text to list and pass to
			//"displaySearch" method
			var list = JSON.parse(this.responseText);
			displaySearch(list);
		
		}
	};
	xmlhttp.open("GET","https://localhost/inalaprimarycare/serverscripts/searchPatients.php?q="+str,true);
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
Sets the patientName field to the text contained in the clickedElement
*/
function selectResult(clickedElement) {

	alert('text is ' + clickedElement.innerText);
	$("#patientName").val(clickedElement.innerText);

}

</script>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

</body>
</html>