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

$pageTitle='Terms Report';
include 'templates/header.php';

//set referring page
$_SESSION['ref_script']=$currentPage;

?>


<table class="headerTable">
<tr><td>
<br />

<b>Limit by Expression Type:</b>

<select name='expressionTypeID' id='expressionTypeID' onchange='javascript:updateTermsReport();'>

<?php

	$display = array();
	$expressionType = new ExpressionType();

	foreach($expressionType->allAsArray() as $display) {
		if (($display['noteType'] == 'Display') && ($display['shortName'] != "Interlibrary Loan (additional notes)")){
			if ($display['shortName'] == "Interlibrary Loan"){
				echo "<option value='" . $display['expressionTypeID'] . "' selected>" . $display['shortName'] . "</option>";
			}else{
				echo "<option value='" . $display['expressionTypeID'] . "'>" . $display['shortName'] . "</option>";
			}
		}
	}

?>

</select>


<br />

<div id='div_report'>

</div>

</td>
</tr>
</table>
</center>

<script type="text/javascript" src="js/terms_report.js"></script>
<?php
include 'templates/footer.php';
?>

