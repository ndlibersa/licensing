<?php
//this script runs the upgrade process in 3 steps
//for the next upgrade the file to be run will need to be detected.

$sql_file = "upgrade_1_11.sql";

//take "step" variable to determine which step the current is
$step = $_POST['step'];


//perform field validation(step 2) and database connection tests (step 2) and send back to previous step if not working
$errorMessage = array();
if ($step == "2"){
	//first, validate all fields are filled in
	$database_host = trim($_POST['database_host']);
	$database_username = trim($_POST['database_username']);
	$database_password = trim($_POST['database_password']);
	$database_name = trim($_POST['database_name']);

	if (!$database_host) $errorMessage[] = 'Host name is required';
	if (!$database_name) $errorMessage[] = 'Database name is required';
	if (!$database_username) $errorMessage[] = 'User name is required';
	if (!$database_password) $errorMessage[] = 'Password is required';

	//only continue to checking DB connections if there were no errors this far
	if (count($errorMessage) > 0){
		$step="1";
	}else{

		//first check connecting to host
		$link = @mysql_connect("$database_host", "$database_username", "$database_password");
		if (!$link) {
			$errorMessage[] = "Could not connect to the server '" . $database_host . "'<br />MySQL Error: " . mysql_error();
		}else{

			//next check that the database exists
			$dbcheck = @mysql_select_db("$database_name");
			if (!$dbcheck) {
				$errorMessage[] = "Unable to access the database '" . $database_name . "'.  Please verify it has been created.<br />MySQL Error: " . mysql_error();
			}else{
				//passed db host, name check, can open/run file now
				//make sure SQL file exists
				$test_sql_file = "test_create.sql";

			    if (!file_exists($test_sql_file)) {
			    	$errorMessage[] = "Could not open sql file: " . $test_sql_file . ".  If this file does not exist you must download new install files.";
			    }else{
					//run the file - checking for errors at each SQL execution
					$f = fopen($test_sql_file,"r");
					$sqlFile = fread($f,filesize($test_sql_file));
					$sqlArray = explode(";",$sqlFile);



					//Process the sql file by statements
					foreach ($sqlArray as $stmt) {
					   if (strlen(trim($stmt))>3){
					   		//replace the DATABASE_NAME parameter with what was actually input
					   		$stmt = str_replace("_DATABASE_NAME_", $database_name, $stmt);

							$result = mysql_query($stmt);
							if (!$result){
								$errorMessage[] = mysql_error() . "<br /><br />For statement: " . $stmt;
								 break;
							}
					    }
					}

				}


				//once this check has passed we can run the entire ddl/dml script
				if (count($errorMessage) == 0){
					if (!file_exists($sql_file)) {
						$errorMessage[] = "Could not open sql file: " . $sql_file . ".  If this file does not exist you must download new install files.";
					}else{
						//run the file - checking for errors at each SQL execution
						$f = fopen($sql_file,"r");
						$sqlFile = fread($f,filesize($sql_file));
						$sqlArray = explode(';',$sqlFile);



						//Process the sql file by statements
						foreach ($sqlArray as $stmt) {
						   if (strlen(trim($stmt))>3){
								//replace the DATABASE_NAME parameter with what was actually input
								$stmt = str_replace("_DATABASE_NAME_", $database_name, $stmt);

								$result = mysql_query($stmt);
								if (!$result){
									$errorMessage[] = mysql_error() . "<br /><br />For statement: " . $stmt;
									 break;
								}
							}
						}

					}
				}

			}
		}

	}

	if (count($errorMessage) > 0){
		$step="1";
	}

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CORAL Installation</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
</head>
<body>
<center>
<table style='width:700px;'>
<tr>
<td style='vertical-align:top;'>
<div style="text-align:left;">


<?php if(!$step){ ?>

	<h3>Welcome to the CORAL Licensing upgrade for Version 1.1!</h3>
	This upgrade will connect to MySQL and run the CORAL Licensing structure changes. No changes to the configuration file are required.  Database structure changes include:
	<ul>
		<li>Renaming Qualification to Qualifier</li>
		<li>Drop qualificationID from Expression</li>
		<li>Add expressionTypeID to Qualifier</li>
		<li>Create table ExpressionQualifierProfile</li>
	</ul>

	<br />

	<span style='color:red'>*Please note* Due to the extent of the change with qualifiers this upgrade will by default remove any qualifier data you have entered.  If you wish to first retrieve a report of existing qualifier data <a href='http://erm.library.nd.edu/' target='_blank'>contact the CORAL team</a> for a script.  Also, the qualifier data can be retained if desired but it will need to be explicitly mapped to the new expression type/qualifier layout first.  Let the <a href='http://erm.library.nd.edu/' target='_blank'>CORAL Team</a> know if you have any questions about this process.</span>

	<br /><br />
	To get started you should have:
	<ul>
		<li>Your MySQL Schema created for CORAL Licensing Module</li>
		<li>Host, username and password for MySQL with permissions to create and alter tables</li>
	</ul>


	<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
	<input type='hidden' name='step' value='1'>
	<input type="submit" value="Continue" name="submit">
	</form>


<?php
//first step - check system info and verify php 5
} else if ($step == '1') {


	if (!$database_host) $database_host='localhost';
	?>
		<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
		<h3>MySQL info with permissions to create and alter tables</h3>
		<?php
			if (count($errorMessage) > 0){
				echo "<span style='color:red'><b>The following errors occurred:</b><br /><ul>";
				foreach ($errorMessage as $err)
					echo "<li>" . $err . "</li>";
				echo "</ul></span>";
			}
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr>
			<tr>
				<td>&nbsp;Database Host</td>
				<td>
					<input type="text" name="database_host" value='<?=$database_host?>' size="30">
				</td>
			</tr>
			<tr>
				<td>&nbsp;Database Schema Name</td>
				<td>
					<input type="text" name="database_name" size="30" value="<?=$database_name?>">
				</td>
			</tr>
			<tr>
				<td>&nbsp;Database Username</td>
				<td>
					<input type="text" name="database_username" size="30" value="<?=$database_username?>">
				</td>
			</tr>
			<tr>
				<td>&nbsp;Database Password</td>
				<td>
					<input type="text" name="database_password" size="30" value="<?=$database_password?>">
				</td>
			</tr>
			<tr>
				<td colspan=2>&nbsp;</td>
			</tr>
			<tr>
				<td align='left'>&nbsp;</td>
				<td align='left'>
				<input type='hidden' name='step' value='2'>
				<input type="submit" value="Continue" name="submit">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" value="Cancel" onclick="document.location.href='index.php'">
				</td>
			</tr>

		</table>
		</form>
<?php
}else if ($step == '2'){ ?>
	<h3>CORAL Licensing upgrade is now complete!</h3>
	It is recommended you now:
	<ul>
		<li>Remove the /install/ directory for security purposes</li>
	</ul>

<?php
}
?>

</td>
</tr>
</table>
<br />
</center>


</body>
</html>
