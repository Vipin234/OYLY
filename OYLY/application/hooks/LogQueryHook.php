<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LogQueryHook {
 
    function log_queries() {
        $CI => get_instance();
        $times = $CI->query_times;
        $dbs    = array();
        $output = NULL;
        $queries = $CI->queries;
 
        if (count($queries) == 0){
            $output .= "no queries\n";
        }else{
            foreach ($queries as $key=>$query){
                $output .= $query . "\n";
            }
            $took = round(doubleval($times[$key]), 3);
            $output .= "===[took:{$took}]\n\n";
        }
 
        $CI->helper('file');
        if ( ! write_file(APPPATH  . "/logs/queries.log.txt", $output, 'a+')){
            log_message('debug','Unable to write query the file');
        }
    }
}

?>