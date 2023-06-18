<?php
 if (! function_exists('get_arrayElement')) {
    function get_arrayElement($array,$index){
        if (isset( $array[$index])) {
            return  $array[$index];     
        }else{
            return "undefined";
        }  
    }    
}