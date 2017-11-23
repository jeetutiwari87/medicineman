<?php
/*
  Plugin Name: Old User Registration
  Plugin URI: #
  Description: Updates user rating based on number of posts.
  Version: 1.0
  Author: Manish
  Author URI: #
 */
 /*
 $first_name
$last_name
$email
$telephone
$street-address
$street-address-line2
$city
$province
$postal-code
$country
$gov-id-one
$gov-id-two
 */
 ob_start();
function old_registration_form( $email,$telephone,$gov_id_one,$message ) { ?>
		
	  
 <style> 
 .error {
    background: none !important; 
}
#errormessage {
    background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    margin-bottom: 30px;
}
#errormessage li::before {
    content: "";
    font-family: FontAwesome;
    font-size: 20px;
    font-style: normal;
    font-weight: normal;
    margin-left: 5px;
    margin-right: 19px;
    text-decoration: inherit;
}
#errormessage li {
    background: #ff7f7f none repeat scroll 0 0;
    color: #ffffff;
    margin-bottom: 5px;
    padding: 8px 13px 13px 9px;
}
.wpcf7-form-control{
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
    height: 40px;
    margin: 0;
    min-width: 121px;
    padding: 0 20px 0 0;
    text-align: center;
    text-transform: uppercase;
	}
	 .inst {
     
    width: 100%;
    margin: 10px 0;
    color: #337ab7;
}

</style> 
 <?php
	$succees = '';
	if($_REQUEST['success']){
		 $succees = '<div style="border: 1px solid; text-align: center; font-size: 19px; color: rgb(255, 255, 255); background: #94C347 none repeat scroll 0% 0%; width: 50%; margin: auto;">User Added</div>';
		 
	}
		
 echo '
 <div class="entry-title title-icon">
			<h3 class="cart-title">CURRENT CUSTOMER SIGNUP
</h3>
		</div>
		
	'.$succees.'
    <form id="regform1" action="' . $_SERVER['REQUEST_URI'] . '" method="post" class="registration-form shadow3"  enctype="multipart/form-data" >
    <div class="row">
      	  <div class="col-sm-3"></div>
      	  <div class="col-sm-6">
		  <div class="vc_column-inner ">
            <div class="wpb_wrapper">
			<!--div class="fullbox">
				  <p class="form1 half">First Name <span>*<span><br>
                 <input name="first_name"  data-validation="required"     value="' . ( isset( $_POST['first_name'] ) ? $first_name : null ) . '" size="40" class="form-control" type="text">
               </p>
               <p class="form1 half">Last Name <span>*<span><br>
                 <input name="last_name" data-validation="required"     value="' . ( isset( $_POST['last_name'] ) ? $last_name : null ) . '" size="40" class="form-control" type="text">
               </p>
			   
			</div-->
			<!--div class="inst">Your name must match your ID</div-->
             <div class="fullbox">
			  <p class="form1 full">Email Address <span>*<span><br>
                 <input name="email"  data-validation="email"  value="' . ( isset( $_POST['email'] ) ? $email : null ) . '" size="40" class="form-control"  type="email">
               </p>
			    
			 </div>
			   <!--div class="inst">We DO NOT share your email with any 3rd parties. It is used as your username and for all communication purposes; receipts, package tracking, promotions, password recovery. </div-->
             <div class="fullbox">
			 <p class="form1 full">Phone Number <span>*<span> <br>
               <input name="telephone"    value="' . ( isset( $_POST['telephone'] ) ? $telephone : null ) . '" size="40" class="form-control"  type="tel">
               </p>
			 </div>
             <!--div class="fullbox">
			  <p class="form1 full">Address 1 <span>*<span><br>
                <input name="street_address" data-validation="required" value= "' . ( isset( $_POST['street_address'] ) ? $street_address : null ) . '" size="40" class="form-control"  type="text"><br>
                </p>
			 </div-->
             <!--div class="fullbox">
			 <p class="form2 half">City <span>*<span><br>
				<input name="city" data-validation="required" value="' . ( isset( $_POST['city'] ) ? $city : null ) . '"  size="40" class="form-control"  type="text">
				</p>
				<p class="form2 half">Province <br>
				<input name="province"    value="' . ( isset( $_POST['province'] ) ? $province : null ) . '" size="40" class="form-control"   type="text">
				</p>
			 </div-->
             <div class="fullbox">
			   
				<p class="form1 full">Government ID <span>*<span><br>
				<input name="gov_id_one"   data-validation="required"    size="40" class="form-control"  type="file">
				</p>
                  
			 </div>
			   <div class="inst">Upload valid Government photo ID for proof of legal age (18+ or 19+) depending on your province. Scan or photograph accepted. </div>
              <div class="fullbox"> 
			    
               <h5><input name="codes_of_conduct" data-validation="required" data-validation-error-msg="You have to agree to our terms"  value="1" class="form-control" aria-invalid="false" type="checkbox"></span> I accept the <a href="'.get_permalink(774).'" target="_blank" style="text-decoration: underline;">Terms of Service</a> and <a href="'.get_permalink(1019).'" target="_blank" style="text-decoration: underline;"> Privacy Policy </a><br></h5>
               
			  </div>
            </div>
         </div>
		  
		  </div>
      	  <div class="col-sm-3"></div>
	 
        
	  <div class="col-md-12 col-sm-12 col-xs-12 text-center">
		<div class="btn btn-default btn-icon">
		<input class="regbtn wpcf7-form-control wpcf7-submit" type="submit" name="submit" value="Register"> 
		  
		</div>
		</div>
	 
   </div> 
