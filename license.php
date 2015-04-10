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
*/

include_once 'directory.php';

$licenseID=$_GET['licenseID'];
$license = new License(new NamedArguments(array('primaryKey' => $licenseID)));

//set this to turn off displaying the title header in header.php
$pageTitle=$license->shortName;
$noHead=1;
include 'templates/header.php';

//set referring page
$_SESSION['ref_script']=$currentPage;


//determine if we should display the SFX tab - if user is admin and if configured in settings to use SFX
$util = new Utility();
$displaySFX = 0;
if (($user->isAdmin()) && ($util->useTermsTool())){
	$displaySFX=1;
}

//as long as license id is valid...
if ($license->shortName){

?>


<input type='hidden' name='licenseID' id='licenseID' value='<?php echo $licenseID; ?>'>

<div id='div_licenseHead'>

</div>

</center>

<input type='hidden' name='licenseID' id='licenseID' value='<?php echo $license->licenseID; ?>'>

<div style="width: 899px;" id ='div_displayDocuments'>
	<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
		<tr>
			<td class="sidemenu">
				<div class="sidemenuselected" style='position: relative; width: 91px'><a href='javascript:void(0)' class='showDocuments'><?= _("Documents");?></a></div>
				<div class='sidemenuunselected'><a href='javascript:void(0)' class='showExpressions'><?= _("Expressions");?></a></div>
				<?php if ($displaySFX == "1"){ ?><div class='sidemenuunselected'><a href='javascript:void(0)' class='showSFXProviders'><?= _("Terms Tool");?></a></div><?php } ?>
				<div class='sidemenuunselected'><a href='javascript:void(0)' class='showAttachments'><?= _("Attachments");?></a><br />&nbsp;<span class='span_AttachmentNumber'></span></div>
			</td>
			<td class='mainContent'>

				<div id='div_documents'>
				<img src = "images/circle.gif"><?= _("Loading...");?>
				</div>
				<br />
				<div id='div_archives'>
				</div>
			</td>
		</tr>
	</table>
</div>



<div id ='div_displayExpressions' style='display:none;width:899px;'>
	<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
		<tr>
			<td class="sidemenu">
				<div class="sidemenuunselected"><a href='javascript:void(0)' class='showDocuments'><?= _("Documents");?></a></div>
				<div class='sidemenuselected' style='position: relative; width: 91px'><a href='javascript:void(0)' class='showExpressions'><?= _("Expressions");?></a></div>
				<?php if ($displaySFX == "1"){ ?><div class='sidemenuunselected'><a href='javascript:void(0)' class='showSFXProviders'><?= _("Terms Tool");?></a></div><?php } ?>
				<div class='sidemenuunselected'><a href='javascript:void(0)' class='showAttachments'><?= _("Attachments");?></a><br />&nbsp;<span class='span_AttachmentNumber'></span></div>
			</td>
			<td class='mainContent'>

				<div id='div_expressions'>
				<img src = "images/circle.gif"><?= _("Loading...");?>
				</div>
			</td>
		</tr>
	</table>
</div>


<div id ='div_displaySFXProviders' style='display:none;width:899px;'>
	<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
		<tr>
			<td class="sidemenu">
				<div class="sidemenuunselected"><a href='javascript:void(0)' class='showDocuments'><?= _("Documents");?></a></div>
				<div class='sidemenuunselected'><a href='javascript:void(0)' class='showExpressions'><?= _("Expressions");?></a></div>
				<div class='sidemenuselected' style='position: relative; width: 91px'><a href='javascript:void(0)' class='showSFXProviders'><?= _("Terms Tool");?></a></div>
				<div class='sidemenuunselected'><a href='javascript:void(0)' class='showAttachments'><?= _("Attachments");?></a><br />&nbsp;<span class='span_AttachmentNumber'></span></div>
			</td>
			<td class='mainContent'>
				<div id='div_sfxProviders'>
				<img src = "images/circle.gif"><?= _("Loading...");?>
				</div>
			</td>
		</tr>
	</table>
</div>


<div id ='div_displayAttachments' style='display:none;width:899px;'>
	<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
		<tr>
			<td class="sidemenu">
				<div class="sidemenuunselected"><a href='javascript:void(0)' class='showDocuments'><?= _("Documents");?></a></div>
				<div class='sidemenuunselected'><a href='javascript:void(0)' class='showExpressions'><?= _("Expressions");?></a></div>
				<?php if ($displaySFX == "1"){ ?><div class='sidemenuunselected'><a href='javascript:void(0)' class='showSFXProviders'><?= _("Terms Tool");?></a></div><?php } ?>
				<div class='sidemenuselected' style='position: relative; width: 91px'><a href='javascript:void(0)' class='showAttachments'><?= _("Attachments");?></a><br />&nbsp;<span class='span_AttachmentNumber'></span></div>
			</td>
			<td class='mainContent'>

				<div id='div_attachments'>
				<img src = "images/circle.gif"><?= _("Loading...");?>
				</div>
			</td>
		</tr>
	</table>
</div>


<script type="text/javascript" src="js/license.js"></script>

<?php
} //end license validity check

include 'templates/footer.php';
?>

