<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/mailer/PHPMailer/PHPMailerAutoload.php';
function json_output($data)
{
    $ci = & get_instance();
    $ci->output->set_status_header(200)->set_content_type(CONTENT_TYPE_JSON,'utf-8')->set_output(json_encode($data,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
    exit;
    
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
function createHash($arr)
{
      $input = implode('',$arr);
      $key='ec84e1b5da73b4119628839ab3759589264f78bd789aad589f46fd90b9df8dafvAwrO9XBhKTok4rlmGxkrSFjps2MaqKehR48KmpFv87mappCDTX1raSg986OviAWWuIebk4Cz96hxge4H7nidRiq7IpeFtDb3A4Mmd12sP4kVZQwgzrzM03yvmMlLn3j3z4vkGobGkwfCCPkPjhIfPuNt8SKpnDbtF2WEoUWlPx6j2YCQz3QbKFoxielsHer';
      $masked = hash_hmac('md5',$input,$key);
      return $masked;
}
function validate_mobile($mobile)
{
    return preg_match('/^[0-9]{10}+$/', $mobile);
}
function validate_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
function mailSend($info)
{   
    $response = new StdClass();
    $result2 = new StdClass();
    $mail = new PHPMailer;
    $mail->isSMTP();                                     
    $mail->Host = 'smtp.ipage.com';  
    $mail->SMTPAuth = true;                              
    $mail->Username = 'admin.2@goolean.com';                
    $mail->Password = 'Abcd1234';                          
    $mail->SMTPSecure = 'tls';                        
    $mail->Port = 587; 
    $name=$info['user_fullname'];
    $email=$info['user_email'];
    $mobile_no=$info['mobile_no'];
    $message=$info['message'];
    date_default_timezone_set('Asia/kolkata'); 
    $now = date('Y-m-d H:i:s');
    $data->name=$name;
    $data->email=$email;
    $data->mobile_no=$mobile_no;
    $data->message=$message;
    $data->create_date=$now;
    $data->status='1';
        $mail->From ='anjali.rawat@goolean.tech';
        $mail->FromName ='OYLY Admin';
        $mail->addAddress($email,'Admin');
        $mail->addCC('anjali.rawat@goolean.tech');
        // $mail->addBCC('pankaj.kumar@goolean.tech');
        $mail->isHTML(true);
        $mail-> Subject= 'OYLY';
        $mail-> Body=$info['body'];
        if(!$mail->Send())
        {
          $data2->status =0;
          $data2->data = "Error sending: " . $mail->ErrorInfo;
          array_push($result2,$data2);
          $response= $data2;
        }
        else
        {
          $data2->status =1;
          $data2->data = 'Email sent successfully';
          array_push($result2,$data2);
          $response= $data2;
        }
    echo  json_output($response);
}
?>