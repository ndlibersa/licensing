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


class Attachment extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}




	//returns array of AttachmentFile objects
	public function getAttachmentFiles(){

		$query = "SELECT * FROM AttachmentFile where attachmentID = '" . $this->attachmentID . "' ORDER BY attachmentFileID";

		$result = $this->db->processQuery($query, 'assoc');

		$objects = array();

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['attachmentFileID'])){

			$object = new AttachmentFile(new NamedArguments(array('primaryKey' => $result['attachmentFileID'])));
			array_push($objects, $object);
		}else{
			foreach ($result as $row) {
				$object = new AttachmentFile(new NamedArguments(array('primaryKey' => $row['attachmentFileID'])));
				array_push($objects, $object);
			}
		}

		return $objects;
	}


}

?>