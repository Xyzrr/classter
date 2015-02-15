<?php	
class Format {

	static function paragraphs($var) {
		$var = htmlspecialchars($var);

		$var = preg_replace("/\r/",'',$var);
		$var = preg_replace("/\n\n/", '</p><p>', $var);
		$var = preg_replace("/\n/", '<br />', $var);

		return $var;
	}

	static function htmlspecialArray(&$variable) {
		foreach ($variable as &$value) {
			if (!is_array($value)) { $value = htmlspecialchars($value); }
			else { htmlspecial_array($value); }
		}
	}
}

?>