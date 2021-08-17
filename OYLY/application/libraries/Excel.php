<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed'); 

require_once APPPATH.'/libraries/PHPExcel/PHPExcel/Classes/PHPExcel.php';
//include_once('PHPExcel/PHPExcel.php');
//print_r(APPPATH);die();
class Excel extends PHPExcel {
 public function __construct() 
 {
 parent::__construct();

 }
}