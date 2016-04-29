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

				$textArray = array();
				$licenseAgreement = "";
				foreach($xml->LicenseDocumentText->TextElement as $licenseText)
				{
					$licenseAgreement .= (string)$licenseText->Text . "\n\n";
					if((string)$licenseText['id'] !== "")
					{
						$textArray[(string)$licenseText['id']]=(string)$licenseText->Text;
					}
				}

				//Save License Agreement to documents directory, create license, attachment, and attachmentFile records and get IDs
				$filename = trim($xml->LicenseDetail->Description);
				print $xml->LicenseDetail->Description;
				$licenseFile = fopen("documents/" . $filename . ".txt", "wb") or die (_("Unable to create file for license."));
				fwrite($licenseFile, $licenseAgreement);
				fclose($licenseFile);
				$licenseObj = new License();
				$licenseObj->shortName = $filename;
				$licenseObj->organizationID = $_POST['licenseOrganizationID'];
				$licenseObj->save();
				$licenseID = $licenseObj->primaryKey;
				$documentObj = new Document();
				$documentObj->shortName = $filename;
				$documentObj->documentTypeID = 3;
				$documentObj->licenseID = $licenseID;
				$documentObj->documentURL = $filename . ".txt";
				$documentObj->save();
				$documentID = $documentObj->primaryKey;

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
						$expressionObj = new Expression();
						$expressionObj->documentID = $documentID;
						$expressionObj->expressionTypeID = $expressionTypeID;
						$expressionText = "";
						foreach($usage->LicenseTextLink as $licenseTextLink)
						{
							$expressionText .= $textArray[(string)$licenseTextLink["href"]] . "\n\n";
						}
						$expressionObj->documentText = $expressionText;
						$expressionObj->lastUpdateDate = "0000-00-00 00:00:00";
						$expressionObj->productionUseInd = "0";
						$expressionObj->save();
						$expressionID = $expressionObj->primaryKey;
						$expressionQualifierProfileObj = new ExpressionQualifierProfile();
						$expressionQualifierProfileObj->expressionID = $expressionID;
						$expressionQualifierProfileObj->qualifierID = $qualifierID;
						$expressionQualifierProfileObj->save();
					}
				}
				if($_POST['supplyTerms'] === "on")
				{
					foreach($xml->SupplyTerms->SupplyTerm as $supplyTerm)
					{
						//get the expressionTypeID -- create expressionType if necessary
						$expression = preg_replace('/^onixPL\:/s','',$supplyTerm->SupplyTermType);
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

						$expressionObj = new Expression();
						$expressionObj->documentID = $documentID;
						$expressionObj->expressionTypeID = $expressionTypeID;
						$expressionText = "";
						foreach($supplyTerm->LicenseTextLink as $licenseTextLink)
						{
							$expressionText .= $textArray[(string)$licenseTextLink["href"]] . "\n\n";
						}
						$expressionObj->documentText = $expressionText;
						$expressionObj->lastUpdateDate = "0000-00-00 00:00:00";
						$expressionObj->productionUseInd = "0";
						$expressionObj->save();
						$expressionID = $expressionObj->primaryKey;
					}
				}
				if($_POST['continuingAccessTerms'] === "on")
				{
					foreach($xml->ContinuingAccessTerms->ContinuingAccessTerm as $continuingAccessTerm)
					{
						//get the expressionTypeID -- create expressionType if necessary
						$expression = preg_replace('/^onixPL\:/s','',$continuingAccessTerm->ContinuingAccessTermType);
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

						$expressionObj = new Expression();
						$expressionObj->documentID = $documentID;
						$expressionObj->expressionTypeID = $expressionTypeID;
						$expressionText = "";
						foreach($continuingAccessTerm->LicenseTextLink as $licenseTextLink)
						{
							$expressionText .= $textArray[(string)$licenseTextLink["href"]] . "\n\n";
						}
						$expressionObj->documentText = $expressionText;
						$expressionObj->lastUpdateDate = "0000-00-00 00:00:00";
						$expressionObj->productionUseInd = "0";
						$expressionObj->save();
						$expressionID = $expressionObj->primaryKey;
					}
				}
				if($_POST['paymentTerms'] === "on")
				{
					foreach($xml->PaymentTerms->PaymentTerm as $paymentTerm)
					{
						//get the expressionTypeID -- create expressionType if necessary
						$expression = preg_replace('/^onixPL\:/s','',$paymentTerm->PaymentTermType);
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

						$expressionObj = new Expression();
						$expressionObj->documentID = $documentID;
						$expressionObj->expressionTypeID = $expressionTypeID;
						$expressionText = "";
						foreach($paymentTerm->LicenseTextLink as $licenseTextLink)
						{
							$expressionText .= $textArray[(string)$licenseTextLink["href"]] . "\n\n";
						}
						$expressionObj->documentText = $expressionText;
						$expressionObj->lastUpdateDate = "0000-00-00 00:00:00";
						$expressionObj->productionUseInd = "0";
						$expressionObj->save();
						$expressionID = $expressionObj->primaryKey;
					}
				}
				if($_POST['generalTerms'] === "on")
				{
					foreach($xml->GeneralTerms->GeneralTerm as $generalTerm)
					{
						//get the expressionTypeID -- create expressionType if necessary
						$expression = preg_replace('/^onixPL\:/s','',$generalTerm->GeneralTermType);
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

						$expressionObj = new Expression();
						$expressionObj->documentID = $documentID;
						$expressionObj->expressionTypeID = $expressionTypeID;
						$expressionText = "";
						foreach($generalTerm->LicenseTextLink as $licenseTextLink)
						{
							$expressionText .= $textArray[(string)$licenseTextLink["href"]] . "\n\n";
						}
						$expressionObj->documentText = $expressionText;
						$expressionObj->lastUpdateDate = "0000-00-00 00:00:00";
						$expressionObj->productionUseInd = "0";
						$expressionObj->save();
						$expressionID = $expressionObj->primaryKey;
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
					<input id='generalTerms' name='generalTerms' type='checkbox'><label for='generalTerms'>General Terms</label><br/><br>
					<label for="licenseOrganizationID" class="formText">
						<?php echo _("Publisher / Provider:");?>
					</label>
					<span id='span_error_organizationName' class='errorText'></span><br />
					<input type='textbox' id='organizationName' name='organizationName' value="<?php echo $organizationName; ?>" />
					<input type='hidden' id='licenseOrganizationID' name='licenseOrganizationID' value='<?php echo $license->organizationID; ?>'>
					<span id='span_error_organizationNameResult' class='errorText'></span>
					<br />
				</div>
			</fieldset>
			<input type="submit" name="submit" value="<?php echo _("Upload");?>" class="submit-button" />
		</form>
		<script type='text/javascript'>
			$("#organizationName").keyup(function() {
				  $.ajax({
					 type:       "GET",
					 url:        "ajax_processing.php",
					 cache:      false,
					 async:	     true,
					 data:       "action=getExistingOrganizationName&shortName=" + $("#organizationName").val(),
					 success:    function(exists) {
						if (exists == "0"){
							$("#licenseOrganizationID").val("");
							$("#span_error_organizationNameResult").html("<br />"+_("Warning!  This organization will be added new."));

						}else{
							$("#licenseOrganizationID").val(exists);
							$("#span_error_organizationNameResult").html("");

						}
					 }
				  });
			});	

			//used for autocomplete formatting
	        formatItem = function (row){ 
	            return "<span style='font-size: 80%;'>" + row[1] + "</span>";
	        }
		 
	        formatResult = function (row){ 
	            return row[1].replace(/(<.+?>)/gi, '');
	        }	

			$("#organizationName").autocomplete('ajax_processing.php?action=getOrganizations', {
				minChars: 2,
				max: 50,
				mustMatch: false,
				width: 233,
				delay: 20,
				cacheLength: 10,
				matchSubset: true,
				matchContains: true,	
				formatItem: formatItem,
				formatResult: formatResult,
				parse: function(data){
				    var parsed = [];
				    var rows = data.split("\n");
				    for (var i=0; i < rows.length; i++) {
				      var row = $.trim(rows[i]);
				      if (row) {
					row = row.split("|");
					parsed[parsed.length] = {
					  data: row,
					  value: row[0],
					  result: formatResult(row, row[0]) || row[0]
					};
				      }
				    }

				    if (parsed.length == 0) {

					  $.ajax({
						 type:       "GET",
						 url:        "ajax_processing.php",
						 cache:      false,
						 async:	     true,
						 data:       "action=getExistingOrganizationName&shortName=" + $("#organizationName").val(),
						 success:    function(exists) {
							if (exists == "0"){
							        $("#licenseOrganizationID").val("");
							        $("#span_error_organizationNameResult").html("<br />"+_("Warning!  This organization will be added new."));

							}else{
								$("#licenseOrganizationID").val(exists);
								$("#span_error_organizationNameResult").html("");

							}
						 }
					  });
				    
				    }
				}		
			 });
		 
			 
			//once something has been selected, change the hidden input value
			$("#organizationName").result(function(event, data, formatted) {
				if (data[0]){
					$("#licenseOrganizationID").val(data[0]);
					$("#span_error_organizationNameResult").html("");
				}
			});
		</script>
<?php
	}
?>

