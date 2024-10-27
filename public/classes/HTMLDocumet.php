<?php
    class HTMLDocument {
        private $title;
        private $stylesheets = [];
        private $scripts = [];
    
        public function __construct($title) {
            $this->title = $title;
            $this->addStylesheet("assets/css/theme.css");
            $this->addStylesheet("assets/css/style.css");
        }
    
        public function addStylesheet($href) {
            $this->stylesheets[] = $href;
        }
    
        public function addScript($src) {
            $this->scripts[] = $src;
        }
    
        public function renderHead() {
            echo "<title>$this->title</title>";
            foreach ($this->stylesheets as $href) {
                echo "<link href='$href' rel='stylesheet' />";
            }
            foreach ($this->scripts as $src) {
                echo "<script src='$src'></script>";
            }
        }
    }
?>