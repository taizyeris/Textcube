<?php
/// Copyright (c) 2004-2014, Needlworks  / Tatter Network Foundation
/// All rights reserved. Licensed under the GPL.
/// See the GNU General Public License for more details. (/documents/LICENSE, /documents/COPYRIGHT)
require ROOT . '/library/preprocessor.php';
if (false) {
	fetchConfigVal();
}
fireEvent($suri['directive'] . '/' . $suri['value']);
if (!headers_sent())
	Respond::NotFoundPage();
?>
