<?
global $service;
global $openid_session_name, $openid_session_id, $openid_session, $openid_session_path;

$openid_session_name = 'openid_sessid';
$openid_session_id = '';
$openid_session = array();
$openid_session_path = "/";

function _openid_ip_address()
{
	return substr( "@{$_SERVER['REMOTE_ADDR']}", 0, 15 );
}

function _openid_new_session()
{
	global $openid_session_name, $openid_session_id;
	global $database, $service, $blogURL;
	global $openid_session_path;

	# OPENID용 세션은 그 ID가 "77XXX..." 형식으로 된다.
	$openid_session_id = dechex(rand(0x77000000, 0x77FFFFFF)) . dechex(rand(0x10000000, 0x7FFFFFFF)) . dechex(rand(0x10000000, 0x7FFFFFFF)) . dechex(rand(0x10000000, 0x7FFFFFFF));
	$result = mysql_query("INSERT INTO {$database['prefix']}Sessions(id, address, created, updated) VALUES('$openid_session_id', '" . _openid_ip_address() . "', UNIX_TIMESTAMP(), UNIX_TIMESTAMP())");
	if ( !headers_sent() && ($result !== false) && (mysql_affected_rows() > 0)) {
		setcookie( $openid_session_name, $openid_session_id, time()+$service['timeout'], $openid_session_path );
		gcSession();
		return true;
	}
	return false;
}

function openid_session_destroy()
{
	global $openid_session_id, $openid_session_name, $database;
	global $openid_session_path;

	if( !$openid_session_id )
	{
		return;
	}

	mysql_query("DELETE FROM {$database['prefix']}Sessions WHERE id = '$openid_session_id'");
	setcookie( $openid_session_name, '', time()-3600, $openid_session_path );
	$openid_session_id = "";
}

function openid_session_read()
{
	global $database, $service;
	global $openid_session_name, $openid_session_id, $openid_session;

	if( !empty($_COOKIE[$openid_session_name]) )
	{
		$openid_session_id = $_COOKIE[$openid_session_name];
	}

	if ($result = mysql_query("SELECT data FROM {$database['prefix']}Sessions WHERE id = '$openid_session_id' AND address = '" . _openid_ip_address() . "' AND updated >= (UNIX_TIMESTAMP() - {$service['timeout']})")) {
		if ( ($openid_session_rec = mysql_fetch_array($result)) )
		{
			if( !isset( $openid_session_rec['data'] ) ) {
				$openid_session = array();
			} else {
				$openid_session = @unserialize( $openid_session_rec['data'] );
			}
			return true;
		}
	}

	return _openid_new_session();
}

function openid_session_write()
{
	global $database, $service;
	global $openid_session_name, $openid_session_id, $openid_session;
	global $openid_session_path;
	global $sessionMicrotime;

	if (strlen($openid_session_id) < 32)
		return false;

	$data = serialize( $openid_session );
	$server = mysql_tt_escape_string($_SERVER['HTTP_HOST']);
	$request = mysql_tt_escape_string($_SERVER['REQUEST_URI']);
	$referer = isset($_SERVER['HTTP_REFERER']) ? mysql_tt_escape_string($_SERVER['HTTP_REFERER']) : '';
	$timer = getMicrotimeAsFloat() - $sessionMicrotime;
	$result = mysql_query("UPDATE {$database['prefix']}Sessions SET data = '$data', server = '$server', request = '$request', referer = '$referer', timer = $timer, updated = UNIX_TIMESTAMP() WHERE id = '$openid_session_id' AND address = '" . _openid_ip_address() . "'");

	if ($result && (mysql_affected_rows() == 1)) {
		@setcookie( $openid_session_name, $openid_session_id, time()+$service['timeout'], $openid_session_path );
		return true;
	}
	return false;
}

function openid_setcookie( $key, $value )
{
	global $openid_session_path;
	@setcookie( $key, $value, time()+3600*24*30, $openid_session_path );
}

function openid_clearcookie( $key )
{
	global $openid_session_path;
	@setcookie( $key, '', time()-3600, $openid_session_path );
}

?>