</form>';
 
 ?>
	 
	 
	
<?php	
}
  
function old_registration_validation( $email,$codes_of_conduct,$telephone,$gov_id_one)  {
global $reg_errors;
$reg_errors = new WP_Error;
  
/*
if ( 
empty( $email ) || empty( $codes_of_conduct ) || empty( $gov_id_one ) ) {
    $reg_errors->add('field', 'Required form field is missing');
}*/
		
		
		
   
   
 if ( !is_email( $email ) ) {
    $reg_errors->add( 'email_invalid', 'Email is not valid' );
}
 if ( empty( $gov_id_one ) ) {
    $reg_errors->add( 'gov_id_one_invalid', 'Please Upload Government Id' );
}
 if ( empty( $telephone ) ) {
    $reg_errors->add( 'telephone_invalid', 'Phone no required' );
}
 if ( empty( $codes_of_conduct ) ) {
    $reg_errors->add( 'codes_of_conduct_invalid', 'Please checked terms ' );
}

if ( email_exists( $email ) ) {
    $reg_errors->add( 'email', 'Email Already in use' );
}

if ( is_wp_error( $reg_errors ) ) {
	 echo '<ul id="errormessage">';
    foreach ( $reg_errors->get_error_messages() as $error ) {
     
        echo '<li>';
        echo $error;
        echo '</li>';
         
    }
  echo '</ul>';
}
 return $reg_errors;
}
function old_complete_registration() {

    global $reg_errors, $password,$email,$telephone,$gov_id_one,$message;
    $password = wp_generate_password( 8, false ); 
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
		
		
		 
		/// die('ssdfsd');
		
        $userdata = array(
        'user_login'    =>   $email,
        'user_email'    =>   $email,
        'user_pass'     =>   $password, 
        'first_name'    =>   '',
        'last_name'     =>   '',
        'nickname'      =>   '',
     
        );
		
       $user_id   = wp_insert_user( $userdata );
		
		wp_update_user( array ('ID' => $user_id, 'role' => 'customer') ) ;
		
		
		// update user meta
		//update_user_meta( $user_id, 'billing_first_name', sanitize_text_field( $first_name ) );
		//update_user_meta( $user_id, 'billing_last_name', sanitize_text_field( $last_name ) );
		update_user_meta($user_id, 'billing_phone', sanitize_text_field($telephone));
		update_user_meta($user_id, 'shipping_phone', sanitize_text_field($telephone));
		
		 if ( !empty( $telephone ) ) {
			global $wpdb;
				$querystr = "SELECT * FROM md3_old_user WHERE phone = $telephone"; 
				$pageposts = $wpdb->get_results($querystr, OBJECT);
				 
				if(count($pageposts) > 0){  
					update_user_meta($user_id, 'is_old_user', 'true');
					update_user_meta($user_id, 'is_gift_send', 'false');
		 
				} 
		} 
		
		
		//update_user_meta( $user_id, 'billing_address_1', sanitize_text_field( $street_address ) );
		//update_user_meta( $user_id, 'billing_address_2', sanitize_text_field( $street_address_line2 ) );
		//update_user_meta( $user_id, 'billing_city', sanitize_text_field( $city ) );
		//update_user_meta( $user_id, 'billing_postcode', sanitize_text_field( $postal_code ) );
		//u//pdate_user_meta( $user_id, 'billing_province', sanitize_text_field( $province ) );
		//update_user_meta( $user_id, 'billing_country', sanitize_text_field( $country ) );
		//update_user_meta( $user_id, 'message', sanitize_text_field( $message ) );
		 
		
		  	 //governmentID
		if ($_FILES['gov_id_one']['error'] == 0) {
	 
			 $uploads = wp_upload_dir();
			 
			  $uploaddir = $uploads['basedir']  .'/users/'; 
					
				$file = $uploaddir . $user_id.basename($_FILES['gov_id_one']['name']); 
				$raw_file_name = $_FILES['gov_id_one']['tmp_name'];
				$name =  $user_id.$_FILES['gov_id_one']['name'];
				if (move_uploaded_file($_FILES['gov_id_one']['tmp_name'], $file)) { 
				update_user_meta($user_id, 'gov_id_one', sanitize_text_field($name)); 	 
				}  
			 
				
			}
			
	/* if ($_FILES['gov_id_two']['error'] == 0) {
			 
				$uploads = wp_upload_dir();
				$uploaddir = $uploads['basedir']  .'/users/'; 
				$file = $uploaddir . $user_id.basename($_FILES['gov_id_two']['name']); 
				$raw_file_name = $_FILES['gov_id_two']['tmp_name'];
				$name = $user_id.$_FILES['gov_id_two']['name'];
				if (move_uploaded_file($_FILES['gov_id_two']['tmp_name'], $file)) { 
				update_user_meta($user_id, 'gov_id_two', sanitize_text_field($name)); 	 
				}  
			}*/
	
	
      //  echo 'Registration complete. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';   
  
	
		$user_info = get_userdata($user_id); 
		$user_email = $user_info->user_email;	 
		 $html = '<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="format-detection" content="telephone=no" /> <!-- disable auto telephone linking in iOS -->
		<title>Respmail is a response HTML email designed to work on all major email platforms and smartphones</title>
		<style type="text/css">
			/* RESET STYLES */
			html {  margin:0; padding:0; }
			body, #bodyTable, #bodyCell, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;font-family:Helvetica, Arial, "Lucida Grande", sans-serif;}
			table{border-collapse:collapse;}
			table[id=bodyTable] {width:100%!important;margin:auto;max-width:500px!important;color:#7A7A7A;font-weight:normal;}
			img, a img{border:0; outline:none; text-decoration:none;height:auto; line-height:100%;}
			a {text-decoration:none !important;border-bottom: 1px solid;}
			h1, h2, h3, h4, h5, h6{color:#5F5F5F; font-weight:normal; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left; letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:10px;margin-left:0;padding-top:0;padding-bottom:0;padding-left:0;padding-right:0;}

			/* CLIENT-SPECIFIC STYLES */
			.ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail/Outlook.com to display emails at full width. */
			.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;} /* Force Hotmail/Outlook.com to display line heights normally. */
			table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up. */
			#outlook a{padding:0;} /* Force Outlook 2007 and up to provide a "view in browser" message. */
			img{-ms-interpolation-mode: bicubic;display:block;outline:none; text-decoration:none;} /* Force IE to smoothly render resized images. */
			body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; font-weight:normal!important;} /* Prevent Windows- and Webkit-based mobile platforms from changing declared text sizes. */
			.ExternalClass td[class="ecxflexibleContainerBox"] h3 {padding-top: 10px !important;} /* Force hotmail to push 2-grid sub headers down */

			/* /\/\/\/\/\/\/\/\/ TEMPLATE STYLES /\/\/\/\/\/\/\/\/ */

			/* ========== Page Styles ========== */
			h1{display:block;font-size:26px;font-style:normal;font-weight:normal;line-height:100%;}
			h2{display:block;font-size:20px;font-style:normal;font-weight:normal;line-height:120%;}
			h3{display:block;font-size:17px;font-style:normal;font-weight:normal;line-height:110%;}
			h4{display:block;font-size:18px;font-style:italic;font-weight:normal;line-height:100%;}
			.flexibleImage{height:auto;}
			.linkRemoveBorder{border-bottom:0 !important;}
			table[class=flexibleContainerCellDivider] {padding-bottom:0 !important;padding-top:0 !important;}

			body, #bodyTable{background-color:#E1E1E1;}
			#emailHeader{background-color:#E1E1E1;}
			#emailBody{background-color:#FFFFFF;}
			#emailFooter{background-color:#E1E1E1;}
			.nestedContainer{background-color:#F8F8F8; border:1px solid #CCCCCC;}
			.emailButton{background-color:#205478; border-collapse:separate;}
			.buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
			.buttonContent a{color:#FFFFFF; display:block; text-decoration:none!important; border:0!important;}
			.emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;}
			.emailCalendarMonth{background-color:#205478; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;}
			.emailCalendarDay{color:#205478; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;}
			.imageContentText {margin-top: 10px;line-height:0;}
			.imageContentText a {line-height:0;}
			#invisibleIntroduction {display:none !important;} /* Removing the introduction text from the view */

			
			span[class=ios-color-hack] a {color:#275100!important;text-decoration:none!important;} /* Remove all link colors in IOS (below are duplicates based on the color preference) */
			span[class=ios-color-hack2] a {color:#205478!important;text-decoration:none!important;}
			span[class=ios-color-hack3] a {color:#8B8B8B!important;text-decoration:none!important;}
			
			.a[href^="tel"], a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:none!important;cursor:default!important;}
			.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:auto!important;cursor:default!important;}


			/* MOBILE STYLES */
			@media only screen and (max-width: 480px){
				/*////// CLIENT-SPECIFIC STYLES //////*/
				body{width:100% !important; min-width:100% !important;} /* Force iOS Mail to render the email at full width. */

				/* FRAMEWORK STYLES */
				
				/*td[class="textContent"], td[class="flexibleContainerCell"] { width: 100%; padding-left: 10px !important; padding-right: 10px !important; }*/
				table[id="emailHeader"],
				table[id="emailBody"],
				table[id="emailFooter"],
				table[class="flexibleContainer"],
				td[class="flexibleContainerCell"] {width:100% !important;}
				td[class="flexibleContainerBox"], td[class="flexibleContainerBox"] table {display: block;width: 100%;text-align: left;}
				
				td[class="imageContent"] img {height:auto !important; width:100% !important; max-width:100% !important; }
				img[class="flexibleImage"]{height:auto !important; width:100% !important;max-width:100% !important;}
				img[class="flexibleImageSmall"]{height:auto !important; width:auto !important;}


				/*
				Create top space for every second element in a block
				*/
				table[class="flexibleContainerBoxNext"]{padding-top: 10px !important;}

				/*
				Make buttons in the email span the
				full width of their container, allowing
				for left- or right-handed ease of use.
				*/
				table[class="emailButton"]{width:100% !important;}
				td[class="buttonContent"]{padding:0 !important;}
				td[class="buttonContent"] a{padding:15px !important;}

			}
 
			@media only screen and (-webkit-device-pixel-ratio:.75){
				/* Put CSS for low density (ldpi) Android layouts in here */
			}

			@media only screen and (-webkit-device-pixel-ratio:1){
				/* Put CSS for medium density (mdpi) Android layouts in here */
			}

			@media only screen and (-webkit-device-pixel-ratio:1.5){
				/* Put CSS for high density (hdpi) Android layouts in here */
			}
			/* end Android targeting */

			/* CONDITIONS FOR IOS DEVICES ONLY
			=====================================================*/
			@media only screen and (min-device-width : 320px) and (max-device-width:568px) {

			}
			
.textContent img {
    margin: auto;
}
			/* end IOS targeting */
		</style>
		
	</head>
	<body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">

		<center style="background-color:#E1E1E1;">
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
				<tr>
					<td align="center" valign="top" id="bodyCell">

						 
						<table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="500" id="emailBody">

							<!-- MODULE ROW // -->
							<!--
								To move or duplicate any of the design patterns
								in this email, simply move or copy the entire
								MODULE ROW section for each content block.
							-->
							<tr>
								<td align="center" valign="top">
									 
									<table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;" bgcolor="#3A3A3B">
										<tr>
											<td align="center" valign="top">
												 
												<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td align="center" valign="top" width="500" class="flexibleContainerCell">
 
															<table border="0" cellpadding="30" cellspacing="0" width="100%">
																<tr>
																	<td align="center" valign="top" class="textContent">
																		<h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;"><img src="http://medicineman.menu/demo/wp-content/uploads/2017/08/medicineman-logo.png" /></h1>
																		 
																	 
																	</td>
																</tr>
															</table>
															<!-- // CONTENT TABLE -->

														</td>
													</tr>
												</table>
												<!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
						   

							<!-- MODULE ROW // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
										<tr>
											<td align="center" valign="top">
												<!-- FLEXIBLE CONTAINER // -->
												<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td align="center" valign="top" width="500" class="flexibleContainerCell">
															<table border="0" cellpadding="30" cellspacing="0" width="100%">
																<tr>
																	<td align="center" valign="top">

																		<!-- CONTENT TABLE // -->
																		<table border="0" cellpadding="0" cellspacing="0" width="100%">
																			<tr>
																				<td valign="top" class="textContent">
																				 
																					<div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
																						<div style="margin:0 auto; width:80%; height:auto; background-color:#f9f9f9; padding:15px;">
																							<div style="margin:0 auto; width:80%; height:auto; background-color:#f9f9f9; padding:15px;">
																								<div >Hello <span >'.$user_info->user_email.'</span>,</div>
																								<div style="font-size:14px; color:#666666; padding-top:15px; padding-bottom:15px;">Welcome to Medicine Man.</div>
																								<div style="font-size:14px; color:#666666; padding-top:0px; padding-bottom:0px;"> Username: '.$user_info->user_email.'</div>
																								<div style="font-size:14px; color:#666666; padding-top:0px; padding-bottom:5px;"> Password: '.$password.'</div>
																								<div style="font-size:14px; color:#666666; padding-top:0px; padding-bottom:5px;"> Thanks for your registration</div>
																								 
																								<div style="font-size:14px; color:#666666; padding-top:5px; padding-bottom:5px;">
																									You will get a approval email soon.
																								</div>
																								
																								<div style="font-size:16px; color:#666666; padding-top:10px; padding-bottom:10px;">
																									Thanks & Kind regards,
																								</div>
																								 <div style="font-size:16px;  color:#666666; padding-top:10px; padding-bottom:10px;">
																									Medicine Man team
																								</div>
																							</div>   
																						</div>  
																					
																					</div>
																				</td>
																			</tr>
																		</table>
																		<!-- // CONTENT TABLE -->

																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
												<!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
						 
						</table>					</td>
				</tr>
			</table>
		</center>
	</body>
</html>
';
		 wc_mail($user_info->user_email, __('Thanks Email '), $html);	  
		// 	email to admin

	   
		 $html1 = '<html><head></head><body style="color:#666666; font-size:18px; font-family: "Work Sans";">
		<link href="https://fonts.googleapis.com/css?family=Work+Sans" rel="stylesheet">
				<div style="background-color:#fff; width:100%; height:auto">
						<div style="padding-top:65px; padding-bottom:65px; text-align:center">
									<img src="http://medicineman.menu/demo/wp-content/uploads/2017/08/medicineman-logo.png">
						</div>
							<div style="margin:0 auto; width:80%; height:auto; background-color:#f9f9f9; padding:15px;">
								<div >Hello <span >Admin</span>,</div>
								<div style="font-size:14px; color:#666666; padding-top:15px; padding-bottom:15px;">A new user registerd on your website , Below is the details of user.</div>
								 
								 
								<div style="font-size:14px; color:#666666; padding-top:0px; padding-bottom:0px;"> Email: '.$user_email.'</div>
								<div style="font-size:14px; color:#666666; padding-top:0px; padding-bottom:0px;"> Phone : '.$telephone.'</div>
							  
								
								<div style="font-size:16px; color:#666666; padding-top:10px; padding-bottom:10px;">
									Thanks & Kind regards,
								</div>
								 <div style="font-size:16px;  color:#666666; padding-top:10px; padding-bottom:10px;">
									Medicine Man team
								</div>
							</div>   
				</div></body></html>';
		wc_mail(get_option('admin_email'), __('New user register '), $html1);			
		 
		   wp_logout();
		 $url = get_permalink(1123);
         wp_redirect( $url.'?success=true');
	}
}

function old_registration_function() {
    if ( isset($_POST['submit'] ) ) {
  
        old_registration_validation(
        
        $_POST['email'],
        $_POST['codes_of_conduct'], 
        $_POST['telephone'], 
        $_FILES['gov_id_one']  
        
        );
         
        // sanitize user form input
		
	 
 

        global $first_name,$last_name,$email,$telephone,$street_address,$street_address_line2,$city,$province,$postal_code,$country,$gov_id_one,$message,$gov_id_two;
        
		//$first_name =   sanitize_text_field( $_POST['last_name'] );
       // $last_name  =   sanitize_text_field( $_POST['last_name'] ); 
        $email      =   sanitize_email( $_POST['email'] );
        $telephone  =   sanitize_text_field( $_POST['telephone'] ); 
        //$street_address  =   sanitize_text_field( $_POST['street_address'] ); 
        //$street_address_line2  =   sanitize_text_field( $_POST['street_address_line2'] ); 
        //$city  =   sanitize_text_field( $_POST['city'] ); 
        //$province  =   sanitize_text_field( $_POST['province'] ); 
        //$postal_code  =   sanitize_text_field( $_POST['postal_code'] ); 
        //$country  =   sanitize_text_field( $_POST['country'] );  
        
        $gov_id_one        =   $_POST['gov_id_one'];
        //$gov_id_two        =    $_POST['gov_id_two'];
 
        // call @function complete_registration to create the user
        // only when no WP_error is found
        old_complete_registration(
      
       // $first_name,
		//$last_name,
		$email,
		$telephone, 
		$gov_id_one 
	 
		 
        );
    }
 
    old_registration_form(
         
		$email,
		$telephone, 
		$gov_id_one,
		$message 
		 
        );
}

// Register a new shortcode: [old_custom_registration]
add_shortcode( 'old_custom_registration', 'old_registration_shortcode' );
 
// The callback function that will replace [book]
function old_registration_shortcode() {
    ob_start();
    old_registration_function();
    return ob_get_clean();
}