<?php
class HomeLayout extends Layout {

    public function render($showNav = true) {
        extract($this->variables);
        $scripts[] = "homeNavbar";
        echo "
        <!DOCTYPE html>
            <html lang='en'>
        ";
        require_once(TEMPLATES_PATH . '/head.php');
        echo "<body><div id='wrapper'>";
        if($showNav) {
            require_once(TEMPLATES_PATH . '/home-navbar.php');
        }
     
        require_once($this->file);

        echo "</div>";

        // require_once(TEMPLATES_PATH . "/home-footer.php");
        require_once(TEMPLATES_PATH . "/footer.php");

        echo "</body></html>";

        exit ();
    }

}
?>