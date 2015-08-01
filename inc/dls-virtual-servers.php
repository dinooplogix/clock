<?php


class Dls_Virtual_Servers {
    
    private $html_display_path;


    function __construct() {
        $this->html_display_path = DLS_PLUGIN_PATH . 'display/';
    }
    
    function display_calc() {
        
        include  $this->html_display_path . 'html-calc.php';
        
    }
    
    

}
