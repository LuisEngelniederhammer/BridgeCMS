<?php
session_start ();
?>
<html>
<head>

<?php
?>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

<script type="text/javascript">
function checkInstallation()
{
	$(".step1").append("<img src='data/load.svg'>Checking the current system, please wait...<br>");
	$.ajax({
		  method: "POST",
		  url: "checkInstallation.php",
		  data: {  }
		})
		  .done(function( msg ) {
			var tmp = JSON.parse(msg);
			
		    $(".step1").html(tmp.msg);
		    if ( tmp.code == "VALID_CFG" ) 
			    {
		    	$(".step3_box").css("display", "block");
		    	step3();
			    } 
		    else 
			    {
			    	$(".step2_box").css("display", "block");
			    }
		    
			    
		  });
	}
	
$(document).ready(function(){ checkInstallation(); });

function step2()
{
	$("input").prop('disabled', true);
	$("#step2_start").css("display", "none");

    $(".step3_box").css("display", "block");
    $(".step3").append("<img src='data/load.svg'>Configuration -> generating");


    	var _host = $("input[type=text][name=host]").val();
    	var _database = $("input[type=text][name=database]").val();
    	var _username = $("input[type=text][name=user]").val();
    	var _password = $("input[type=text][name=password]").val();
    	var _prefix = $("input[type=text][name=prefix]").val();
    	
    $.ajax({
    	  method: "POST",
    	  url: "createCFG.php",
    	  data: { host: _host, database: _database, username: _username, username: _username, password: _password,  prefix: _prefix }
    	}) 
    	  .done(function( msg ) {
    	    $(".step3").append(msg);
    	    step3();
    	  });
    
}
function step3()
{    	

    $(".step3").html("<img src='data/load.svg'><br>Configuration -> done");
    $(".step3").append("<br>Database -> generating (This may take some minutes, do not close the browser!)<br>");
    
    $.ajax({
  	  method: "POST",
  	  url: "writeDB.php",
  	  data: {}
  	}) 
  	  .done(function( msg ) {
  	    $(".step3").append(msg);
  	    
  	  
  		$(".step1_box").css("display", "none");
  		$(".step2_box").css("display", "none");
  		$(".step3_box").css("display", "none");
  		
  		$("input").prop('disabled', false);
  		
  		$(".step4_box").css("display", "block");
  
  	  });
	}
	
function step4()
{
	var _admin_password = $("input[type=password][name=admin_password]").val();
	var _admin_user = $("input[type=text][name=admin_user]").val();
	$("input").prop('disabled', true);
	$(".step4").html("<img src='data/load.svg'>");
	$.ajax({
	  	  method: "POST",
	  	  url: "writeDefault.php",
	  	  data: { admin_user: _admin_user, admin_password: _admin_password, }
	  	}) 
	  	  .done(function( msg ) {
	  		$(".step4").append(msg);
	  		$(".step4").html("<a href='../index.php'><button>Finish</button></a>").fadeIn(1000);
	  	  });
	}
</script>
<link rel="stylesheet" type="text/css"
	href="../style_templates/default/main_backend.css">

<style type="text/css">
input[type=text], input[type=password] {
	width: 100%;
}

table {
	border-collapse: collapse;
	width: 100%;
}

th, td {
	text-align: left;
	padding: 8px;
	height: 50px;
	background-color: #808080;
	width: 100px;
}

tr:hover {
	background-color: #c0c0c0;
}
</style>
</head>
<body>
	<div id="content_box" class="step1_box">
		<div class="title gold">Installer 1/4</div>
		<div class="content step1"></div>
	</div>

	<div id="content_box" class="step2_box" style="display: none;">
		<div class="title gold">Installer 2/4</div>
		<div class="content step2">
			Create a MySQL database on your hostservice and enter the received
			informations below:<br>

		<table>
				<tr>
					<td>Host:</td>
					<td><input type="text" name="host"></td>
				</tr>
				<tr>
					<td>Database:</td>
					<td><input type="text" name="database"></td>
				</tr>
				<tr>
					<td>User:</td>
					<td><input type="text" name="user"></td>
				</tr>
				<tr>
					<td>Password:</td>
					<td><input type="text" name="password"></td>
				</tr>
				<tr>
					<td>Prefix:</td>
					<td><input type="text" name="prefix" value="<?php echo uniqid("cms_");?>"></td>
				</tr>

			</table>
			<div>
				<button id="step2_start" onClick="step2()">Continue</button>
			</div>

		</div>
	</div>

	<div id="content_box" class="step3_box" style="display: none">
		<div class="title gold">Installer 3/4</div>
		<div class="content step3"></div>
	</div>
	
	<div id="content_box" class="step4_box" style="display: none">
		<div class="title gold">Installer 4/4</div>
		<div class="content step4">
			<br> Enter the informations for the default administrator account:
			<table>
				<tr>
					<td>Admin username:</td>
					<td><input type="text" name="admin_user"></td>
				</tr>
				<tr>
					<td>Admin password:</td>
					<td><input type="password" name="admin_password"></td>
				</tr>
			</table>
			<br>
			<div>
				<button id="step4_start" onClick="step4()">Continue</button>
			</div>
		</div>
	</div>
</body>
</html>
