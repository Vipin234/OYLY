<?php
error_reporting(0);
include 'db.php';
require_once'firebase.php';
require_once 'push.php';
require_once 'config.php';
 $id=$_GET['id'];
 $getUsers="SELECT notification_id  FROM login_manage_sales WHERE user_id='$saleID'";
 $runUsers=mysqli_query($db,$getUsers);
 while($rowUsers=mysqli_fetch_array($runUsers))
 {
   $notification_id=$rowUsers['notification_id'];
 }
?>
        <?php
        // Enabling error reporting
      /*  error_reporting(-1);*/
        ini_set('display_errors', 'On');


        $firebase = new Firebase();
        $push = new Push();

        // optional payload
        $payload = array();
        $payload['team'] = 'India';
        $payload['score'] = '5.6';

        // notification title
        $title = isset($_GET['title']) ? $_GET['title'] : '';
        
        // notification message
        $message = isset($_GET['message']) ? $_GET['message'] : '';
        
        // push type - single user / topic
        $push_type = isset($_GET['push_type']) ? $_GET['push_type'] : '';
        
        // whether to include to image or not
        $include_image = isset($_GET['include_image']) ? TRUE : FALSE;


        $push->setTitle($title);
        $push->setMessage($message);
        if ($include_image) {
            $push->setImage('http://api.androidhive.info/images/minion.jpg');
        } else {
            $push->setImage('');
        }
        $push->setIsBackground(FALSE);
        $push->setPayload($payload);


        $json = '';
        $response = '';

        if ($push_type == 'topic') {
            $json = $push->getPush();
            $response = $firebase->sendToTopic('global', $json);
        } else if ($push_type == 'individual') {
            $json = $push->getPush();
            $regId = isset($_GET['regId']) ? $_GET['regId'] : '';
            $response = $firebase->send($regId, $json);
        }
        ?>
  <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
             
<!-- ============================================================================================================================================================== -->
<!DOCTYPE html>

<html>
<head>

</head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<!-- ================================================================================================================================================================ -->
                                          <?php include 'includes/head.php';?>    
<!-- ================================================================================================================================================================ -->

<body id="pages" class="full-layout  nav-right-hide nav-right-start-hide  nav-top-fixed      responsive    clearfix" data-active="pages "  data-smooth-scrolling="1">     

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script>tinymce.init({ selector:'textarea' });</script>


<div class="vd_body">
<!-- Header Start -->
  <!-- ================================================================================================================================================================ -->
                                         <?php include 'includes/header.php'; ?>
<!-- ============================================================================================================================================================== -->
  <!-- Header Ends --> 
<div class="content">
  <div class="container">
<!-- ================================================================================================================================================================ -->
                                         <?php include 'includes/nav_left.php'; ?>
