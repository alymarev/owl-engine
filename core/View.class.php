<?php

class View {

    private $dir;

    public function __construct($dir = Config::TEMPLATES_DIR) {
        $this->dir = $dir;
    }

    public function render($file, $params, $return = false) {
        $template = $this->dir.$file.".tpl";
        extract($params);
        ob_start();
        include($template);
        if ($return) return ob_get_clean();
        else echo ob_get_clean();
    }
}

?>