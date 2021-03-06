<?php
defined('BASEPATH') OR exit('No direct script access allowed');
function json_output($data)
{
    $ci = & get_instance();
    $ci->output->set_status_header(200)->set_content_type(CONTENT_TYPE_JSON,'utf-8')->set_output(json_encode($data,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
    exit;
    
}

 function send_cust_messesg($mobile_no,$message)
    {
        // Set POST variables


        $url = 'https://2factor.in/API/V1/c43867a9-ba7e-11e9-ade6-0200cd936042/SMS/'.$mobile_no.'/'.$message.'/'.''.'';
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($url));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        
        return $result;
    }
 function paymentTransaction($order_id,$pemurl)
{
      $ci = & get_instance();
      $access_code=$ci->config->item('access_code');
      //print_r($access_code);exit;
      $url = "https://secure.ccavenue.com/transaction/getRSAKey";
      $fields = array(
              'access_code'=>$access_code,
              'order_id'=>$order_id
      );
      $postvars='';
      $sep='';
      foreach($fields as $key=>$value)
      {
              $postvars.= $sep.urlencode($key).'='.urlencode($value);
              $sep='&';
      }
    	//print_r($pemurl);exit;
      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL,$url);
      curl_setopt($ch,CURLOPT_POST,count($fields));
      curl_setopt($ch, CURLOPT_CAINFO,$pemurl);
      curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $result = curl_exec($ch);
      return $result;
}
 function generateToken($api_key,$app_id,$json)
{

    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.cashfree.com/api/v2/cftoken/order',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>$json,
    CURLOPT_HTTPHEADER => array(
      'x-client-secret:c5738d7f77d341b5baf79c8dfdec8108754780ac',
      'x-client-id:105961a148c41ab4a53c4432e6169501',
      'Content-Type: application/json'
    ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return  $response;
}
function sendPushNotification($title,$message,$notification_id)
{
          $firebase = new Firebase();
          $push = new Push();
          $payload = array();
          $payload['team'] = 'India';
          $payload['score'] = '5.6';          
          $push_type ='individual';
          $include_image ='';
          $push->setTitle($title);
          $push->setMessage($message);
          if ($include_image) {
            $push->setImage('https://api.androidhive.info/images/minion.jpg');
           } else {
            $push->setImage('');
          }
          $push->setIsBackground(FALSE);
          $push->setPayload($payload);
          $json = '';
          $response = '';
          if ($push_type == 'topic' && !empty($notification_id)) {
            $json = $push->getPush();
            $response = $firebase->sendToTopic('global', $json);
            } else if ($push_type == 'individual' && !empty($notification_id)){
                $json = $push->getPush();
                $json2=$firebase->send($notification_id, $json);
                //print_r($json2);exit;
                 if(json_decode($json2)->success==1)
                    {
                        return TRUE;
                    }else
                    {
                       return false;
                    }
                }
}
?>