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

// Useful directory constants, ending with |/|.
define('ADMIN_DIR', dirname(__FILE__) . '/admin/');
define('BASE_DIR', dirname(__FILE__) . '/');
define('CLASSES_DIR', ADMIN_DIR . 'classes/');

// Automatically load undefined classes from subdirectories of |CLASSES_DIR|.
function __autoload( $className ) {
	if (file_exists(CLASSES_DIR) && is_readable(CLASSES_DIR) && is_dir(CLASSES_DIR)) {
		$directory = dir(CLASSES_DIR);

		// Iterate over the files and directories in |CLASSES_DIR|.
		while (false !== ($entry = $directory->read())) {
			$path = CLASSES_DIR . $entry;

			// Look only at subdirectories
			if (is_dir($path)) {
				$filename = $path . '/' . $className . '.php';
				if (file_exists($filename) && is_readable($filename) && is_file($filename)) {
					// Could probably safely use |require()| here, since |__autoload()| is only called when a class isn't loaded.
					require_once($filename);
				}
			}
		}
		$directory->close();
	}
}

// Add lcfirst() for PHP < 5.3.0
if (false === function_exists('lcfirst')) {
	function lcfirst($string) {
		return strtolower(substr($string, 0, 1)) . substr($string, 1);
	}
}


//fix default timezone for PHP > 5.3
if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get")){
	@date_default_timezone_set(@date_default_timezone_get());
}


function format_date($mysqlDate) {

	//see http://php.net/manual/en/function.date.php for options

	//there is a dependence on strtotime recognizing date format for date inputs
	//thus, european format (d-m-Y) must use dashes rather than slashes

	//upper case Y = four digit year
	//lower case y = two digit year
	//make sure digit years matches for both directory.php and common.js

	//SUGGESTED: "m/d/Y" or "d-m-Y"

	return date("m/d/Y", strtotime($mysqlDate));

}

// Verify the language of the browser
    if(isset($_COOKIE["lang"])){
        $http_lang = $_COOKIE["lang"];
    }else{
        $http_lang = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
    }
    switch ($http_lang) {
        case 'fr': 
            $language = "fr_FR.utf8"; 
        break;	
        case 'en': 
            $language = "en_US.utf8"; 
        break;			
        default: 
            $language = "en_US.utf8"; 
        break;
    }
    putenv("LC_ALL=$language");
	setlocale(LC_ALL, $language);
	bindtextdomain("messages", "./locale");
	textdomain("messages");
?>