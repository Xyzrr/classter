<?php
class MainLayout extends Layout {

    public function render() {
        extract($this->variables);
        echo "
        <!DOCTYPE html>
            <html lang='en'>
        ";
        require_once(TEMPLATES_PATH . '/head.php');
        echo "<body class='main'><div id='wrapper'>";
        require_once(TEMPLATES_PATH . '/navbar.php');
        require_once(TEMPLATES_PATH . '/sidebar.php');
     
        require_once($this->file);

        echo "</div>";

        require_once(TEMPLATES_PATH . "/footer.php");

        echo "</body></html>";

        exit ();
    }

}
?>