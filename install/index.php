<?php
include_once 'CORALInstaller.php';
$installer = new CORALInstaller();

if (!$installer->installed()) {
  header('Location: install.php');
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CORAL Upgrades</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
</head>
<body>
<div style="width:700px;margin-left:auto;margin-right:auto;text-align:left;">
  
  <h3>CORAL Licensing</h3>
	<p>Your CORAL Licensing Module is correctly installed and there are no pending upgrades.</p>

</div>
</body>
</html>