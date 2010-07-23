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


class ExpressionNote extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}



	//reorders expression note based on direction the user clicked
	public function reorder($dir, $oldSeq){

		if ($dir == "up"){
			$newSeq = $oldSeq - 1;

			//move note currently occupying the new position
 			$query = "UPDATE ExpressionNote SET displayOrderSeqNumber = '" . $oldSeq . "'
 						WHERE displayOrderSeqNumber = '" . $newSeq . "' AND expressionID = '" . $this->expressionID . "';";

			$this->db->processQuery($query);

			//move note to new position
 			$query = "UPDATE ExpressionNote SET displayOrderSeqNumber = '" . $newSeq . "'
 						WHERE expressionNoteID = '" . $this->expressionNoteID . "';";

			$this->db->processQuery($query);


		}else{  //down
			$newSeq = $oldSeq + 1;

			//move note currently occupying the new position
 			$query = "UPDATE ExpressionNote SET displayOrderSeqNumber = '" . $oldSeq . "'
 						WHERE displayOrderSeqNumber = '" . $newSeq . "' AND expressionID = '" . $this->expressionID . "';";

			$this->db->processQuery($query);

			//move note to new position
 			$query = "UPDATE ExpressionNote SET displayOrderSeqNumber = '" . $newSeq . "'
 						WHERE expressionNoteID = '" . $this->expressionNoteID . "';";

			$this->db->processQuery($query);

		}


	}


}

?>