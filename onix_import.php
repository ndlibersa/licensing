<?php
	function searchForShortName($shortName, $array)
	{
		foreach($array as $key=> $val)
		{
			if(strtolower($val['shortName']) == strtolower($shortName)) {
				return $key;
				break;
			}
		}
		return null;
	}
	session_start();
	include_once 'directory.php';
	$pageTitle=_('ONIX-PL Import');
	include 'templates/header.php';
?>
<div id="onixImportPage"><h1><?php echo _("ONIX-PL Import");?></h1>
<?php
	// CSV configuration
	if ($_POST['submit'])
	{
		$expressionTypeInserted = 0;
		$qualifierInserted = 0;

		$uploaddir = 'attachments/';
		$uploadfile = $uploaddir . basename($_FILES['uploadFile']['name']);
		if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadfile))
		{  
			print '<p>'._("The file has been successfully uploaded.").'</p>';
			// Let's analyze this file
			if(($xml = simplexml_load_file($uploadfile)) !== FALSE)
			{
				//get all expression types
				$expressionTypeArray = array();
				$expressionTypeObj = new ExpressionType();
				$expressionTypeArray = $expressionTypeObj->allAsArray();

				$licenseAgreement = "";
				foreach($xml->LicenseDocumentText->TextElement as $licensetext)
				{
					$licenseAgreement .= $licensetext->Text . "\n\n";
				}

				//Save License Agreement and get IDs
				print $xml->LicenseDetail->Description;
				$licenseFile = fopen("documents/" . trim($xml->LicenseDetail->Description) . ".txt", "wb") or die (_("Unable to create file for license."));
				fwrite($licenseFile, $licenseAgreement);
				fclose($licenseFile);


				if($_POST['usageTerms'] === "on")
				{
					foreach($xml->UsageTerms->Usage as $usage)
					{
						//get the expressionTypeID -- create expressionType if necessary
						$expression = preg_replace('/^onixPL\:/s','',$usage->UsageType);
						$index = searchForShortName($expression, $expressionTypeArray);
						if($index !== null)
						{
							$expressionTypeID = $expressionTypeArray[$index]['expressionTypeID'];
							$expressionTypeObj = new ExpressionType(new NamedArguments(array('primaryKey' => $expressionTypeID)));
						}
						else
						{
							$expressionTypeObj = new ExpressionType();
							$expressionTypeObj->shortName = $expression;
							$expressionTypeObj->noteType = "Internal";
							$expressionTypeObj->save();
							$expressionTypeID = $expressionTypeObj->primaryKey;
							$expressionTypeArray = $expressionTypeObj->allAsArray();
							$expressionTypeInserted++;
						}

						//get the qualifierID -- create qualifier if necessary
						//var_dump($expressionTypeObj);
						$expressionQualifiers = $expressionTypeObj->getQualifiers();
						$qualifier = preg_replace('/^onixPL\:/s','',$usage->UsageStatus);
						$qualifierID = -1;
						foreach($expressionQualifiers as $expressionQualifier)
						{
							if(strtolower($expressionQualifier->shortName) == strtolower($qualifier))
							{
								$qualifierID = $expressionQualifier->qualifierID;
								break;
							}
						}
						if($qualifierID === -1)
						{
							$qualifierObj = new Qualifier();
							$qualifierObj->expressionTypeID = $expressionTypeID;
							$qualifierObj->shortName = $qualifier;
							$qualifierObj->save();
							$qualifierID = $qualifierObj->primaryKey;
							$qualifierInserted++;
						}
					}

				}
				echo "<p>" . $expressionTypeInserted . _(" Expression Type(s) Created") . "</p>";
				echo "<p>" . $qualifierInserted . _(" Qualifiers Created") . "</p>";
			}
			else
			{
				$error = _("Cannot create XML object");
			}
		}
		else
		{
			$error = _("Unable to upload the file");
		}
		if ($error)
		{
			print "<p>"._("Error: ").$error.".</p>";
		}
		else
		{
		}
	}
	elseif ($_POST['matchsubmit'])
	{
	}
	else
	{
?>
		<form enctype="multipart/form-data" action="onix_import.php" method="post" id="importForm">
			<fieldset>
				<legend><?php echo _("File selection");?></legend>
				<label for="uploadFile"><?php echo _("CSV File");?></label>
				<input type="file" name="uploadFile" id="uploadFile" />
			</fieldset>
			<fieldset>
				<legend><?php echo _("Import options");?></legend>
				<div id='importOptions'>
					<input id='usageTerms' name='usageTerms' type='checkbox'><label for='usageTerms'>Usage Terms</label><br/>
					<input id='supplyTerms' name='supplyTerms' type='checkbox'><label for='supplyTerms'>Supply Terms</label><br/>
					<input id='continuingAccessTerms' name='continuingAccessTerms' type='checkbox'><label for='continuingAccessTerms'>Continuing Access Terms</label><br/>
					<input id='paymentTerms' name='paymentTerms' type='checkbox'><label for='paymentTerms'>Payment Terms</label><br/>
					<input id='generalTerms' name='generalTerms' type='checkbox'><label for='generalTerms'>General Terms</label><br/>
				</div>
			</fieldset>
			<input type="submit" name="submit" value="<?php echo _("Upload");?>" class="submit-button" />
		</form>
<?php
	}
?>