<!-- ============================================================================================================================================================== -->   

  </div>    
   

    <!-- Middle Content Start -->

    

    <div class="vd_content-wrapper">

      <div class="vd_container">
        <div class="vd_content clearfix">
          <div class="vd_head-section clearfix">
            <div class="vd_panel-header">
              <ul class="breadcrumb">
                <li><a href="index.php">Home</a> </li>
                <li><a href="fitouts.php?action=all_fitouts">Send Notification</a> </li>
              </ul>
              <div class="vd_panel-menu hidden-sm hidden-xs" data-intro="" data-step=5  data-position="left">
                <div data-action="remove-navbar" data-original-title="Remove Navigation Bar Toggle" data-toggle="tooltip" data-placement="bottom" class="remove-navbar-button menu"> <i class="fa fa-arrows-h"></i>
                </div>

                <div data-action="remove-header" data-original-title="Remove Top Menu Toggle" data-toggle="tooltip" data-placement="bottom" class="remove-header-button menu"> <i class="fa fa-arrows-v"></i>
                </div>

                <div data-action="fullscreen" data-original-title="Remove Navigation Bar and Top Menu Toggle" data-toggle="tooltip" data-placement="bottom" class="fullscreen-button menu"> <i class="glyphicon glyphicon-fullscreen"></i> 
                </div>
              </div>
            </div>

          </div>

          <div class="vd_title-section clearfix">
            <div class="vd_panel-header">
              <h1>Send Notification</h1>
              <div class="vd_panel-menu hidden-xs">
              </div>
            </div>
          </div>

          <div class="vd_content-section clearfix" id="ecommerce-product-add">
            <div class="row">
              <div class="col-md-12">
                <div class="panel widget panel-bd-left light-widget">
                 
                  <div class="container">
            <div class="fl_window">
                <div><img src="http://api.androidhive.info/images/firebase_logo.png" width="200" alt="Firebase"/></div>
                <br/>
                <?php if ($json != '') { ?>
                    <label><b>Request:</b></label>
                    <div class="json_preview">
                        <pre><?php echo json_encode($json) ?></pre>
                    </div>
                <?php } ?>
                <br/>
                <?php if ($response != '') { ?>
                    <label><b>Response:</b></label>
                    <div class="json_preview">
                        <pre><?php echo json_encode($response) ?></pre>
                    </div>
                <?php } ?>

            </div>

            <form class="pure-form pure-form-stacked" method="get">
                <fieldset>
                    <legend>Send to Single Device</legend>

                    <label for="redId">Firebase Reg Id</label>
                    <input type="text" id="redId" readonly name="regId" class="pure-input-1-2" placeholder="Enter firebase registration id" value="<?php echo $notification_id; ?>">

                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" class="pure-input-1-2" placeholder="Enter title">

                    <label for="message">Message</label>
                    <textarea class="pure-input-1-2" rows="5" name="message" id="message" placeholder="Notification message!"></textarea>

                    <label for="include_image" class="pure-checkbox">
                        <input name="include_image" id="include_image" type="checkbox"> Include image
                    </label>
                    <input type="hidden" name="push_type" value="individual"/>
                    <button type="submit" class="pure-button pure-button-primary btn_send">Send</button>
                </fieldset>
            </form>
            <br/><br/><br/><br/>

            <form class="pure-form pure-form-stacked" method="get">
                <fieldset>
                    <legend>Send to Topic `global`</legend>

                    <label for="title1">Title</label>
                    <input type="text" id="title1" name="title" class="pure-input-1-2" placeholder="Enter title">

                    <label for="message1">Message</label>
                    <textarea class="pure-input-1-2" name="message" id="message1" rows="5" placeholder="Notification message!"></textarea>

                    <label for="include_image1" class="pure-checkbox">
                        <input id="include_image1" name="include_image" type="checkbox"> Include image
                    </label>
                    <input type="hidden" name="push_type" value="topic"/>
                    <button type="submit" class="pure-button pure-button-primary btn_send">Send to Topic</button>
                </fieldset>
            </form>
        </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
    

  </div>

  <!-- .container --> 

</div>

<!-- .content -->
<!-- Footer Start -->

<!-- ================================================================================================================================================================ -->
                                         <?php include 'includes/footer.php'; ?>
<!-- ============================================================================================================================================================== -->

<!-- Footer END -->
</div>
<!-- .vd_body END  -->
<a id="back-top" href="#" data-action="backtop" class="vd_back-top visible"> <i class="fa  fa-angle-up"> </i> </a>
<!--
<a class="back-top" href="#" id="back-top"> <i class="icon-chevron-up icon-white"> </i> </a> -->
<!-- Javascript =============================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script type="text/javascript" src="js/jquery.js"></script> 
<!--[if lt IE 9]>
  <script type="text/javascript" src="js/excanvas.js"></script>      
<![endif]-->
<script type="text/javascript" src="js/bootstrap.min.js"></script> 
<script type="text/javascript" src='plugins/jquery-ui/jquery-ui.custom.min.js'></script>
<script type="text/javascript" src="plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="js/caroufredsel.js"></script> 
<script type="text/javascript" src="js/plugins.js"></script>
<script type="text/javascript" src="plugins/breakpoints/breakpoints.js"></script>
<script type="text/javascript" src="plugins/dataTables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="plugins/prettyPhoto-plugin/js/jquery.prettyPhoto.js"></script> 
<script type="text/javascript" src="plugins/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="plugins/tagsInput/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap-switch/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="plugins/blockUI/jquery.blockUI.js"></script>
<script type="text/javascript" src="plugins/pnotify/js/jquery.pnotify.min.js"></script>
<script type="text/javascript" src="js/theme.js"></script>
<script type="text/javascript" src="custom/custom.js"></script>
<!-- Specific Page Scripts Put Here -->
<script type="text/javascript" src='plugins/tagsInput/jquery.tagsinput.min.js'></script>
<script type="text/javascript" src='plugins/ckeditor/ckeditor.js'></script>
<script type="text/javascript" src='plugins/ckeditor/adapters/jquery.js'></script>
<script type="text/javascript" src="plugins/bootstrap-wysiwyg/js/wysihtml5-0.3.0.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap-wysiwyg/js/bootstrap-wysihtml5-0.0.2.js"></script>

<script src="../blueimp.github.io/JavaScript-Load-Image/js/load-image.min.php"></script>
</body>
</html>