<?php
    spl_autoload_register('myautoloader');
    function myautoloader($className){
        $url = $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        
        if(strpos($url,"includes") !== false){
            $path = "../classes/";
        } else {
            $path = "./classes/";
        }
        $fileName = "$path$className.php";
        if(!file_exists($fileName)){
            return false;
        }
        include "$fileName";
    }
?>