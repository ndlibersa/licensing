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


class LdapPerson extends DynamicObject {

	public function __construct($userKey) {

		$config = new Configuration;

		if ($ds = ldap_connect($config->ldap->host)) {

			$bd = ldap_bind($ds);

			$filter = $config->ldap->search_key . "=" . $userKey;

			$sr = ldap_search($ds, $config->ldap->base_dn, $filter);

			if ($entries = ldap_get_entries($ds, $sr)) {
				$entry = $entries[0];

				$fieldNames = array('fname', 'lname', 'email', 'phone', 'department', 'title', 'address');

				foreach ($fieldNames as $fieldName) {
					$configName = $fieldName . '_field';

					$this->$fieldName = $entry[$config->ldap->$configName][0];

				}
				$this->fullname = addslashes($this->fname . ' ' . $this->lname);

			}

			ldap_close($ds);

		}

	}

}

?>