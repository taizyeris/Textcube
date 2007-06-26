<?php
/// Copyright (c) 2004-2007, Needlworks / Tatter Network Foundation
/// All rights reserved. Licensed under the GPL.
/// See the GNU General Public License for more details. (/doc/LICENSE, /doc/COPYRIGHT)
define('ROOT', '../../../../..');
define('OPENID_REGISTERS', 10); /* check also ../index.php */

$IV = array(
	'GET' => array(
		'openid_identifier' => array('string'),
		'random' => array('int', 'default'=>'' ),
		'mode' => array('string')
	)
);

require ROOT . '/lib/includeForBlogOwner.php';

global $openid_list;
$openid_list = array();
for( $i=0; $i<OPENID_REGISTERS; $i++ )
{
	$openid = getUserSetting( "openid." . $i );
	if( !empty($openid) ) {
		array_push( $openid_list, $openid );
	}
}

function loginOpenIDforAdding()
{
	global $blogURL;
	header( "Location: $blogURL/plugin/openid/try_auth" .
		"?openid_identifier=" . urlencode($_GET['openid_identifier']) . 
		"&requestURI=" .  urlencode( $blogURL . "/owner/setting/account/openid" . "?mode=add&openid_identifier=" . urlencode($_GET['openid_identifier']) ) );
}

function addOpenID()
{
	global $openid_list;
	global $blogURL;
	global $openid_session;
	$currentOpenID = fireEvent("OpenIDGetCurrent", null);
	$claimedOpenID = fireEvent("OpenIDFetch", $_GET['openid_identifier']);

	if( empty($currentOpenID) || $claimedOpenID != $currentOpenID ) {
		loginOpenIDforAdding();
		return;
	}

	if( !in_array( $currentOpenID, $openid_list ) ) {
		for( $i=0; $i<OPENID_REGISTERS; $i++ )
		{
			$openid = getUserSetting( "openid." . $i );
			if( empty($openid) ) {
				setUserSetting( "openid." . $i, $currentOpenID );
				fireEvent("OpenIDSetUserId", $currentOpenID);
				break;
			}
		}
	}

	echo "<html><head><script>alert('" . _t('추가하였습니다.') . "'); document.location.href='" . $blogURL . "/owner/setting/account'</script></head></html>";

}

function deleteOpenID($openidForDel)
{
	global $blogURL;
	for( $i=0; $i<OPENID_REGISTERS; $i++ )
	{
		$openid = getUserSetting( "openid." . $i );
		if( $openid == $openidForDel ) {
			removeUserSetting( "openid." . $i );
			fireEvent("OpenIDResetUserId", $openidForDel);
			break;
		}
	}

	echo "<html><head><script>alert('" . _t('삭제되었습니다.') . "'); document.location.href='" . $blogURL . "/owner/setting/account'</script></head></html>";

}

switch( $_GET['mode'] ) {
	case 'del':
		deleteOpenID($_GET['openid_identifier']);
		break;
	case 'add':
	default:
		addOpenID();
		break;
}

?>
