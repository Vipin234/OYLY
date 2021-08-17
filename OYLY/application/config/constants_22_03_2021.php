<?php
defined('BASEPATH') OR exit('No direct script access allowed');


defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);


defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);


defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');


defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


$tables=array(
				'table'=>'businesses',	
				'table1'=>'keys',
				'table2'=>'reservations',
				'table3'=>'spots',	
				'table4'=>'tbl_admin',
				'table5'=>'tbl_amenities_type',
				'table6'=>'tbl_contact_detail_for_customer',
				'table7'=>'tbl_feedback_for_restaurant_by_customer',
				'table8'=>'tbl_food_type',
				'table9'=>'tbl_get_order_for_waiter_restaurant',
				'table11'=>'tbl_gst_amount_detail',
				'table12'=>'tbl_login_customer',
				'table13'=>'tbl_manage_login_user',
				'table14'=>'tbl_master',
				'table15'=>'tbl_notification_by_customer',
				'table16'=>'tbl_notification_by_staff',
				'table17'=>'tbl_order_detail_for_restaurant',
				'table19'=>'tbl_otp_admin',
				'table20'=>'tbl_otp_customer',
				'table21'=>'tbl_rating_for_customer',
				'table22'=>'tbl_registration_customer',
				'table23'=>'tbl_restaurant_banner_image',
				'table24'=>'tbl_restaurant_menu_item_list',
				'table25'=>'tbl_restaurant_staff_registration',
				'table26'=>'tbl_stafftype',
				'table27'=>'tbl_user_type',
				'table28'=>'users',
				'table29'=>'working_hours',
				'table30'=>'tbl_sub_order_detail_for_restaurant',
				'table31'=>'master_item',
                'table32'=>'sub_master_item'

			);
$gstArrayValues=array(
				'GST'=>'0',
				'GST'=>'5',
				'GST'=>'18',
				'GST'=>'18'
);
$gstArrayName=array(
				'GST'=>'GST0',
				'GST'=>'GST05',
				'GST'=>'GST18',
				'GST'=>'VAT'
);
define('GSTVALUE', json_encode($gstArrayValues));
define('GSTNAME', json_encode($gstArrayName));
define('TABLES', json_encode($tables));
