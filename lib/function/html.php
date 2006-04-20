<?

function stripHTML($text, $allowTags = array()) {
	$text = eregi_replace('<script[^>]*>([^<]|<[^/]|</[^s]|</s[^c])*</script>', '', $text);
	$text = eregi_replace('<style[^>]*>([^<]|<[^/]|</[^s]|</s[^t])*</style>', '', $text);
	if (count($allowTags) == 0)
		$text = ereg_replace('<[[:alpha:]/!][^>]*>', '', $text);
	else {
		preg_match_all('/<\/?([[:alpha:]]+)[^>]*?>/s', $text, $matches);
		for ($i = 0; $i < count($matches[0]); $i++) {
			if (!in_array(strtolower($matches[1][$i]), $allowTags))
				$text = str_replace($matches[0][$i], '', $text);
		}
	}
	$text = ereg_replace('&nbsp;', ' ', $text);
	$text = ereg_replace('[[:space:]]+', ' ', $text);
	if (!empty($text))
		$text = html_entity_decode($text);
	return $text;
}

function nl2brWithHTML($str) {
	$str = str_replace('[CODE]', '[CODE][HTML]', $str);
	$str = str_replace('[/CODE]', '[/HTML][/CODE]', $str);
	$inHTML = false;
	$out = '';
	while (true) {
		if ($inHTML) {
			$end = strpos($str, '[/HTML]');
			if ($end === false)
				break;
			else {
				$out .= substr($str, 0, $end);
				$str = substr($str, $end + 7);
				$inHTML = false;
			}
		} else {
			$offset = strpos($str, '[HTML]');
			if ($offset === false) {
				$out .= nl2br($str);
				break;
			} else {
				$out .= nl2br(substr($str, 0, $offset));
				$str = substr($str, $offset + 6);
				$inHTML = true;
			}
		}
	}
	return $out;
}

function addLinkSense($text, $attributes = '') {
	return ereg_replace("(^| |\t|\r|\n|\"|')(http://[^ \t\r\n\"']+)", "\\1<a href=\"\\2\"$attributes>\\2</a>", $text);
}

function addProtocolSense($url, $protocol = 'http://') {
	return ereg('^[[:alnum:]]+:', $url) ? $url : $protocol . $url;
}

function escapeURL($url) {
	return str_replace('#', '%23', str_replace('?', '%3F', str_replace('%', '%25', $url)));
}

function str_tag_on($str) {
	$str = str_replace("&amp;", "&", $str);
	$str = str_replace("&lt;", "<", $str);
	return str_replace("&gt;", ">", $str);
}

function str_tag_off($str) {
	$str = str_replace("<", "&lt;", $str);
	return str_replace(">", "&gt;", $str);
}

function str_tag_truncate($str) {
	return strip_tags(ereg_replace('\[##.+##\]', '', $str));
}

function str_cut($str, $maxlen, $type) {
	$str = str_trans_rev($str);
	$len = strlen($str);
	if ($len <= $maxlen)
		return str_tag_off(str_trans($str));
	$return_str = "";
	for ($i = 0; $i < ($maxlen - 1); $i++) {
		if (ord(substr($str, $i, 1)) < 128) {
			$return_str .= substr($str, $i, 1);
		} else {
			$return_str .= substr($str, $i, 2);
			$i++;
		}
	}
	$str = str_tag_off(str_trans($str));
	$return_str = str_tag_off(str_trans($return_str));
	if ($type)
		return "<span title=\"$str\">{$return_str}..</span>";
	else
		return $return_str . "..";
}
?>