<?php
class ErrorChecker {
	private $layout;

	function __construct($layout, $indices) {
		$this->layout = $layout;

		foreach($indices as $index) {
			if(empty($_POST[$index])) {
				$this->writeError("Please enter the " . $index . ".");
			} else {
				$this->layout->append($index, $_POST[$index]);
			}
		}
	}

	public function writeError($error) {
		$this->layout->append("error", "<div class='alert alert-danger' role-'alert'>" . $error . "</div>");
		$this->layout->render();
	}

	public function getArray() {
		return $this->layout->getVariables();
	}
}
?>