<?
class UserInfo {

	function UserInfo() {
		$this->reset();
	}
	var $_result;
	function reset() {
		$this->userid =
		$this->loginid =
		$this->password =
		$this->name =
		$this->created =
		$this->lastLogin =
		$this->host =
		$this->_result =
			null;
	}
	
	function getUser() {
		global $database, $owner;
		if (is_null($this->userid)) {
			$_result = fetchQueryRow("SELECT * FROM {$database['prefix']}Users WHERE userid = $owner");
			foreach($_result as $key => $value) {
				if(!is_numeric($key)) {
					$this->$key = $value;
				}
			}
			return ;
		}
	}
	
	function add() {
		global $database, $owner;
		if(!isset($this->userid))		
			return $this->_error('insert');
		if(!isset($this->password))		
			return $this->_error('password');
		if(!isset($this->name))		
			return $this->_error('name');
		if(!isset($this->created))		
			return $this->_error('created');
		if(!isset($this->lastLogin))		
			return $this->_error('lastLogin');
		if(!isset($this->host))		
			return $this->_error('host');		
		if (!$query = $this->_buildQuery())
			return $this->_error('query generate error');		
		if (!$query->insert())
			return $this->_error('insert');
			$this->id = $query->id;
		return true;
	}
	
	function update() {
		global $database, $owner;
		if(!isset($this->userid))		
			return $this->_error('insert');
		if (!$query = $this->_buildQuery())
			return $this->_error('query generate error');		
		if (!$query->update())
			return $this->_error('update');
			$this->id = $query->id;
		return true;
	}
	
	function getUserid() {
		if (is_null($this->userid)) 
			$this->getUser();
		return $this->userid;
	}
	
	function getLoginid() {
		if (is_null($this->loginid)) 
			$this->getUser();
		return $this->loginid;
	}
	
	function getPassword() {
		if (is_null($this->password)) 
			$this->getUser();
		return $this->password;
	}
	
	function getName() {
		if (is_null($this->name)) 
			$this->getUser();
		return $this->name;
	}
	
	function getCreated() {
		if (is_null($this->created)) 
			$this->getUser();
		return $this->created;
	}
	
	function getLastLogin() {
		if (is_null($this->lastLogin)) 
			$this->getUser();
		return $this->LastLogin;
	}
	
	function getHost() {
		if (is_null($this->host)) 
			$this->getUser();
		return $this->host;
	}
	
	function close() {
		if (isset($this->_result)) {
			mysql_free_result($this->_result);
			unset($this->_result);
		}
		$this->_count = 0;
		$this->reset();
	}

	function _buildQuery() {
		global $database, $owner;
		$query = new TableQuery($database['prefix'] . 'Users');
		$query->setQualifier('userid', $owner);
		
		if (isset($this->userid)) {
			if (!Validator::number($this->userid, 1))
				return $this->_error('userid');
			$query->setQualifier('userid', $this->userid);
		}
		
		if (isset($this->loginid)) {
			$this->loginid = trim($this->loginid);
			if(empty($this->loginid))
				return $this->_error('loginid');
			$query->setAttribute('loginid', $this->loginid,true);
		}
		
		if (isset($this->password)) {
			$this->password = trim($this->password);
			if(empty($this->password))
				return $this->_error('password');
			$query->setAttribute('password', $this->password,true);
		}
	
		if (isset($this->name)) {
			$this->name = trim($this->name);
			if(empty($this->name))
				return $this->_error('name');
			$query->setAttribute('name', $this->name,true);
		}
		
		if (isset($this->created)) {
			if (!Validator::number($this->created, 0))
				return $this->_error('created');
			$query->setAttribute('created', $this->created);
		}
		
		if (isset($this->lastLogin)) {
			if (!Validator::number($this->lastLogin, 1))
				return $this->_error('lastLogin');
			$query->setAttribute('lastLogin', $this->lastLogin);
		}
		
		if (isset($this->host)) {
			if (!Validator::number($this->host, 0))
				return $this->_error('host');
			$query->setAttribute('host', $this->host);
		}
		return $query;
	}

	function _error($error) {
		$this->error = $error;
		return false;
	}
	
}
?>