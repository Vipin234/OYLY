
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a class="site_title"><i class="fa fa-paw"></i> <span>Restaurants</span></a>
            </div>

            <div class="clearfix"></div>

             <input type="hidden" name="base_url"id="base_url"value="<?php echo base_url();?>index.php/">
            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                <?php 
                  if($_SESSION['user_role']==3)
                  {?>
                     <li><a href="<?php echo base_url();?>index.php/KotController/orderList"><i class="fa fa-file-pdf-o"></i>Create Slip</a></li>
                 <?php }
                 else{
                  if($_SESSION['user_role']==1)
                  {?>
                     <li><a href="<?php echo base_url();?>index.php/SuperAdmin/superAdmin"><i class="fa fa-home"></i>Admin</a></li>
                 <?php }

                  ?>
                 

                  <li><a href="<?php echo base_url();?>index.php/Admin/dashboard"><i class="fa fa-home"></i>Restaurant</a></li>
                  <li><a href="<?php echo base_url();?>index.php/RestaurantKyc/restaurantKyc"><i class="fa fa-home"></i>Restaurant KYC</a>

                  </li>
                  <li><a href="<?php echo base_url();?>index.php/StaffListController/staffList"><i class="fa fa-edit"></i>Restaurant Staff </a>
                  
                  </li>
                  <li><a href="<?php echo base_url();?>index.php/Menu/menu"><i class="fa fa-desktop"></i>Restaurant Menu</a>
                  
                  </li>
                  <li><a href="<?php echo base_url();?>index.php/Cat/category"><i class="fa fa-home"></i>Category</a>

                  </li>
                  <li><a href="<?php echo base_url();?>index.php/SubCat/subCategory"><i class="fa fa-home"></i>Sub Category</a>

                  </li>
                  <?php 
                  if($_SESSION['user_role']==1){?>
                     <li><a href="<?php echo base_url();?>index.php/SpreesdSheetController/SpreedSheet"><i class="fa fa-home"></i>File Upload</a></li>
              <?php    }
                  ?>
                 

                 <!--  </li>
                    <li><a href="<?php echo base_url();?>index.php/GetPaymentTransfer/PaymentTransfer"><i class="fa fa-home"></i>InvoiceList</a> -->

                  <?php 
                  if($_SESSION['user_role']==1){?>
                    <li><a href="<?php echo base_url();?>index.php/PaymentTxns/Payment"><i class="fa fa-home"></i>Payments Txns</a>             </li>
                 <?php }
                  ?>
                   <?php 
                  if($_SESSION['user_role']==1){?>
                     <li><a href="<?php echo base_url();?>index.php/Gst/gst"><i class="fa fa-home"></i>Gst</a> </li>
                 <?php }
                  ?>
                 

                   <?php 
                  if($_SESSION['user_role']==1){?>
                     <li><a href="<?php echo base_url();?>index.php/Convenience/convenience"><i class="fa fa-home"></i>Convenience</a> </li>
                 <?php }
                 }
                  ?>
 
                </ul>
              </div>
              
            </div>
            <!-- /sidebar menu -->

           
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
              <nav class="nav navbar-nav">
              <ul class=" navbar-right">
                <li class="nav-item dropdown open" style="padding-left: 15px;">
                  <a href="<?php echo base_url();?>index.php/Admin/logout" title="Logout"><?php echo $_SESSION['user_fullname'];?>
                  </a>
                 <!--  <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item"  href="javascript:;"> Profile</a>
                      <a class="dropdown-item"  href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                  <a class="dropdown-item"  href="javascript:;">Help</a>
                    <a class="dropdown-item"  href="<?php echo base_url();?>index.php/Admin/logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                  </div> -->
                </li>

               
              </ul>
            </nav>
          </div>
        </div>
        <!-- page content -->
        
        


