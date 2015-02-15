<?php
class Layout {
    protected $file;
	protected $variables;

    function __construct($fileName, $variables = array()) {
        $this->file = TEMPLATES_PATH . "/" . $fileName;
        if(!file_exists($this->file)) {
            require_once(TEMPLATES_PATH . "/error.php");
        }
        $this->variables = $variables;
    }
    
    public function getVariables() {
        return $this->variables;
    }

    public function append($index, $str) {
        if(!array_key_exists("${index}", $this->variables)) {
            $this->variables["${index}"] = "";
        }
        $this->variables["${index}"] .= $str;
    }

    public function render() {
    	echo "Error: Forgot to override render function.";
    }
}
?>