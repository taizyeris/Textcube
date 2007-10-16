<?php
/// Copyright (c) 2004-2007, Needlworks / Tatter Network Foundation
/// All rights reserved. Licensed under the GPL.
/// See the GNU General Public License for more details. (/doc/LICENSE, /doc/COPYRIGHT)

DBQuery::bind($database);

function tc_escape_string($string, $link = null) {
	return DBQuery::escapeString($string, $link);
}

function escapeSearchString($str) {
	return is_string($str) ? str_replace('_', '\_', str_replace('%', '\%', tc_escape_string($str))) : $str;
}

function doesExistTable($tablename)
{
	requireModel('common.setting');

	global $database;
	static $tables = array();
	if( empty($tables) ) {
		$escapename = escapeSearchString($database['prefix']);
		$tables = DBQuery::queryColumn( "SHOW TABLES LIKE '{$escapename}%'" );
	}
	
	$dbCaseInsensitive = getServiceSetting('lowercaseTableNames');
	if($dbCaseInsensitive == null) {
		$result = DBQuery::queryRow("SHOW VARIABLES LIKE 'lower_case_table_names'");
		$dbCaseInsensitive = ($result['Value'] == 1) ? true : false;
		setServiceSetting('lowercaseTableNames',$dbCaseInsensitive);
	}
	if($dbCaseInsensitive == true) $tablename = strtolower($tablename);
	if( in_array( $tablename, $tables ) ) {
		return true;
	}
	return false;
}
?>
