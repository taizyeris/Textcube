<?php
/// Copyright (c) 2004-2009, Needlworks / Tatter Network Foundation
/// All rights reserved. Licensed under the GPL.
/// See the GNU General Public License for more details. (/doc/LICENSE, /doc/COPYRIGHT)

//require 'common.correctTT.php';

function getTrashTrackbackWithPagingForOwner($blogid, $category, $site, $url, $ip, $search, $page, $count) {
	global $database;
	
	$postfix = '';
	$sql = "SELECT t.*, c.name categoryName 
		FROM {$database['prefix']}RemoteResponses t 
		LEFT JOIN {$database['prefix']}Entries e ON t.blogid = e.blogid AND t.entry = e.id AND e.draft = 0 
		LEFT JOIN {$database['prefix']}Categories c ON t.blogid = c.blogid AND e.category = c.id 
		WHERE t.blogid = $blogid AND t.isFiltered > 0 AND t.type = 'trackback'";
	if ($category > 0) {
		$categories = Data_IAdapter::queryColumn("SELECT id FROM {$database['prefix']}Categories WHERE blogid = $blogid AND parent = $category");
		array_push($categories, $category);
		$sql .= ' AND e.category IN (' . implode(', ', $categories) . ')';
		$postfix .= '&amp;category=' . rawurlencode($category);
	} else
		$sql .= ' AND e.category >= 0';
	if (!empty($site)) {
		$sql .= ' AND t.site = \'' . Data_IAdapter::escapeString($site) . '\'';
		$postfix .= '&amp;site=' . rawurlencode($site);
	}
	if (!empty($url)) {
		$sql .= ' AND t.url = \'' . Data_IAdapter::escapeString($url) . '\'';
		$postfix .= '&amp;url=' . rawurlencode($url);
	}
	if (!empty($ip)) {
		$sql .= ' AND t.ip = \'' . Data_IAdapter::escapeString($ip) . '\'';
		$postfix .= '&amp;ip=' . rawurlencode($ip);
	}
	if (!empty($search)) {
		$search = Data_IAdapter::escapeSearchString($search);
		$sql .= " AND (t.site LIKE '%$search%' OR t.subject LIKE '%$search%' OR t.excerpt LIKE '%$search%')";
		$postfix .= '&amp;search=' . rawurlencode($search);
	}
	$sql .= ' ORDER BY t.written DESC';
	list($trackbacks, $paging) =  fetchWithPaging($sql, $page, $count);
	if (strlen($postfix) > 0) {
		$paging['postfix'] .= $postfix . '&amp;withSearch=on';
	}
	return array($trackbacks, $paging);
}


function getTrashCommentsWithPagingForOwner($blogid, $category, $name, $ip, $search, $page, $count) {
	global $database;
	$sql = "SELECT c.*, e.title, c2.name parentName 
		FROM {$database['prefix']}Comments c 
		LEFT JOIN {$database['prefix']}Entries e ON c.blogid = e.blogid AND c.entry = e.id AND e.draft = 0 
		LEFT JOIN {$database['prefix']}Comments c2 ON c.parent = c2.id AND c.blogid = c2.blogid 
		WHERE c.blogid = $blogid AND c.isFiltered > 0";

	$postfix = '';	
	if ($category > 0) {
		$categories = Data_IAdapter::queryColumn("SELECT id FROM {$database['prefix']}Categories WHERE parent = $category");
		array_push($categories, $category);
		$sql .= ' AND e.category IN (' . implode(', ', $categories) . ')';
		$postfix .= '&amp;category=' . rawurlencode($category);
	} else
		$sql .= ' AND (e.category >= 0 OR c.entry = 0)';
	if (!empty($name)) {
		$sql .= ' AND c.name = \'' . Data_IAdapter::escapeString($name) . '\'';
		$postfix .= '&amp;name=' . rawurlencode($name);
	}
	if (!empty($ip)) {
		$sql .= ' AND c.ip = \'' . Data_IAdapter::escapeString($ip) . '\'';
		$postfix .= '&amp;ip=' . rawurlencode($ip);
	}
	if (!empty($search)) {
		$search = Data_IAdapter::escapeSearchString($search);
		$sql .= " AND (c.name LIKE '%$search%' OR c.homepage LIKE '%$search%' OR c.comment LIKE '%$search%')";
		$postfix .= '&amp;search=' . rawurlencode($search);
	}
	$sql .= ' ORDER BY c.written DESC';
	list($comments, $paging) =  fetchWithPaging($sql, $page, $count);
	if (strlen($postfix) > 0) {
		$paging['postfix'] .= $postfix . '&amp;withSearch=on';
	}
	return array($comments, $paging);
}

