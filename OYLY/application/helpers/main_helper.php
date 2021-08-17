<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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
function image_upload($image,$admin_id,$path)
{
        $img = str_replace('data:image/jpeg;base64,','',$image);
        $img = str_replace('','+',$img);
        $dataimage = base64_decode($img);
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $dataimage, FILEINFO_MIME_TYPE);
        $split = explode( '/', $mime_type );
        $extension = $split[1]; 
        if($extension=='msword' || $extension=='octet-stream')
        {
          $extension='docx';
        }
        elseif ($extension=='vnd.openxmlformats-officedocument.wordprocessingml.document')
        {
          $extension='doc';
        }
        $ext_array=array('jpeg','JPEG','png','PNG','jpg','JPG','PDF','pdf','DOC','doc','docx');
        if(in_array($extension,$ext_array))
        {
          $imageName = "".$admin_id.'_'.time().".".$extension;
          $dir= $path.$imageName;
          $res=file_put_contents($dir,$dataimage);
        if(!empty($res)) {
          return $dir;  
        } else {
          return '';
        }
        }
        else{
        return '';
        }

}
?>