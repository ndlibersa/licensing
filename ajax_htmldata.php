<?php

/*
**************************************************************************************************************************
** CORAL Licensing Module v. 1.0
**
** Copyright (c) 2010 University of Notre Dame
**
** This file is part of CORAL.
**
** CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
**
** CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
**
**************************************************************************************************************************
**************************************************************************************************************************
** ajax_htmldata.php formats display data for all pages - tables and search results
**
** when ajax_htmldata.php is called through ajax, 'action' parm is required to dictate which data will be returned
**
** this also displays the links to edit/delete data depending on user privileges
**
**************************************************************************************************************************
*/


include_once 'directory.php';
include_once 'user.php';


switch ($_GET['action']) {

	//this is the head to license info on license.php
	case 'getLicenseHead':
		$licenseID = $_GET['licenseID'];
		$license = new License(new NamedArguments(array('primaryKey' => $licenseID)));
		$consortium = new Consortium(new NamedArguments(array('primaryKey' => $license->consortiumID)));

		?>

		<table class="headerTable">
		<tr style='vertical-align:top'><td style='padding-left:20px;'>
		<font style='font-size:130%;font-weight:bold;'><?php echo $license->shortName; ?></font>

		<?php

		if ($user->canEdit()){?>
			<a href='ajax_forms.php?action=getLicenseForm&licenseID=<?php echo $licenseID; ?>&height=260&width=260&modal=true' class='thickbox'><?php echo _("edit license");?></a>  |  <a href='javascript:deleteLicense("<?php echo $licenseID; ?>");'><?php echo _("remove license");?></a>
		<?php }

		echo "<div style='margin-top:10px;margin-bottom:20px;'>";

		//make sure they have org module installed before we give them a link to view the organization
		$config = new Configuration;

		if ($config->settings->organizationsModule == 'Y'){
			$util = new Utility();

			echo $license->getOrganizationName() . "  <a href='" . $util->getOrganizationURL() . $license->organizationID . "' target='_blank' style='text-decoration:none;'><i class='fa fa-pencil-square-o' style='margin-left:15px;'></i>"._("edit organization")."</a>";

			if ($license->consortiumID) {
				echo "<br />" . $license->getConsortiumName();
			}
		}else{
			echo $license->getOrganizationName();
			if ($license->consortiumID) {
				echo "<br />" . $license->getConsortiumName();
			}
		}

		?>
		</div>
		</td>
		<td style='text-align:right'>
		<?php if ($user->canEdit()){ ?>
			<b><?php echo _("License Status:");?></b><br />
			<select id='statusID' name='statusID' onchange='javascript:updateStatus();'>
			<option value=''></option>
			<?php


			$display = array();
			$status = new Status();

			foreach($status->allAsArray as $display) {
				if ($license->statusID == $display['statusID']) {
					echo "<option value='" . $display['statusID'] . "' selected>" . $display['shortName'] . "</option>";
				}else{
					echo "<option value='" . $display['statusID'] . "'>" . $display['shortName'] . "</option>";
				}
			}

			?>
			</select>
			<br />
			<span style='color:red' id='span_updateStatusResponse' name='span_updateStatusResponse'></span>
		<?php } ?>
		<br />
		</td></tr>
		</table>

		<?php


		break;


	//sfx display for the sfx tab on license.php
	case 'getAllSFXProviders':

		$licenseID = $_GET['licenseID'];

		$license = new License(new NamedArguments(array('primaryKey' => $licenseID)));
		$document = new Document();
		$documentArray = $license->getDocuments();

		$rowCount=0;

		//loop through each document separately
		//note - this tab is only displayed for admin users so no validation is required
		foreach($documentArray as $document) {
			$sfxProvider = new SFXProvider();
			foreach($document->getSFXProviders as $sfxProvider) {
				$rowCount++;
				if ($rowCount == "1"){
				?>
						<table class='verticalFormTable'>
						<tr>
						<th><?php echo _("For Document");?></th>
						<th><?php echo _("Resource");?></th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						</tr>
				<?php
				}
				echo "<tr>";
				echo "<td>" . $document->shortName . "</td>";
				echo "<td>" . $sfxProvider->shortName . "</td>";
				echo "<td><a href='ajax_forms.php?action=getSFXForm&height=178&width=260&modal=true&licenseID=" . $licenseID . "&providerID=" . $sfxProvider->sfxProviderID . "' class='thickbox' id='editSFXProvider'>"._("edit")."</a></td>";
				echo "<td><a href='javascript:deleteSFXProvider(\"" . $sfxProvider->sfxProviderID . "\");'>"._("remove")."</a></td>";
				echo "</tr>";
			}


		//end loop over sfx provider records
		}
		?>

		</table>

		<?php
		if ($rowCount == "0"){
			echo _("(none found)");
		}

		if ($user->canEdit()){
			echo "<br /><br /><a href='ajax_forms.php?action=getSFXForm&licenseID=" . $licenseID . "&height=178&width=260&modal=true' class='thickbox' id='addSFXResource'>"._("add new terms tool resource link")."</a>";
		}

		break;



	//attachments display for attachments tab on license.php
	//note - this was originally called email logs since that's the main intent but we renamed to attachments so it could be general purpose
	case 'getAllAttachments':

		$licenseID = $_GET['licenseID'];

		$license = new License(new NamedArguments(array('primaryKey' => $licenseID)));
		$attachment = new Attachment();
		$attachmentArray = $license->getAttachments();

		if (count($attachmentArray) > 0){

		?>

		<table class='verticalFormTable'>
		<tr>
		<th style='width:80px;'><?php echo _("Date");?></th>
		<th style='width:540px;'><?php echo _("Details");?></th>
		<th style='width:150px;'>&nbsp;</th>
		<?php if ($user->canEdit()){ ?>
			<th style='width:100px;'>&nbsp;</th>
		<?php } ?>
		</tr>

			<?php


			foreach($attachmentArray as $attachment) {
				if (($attachment->sentDate == "0000-00-00") || ($attachment->sentDate == "")) {
					$sentDate='';
				}else{
					$sentDate=format_date($attachment->sentDate);
				}
				$attachmentText = nl2br($attachment->attachmentText);

				echo "<tr>";
				echo "<td>" . $sentDate . "</td>";
				echo "<td><div id='attachment_short_" . $attachment->attachmentID . "'>" . substr($attachmentText, 0,200);

				if (strlen($attachmentText) > 200){
					echo "...&nbsp;&nbsp;<a href='javascript:showFullAttachmentText(\"" . $attachment->attachmentID . "\");'>"._("more...")."</a>";
				}

				echo "</div>";
				echo "<div id='attachment_full_" . $attachment->attachmentID . "' style='display:none'>" . $attachmentText;
					echo "&nbsp;&nbsp;<a href='javascript:hideFullAttachmentText(\"" . $attachment->attachmentID . "\");'>"._("less...")."</a>";
				echo "</div>";

				echo "</td>";

				$attachmentFileArray=$attachment->getAttachmentFiles();
				$attachmentFile = new AttachmentFile();

				echo "<td>";


				if (count($attachmentFileArray) == 0){
					echo _("(none uploaded)")."<br />";
				}

				$i=1;
				foreach($attachmentFileArray as $attachmentFile) {
					echo "<a href='attachments/" . $attachmentFile->attachmentURL . "' target='_BLANK'>"._("view attachment ") . $i . "</a><br />";
					$i++;
				}
				echo "</td>";

				if ($user->canEdit()){
					echo "<td><a href='ajax_forms.php?action=getAttachmentForm&height=398&width=305&modal=true&licenseID=" . $licenseID . "&attachmentID=" . $attachment->attachmentID . "' class='thickbox' id='editAttachment'>"._("edit")."</a>&nbsp;&nbsp;<a href='javascript:deleteAttachment(\"". $attachment->attachmentID . "\");'>"._("remove")."</a></td>";
				}

				echo "</tr>";

			}
			?>

		</table>
		<?php
		}else{
			echo _("(none found)");
		}

		if ($user->canEdit()){
			echo "<br /><br /><a href='ajax_forms.php?action=getAttachmentForm&licenseID=" . $licenseID . "&height=380&width=305&modal=true' class='thickbox' id='attachment'>"._("add new attachment")."</a>";
		}

		break;


	//number of attachments, used to display on the tab so user knows whether to look on tab
	case 'getAttachmentsNumber':
		$licenseID = $_GET['licenseID'];
		$license = new License(new NamedArguments(array('primaryKey' => $licenseID)));

		echo count($license->getAttachments());

		break;

	//license search - used on index.php
	case 'getSearchLicenses':

		$pageStart = intval($_GET['pageStart']);
		$numberOfRecords = intval($_GET['numberOfRecords']);
		$whereAdd = array();

		//get where statements together

		//if the org module is installed where clause must go to the org database
		$config = new Configuration;
		if ($config->settings->organizationsModule == 'Y'){
			//searches against org name and aliases
			if ($_GET['shortName']) $whereAdd[] = "(UPPER(L.shortName) LIKE UPPER('%" .  str_replace("'","''",$_GET['shortName']) . "%') OR UPPER(D.shortName) LIKE  UPPER('%" .  str_replace("'","''",$_GET['shortName']) . "%') OR UPPER(O.name) LIKE  UPPER('%" .  str_replace("'","''",$_GET['shortName']) . "%') OR UPPER(A.name) LIKE  UPPER('%" .  str_replace("'","''",$_GET['shortName']) . "%'))";
		}else{
			if ($_GET['shortName']) $whereAdd[] = "(UPPER(L.shortName) LIKE UPPER('%" .  str_replace("'","''",$_GET['shortName']) . "%') OR UPPER(D.shortName) LIKE  UPPER('%" .  str_replace("'","''",$_GET['shortName']) . "%') OR UPPER(O.shortName) LIKE  UPPER('%" .  str_replace("'","''",$_GET['shortName']) . "%'))";
		}

		if ($_GET['organizationID']){
			$whereAdd[] = "O.organizationID = '" . $_GET['organizationID'] . "'";
		}

		$consortiumID = $_GET['consortiumID'];
		if ($consortiumID == "0") {
			$whereAdd[] = " L.consortiumID IS NULL ";
		}else{
			if ($consortiumID <> "") $whereAdd[] = " L.consortiumID = '" . $consortiumID . "'";
		}

		if ($_GET['statusID']) $whereAdd[] = "S.statusID = '" . $_GET['statusID'] . "'";
		if ($_GET['documentTypeID']) $whereAdd[] = "D.documentTypeID = '" . $_GET['documentTypeID'] . "'";

		if ($_GET['expressionTypeID']) $whereAdd[] = "E.expressionTypeID = '" . $_GET['expressionTypeID'] . "'";
		if ($_GET['qualifierID']) $whereAdd[] = "E.expressionID IN (SELECT expressionID FROM ExpressionQualifierProfile WHERE  qualifierID = '" . $_GET['qualifierID'] . "')";

		if ($_GET['startWith']) $whereAdd[] = "TRIM(LEADING 'THE ' FROM UPPER(L.shortName)) LIKE UPPER('" . $_GET['startWith'] . "%')";



		$orderBy = $_GET['orderBy'];

		//get total number of records to print out and calculate page selectors
		$totalLicenseObj = new License();

		$totalRecords = $totalLicenseObj->searchCount($whereAdd);

		//reset pagestart to 1 - happens when a new search is run but it kept the old page start
		if ($totalRecords <= $pageStart){
			$pageStart=1;
		}

		$limit = ($pageStart-1) . ", " . $numberOfRecords;

		$licenseObj = new License();
		$licenseArray = array();
		$licenseArray = $licenseObj->search($whereAdd, $orderBy, $limit);
    $pagination = '';
		if ($totalRecords == 0){
			echo "<br /><br /><i>"._("Sorry, no licenses fit your query")."</i>";
			$i=0;
		}else{
		  //maximum number of pages to display on screen at one time
			$maxDisplay = 25;
			
			$thisPageNum = count($licenseArray) + $pageStart - 1;
			echo "<span style='font-weight:bold;'>"._("Displaying ") . $pageStart . _(" to ") . $thisPageNum . _(" of ") . $totalRecords . _(" License Records")."</span><br />";

			//print out page selectors
			if ($totalRecords > $numberOfRecords){
				echo "<div id='pagination-div'>";
				if ($pageStart == "1"){
					$pagination .= "<span class='smallText'><i class='fa fa-backward'></i></span>&nbsp;";
				}else{
					$pagination .= "<a href='javascript:setPageStart(1);' class='smallLink'><i class='fa fa-backward'></i></a>&nbsp;";
				}
        $page = floor($pageStart/$numberOfRecords) + 1;
        //now determine the starting page - we will display 3 prior to the currently selected page
				if ($page > 3){
					$startDisplayPage = $page - 3;
				}else{
					$startDisplayPage = 1;
				}
				
				$maxPages = floor($totalRecords / $numberOfRecords) + 1;

				//now determine last page we will go to - can't be more than maxDisplay
				$lastDisplayPage = $startDisplayPage + $maxDisplay;
				if ($lastDisplayPage > $maxPages){
					$lastDisplayPage = ceil($maxPages);
				}
        
				for ($i=$startDisplayPage; $i<=$lastDisplayPage; $i++){

					$nextPageStarts = ($i-1) * $numberOfRecords + 1;
					if ($nextPageStarts == "0") $nextPageStarts = 1;


					if ($pageStart == $nextPageStarts){
						$pagination .= "<span class='smallText'>" . $i . "</span>&nbsp;";
					}else{
						$pagination .= "<a href='javascript:setPageStart(" . $nextPageStarts  .");' class='smallLink'>" . $i . "</a>&nbsp;";
					}
				}

				if ($pageStart == $nextPageStarts){
					$pagination .= "<span class='smallText'><i class='fa fa-forward'></i></span>&nbsp;";
				}else{
					$pagination .= "<a href='javascript:setPageStart(" . $nextPageStarts  .");' class='smallLink'><i class='fa fa-forward'></i></a>&nbsp;";
				}
				echo $pagination;
				echo "</div>";
			} else {
				echo "<div id='pagination-empty-div'></div>";
			}


			?>
			<table class='dataTable' style='width:840px'>
			<tr>
			<th><table class='noBorderTable'><tr><td><?php echo _("Name");?></td><td class='arrow'><a href='javascript:setOrder("L.shortName","asc");'><img src='images/arrowup.png' border=0></a>&nbsp;<a href='javascript:setOrder("L.shortName","desc");'><img src='images/arrowdown.png' border=0></a></td></tr></table></th>
			<th><table class='noBorderTable'><tr><td><?php echo _("Publisher / Provider");?></td><td class='arrow'><a href='javascript:setOrder("providerName","asc");'><img src='images/arrowup.png' border=0></a>&nbsp;<a href='javascript:setOrder("providerName","desc");'><img src='images/arrowdown.png' border=0></a></td></tr></table></th>
			<th style='width:100px;'><table class='noBorderTable'><tr><td><?php echo _("Consortium");?></td><td class='arrow'><a href='javascript:setOrder("C.shortName","asc");'><img src='images/arrowup.png' border=0></a>&nbsp;<a href='javascript:setOrder("C.shortName","desc");'><img src='images/arrowdown.png' border=0></a></td></tr></table></th>
			<th style='width:65px;'><table class='noBorderTable'><tr><td><?php echo _("Status");?></td><td class='arrow'><a href='javascript:setOrder("S.shortName","asc");'><img src='images/arrowup.png' border=0></a>&nbsp;<a href='javascript:setOrder("S.shortName","desc");'><img src='images/arrowdown.png' border=0></a></td></tr></table></th>
			</tr>

			<?php

			$i=0;
			foreach ($licenseArray as $license){
				$i++;
				if ($i % 2 == 0){
					$classAdd="";
				}else{
					$classAdd="class='alt'";
				}
				echo "<tr>";
				echo "<td $classAdd><a href='license.php?licenseID=" . $license['licenseID'] . "'>" . $license['licenseName'] . "</a></td>";
				echo "<td $classAdd>" . $license['providerName'] . "</td>";
				echo "<td $classAdd>" . $license['consortiumName'] . "</td>";
				echo "<td $classAdd>" . $license['status'] . "</td>";
				echo "</tr>";
			}

			?>
			</table>

			<table style='width:100%;margin-top:4px'>
			<tr>
			<td style='text-align:left;float:left;'>
			<?php
			//print out page selectors
			if ($pagination){
				echo $pagination;
			}
			?>
			</td>
			<td style="text-align:left;padding-top:10px;" id="records-per-page">
			<select id='numberOfRecords' name='numberOfRecords' onchange='javascript:setNumberOfRecords();' style='width:50px;'>
				<?php
				for ($i=5; $i<=50; $i=$i+5){
					if ($i == $numberOfRecords){
						echo "<option value='" . $i . "' selected>" . $i . "</option>";
					}else{
						echo "<option value='" . $i . "'>" . $i . "</option>";
					}
				}
				?>
			</select>
			<span class='smallText'><?php echo _("records per page");?></span>
			</td>
			</tr>
			</table>

			<?php

			//set everything in sessions to make form "sticky"
			$_SESSION['license_pageStart'] = $_GET['pageStart'];
			$_SESSION['license_numberOfRecords'] = $_GET['numberOfRecords'];
			$_SESSION['license_shortName'] = $_GET['shortName'];
			$_SESSION['license_organizationID'] = $_GET['organizationID'];
			$_SESSION['license_consortiumID'] = $_GET['consortiumID'];
			$_SESSION['license_statusID'] = $_GET['statusID'];
			$_SESSION['license_documentTypeID'] = $_GET['documentTypeID'];
			$_SESSION['license_startWith'] = $_GET['startWith'];
			$_SESSION['license_orderBy'] = $_GET['orderBy'];
			$_SESSION['license_expressionTypeID'] = $_GET['expressionTypeID'];
			$_SESSION['license_qualifierID'] = $_GET['qualifierID'];
		}

		break;


	//used for in progress page.
	//note that in the License class the statuses are hard-coded
	case 'getInProgressLicenses':
		try {
			?>
				<table class='dataTable'>
				<tr>
				<th><?php echo _("Name");?></th>
				<th><?php echo _("Publisher / Provider");?></th>
				<th style='width:135px;'><?php echo _("Consortium");?></th>
				<th style='width:115px;'><?php echo _("Status");?></th>
				</tr>

				<?php

				$i=0;
				$license=new License();
				$licenseArray = array();


				foreach ($license->getInProgressLicenses() as $licenseArray){
					$i++;
					if ($i % 2 == 0){
						$classAdd="";
					}else{
						$classAdd="class='alt'";
					}
					echo "<tr>";
					echo "<td $classAdd><a href='license.php?licenseID=" . $licenseArray['licenseID'] . "'>" . $licenseArray['licenseName'] . "</a></td>";
					echo "<td $classAdd>" . $licenseArray['providerName'] . "</td>";
					echo "<td $classAdd>" . $licenseArray['consortiumName'] . "</td>";
					echo "<td $classAdd>" . $licenseArray['status'] . "</td>";
					echo "</tr>";
				}

				?>
				</table>

				<?php
			}catch(Exception $e){
				echo "<span style='color:red'>"._("There was an error processing this request - please verify configuration.ini is set up for organizations correctly and the database and tables have been created.")."</span>";
			}

		break;


	//used for expression comparison tool (compare.php)
	case 'getComparisonList':

		$expressionTypeID = $_GET['expressionTypeID'];

		//populate array with the expression types that we are to display
		//if expression type is passed in, only that one
		if ($expressionTypeID != "") {
			$expressionTypeArray[] = $expressionTypeID;
		}else{
			$et = new ExpressionType();
			foreach($et->allAsArray() as $expressionType){
				$expressionTypeArray[] = $expressionType['expressionTypeID'];
			}

		}


		foreach($expressionTypeArray as $expressionTypeID){ {
			$expressionType = new ExpressionType(new NamedArguments(array('primaryKey' => $expressionTypeID)));
			$etArray = $expressionType->getComparisonList($_GET['qualifierID']);

			if (count($etArray) > 0){

				echo "<br /><h3>" . $expressionType->shortName . "</h3>";

				foreach($etArray as $expressionTypeArray){

					echo "<div style='margin-top:10px;margin-bottom:20px;padding:20px; border-width:1px;border-color: #e0dfe3;border-style: solid;' id='exp-comparison'>";
					echo "\n<table class='noBorder' style='width:100%;'><tr><td style='text-align:left;padding-left:0 !important;padding-bottom:0 !important;width:450px !important;color:#1B77AA;'><span style='font-weight:bold;font-size:110%;text-align:left;'>" . $expressionTypeArray['document'] . "</span></td>";


					if ($user->canEdit()){
						echo "\n<td style='text-align:right;width:350px;'><a href='license.php?licenseID=" . $expressionTypeArray['licenseID'] . "' target='_BLANK'>"._("view / edit license")."</a>&nbsp;&nbsp;<a href='ajax_forms.php?action=getExpressionNotesForm&height=330&width=440&modal=true&org=compare&expressionID=" . $expressionTypeArray['expressionID'] . "' class='thickbox' id='ExpressionNotes'>"._("view / edit ") . strtolower($expressionType->noteType) . _(" notes")."</a>&nbsp;&nbsp;<a href='documents/" . $expressionTypeArray['documentURL'] . "' target='_BLANK'>"._("view document")."</a></td></tr></table>";
					}else{
						echo "\n<td style='text-align:right;'><a href='license.php?licenseID=" . $expressionTypeArray['licenseID'] . "' target='_BLANK'>"._("view license")."</a>&nbsp;&nbsp;<a href='documents/" . $expressionTypeArray['documentURL'] . "' target='_BLANK'>"._("view document")."</a></td></tr></table>";
					}

					echo "<div style='margin-top:15px;'>";

					if ($expressionTypeArray['documentText']){
						echo "<b>"._("Document Text:")."</b> <br /><br />" . nl2br($expressionTypeArray['documentText']) . "<br />";
					}


					$expr_notes = "<br /><b>" . ucfirst($expressionTypeArray['noteType']) . _(" Notes:")."  </b>";

					$expression = new Expression(new NamedArguments(array('primaryKey' => $expressionTypeArray['expressionID'])));
					$expressionNotes = $expression->getExpressionNotes();

					if  (count($expressionNotes) > "0"){
						echo "<ul class='moved'>";

						foreach($expressionNotes as $expressionNote){
							$expr_notes .=  "<li>";
							$expr_notes .= $expressionNote->note;
							$expr_notes .=  "</li>";
						}

						$expr_notes .= "</ul>";

						echo $expr_notes;
					}

					if ($user->canEdit()){
						echo "";
					}



					if ($expressionTypeArray['qualifiers']){
						echo "<br /><b>"._("Qualifiers:")."</b><br />  " . $expressionTypeArray['qualifiers'];
					}

					echo "</div>";
					echo "</div>";

				#end expressions loop
				}

			}
			}

		#end expression type loop
		}


		echo "</table>";
		break;





	//used for terms tool report (terms_report.php)
	case 'getTermsReport':

		$expressionTypeID = $_GET['expressionTypeID'];

		//populate array with the expression types that we are to display
		//if expression type is passed in, only that one
		if ($expressionTypeID != "") {
			$expressionTypeArray[] = $expressionTypeID;
		}else{
			$et = new ExpressionType();
			foreach($et->allAsArray() as $expressionType){
				if ($expressionType['noteType'] == 'Display'){
					$expressionTypeArray[] = $expressionType['expressionTypeID'];
				}
			}

		}


		foreach($expressionTypeArray as $expressionTypeID){


			$expressionType = new ExpressionType(new NamedArguments(array('primaryKey' => $expressionTypeID)));
			$etArray = $expressionType->getTermsReport();

			echo "<br /><h3>" . $expressionType->shortName . "</h3>";
			echo "<table class='dataTable' style='max-width:955px;width:900px;text-align:left;'>";


			if (count($etArray) > 0){
				?>

				<tr style='width:900px;'>
				<th style='width:50px;'><?php echo _("License");?></th>
				<th style='width:300px;'><?php echo ucfirst($expressionType->noteType); ?> <?php echo _("Notes");?></th>
				<th style='width:255px;'><?php echo _("Document Text");?></th>
				</tr>

				<?php

				foreach($etArray as $expressionTypeArray){

					echo "\n<tr><td colspan='3'><span style='font-weight:bold'>" . $expressionTypeArray['document'] . "</span>  <a href='license.php?licenseID=" . $expressionTypeArray['licenseID'] . "'>"._("view license")."</a></td></tr>";

					if ($expressionTypeArray['documentText']){
						$documentText = $expressionTypeArray['documentText'];
					}else{
						$documentText = _("(document text not entered)");
					}

					echo "\n<tr style='vertical-align:top'>";
					echo "<td style='width:50px;'>&nbsp;</td>";

					echo "<td>";

					$expr_notes = ucfirst($expressionTypeArray['noteType']) . _(" Notes:")."  <ul class='moved'>";

					$expression = new Expression(new NamedArguments(array('primaryKey' => $expressionTypeArray['expressionID'])));
					$expressionNotes = $expression->getExpressionNotes();

					foreach($expressionNotes as $expressionNote){
						$expr_notes .=  "<li>";
						$expr_notes .= $expressionNote->note;
						$expr_notes .=  "</li>";
					}

					$expr_notes .= "</ul><br />";

					if  (count($expressionNotes) > "0"){
						echo $expr_notes;
					}

					echo "</td>";
					echo "<td style='width:450px;'>";

					echo "<div id='text_short_" . $expressionTypeArray['expressionID'] . "'>" . substr($documentText, 0,200);

					if (strlen($documentText) > 200){
						echo "...&nbsp;&nbsp;<a href='javascript:showFullDocumentText(\"" . $expressionTypeArray['expressionID'] . "\");'>"._("more...")."</a>";
					}

					echo "</div>";
					echo "<div id='text_full_" . $expressionTypeArray['expressionID'] . "' style='display:none'>" . $documentText;
					echo "&nbsp;&nbsp;<a href='javascript:hideFullDocumentText(\"" . $expressionTypeArray['expressionID'] . "\");'>"._("less...")."</a>";
					echo "</div>";


					echo "</td>";
					echo "</tr>";

				#end expressions loop
				}

			#end numrows if
			}else{
				echo "<tr><td colspan='3'>"._("(none for ") . $expressionTypeArray['shortName'] . ")</td></tr>";
			}

			echo "</table>";





		#end expression type loop
		}


		echo "</table>";
		break;






	//display table for all documents for the license on license.php
	case 'getAllDocuments':

		$licenseID = $_GET['licenseID'];
		if (isset($_GET['displayArchiveInd'])) $displayArchiveInd = $_GET['displayArchiveInd']; else $displayArchiveInd = '';
		if (isset($_GET['parentOrderBy'])) $parentOrderBy = $_GET['parentOrderBy'];
		if (isset($_GET['childOrderBy'])) $childOrderBy = $_GET['childOrderBy'];
		if (isset($_GET['parentArchivedOrderBy'])) $parentArchivedOrderBy = $_GET['parentArchivedOrderBy'];
		if (isset($_GET['childArchivedOrderBy'])) $childArchivedOrderBy = $_GET['childArchivedOrderBy'];

		//used to turn on/off display of archived documents
		if ($displayArchiveInd == 'undefined') $displayArchiveInd='';

		//used to turn on/off display of specific child documents
		if (isset($_GET['showChildrenDocumentID'])) $showChildrenDocumentID = $_GET['showChildrenDocumentID']; else $showChildrenDocumentID = '';
		if ($showChildrenDocumentID == 'undefined')	$showChildrenDocumentID='';


		$license = new License(new NamedArguments(array('primaryKey' => $licenseID)));
		$document = new Document();
		//display archive not sent in for unarchived docs
		if ($displayArchiveInd == ''){
			$documentArray = $license->getDocumentsWithoutParents($parentOrderBy);
			$chJSFunction = "setChildOrder";

			$isArchive='N';
		}else if ($displayArchiveInd == '1'){
			$documentArray = $license->getArchivedDocumentsWithoutParents($parentArchivedOrderBy);
			if (count($documentArray) > 0){
				echo "<font style='font-size:110%;font-weight:bold;'>"._("Archived Documents")."</font>  <i><a href='javascript:updateArchivedDocuments(2)'>"._("hide archives")."</a></i>";
			}

			$chJSFunction = "setChildArchivedOrder";
			$childOrderBy = $childArchivedOrderBy;

			$isArchive='Y';
		}else{
			$documentArray = $license->getArchivedDocumentsWithoutParents($parentArchivedOrderBy);
			$jsFunction = "setParentArchivedOrder";
			$chJSFunction = "setChildArchivedOrder";
			$childOrderBy = $childArchivedOrderBy;

			$isArchive='Y';
		}



		$numRows = count($documentArray);

		if (($numRows > 0) && ($displayArchiveInd != '2')){

		?>

		<table class='verticalFormTable'>
		<tr>

		<?php if ($isArchive == 'N') { ?>
		<th><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Name");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setParentOrder("D.shortName","asc");'><img src='images/arrowup<?php if ($parentOrderBy == 'D.shortName asc') echo "_sel"; ?>.png' border=0></a>&nbsp;<a href='javascript:setParentOrder("D.shortName","desc");'><img src='images/arrowdown<?php if ($parentOrderBy == 'D.shortName desc') echo "_sel"; ?>.png' border=0></a></td></tr></table></th>
		<th><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Type");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setParentOrder("DT.shortName","asc");'><img src='images/arrowup<?php if ($parentOrderBy == 'DT.shortName asc') echo "_sel"; ?>.png' border=0></a>&nbsp;<a href='javascript:setParentOrder("DT.shortName","desc");'><img src='images/arrowdown<?php if ($parentOrderBy == 'DT.shortName desc') echo "_sel"; ?>.png' border=0></a></td></tr></table></th>
		<th style='width:120px;'><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Effective Date");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setParentOrder("D.effectiveDate","asc");'><img src='images/arrowup<?php if ($parentOrderBy == 'D.effectiveDate asc') echo "_sel"; ?>.png' border=0></a>&nbsp;<a href='javascript:setParentOrder("D.effectiveDate","desc");'><img src='images/arrowdown<?php if ($parentOrderBy == 'D.effectiveDate desc') echo "_sel"; ?>.png' border=0></a></td></tr></table></th>
		<th style='width:180px;'><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Signatures");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setParentOrder("min(signatureDate) asc, min(signerName)","asc");'><img src='images/arrowup<?php if ($parentOrderBy == 'min(signatureDate) asc, min(signerName) asc') echo "_sel"; ?>.png' border=0></a>&nbsp;<a href='javascript:setParentOrder("max(signatureDate) desc, max(signerName)","desc");'><img src='images/arrowdown<?php if ($parentOrderBy == 'max(signatureDate) desc, max(signerName) desc') echo "_sel"; ?>.png' border=0></a></td></tr></table></th>
		<?php }else{ ?>
		<th><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Name");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setParentArchivedOrder("D.shortName","asc");'><img src='images/arrowup<?php if ($parentArchivedOrderBy == 'D.shortName asc') echo "_sel"; ?>.png' border=0></a>&nbsp;<a href='javascript:setParentArchivedOrder("D.shortName","desc");'><img src='images/arrowdown<?php if ($parentArchivedOrderBy == 'D.shortName desc') echo "_sel"; ?>.png' border=0></a></td></tr></table></th>
		<th><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Type");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setParentArchivedOrder("DT.shortName","asc");'><img src='images/arrowup<?php if ($parentArchivedOrderBy == 'DT.shortName asc') echo "_sel"; ?>.png' border=0></a>&nbsp;<a href='javascript:setParentArchivedOrder("DT.shortName","desc");'><img src='images/arrowdown<?php if ($parentArchivedOrderBy == 'DT.shortName desc') echo "_sel"; ?>.png' border=0></a></td></tr></table></th>
		<th style='width:120px;'><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Effective Date");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setParentArchivedOrder("D.effectiveDate","asc");'><img src='images/arrowup<?php if ($parentArchivedOrderBy == 'D.effectiveDate asc') echo "_sel"; ?>.png' border=0></a>&nbsp;<a href='javascript:setParentArchivedOrder("D.effectiveDate","desc");'><img src='images/arrowdown<?php if ($parentArchivedOrderBy == 'D.effectiveDate desc') echo "_sel"; ?>.png' border=0></a></td></tr></table></th>
		<th style='width:180px;'><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Signatures");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setParentArchivedOrder("min(signatureDate) asc, min(signerName)","asc");'><img src='images/arrowup<?php if ($parentArchivedOrderBy == 'min(signatureDate) asc, min(signerName) asc') echo "_sel"; ?>.png' border=0></a>&nbsp;<a href='javascript:setParentArchivedOrder("max(signatureDate) desc, max(signerName)","desc");'><img src='images/arrowdown<?php if ($parentArchivedOrderBy == 'max(signatureDate) desc, max(signerName) desc') echo "_sel"; ?>.png' border=0></a></td></tr></table></th>
		<?php } ?>


		<th style='width:100px;'>&nbsp;</th>
		<?php if ($user->canEdit()){ ?>
		<th style='width:100px;'>&nbsp;</th>
		<?php } ?>
		</tr>

			<?php

			$numrows=0;
			foreach($documentArray as $document) {

				$documentType = new DocumentType(new NamedArguments(array('primaryKey' => $document->documentTypeID)));

				//determine coloring of the row
				if(($document->expirationDate != "0000-00-00") && ($document->expirationDate != "")){
					//$classAdd="class='archive'";
					$classAdd="";
				}else if ((strtoupper($documentType->shortName) == 'AGREEMENT') || (strpos(strtoupper($documentType->shortName),'AGREEMENT'))){
					$classAdd="class='agreement'";
				}else{
					$classAdd="";
				}
				$numrows++;

				if (($document->effectiveDate == "0000-00-00") || ($document->effectiveDate == "")){
					$displayEffectiveDate = '';
				}else{
					$displayEffectiveDate = format_date($document->effectiveDate);
				}

				if (($document->expirationDate != "0000-00-00") && ($document->expirationDate != "")){
					$displayExpirationDate = _("archived on: ") . format_date($document->expirationDate);
				}else{
					$displayExpirationDate = '';
				}


				echo "<tr>";
				echo "<td $classAdd>" . $document->shortName . "</td>";
				echo "<td $classAdd>" . $documentType->shortName . "</td>";
				echo "<td $classAdd>" . $displayEffectiveDate . "</td>";
				echo "<td $classAdd>";

				$signature = array();
				$signatureArray = $document->getSignaturesForDisplay();

				if (count($signatureArray) > 0){
					echo "<table class='noBorderTable'>";

					foreach($signatureArray as $signature) {

						if (($signature['signatureDate'] != '') && ($signature['signatureDate'] != "0000-00-00")) {
							$signatureDate = format_date($signature['signatureDate']);
						}else{
							$signatureDate=_("(no date)");
						}

						echo "<tr>";
						echo "<td $classAdd>" . $signature['signerName'] . "</td>";
						echo "<td $classAdd>" . $signatureDate . "</td>";
						echo "</tr>";

					}
					echo "</table>";
					if ($user->canEdit()){
						echo "<a href='ajax_forms.php?action=getSignatureForm&height=270&width=460&modal=true&documentID=" . $document->documentID . "' class='thickbox' id='signatureForm'>"._("add/view details")."</a>";
					}


				}else{
					echo _("(none found)")."<br />";
					if ($user->canEdit()){
						echo "<a href='ajax_forms.php?action=getSignatureForm&height=170&width=460&modal=true&documentID=" . $document->documentID . "' class='thickbox' id='signatureForm'>"._("add signatures")."</a>";
					}
				}

				echo "</td>";

				echo "<td $classAdd>";
				if (!$user->isRestricted()) {
					if ($document->documentURL != ""){
						echo "<a href='documents/" . $document->documentURL . "' target='_blank'>"._("view document")."</a><br />";
					}else{
						echo _("(none uploaded)")."<br />";
					}
				}

				if (count($document->getExpressions) > 0){
					echo "<a href='javascript:showExpressionForDocument(" . $document->documentID . ");'>"._("view expressions")."</a>";
				}

				echo "</td>";

				if ($user->canEdit()){
					echo "<td $classAdd><a href='ajax_forms.php?action=getUploadDocument&height=295&width=317&modal=true&licenseID=" . $licenseID . "&documentID=" . $document->documentID . "' class='thickbox' id='editDocument'>"._("edit document")."</a><br /><a href='javascript:deleteDocument(\"" . $document->documentID . "\");'>"._("remove document")."</a>";
					echo "<br />" . $displayExpirationDate . "</td>";
				}
				echo "</tr>";

				$numberOfChildren = $document->getNumberOfChildren();
				if ($numberOfChildren > 0) {
					//if display for this child is turned off
					if ((($showChildrenDocumentID) && ($showChildrenDocumentID != $document->documentID)) || !($showChildrenDocumentID)) {
						if ($displayArchiveInd == '1') {
							echo "<tr><td colspan='6'><i>"._("This document has ") . $numberOfChildren . _(" children document(s) not displayed.")."  <a href='javascript:updateArchivedDocuments(\"\"," . $document->documentID . ")'>"._("show all documents for this parent")."</a></i></td></tr>";
						}else{
							echo "<tr><td colspan='6'><i>"._("This document has ") . $numberOfChildren . _(" children document(s) not displayed.")."  <a href='javascript:updateDocuments(" . $document->documentID . ")'>"._("show all documents for this parent")."</a></i></td></tr>";
						}
					}else{
						if ($displayArchiveInd == '1') {
							echo "<tr><td colspan='6'><i>"._("The following ") . $numberOfChildren . _(" document(s) belong to ") . $document->shortName . ".  <a href='javascript:updateArchivedDocuments(\"\",\"\")'>"._("hide children documents for this parent")."</a></i></td></tr>";
						}else{
							echo "<tr><td colspan='6'><i>"._("The following ") . $numberOfChildren . _(" document(s) belong to ") . $document->shortName . ".  <a href='javascript:updateDocuments(\"\")'>"._("hide children documents for this parent")."</a></i></td></tr>";
						}

						?>
						<tr>
						<?php if ($isArchive == 'N') { ?>
						<th><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Name");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setChildOrder("D.shortName","asc");'><img src='images/arrowup<?php if ($childOrderBy == 'D.shortName asc') echo "_sel"; ?>.gif' border=0></a>&nbsp;<a href='javascript:setChildOrder("D.shortName","desc");'><img src='images/arrowdown<?php if ($childOrderBy == 'D.shortName desc') echo "_sel"; ?>.gif' border=0></a></td></tr></table></th>
						<th><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Type");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setChildOrder("DT.shortName","asc");'><img src='images/arrowup<?php if ($childOrderBy == 'DT.shortName asc') echo "_sel"; ?>.gif' border=0></a>&nbsp;<a href='javascript:setChildOrder("DT.shortName","desc");'><img src='images/arrowdown<?php if ($childOrderBy == 'DT.shortName desc') echo "_sel"; ?>.gif' border=0></a></td></tr></table></th>
						<th style='width:120px;'><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Effective Date");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setChildOrder("D.effectiveDate","asc");'><img src='images/arrowup<?php if ($childOrderBy == 'D.effectiveDate asc') echo "_sel"; ?>.gif' border=0></a>&nbsp;<a href='javascript:setChildOrder("D.effectiveDate","desc");'><img src='images/arrowdown<?php if ($childOrderBy == 'D.effectiveDate desc') echo "_sel"; ?>.gif' border=0></a></td></tr></table></th>
						<th style='width:180px;'><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Signatures");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setChildOrder("min(signatureDate) asc, min(signerName)","asc");'><img src='images/arrowup<?php if ($childOrderBy == 'min(signatureDate) asc, min(signerName) asc') echo "_sel"; ?>.gif' border=0></a>&nbsp;<a href='javascript:setChildOrder("max(signatureDate) desc, max(signerName)","desc");'><img src='images/arrowdown<?php if ($childOrderBy == 'max(signatureDate) desc, max(signerName) desc') echo "_sel"; ?>.gif' border=0></a></td></tr></table></th>
						<?php }else{ ?>
						<th><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Name");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setChildArchivedOrder("D.shortName","asc");'><img src='images/arrowup<?php if ($childArchivedOrderBy == 'D.shortName asc') echo "_sel"; ?>.gif' border=0></a>&nbsp;<a href='javascript:setChildArchivedOrder("D.shortName","desc");'><img src='images/arrowdown<?php if ($childArchivedOrderBy == 'D.shortName desc') echo "_sel"; ?>.gif' border=0></a></td></tr></table></th>
						<th><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Type");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setChildArchivedOrder("DT.shortName","asc");'><img src='images/arrowup<?php if ($childArchivedOrderBy == 'DT.shortName asc') echo "_sel"; ?>.gif' border=0></a>&nbsp;<a href='javascript:setChildArchivedOrder("DT.shortName","desc");'><img src='images/arrowdown<?php if ($childArchivedOrderBy == 'DT.shortName desc') echo "_sel"; ?>.gif' border=0></a></td></tr></table></th>
						<th style='width:120px;'><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Effective Date");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setChildArchivedOrder("D.effectiveDate","asc");'><img src='images/arrowup<?php if ($childArchivedOrderBy == 'D.effectiveDate asc') echo "_sel"; ?>.gif' border=0></a>&nbsp;<a href='javascript:setChildArchivedOrder("D.effectiveDate","desc");'><img src='images/arrowdown<?php if ($childArchivedOrderBy == 'D.effectiveDate desc') echo "_sel"; ?>.gif' border=0></a></td></tr></table></th>
						<th style='width:180px;'><table class='noBorderTable'><tr><td style='background-color: #e5ebef'><?php echo _("Signatures");?></td><td class='arrow' style='background-color: #e5ebef'><a href='javascript:setChildArchivedOrder("min(signatureDate) asc, min(signerName)","asc");'><img src='images/arrowup<?php if ($childArchivedOrderBy == 'min(signatureDate) asc, min(signerName) asc') echo "_sel"; ?>.gif' border=0></a>&nbsp;<a href='javascript:setChildArchivedOrder("max(signatureDate) desc, max(signerName)","desc");'><img src='images/arrowdown<?php if ($childArchivedOrderBy == 'max(signatureDate) desc, max(signerName) desc') echo "_sel"; ?>.gif' border=0></a></td></tr></table></th>
						<?php } ?>
						<th style='width:100px;'>&nbsp;</th>
						<?php if ($user->canEdit()){ ?>
						<th style='width:100px;'>&nbsp;</th>
						<?php } ?>
						</tr>

						<?php
						$childrenDocumentArray = $document->getChildrenDocuments($childOrderBy);
						$classAdd='';
						foreach($childrenDocumentArray as $childDocument) {

							$documentType = new DocumentType(new NamedArguments(array('primaryKey' => $childDocument->documentTypeID)));


							if (($childDocument->effectiveDate == "0000-00-00") || ($childDocument->effectiveDate == "")){
								$displayEffectiveDate = '';
							}else{
								$displayEffectiveDate = format_date($childDocument->effectiveDate);
							}

							if ((($childDocument->expirationDate == "0000-00-00") || ($childDocument->expirationDate == "")) && ($user->canEdit())){
								$displayExpirationDate = "<a href='javascript:archiveDocument(" . $childDocument->documentID . ");'>"._("archive document")."</a>";
							}else{
								$displayExpirationDate = _("archived on: ") . format_date($childDocument->expirationDate);
							}


							echo "<tr>";
							echo "<td $classAdd>" . $childDocument->shortName . "</td>";
							echo "<td $classAdd>" . $documentType->shortName . "</td>";
							echo "<td $classAdd>" . $displayEffectiveDate . "</td>";
							echo "<td $classAdd>";

							$signature = array();
							$signatureArray = $childDocument->getSignaturesForDisplay();

							if (count($signatureArray) > 0){
								echo "<table class='noBorderTable'>";


								foreach($signatureArray as $signature) {
									if (($signature['signatureDate'] != '') && ($signature['signatureDate'] != "0000-00-00")) {
										$signatureDate = format_date($signature['signatureDate']);
									}else{
										$signatureDate=_("(no date)");
									}

									echo "<tr>";
									echo "<td $classAdd>" . $signature['signerName'] . "</td>";
									echo "<td $classAdd>" . $signatureDate . "</td>";
									echo "</tr>";

								}
								echo "</table>";
								if ($user->canEdit()){
									echo "<a href='ajax_forms.php?action=getSignatureForm&height=270&width=460&modal=true&documentID=" . $childDocument->documentID . "' class='thickbox' id='signatureForm'>"._("add/view details")."</a>";
								}


							}else{
								echo _("(none found)")."<br />";
								if ($user->canEdit()){
									echo "<a href='ajax_forms.php?action=getSignatureForm&height=170&width=460&modal=true&documentID=" . $childDocument->documentID . "' class='thickbox' id='signatureForm'>"._("add signatures")."</a>";
								}
							}

							echo "</td>";

							echo "<td $classAdd>";
							if (!$user->isRestricted) {
								if ($childDocument->documentURL != ""){
									echo "<a href='documents/" . $childDocument->documentURL . "' target='_blank'>"._("view document")."</a><br />";
								}else{
									echo _("(none uploaded)")."<br />";
								}							}

							if (count($childDocument->getExpressions) > 0){
								echo "<a href='javascript:showExpressionForDocument(" . $childDocument->documentID . ");'>"._("view expressions")."</a>";
							}

							echo "</td>";

							if ($user->canEdit()){
								echo "<td $classAdd><a href='ajax_forms.php?action=getUploadDocument&height=285&width=305&modal=true&licenseID=" . $licenseID . "&documentID=" . $childDocument->documentID . "' class='thickbox' id='editDocument'>"._("edit document")."</a><br /><a href='javascript:deleteDocument(\"" . $childDocument->documentID . "\");'>"._("remove document")."</a>";
								//echo "<br />" . $displayExpirationDate . "</td>";
							}
							echo "</tr>";

							$numberOfChildren = $childDocument->getNumberOfChildren;

							if ($numberOfChildren > 0){
								if ($displayArchiveInd == '1') {
									echo "<tr><td colspan='6'><i>"._("The following ") . $numberOfChildren . _(" document(s) belong to ") . $childDocument->shortName . ".</i></td></tr>";
								}else{
									echo "<tr><td colspan='6'><i>"._("The following ") . $numberOfChildren . _(" document(s) belong to ") . $childDocument->shortName . ".</i></td></tr>";
								}
							}

						//end loop over child document records
						}

						echo "<tr><td colspan='6'>&nbsp;</td></tr>";
					//end display child if
					}
				//end number of children if
				}


			//end loop over document records
			}
			?>

		</table>

		<?php
		}else{
			if ($displayArchiveInd == ""){
				echo _("(none found)");
			}else if (($displayArchiveInd == "1") || ($numRows == "0")){
				//echo "(no archived documents found)";
			}else{
				echo "<i>" . $numRows . _(" archive(s) available.")."  <a href='javascript:updateArchivedDocuments(1)'>"._("show archives")."</a></i><br /><br />";
			}
		}


		if (($user->canEdit()) && ($displayArchiveInd != "")){
			echo "<a href='ajax_forms.php?action=getUploadDocument&licenseID=" . $licenseID . "&height=280&width=310&modal=true' class='thickbox' id='uploadDocument'>"._("upload new document")."</a>";
		}


		break;





	//display for expressions tab on license.php
	case 'getAllExpressions':

		$licenseID = $_GET['licenseID'];
		$documentID = $_GET['documentID'];



		//if 'view expressions' link is clicked on a specific document, we're just displaying expressions for that document
		//otherwise we're displaying expressions for all un-archived documents
		if ($documentID != ''){
			$document = new Document(new NamedArguments(array('primaryKey' => $documentID)));
			$documentArray = $document->getDocumentsForExpressionDisplay();
		}else{
			$license = new License(new NamedArguments(array('primaryKey' => $licenseID)));
			$documentArray = $license->getAllDocumentsForExpressionDisplay();
		}

		$util = new Utility();



		$numRows = count($documentArray);

		if ($numRows > 0){
			$documentObj = new Document();

			//documents are the outside loop, then we find expressions for each document in the loop
			foreach($documentArray as $documentObj) {

			?>

				<b><?php echo _("For Document:");?>  </b><?php echo $documentObj->shortName; ?>

				<table class='verticalFormTable'>
				<tr>
				<th style='width:80px;'><?php echo _("Type");?></th>
				<th><?php echo _("Document Text");?></th>
				<?php if ($user->canEdit()){ ?>
					<th><?php echo _("Qualifier");?></th>
					<th>&nbsp;</th>
				<?php } ?>
				</tr>

				<?php



				$expressionArray = $documentObj->getExpressionsForDisplay();

				foreach($expressionArray as $expressionIns) {
					$expression = new Expression(new NamedArguments(array('primaryKey' => $expressionIns['expressionID'])));


					//get qualifiers set up for this expression
					$sanitizedInstance = array();
					$instance = new Qualifier();
					$qualifierArray = array();
					foreach ($expression->getQualifiers() as $instance) {
						$qualifierArray[]=$instance->shortName;
					}
					?>
					<tr>
					<td class='alt'>
						<table class='noBorderTable'>
						<tr>
						<td class='alt' ><?php echo $expressionIns['expressionTypeName']; ?>
						<?php
						//if not configured to use the terms tool, hide the production use in terms tool checkbox/display
						if ((strtoupper($expressionIns['noteType']) == 'DISPLAY') && ($util->useTermsTool())){
							if ($user->isAdmin()) {
								if ($expressionIns['productionUseInd'] == "1"){
									echo "</td><td class='alt' style='float: right;text-align:right;'><input type='checkbox' id='productionUseInd_" . $expressionIns['expressionID'] . "' name='productionUseInd_" . $expressionIns['expressionID'] . "' onclick='javascript:changeProdUse(" . $expressionIns['expressionID'] . ")' checked></td>";
								}else{
									echo "</td><td class='alt' style='float: right;text-align:right;'><input type='checkbox' id='productionUseInd_" . $expressionIns['expressionID'] . "' name='productionUseInd_" . $expressionIns['expressionID'] . "' onclick='javascript:changeProdUse(" . $expressionIns['expressionID'] . ")'></td>";
								}
							}else{
								if ($expressionIns['productionUseInd'] == "1"){
									echo "<br /><br /><i>"._("used in terms tool")."</i></td>";
								}
							}

						}
						?>
						</tr>
						</table>
						<span id='span_prod_use_<?php echo $expressionIns['expressionID']; ?>' class='redText'></span>
					</td>

					<?php
					echo "<td class='alt'>" . nl2br($expressionIns['documentText']) . "</td>";

					if ($user->canEdit()){
						echo "<td class='alt'>";

						if (count($qualifierArray) > 0){
							echo implode("<br />", $qualifierArray);
						}else{
							echo "&nbsp;";
						}


						echo "</td>";
						echo "<td class='alt'><a href='ajax_forms.php?action=getExpressionForm&licenseID=" . $licenseID . "&expressionID=" . $expressionIns['expressionID'] . "&height=420&width=345&modal=true' class='thickbox'>"._("edit")."</a>&nbsp;&nbsp;<a href='javascript:deleteExpression(" . $expressionIns['expressionID'] . ");'>"._("remove")."</a></td>";
					}
					echo "</tr>";

					if ($user->canEdit()){
						echo "<tr><td class='alt'>&nbsp;</td><td colspan='4' class='alt'>" . ucfirst($expressionIns['noteType']) . _(" Notes:")."  <ul class='moved'>";
					}else{
						echo "<tr><td class='alt'>&nbsp;</td><td colspan='2' class='alt'>" . ucfirst($expressionIns['noteType']) . _(" Notes:")."  <ul class='moved'>";
					}

					$expressionNoteArray = $expression->getExpressionNotes();

					$rowcount=0;
					foreach($expressionNoteArray as $expressionNoteObj) {
						echo "<li>";
						echo nl2br($expressionNoteObj->note);
						echo "</li>";
						$rowcount++;
					}

					if  ($rowcount == "0"){ echo _("(none)"); }

					echo "</ul>";

					//link to view/edit display notes
					if ($user->canEdit()){
						echo "<a href='ajax_forms.php?action=getExpressionNotesForm&height=330&width=440&modal=true&expressionID=" . $expressionIns['expressionID'] . "' class='thickbox' id='ExpressionNotes'>"._("add/view ") . lcfirst($expressionIns['noteType']) . _(" notes")."</a>";
					}
					echo "</td>";
					echo "</tr>";
					if ($user->canEdit()){
						echo "<tr><td colspan='4'>&nbsp;</td></tr>";
					}else{
						echo "<tr><td colspan='2'>&nbsp;</td></tr>";
					}


				}
				?>

			</table>

			<?php
			}

		}else{
			echo _("(none found)");
		}

		if ($user->canEdit()){
			echo "<br /><br /><a href='ajax_forms.php?action=getExpressionForm&licenseID=" . $licenseID . "&height=420&width=345&modal=true' class='thickbox' id='expression'>"._("add new expression")."</a>";
		}







		break;




	//generic admin data (lookup table) display - all tables have ID and shortName so we can simplify retrieving this data
	case 'getAdminList':
		$className = $_GET['tableName'];
		$instance = new $className();

		$resultArray = $instance->allAsArray();

		if (count($resultArray) > 0){
			?>
			<table class='dataTable' style='width:350px'>
				<?php

				foreach($resultArray as $result){
					echo "<tr>";
					echo "<td>" . $result['shortName'] . "</td>";
					echo "<td style='width:70px'><a href='ajax_forms.php?action=getAdminUpdateForm&tableName=" . $className . "&updateID=" . $result[lcfirst($className) . 'ID'] . "&height=130&width=250&modal=true' class='thickbox' id='expression'>"._("edit")."</a></td>";
					echo "<td style='width:50px'><a href='javascript:deleteData(\"" . $className . "\",\"" . $result[lcfirst($className) . 'ID'] . "\")'>"._("remove")."</a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo _("(none found)");
		}

		break;


	//display user info for admin screen
	case 'getAdminUserList':

		$instanceArray = array();
		$user = new User();
		$tempArray = array();
		$util = new Utility();

		if (count($user->allAsArray()) > 0){

			?>
			<table class='dataTable' style='width:550px'>
				<tr>
				<th><?php echo _("Login ID");?></th>
				<th><?php echo _("First Name");?></th>
				<th><?php echo _("Last Name");?></th>
				<th><?php echo _("Privilege");?>

				</th>
				<?php
				//if not configured to use terms tool, hide the Terms Tool Update Email
				if ($util->useTermsTool()){
					echo "<th>"._("Terms Tool Update Email")."</th>";
				}
				?>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<?php

				foreach($user->allAsArray() as $instance) {
					$privilege = new Privilege(new NamedArguments(array('primaryKey' => $instance['privilegeID'])));

					echo "<tr>";
					echo "<td>" . $instance['loginID'] . "</td>";
					echo "<td>" . $instance['firstName'] . "</td>";
					echo "<td>" . $instance['lastName'] . "</td>";
					echo "<td>" . $privilege->shortName . "</td>";
					//if not configured to use SFX, hide the Terms Tool Update Email
					if ($util->useTermsTool()){
						echo "<td>" . $instance['emailAddressForTermsTool'] . "</td>";
					}
					echo "<td style='width:30px'><a href='ajax_forms.php?action=getAdminUserUpdateForm&loginID=" . $instance['loginID'] . "&height=210&width=295&modal=true' class='thickbox' id='expression'>"._("update")."</a></td>";
					echo "<td style='width:50px'><a href='javascript:deleteUser(\"" . $instance['loginID'] . "\")'>"._("remove")."</a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo _("(none found)");
		}

		break;






	//display expression type list for admin screen - needs its own display because of note type
	case 'getExpressionTypeList':

		$instanceArray = array();
		$expressionType = new ExpressionType();
		$tempArray = array();

		foreach ($expressionType->allAsArray() as $tempArray) {
			array_push($instanceArray, $tempArray);
		}



		if (count($instanceArray) > 0){

			?>
			<table class='dataTable' style='width:400px'>
				<tr>
				<th><?php echo _("Expression Type");?></th>
				<th><?php echo _("Note Type");?></th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<?php

				foreach($instanceArray as $instance) {
					echo "<tr>";
					echo "<td>" . $instance['shortName'] . "</td>";
					echo "<td>" . $instance['noteType'] . "</td>";
					echo "<td style='width:30px'><a href='ajax_forms.php?action=getExpressionTypeForm&expressionTypeID=" . $instance['expressionTypeID'] . "&height=158&width=265&modal=true' class='thickbox'>"._("update")."</a></td>";
					echo "<td style='width:50px'><a href='javascript:deleteExpressionType(\"" . $instance['expressionTypeID'] . "\")'>"._("remove")."</a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo _("(none found)");
		}

		break;

	//display expression type list for admin screen - needs its own display because of note type
	case 'getCalendarSettingsList':

		$instanceArray = array();
		$calendarSettings = new CalendarSettings();
		$tempArray = array();

		foreach ($calendarSettings->allAsArray() as $tempArray) {
			array_push($instanceArray, $tempArray);
		}

		if (count($instanceArray) > 0){

			?>
			<table class='dataTable' style='width:400px'>
				<tr>
				<th><?php echo _("Setting");?></th>
				<th><?php echo _("Value");?></th>
				<th>&nbsp;</th>

				<?php

				foreach($instanceArray as $instance) {
					echo "<tr>";
					echo "<td>" . $instance['shortName'] . "</td>";
					echo "<td>";
						if (strtolower($instance['shortName']) == strtolower('Authorized Site(s)')) { 
							$display = array();
							$authorizedSite = new AuthorizedSite();
							$siteCount = 0;
                            $authorizedSitesArray = $authorizedSite->getAllAuthorizedSite();
                            if ($authorizedSitesArray['authorizedSiteID']) {
                                $authorizedSitesArray = array($authorizedSitesArray);
                            }

								foreach($authorizedSitesArray as $display) {
									if (in_array($display['authorizedSiteID'], explode(",", $instance['value']))) {
										if ($siteCount > 0) {
											echo ", ";
										}
										echo $display['shortName'];
										$siteCount = $siteCount + 1;
									}	
								}
						} elseif (strtolower($instance['shortName']) == strtolower('Resource Type(s)')) {
							$display = array();
							$resourceType = new ResourceType();
							$siteCount = 0;
								foreach($resourceType->getAllResourceType() as $display) {
									if (in_array($display['resourceTypeID'], explode(",", $instance['value']))) {
										if ($siteCount > 0) {
											echo ", ";
										}
										echo $display['shortName'];
										$siteCount = $siteCount + 1;
									}	
								}							
						} else {
							echo $instance['value'];
						}
					echo "</td>";						
						
					
					echo "<td style='width:30px'><a href='ajax_forms.php?action=getCalendarSettingsForm&calendarSettingsID=" . $instance['calendarSettingsID'] . "&height=158&width=265&modal=true' class='thickbox'>"._("edit")."</a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo _("(none found)");
		}

		break;


	//display qualifier list for admin screen - needs its own display because of expression type
	case 'getQualifierList':



		$expressionType = new ExpressionType();


		?>
		<table class='dataTable' style='width:400px'>
			<tr>
			<th><?php echo _("For Expression Type");?></th>
			<th><?php echo _("Qualifier");?></th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<?php

			foreach($expressionType->all() as $expressionTypeObj) {
				$i = 0; //counter to display expression type first time only
				foreach ($expressionTypeObj->getQualifiers() as $qualifier){
					if ($i == 0) $displayET = $expressionTypeObj->shortName; else $displayET = '&nbsp;';
					echo "<tr>";
					echo "<td>" . $displayET . "</td>";
					echo "<td>" . $qualifier->shortName . "</td>";
					echo "<td style='width:70px'><a href='ajax_forms.php?action=getQualifierForm&qualifierID=" . $qualifier->qualifierID . "&height=158&width=295&modal=true' class='thickbox'>"._("update")."</a></td>";
					echo "<td style='width:50px'><a href='javascript:deleteQualifier(\"" . $qualifier->qualifierID . "\")'>"._("remove")."</a></td>";
					echo "</tr>";
					$i++;
				}

			}

			?>
		</table>
		<?php

		break;



	//display qualifier dropdown - for the search (index.php)
	case 'getQualifierDropdownHTML':


		if (isset($_GET['expressionTypeID'])){
			$selectedValue = '';
			$reset = '';

			if (isset($_GET['page'])){
				$selectedValue = $_SESSION['license_qualifierID'];
				$reset = $_GET['reset'];
			}

			$expressionTypeID = $_GET['expressionTypeID'];
			$expressionType = new ExpressionType(new NamedArguments(array('primaryKey' => $expressionTypeID)));

			$qualifierArray = array();

			$qualifierArray = $expressionType->getQualifiers();

			if (count($qualifierArray) > 0 ) {
				if (!isset($_GET['page'])) echo "<b>"._("Limit by Qualifier:")."</b>";
			?>
				<select name='qualifierID' id='qualifierID' <?php if ((isset($_GET['page']))) echo "style='width:150px'"; ?> onchange='javsacript:updateSearch();'>
				<option value='' <?php if ((!$selectedValue) || ($reset == 'Y')) echo "selected"; ?> ></option>
				<?php

				foreach($qualifierArray as $qualifier) {
					if (($selectedValue == $qualifier->qualifierID) && ($reset != 'Y')){
						echo "<option value='" . $qualifier->qualifierID . "' selected>" . $qualifier->shortName . "</option>\n";
					}else{
						echo "<option value='" . $qualifier->qualifierID . "'>" . $qualifier->shortName . "</option>\n";
					}

				}

				?>
				</select>

			<?php
			}

		}

		break;









	//display qualifier dropdown - for the expression form
	case 'getQualifierCheckboxHTML':

		if (isset($_GET['expressionTypeID'])){
			$expressionTypeID = $_GET['expressionTypeID'];
			$expressionType = new ExpressionType(new NamedArguments(array('primaryKey' => $expressionTypeID)));

			$qualifierArray = array();

			$qualifierArray = $expressionType->getQualifiers();

			$i=0;
			if (count($qualifierArray) > 0){
				echo "<table>";
				//loop over all qualifiers available for this expression type
				foreach ($qualifierArray as $expressionQualifierIns){
					$i++;
					if(($i % 2)==1){
						echo "<tr>\n";
					}
					echo "<td><input class='check_Qualifiers' type='checkbox' name='" . $expressionQualifierIns->qualifierID . "' id='" . $expressionQualifierIns->qualifierID . "' value='" . $expressionQualifierIns->qualifierID . "' />   " . $expressionQualifierIns->shortName . "</td>\n";

					if(($i % 2)==0){
						echo "</tr>\n";
					}
				}

				if(($i % 2)==1){
					echo "<td>&nbsp;</td></tr>\n";
				}

				echo "</table>";
			}


		}

		break;

	case 'getRightPanel':
		$licenseID = $_GET['licenseID'];
		$license = new License(new NamedArguments(array('primaryKey' => $licenseID)));
		$config = new Configuration;

		//get resources (already returned in array)
		$resourceArray = $license->getResourceArray();

		if ((count($resourceArray) > 0) && ($config->settings->resourcesModule == 'Y')) {

		?>
			<div style='background-color:white; width:219px; padding:7px;'>
				<div class='rightPanelHeader'><?php echo _("Resources Module");?></div>

				<?php
				foreach ($resourceArray as $resource){
					echo "<div class='rightPanelLink'><a href='" . $util->getResourceURL() . $resource['resourceID'] . "' target='_blank' class='helpfulLink'>" . $resource['resource'] . "</a></div>";
				}

				?>

			</div>

		<?php

	}

		break;

	default:
       echo _("Action ") . $action . _(" not set up!");
       break;


}



?>
