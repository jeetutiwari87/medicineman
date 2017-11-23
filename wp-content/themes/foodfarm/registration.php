<?php /* Template Name: CustomPageT1 */ ?>
 
<?php get_header(); ?>
 
 
<div class="container">
	<div class="row">	
	<?php 
	if($_REQUEST['gove']){
		$user_id = $_REQUEST['id'];
		$error = '';
		if ( isset($_POST['submit'] ) ) {
			 
			 if ( empty( $_FILES['gov_id_one']['name'] ) ) {
				 $error = 'Please Upload Government Id' ;
				 
			}else{
				
				if ($_FILES['gov_id_one']['error'] == 0) {
		 
					$uploads = wp_upload_dir();
				 
					$uploaddir = $uploads['basedir']  .'/users/'; 
						
					$file = $uploaddir . $user_id.basename($_FILES['gov_id_one']['name']); 
					$raw_file_name = $_FILES['gov_id_one']['tmp_name'];
					$name =  $user_id.$_FILES['gov_id_one']['name'];
					if (move_uploaded_file($_FILES['gov_id_one']['tmp_name'], $file)) { 
					update_user_meta($user_id, 'gov_id_one', sanitize_text_field($name)); 	 
					} 

					$user_approved_status =  get_user_meta($user_id, 'pw_user_status', true);
 					 if($user_approved_status == 'approved'){
					 $my_acc = get_permalink( get_option('woocommerce_myaccount_page_id') );
					 }else{
					    $my_acc = get_permalink(955); 
				    	wp_redirect( $my_acc.'?approve=false&id='.$user_id );
					 }
					
					
				
				}
			}
		}

	?>
		
		<div class="vc_row wpb_row vc_row-fluid"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner "><div class="wpb_wrapper">
				<div class="wpb_text_column wpb_content_element ">
					<div class="wpb_wrapper">
						
			 <div class="wpb_wrapper"> 
							 
 
			<div class="entry-title title-icon">
				<h3 class="cart-title">Thank You</h3>
			</div>
			<form id="regform1" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="registration-form shadow3" enctype="multipart/form-data">
				<div class="row">
					  <div class="col-sm-3"></div>
					  <div class="col-sm-6">
					  <div class="vc_column-inner ">
							<?php if($error != ''){
								 echo '<ul id="errormessage">';
								echo '<li>';
										echo $error;
										echo '</li>';
								 echo '</ul>';		
							} ?>
						 
						 <div class="fullbox">
						     <p><strong>Thank you for registration!</strong></p>
							<p class="form1 full">Government ID <span>*<span><br>
							<input name="gov_id_one" data-validation="required" size="40" class="form-control" type="file">
							</span></span></p>
							  
						 </div>
						   <div class="inst">Upload valid Government photo ID for proof of legal age (18+ or 19+) depending on your province. Scan or photograph accepted. </div>
						  <div class="fullbox"> 
						   
						</div>
					 </div> 
					  </div>
					  <div class="col-sm-3"></div> 	
					<div class="col-md-12 col-sm-12 col-xs-12 text-center">
					<div class="btn btn-default btn-icon">
					<input class="regbtn wpcf7-form-control wpcf7-submit" name="submit" value="Submit" type="submit"> 
					  
					</div>
					</div>
				 
			   </div> 
			</form> 
			 

		</div>
		</div>
		</div> 
			</div></div></div></div>
		
	<?php }else if($_REQUEST['denied']){ 
		$user_id = base64_decode($_REQUEST['id']);
		
		$user_info = get_userdata($user_id);
		$error = '';
		if ( isset($_POST['submit2'] ) ) {
			 
			 if ( empty( $_FILES['gov_id_one']['name'] ) ) {
				 $error = 'Please Upload Government Id' ;
				 
			}else{
				
				if ($_FILES['gov_id_one']['error'] == 0) {
		 
					$uploads = wp_upload_dir();
				 
					$uploaddir = $uploads['basedir']  .'/users/'; 
						
					$file = $uploaddir . $user_id.basename($_FILES['gov_id_one']['name']); 
					$raw_file_name = $_FILES['gov_id_one']['tmp_name'];
					$name =  $user_id.$_FILES['gov_id_one']['name'];
					if (move_uploaded_file($_FILES['gov_id_one']['tmp_name'], $file)) { 
					update_user_meta($user_id, 'gov_id_one', $name); 	 
					} 

					$user_approved_status =  get_user_meta($user_id, 'pw_user_status', true);
 					 if($user_approved_status == 'approved'){
					 $my_acc = get_permalink( get_option('woocommerce_myaccount_page_id') );
					 }else{
						$link = 'http://medicinemanshop.ca/wp-admin/users.php?page=new-user-approve-admin&tab=denied_users';
						 $content2 = '<h3>Hello, Admin</h3>
						<br>
						<h3><small>A denied user uploaded own Government Id again. Please check and approve the users.</small></h3>
						 <br/>
						<h4>Account Details :</h4> 
						<p>Username: '.$user_info->user_login.'<p> 
						<br/>
						<p><a  target="_blank" href="'.$link.'" class="btn">Click Here To View User</a></p>';
						
						$html1 = email_template($content2);			
						wc_mail(get_option('admin_email'), __('Government Id uploaded By Denied User'), $html1);
		
		
					    $my_acc = get_permalink(955); 
				    	wp_redirect( $my_acc.'?approve=false&id='.$user_id );
						
						
					 }
					
					
				
				}
			}
		}

	?>
		
		<div class="vc_row wpb_row vc_row-fluid"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner "><div class="wpb_wrapper">
				<div class="wpb_text_column wpb_content_element ">
					<div class="wpb_wrapper">
						
			 <div class="wpb_wrapper"> 
							 
 
			<div class="entry-title title-icon">
				<h3 class="cart-title">Your Government Id Is Not Valid , Please Upload Again</h3>
			</div>
			<form id="regform2" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="registration-form shadow3" enctype="multipart/form-data">
				<div class="row">
					  <div class="col-sm-3"></div>
					  <div class="col-sm-6">
					  <div class="vc_column-inner ">
							<?php if($error != ''){
								 echo '<ul id="errormessage">';
								echo '<li>';
										echo $error;
										echo '</li>';
								 echo '</ul>';		
							} ?>
						 
						 <div class="fullbox">
						     <p><strong>Please upload your Government Id Again </strong></p>
							<p class="form1 full">Government ID <span>*<span><br>
							<input name="gov_id_one" data-validation="required" size="40" class="form-control" type="file">
							</span></span></p>
							  
						 </div>
						   <div class="inst">Upload valid Government photo ID for proof of legal age (18+ or 19+) depending on your province. Scan or photograph accepted. </div>
						  <div class="fullbox"> 
						   
						</div>
					 </div> 
					  </div>
					  <div class="col-sm-3"></div> 	
					<div class="col-md-12 col-sm-12 col-xs-12 text-center">
					<div class="btn btn-default btn-icon">
					<input class="regbtn wpcf7-form-control wpcf7-submit" name="submit2" value="Submit" type="submit"> 
					  
					</div>
					</div>
				 
			   </div> 
			</form> 
			 

		</div>
		</div>
		</div> 
			</div></div></div></div>
		
	<?php }else if($_REQUEST['approve']){  		 
	?>
		
			 <div class="vc_row wpb_row vc_row-fluid"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner "><div class="wpb_wrapper">
			<div class="wpb_text_column wpb_content_element ">
					<div class="wpb_wrapper">
						<p><strong>Thank you for filling out your information!</strong></p>
			<p>You will get a approval email when your account has been approved.</p>

					</div>
				</div>
			<div class="vc_empty_space" style="height: 32px"><span class="vc_empty_space_inner"></span></div>
			</div></div></div></div>
		
		
	<?php }else{
 
	 while ( have_posts() ) : the_post();
 
              the_content();  
 
            // End of the loop.
        endwhile;
	}
	?>	 
	 
	</div>
</div>
 
 
<?php get_footer(); ?>