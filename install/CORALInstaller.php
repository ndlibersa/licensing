<?php
require_once('../directory.php');

if (!function_exists('debug')) {
  function debug($value) {
    echo '<pre>'.print_r($value, true).'</pre>';
  }
}

class CORALInstaller {
  
  protected $db;
  protected $db_error;
  protected $config;
  
  public function __construct() {
    if (is_file($this->configFilePath())) {
      $this->config = new Configuration();
      $this->connect();
    }
  }
  
  protected function connect() {
    $this->db_error = '';
		$host = $this->config->database->host;
		$username = $this->config->database->username;
		$password = $this->config->database->password;
		$this->db = mysql_connect($host, $username, $password);
		$this->db_error = mysql_error($this->db);
		if (!$this->db_error) {
  		$databaseName = $this->config->database->name;
  		mysql_select_db($databaseName, $this->db);
  		$this->db_error = mysql_error($this->db);
		}
		if ($this->db_error) {
		  $this->db = null;
		}
	}
	
	public function query($sql) {
		$result = mysql_query($sql, $this->db);
		
		$this->checkForError();
		$data = array();

		if (is_resource($result)) {
			while ($row = mysql_fetch_array($result)) {
				array_push($data, $row);
			}
		} else if ($result) {
			$data = mysql_insert_id($this->db);
		}

		return $data;
	}
	
	protected function checkForError() {
		if ($this->db_error = mysql_error($this->db)) {
			throw new Exception("There was a problem with the database: " . $this->db_error);
		}
	}
  
  public function modulePath() {
    //returns file path for this module, i.e. /coral/licensing/
    return preg_replace("/\/install$/", "", dirname(__FILE__));
  }

  public function configFilePath() {
    return $this->modulePath().'/admin/configuration.ini';
  }
  
  public function isDatabaseConfigValid() {
    return $this->config && $this->db;
  }
  
  public function hasPermission($permission) {
    if ($this->isDatabaseConfigValid()) {
      $grants = array();
      foreach ($this->query("SHOW GRANTS FOR CURRENT_USER()") as $row) {
        $grant = $row[0];
        if (strpos(str_replace('\\', '', $grant), $this->config->database->name) !== false) {
          if (preg_match("/(GRANT|,) $permission(,| ON)/i",$grant)) {
            return true;
          }
        }
      }
    }
    return false;
  }
  
  public function installed() {
    if ($this->isDatabaseConfigValid()) {
      $table_names = array();
      $test_table_names = array("License","Document","Expression");
      foreach ($this->query("SHOW TABLES") as $row) {
        $table_names []= $row[0];
      }
      foreach ($test_table_names as $name) {
        if (!in_array($name, $table_names)) {
          return false;
        }
      }
      return true;
    }
    return false;
  }
}
?>