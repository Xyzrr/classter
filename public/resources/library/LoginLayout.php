<?php
	class LoginLayout extends Layout {

		public function render () {
			extract($this->variables);
			
	        echo "
	        <!DOCTYPE html>
	            <html lang='en'>
	        ";
	        require_once(TEMPLATES_PATH . '/head.php');
	        echo "<body class='full'>";
	     
	        require_once($this->file);

	        require_once(TEMPLATES_PATH . "/login-footer.php");
	        require_once(TEMPLATES_PATH . "/footer.php");

	        echo "</body></html>";
	        
	        exit ();
	    }

	}
?>