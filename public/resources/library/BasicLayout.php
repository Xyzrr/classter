<?php
class BasicLayout {
	private $output;
    private $isHTML;

	function __construct($output) {
		$this->output = $output;
        if(preg_match("/<[^<]+>/", $output) != 0) {
            $this->output = $output;
            $this->isHTML = true;
        } else {
            $this->output = TEMPLATES_PATH . "/" . $output;
            $this->isHTML = false;
        }
	}

	public function render () {
		echo "
        <!DOCTYPE html>
            <html lang='en'>
        ";
        require_once(TEMPLATES_PATH . '/head.php');
        echo "<body class='full'>";
     
        if($this->isHTML) {
            echo $this->output;
        } else {
            require_once($this->output);
        }

        require_once(TEMPLATES_PATH . "/footer.php");

        echo "</body></html>";
	}

}
?>