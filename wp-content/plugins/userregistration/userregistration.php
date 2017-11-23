<?php
/*
  Plugin Name: Custom Registration
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
function registration_form( $email,$telephone,$gov_id_one ) { ?>
		
	  
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
		
	if ( is_user_logged_in() ) {
	 
			  $current_user = wp_get_current_user();
			    $userId = $current_user->ID;
				$user_approved_status =  get_user_meta($userId, 'pw_user_status', true); 
				$govid = get_user_meta($userId, 'gov_id_one', true);
				 // if user already approved
				 if($user_approved_status == 'approved'){
					   //echo 'approved';die;
					   $my_acc = get_permalink( get_option('woocommerce_myaccount_page_id') );
						header("Location: ".$my_acc, true);
						die();
						 
				 }else if( $user_approved_status != 'approved' && $govid ==''){ 
					  wp_logout();
					  $my_acc = get_permalink(955);
					 wp_redirect( $my_acc.'?gove=false&id='.$userId );
					//header("Location: ".$my_acc, true);
					die();
						
					// wp_logout_url( 'http://example.com' );
				 }else{
						$my_acc = get_permalink(955);
						wp_logout();
						wp_redirect( $my_acc.'?approve=false&id='.$userId );
					 	die;
				 }
				//$u_meta = get_user_meta($userId, 'gov_id_one', true);
			 
				 
	} 
		
 echo '
 <div class="entry-title title-icon">
			<h3 class="cart-title">Sign up</h3>
		</div>
		
	 <div class="socireg" style="text-align: center;">
	'.do_shortcode( '[alka_facebook/]' ).'	 
	'.do_shortcode( '[alka_gmail/]' ). 
	'<div id="alka-gmail-wrapper"><a class="login btn"  id="alka-gmail-button" href="javascript:void(0);"><img src="'.get_template_directory_uri('template_url').'/images/loginwithgoogle.png"  style="width: 50%;"/></a></div>
	
	<h3>OR</h3></div>	 
		<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
		<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

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
			 <p class="form1 full">Phone Number  <br>
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
			   <!--p>Additional Message<br>
                  <textarea name="message" value=""' . ( isset( $_POST['message'] ) ? $message : null ) . '"" cols="40" rows="3" class="form-control" aria-invalid="false"></textarea>
               </p-->
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
  
function registration_validation( $email,$codes_of_conduct,$gov_id_one)  {
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
function complete_registration() {
	 
	 
    global $reg_errors, $password,$email,$telephone,$gov_id_one;
    $password = wp_generate_password( 8, false ); 
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
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
		//update_user_meta( $user_id, 'billing_address_1', sanitize_text_field( $street_address ) );
		//update_user_meta( $user_id, 'billing_address_2', sanitize_text_field( $street_address_line2 ) );
		//update_user_meta( $user_id, 'billing_city', sanitize_text_field( $city ) );
		//update_user_meta( $user_id, 'billing_postcode', sanitize_text_field( $postal_code ) );
		//u//pdate_user_meta( $user_id, 'billing_province', sanitize_text_field( $province ) );
		//update_user_meta( $user_id, 'billing_country', sanitize_text_field( $country ) );
		//update_user_meta( $user_id, 'message', sanitize_text_field( $message ) );
		
		
		
		  if ( !empty( $telephone ) ) {
			global $wpdb;
				$querystr = "SELECT * FROM md3_old_user WHERE phone = $telephone"; 
				$pageposts = $wpdb->get_results($querystr, OBJECT);
				 
				if(count($pageposts) > 0){  
					update_user_meta($user_id, 'is_old_user', 'true');
					update_user_meta($user_id, 'is_gift_send', 'false');
		 
				} 
		} 
		
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
		 $content1 = '<h3>Hello, '.$user_info->user_email.'</h3>
						<br>
						<h3><small>Welcome to <a href="'.site_url() .'">Medicine Man.</small></a></h3>
						<p>We’ve sent you 175 points for signing up! You can start collecting these points to either redeem for cool prizes or discounts towards your future orders! </p> 				
						<br/>
						<p>An approval email will be sent to your account when your age has been approved. In the meantime you can still <a href="http://medicinemanshop.ca/cart/">finalize</a> your order to speed up the process and enjoy your product sooner! </p> 				
						<br/>
						<h4>Your Account Details :</h4>
						 
						<p>Username: '.$user_info->user_email.'<p>
						<p>Password: '.$password.'<p>
						<br/>';
		$html = email_template($content1);		
		 wc_mail($user_info->user_email, __('The Medicine Man would like to Thank You for signing up and choosing to shop with us!'), $html);	  
		// 	email to admin
		$u_meta = get_user_meta($user_id, 'gov_id_one', true);
		$uploads = wp_upload_dir();
		$uploaddir = 'http://medicinemanshop.ca/wp-content/uploads/users/'.$u_meta; 
		
		
		$approve = 'http://medicinemanshop.ca/wp-admin/users.php?page=new-user-approve-admin&user='.$user_id.'&status=approve&tab=pending_users&_wpnonce=c1c42af919';
		$deny = 'http://medicinemanshop.ca/wp-admin/users.php?page=new-user-approve-admin&user='.$user_id.'&status=deny&tab=pending_users&_wpnonce=c1c42af919';
		
	   $content2 = ' <h3>Hello, Admin</h3>
					<p>A new user registered on your website , Below is the details of user.</p><br/>
					<h4>New Account Details :</h4> 
					<p>Username: '.$user_email.'<p> 
					<p><a download href="'.$uploaddir.'">Click Here</a> To Download User Government ID</p>
					<p>To approve or deny this user access to Medicine Man go to </p>
					<a target="_blank" class="btn" href="'.$approve.'">Click To Approve </a>  	<a target="_blank" class="btn" href="'.$deny.'">Click To Deny </a> 
					<br/><br/>';
					
					
					
		$html1 = email_template($content2);			
		wc_mail(get_option('admin_email'), __('New user register '), $html1);			
		 
		   wp_logout();
		 $url = get_permalink(955);
         wp_redirect( $url);
	}
}

function custom_registration_function() {
    if ( isset($_POST['submit'] ) ) {
  
        registration_validation(
        
        $_POST['email'],
        $_POST['codes_of_conduct'], 
        $_FILES['gov_id_one']  
        
        );
         
        // sanitize user form input
		
	 
 

        global $first_name,$last_name,$email,$telephone,$street_address,$street_address_line2,$city,$province,$postal_code,$country,$gov_id_one,$gov_id_two;
        
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
      //  $message        =   esc_textarea( $_POST['message'] );
        $gov_id_one        =   $_POST['gov_id_one'];
        //$gov_id_two        =    $_POST['gov_id_two'];
 
        // call @function complete_registration to create the user
        // only when no WP_error is found
        complete_registration(
      
       // $first_name,
		//$last_name,
		$email,
		$telephone,
		//$street_address,
		//$street_address_line2,
		//$city,
		//$province,
		//$postal_code,
		//$country,
		$gov_id_one
		//$gov_id_two
        );
    }
 
    registration_form(
         
		$email,
		$telephone, 
		$gov_id_one
		 
        );
}

// Register a new shortcode: [cr_custom_registration]
add_shortcode( 'cr_custom_registration', 'custom_registration_shortcode' );
 
// The callback function that will replace [book]
function custom_registration_shortcode() {
    ob_start();
    custom_registration_function();
    return ob_get_clean();
}