function getTrackbackTrash($entry) {
	global $database;
	$trackbacks = array();
	$result = Data_IAdapter::queryAll("SELECT * 
			FROM {$database['prefix']}RemoteResponses 
			WHERE blogid = ".getBlogId()."
				AND entry = $entry 
			ORDER BY written",'assoc');
	if(!empty($result)) return $result;
	else return array();
}

function getRecentTrackbackTrash($blogid) {
	global $database;
	global $skinSetting;
	$trackbacks = array();
	$sql = doesHaveOwnership() ? "SELECT * FROM {$database['prefix']}RemoteResponses
		WHERE blogid = $blogid 
		ORDER BY written DESC LIMIT {$skinSetting['trackbacksOnRecent']}" : 
		"SELECT t.* FROM {$database['prefix']}RemoteResponses t, 
		{$database['prefix']}Entries e 
		WHERE t.blogid = $blogid AND t.blogid = e.blogid AND t.entry = e.id AND t.type = 'trackback' AND e.draft = 0 AND e.visibility >= 2 
		ORDER BY t.written DESC LIMIT {$skinSetting['trackbacksOnRecent']}";
	if ($result = Data_IAdapter::query($sql)) {
		while ($trackback = Data_IAdapter::fetch($result))
			array_push($trackbacks, $trackback);
	}
	return $trackbacks;
}

function deleteTrackbackTrash($blogid, $id) {
	global $database;
	$entry = Data_IAdapter::queryCell("SELECT entry FROM {$database['prefix']}RemoteResponses WHERE blogid = $blogid AND id = $id");
	if ($entry === null)
		return false;
	if (!Data_IAdapter::execute("DELETE FROM {$database['prefix']}RemoteResponses WHERE blogid = $blogid AND id = $id"))
		return false;
	if (updateTrackbacksOfEntry($blogid, $entry))
		return $entry;
	return false;
}

function restoreTrackbackTrash($blogid, $id) {
   	global $database;
	$entry = Data_IAdapter::queryCell("SELECT entry FROM {$database['prefix']}RemoteResponses WHERE blogid = $blogid AND id = $id");
	if ($entry === null)
		return false;
	if (!Data_IAdapter::execute("UPDATE {$database['prefix']}RemoteResponses SET isFiltered = 0 WHERE blogid = $blogid AND id = $id"))
		return false;
	if (updateTrackbacksOfEntry($blogid, $entry))
		return $entry;
	return false;
}

function trashVan() {
   	global $database;
	requireModel('common.setting');
	if(Timestamp::getUNIXtime() - getServiceSetting('lastTrashSweep',0) > 86400) {
		Data_IAdapter::execute("DELETE FROM {$database['prefix']}Comments where isFiltered < UNIX_TIMESTAMP() - 1296000 AND isFiltered > 0");
		Data_IAdapter::execute("DELETE FROM {$database['prefix']}RemoteResponses where isFiltered < UNIX_TIMESTAMP() - 1296000 AND isFiltered > 0");
		setServiceSetting('lastTrashSweep',Timestamp::getUNIXtime());
	}
	if(Timestamp::getUNIXtime() - getServiceSetting('lastNoticeRead',0) > 43200) {
		removeServiceSetting('TextcubeNotice%',true);
		setServiceSetting('lastNoticeRead',Timestamp::getUNIXtime());
	}
}

function emptyTrash($comment = true)
{
   	global $database;
	requireModel('common.setting');
	$blogid = getBlogId();
	if ($comment == true) {
		Data_IAdapter::execute("DELETE FROM {$database['prefix']}Comments where blogid = ".$blogid." and isFiltered > 0");
	} else {
		Data_IAdapter::execute("DELETE FROM {$database['prefix']}RemoteResponses where blogid = ".$blogid." and isFiltered > 0");
	}
}

?>
