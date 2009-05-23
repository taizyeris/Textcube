<?php
/// Copyright (c) 2004-2009, Needlworks / Tatter Network Foundation
/// All rights reserved. Licensed under the GPL.
/// See the GNU General Public License for more details. (/doc/LICENSE, /doc/COPYRIGHT)
define('NO_SESSION', true);
define('__TEXTCUBE_LOGIN__',true);

require ROOT . '/library/preprocessor.php';
requireModel("blog.feed");
requireModel("blog.entry");

requireStrictBlogURL();
if (false) {
	fetchConfigVal();
}
$cache = new Cache_Page;
if(!empty($suri['id'])) {
	$cache->name = 'commentATOM_'.$suri['id'];
	if(!$cache->load()) {
		$result = getCommentFeedByEntryId(getBlogId(),$suri['id'], false, 'atom');
		if($result !== false) {
			$cache->contents = $result;
			$cache->update();
		}
	}
} else {
	$cache->name = 'commentATOM';
	if(!$cache->load()) {
		$result = getCommentFeedTotal(getBlogId(), false, 'atom');
		if($result !== false) {
			$cache->contents = $result;
			$cache->update();
		}
	}
}
header('Content-Type: application/atom+xml; charset=utf-8');
fireEvent('FeedOBStart');
echo fireEvent('ViewCommentATOM', $cache->contents);
fireEvent('FeedOBEnd');
?>
