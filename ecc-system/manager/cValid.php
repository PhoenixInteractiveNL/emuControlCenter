<?php
define('REGEX_VALID_FILENAME', "/^[ a-zA-Z0-9_\-\+\(\)\[\]\!\.]*$/");

class Valid {

	static public function fileName($fileName) {
		if (!trim($fileName)) return false;
		return preg_match(REGEX_VALID_FILENAME, $fileName, $matches);
	}
}
?>