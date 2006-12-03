<?php

function EAS_Call($type, $name, $title, $url, $content)
{
	requireComponent('Eolin.PHP.Core');
	requireComponent('Eolin.PHP.XMLRPC');
	
	global $hostURL, $blogURL, $database;
	
	$blogstr = $hostURL . $blogURL;
	
	$rpc = new XMLRPC();
	$rpc->url = 'http://antispam.eolin.com/RPC/index.php';
	if ($rpc->call('checkSpam', $blogstr, $type, $name, $title, $url, $content, $_SERVER['REMOTE_ADDR']) == false) 
	{
		// call fail
		// Do Local spam check with "Thief-cat algorithm"
		$count = 0;
		$tableName = $database['prefix'] . 'Trackbacks';
			
		if ($type == 2) // Trackback Case
		{
			$sql = 'SELECT COUNT(id) as cc FROM ' . $database['prefix'] . 'Trackbacks WHERE';
			$sql .= ' url = \'' . mysql_tt_escape_string($url) . '\'';
			$sql .= ' AND isFiltered > 0';
			
			if ($result = mysql_query($sql)) {
				$row = mysql_fetch_row($result);
				$count += @$row[0];
			}
			
		} else { // Comment Case
			$tableName = $database['prefix'] . 'Comments';	

			$sql = 'SELECT COUNT(id) as cc FROM ' . $database['prefix'] . 'Comments WHERE';
			$sql .= ' comment = \'' . mysql_tt_escape_string($content) . '\'';
			$sql .= ' AND homepage = \'' . mysql_tt_escape_string($url) . '\'';
			$sql .= ' AND name = \'' . mysql_tt_escape_string($name) . '\'';
			$sql .= ' AND isFiltered > 0';
			
			if ($result = mysql_query($sql)) {
				$row = mysql_fetch_row($result);
				$count += @$row[0];
			}
		}

		// Check IP
		$sql = 'SELECT COUNT(id) as cc FROM ' . $tableName . ' WHERE';
		$sql .= ' ip = \'' . mysql_tt_escape_string($_SERVER['REMOTE_ADDR']) . '\'';
		$sql .= ' AND isFiltered > 0';

		if ($result = mysql_query($sql)) {
			$row = mysql_fetch_row($result);
			$count += @$row[0];
		}
		
		if ($count >= 10) {
			return false;
		}
		
		return true;
	}
	
	if (!is_null($rpc->fault)) {
		// EAS has some problem
		return true;
	}
	
	if ($rpc->result['result'] == true) {
		return false; // it's spam
	}
	
	return true;
}

function EAS_AddingTrackback($target, $mother)
{
	return EAS_Call(2, $mother['site'], $mother['title'], $mother['url'], $mother['excerpt']);
}

function EAS_AddingComment($target, $mother)
{
	global $owner, $user;
	if ($mother['secret'] ==  true) // it's secret(only owner can see it)
	{
		// Don't touch
		return $target;
	}
	
	$type = 1; // comment
	if ($mother['entry'] == 0) $type = 3; // guestbook
	
	return $target && EAS_Call($type, $mother['name'], '', $mother['homepage'], $mother['comment']);
}

?>