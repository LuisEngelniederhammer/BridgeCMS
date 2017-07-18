<?php
session_start ();
require_once '../system/core.class.php';
require_once SYSTEM_ROOT . '/system/stylemgr.class.php';
use system\styleManager;
$styleMgr = new styleManager ();
?>
<html>

<head>
<?php
$styleMgr->parseCustomStyle ( "login.backend" );
?>
</head>

<body>
	<div id="login_wrapper">
		<div class="titel">ACP Login</div>
		<div class="content">
			<form action="login.php" method="POST">
				<label>Username:<br></label> 
				<input type="text" name="username" placeholder="Username..." required> 
				<label>Password:<br></label>
				<input type="password" name="password" placeholder="Password..." required>
				
				<input type="submit" value="submit">
			</form>
			<?php 
			if (isset($_GET['code']))
			{
				echo $_GET['code'];
			}
			?>
		</div>
	</div>

</body>

</html>