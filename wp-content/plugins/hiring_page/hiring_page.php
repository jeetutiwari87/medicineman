<?php
@session_start();
ob_start();

/*

Plugin Name: Hiring page



Description: This plugin show hiring page on front-end and save data in admin listing



Author: jeetendra sharma (jeetutiwari87@gmail.com)

*/



//error_reporting(0);




if (!class_exists("HiringPage")){



	class HiringPage{



		var $product_table_name = '';



		var $plugin_url = '';



		var $plugin_path = '';



		var $init = 0;

		

        var $phpThumbBase = '';

		

		

		



		function HiringPage(){



			//constructor



			$this->plugin_url = get_bloginfo("wpurl")."/wp-content/plugins/hiring_page";



			$this->plugin_path = str_replace('\\','/',dirname(__FILE__));

			

		}

		



		function Init(){



			//create table
			

			if ($this->CreateHiringTable() == false){

				return false;

			}

			



			$this->init = 1;



			



			return true;



		}

		



		function addHeader() {



			//echo "<link type='text/css' href = '".get_bloginfo('wpurl')."/wp-content/plugins/book_classes/css/book_class_style.css' />\n";

			/*echo "<script type = 'text/javascript' src = '".get_bloginfo('wpurl')."/wp-content/plugins/book_classes/js/buy_class.js'></script>\n";*/

			



		}



		



		/**



		 * Creates roduct table



		 */



		function CreateHiringTable() {



			 global $wpdb,$table_prefix;



			$rs = "SHOW TABLES LIKE '".$table_prefix."hirings'";



			$rowcount             = $wpdb->get_var( $rs );



			if ($rowcount==0 ) {



				   $sql = "CREATE TABLE ".$table_prefix."hirings (

				          `id` bigint(20) NOT NULL AUTO_INCREMENT,

						  `what_position_looking_for` varchar(255) NOT NULL,

						  `name` varchar(255) NOT NULL,

						  `address` varchar(255) NOT NULL,

						  `age` varchar(50) NOT NULL,

						  `tell_us_yourself` text NOT NULL,
						  
						  `briefly_tell_us_yourself` text NOT NULL,

						  `resume` varchar(255) NOT NULL,
						  
						  `cover_letter` varchar(255) NOT NULL,

						  `availability` datetime NOT NULL,

						  `created_at` datetime NOT NULL,

	                      `updated_at` datetime NOT NULL,

						   PRIMARY KEY (`id`)

						);



						";



				$wpdb->query($sql);

	

				return true;



			}



			return false;



		}

		 function manage_hiring(){

            global $wpdb,$table_prefix;

			

			include_once('includes/manage_hiring.php');

			



		 }


		 

	}//end of class EasyProducts



}











if (class_exists("HiringPage")) {



	$hl_EasyProducts = new HiringPage();



}





if (!function_exists("HiringPage_ap")) {



	function HiringPage_ap() {



		global $hl_EasyProducts;



		if (!isset($hl_EasyProducts)) {



			return;



		}

	

		add_menu_page('Hirings', 'Hirings', 9 , basename(__FILE__) , array(&$hl_EasyProducts, 'manage_hiring') );

		

		add_submenu_page (basename(__FILE__),'Manage Hirings', 'Manage Hirings', 9 , 'manage_hiring', array(&$hl_EasyProducts, 'manage_hiring') );


	
		remove_submenu_page( basename(__FILE__), basename(__FILE__));

	}	



}



if (isset($hl_EasyProducts)) {



	add_action('admin_menu', 'HiringPage_ap',1);



	add_action('activate_hiring_page/hiring_page.php', array(&$hl_EasyProducts, 'Init'));



}


add_action( 'wp_ajax_nopriv_save_hiring', 'save_hiring' );

add_action( 'wp_ajax_save_hiring', 'save_hiring' );

	

function save_hiring(){

  global $wpdb,$table_prefix;

  $response_data=array();

    
    if(isset($_POST)){
        $is_error=0;
		$what_position_looking_for=addslashes($_POST['what_position_looking_for']);
		$name=addslashes($_POST['name']);
		$address=addslashes($_POST['address']);
		$age=addslashes($_POST['age']);
		$tell_us_yourself=addslashes($_POST['tell_us_yourself']);
		$briefly_tell_us_yourself=addslashes($_POST['briefly_tell_us_yourself']);
		$availability=date('Y-m-d H:i:s',strtotime($_POST['availability']));
	    $CreatedOn=date('Y-m-d H:i:s');
		$updateOn=date('Y-m-d H:i:s');
		
		if(isset($_FILES['resume']['name']) && $_FILES['resume']['name']!=''){
		   $ext=strtolower(end(explode(".",$_FILES['resume']['name'])));
		   if($ext=='pdf' || $ext=='doc' || $ext=='docx' || $ext=='jpg' || $ext=='png'){
		     $resume_file_name=time().'.'.$ext;
			 copy($_FILES['resume']['tmp_name'],dirname(__FILE__).'uploads/'.$resume_file_name);
		   }else{
		     $is_error=1;
			   $response_data['status']=0;
			  $response_data['message']="Resume file only accept pdf, doc, jpg and png";
		   }
		}else{
		  $resume_file_name='';
		}
		if(isset($_FILES['cover_letter']['name']) && $_FILES['cover_letter']['name']!=''){
		   $ext=strtolower(end(explode(".",$_FILES['cover_letter']['name'])));
		   if($ext=='pdf' || $ext=='doc' || $ext=='docx' || $ext=='jpg' || $ext=='png'){
		     $cover_letter_file_name=time().'.'.$ext;
			 copy($_FILES['cover_letter']['tmp_name'],dirname(__FILE__).'uploads/'.$cover_letter_file_name);
		   }else{
		     $is_error=1;
			  $response_data['status']=0;
			  $response_data['message']="cover letter file only accept pdf, doc, jpg and png";
		   }
		}else{
		  $cover_letter_file_name='';
		}
	
	 if($is_error==0){
        echo $sqlString = "INSERT INTO ".$wpdb."hirings SET 
			                                         what_position_looking_for='".$what_position_looking_for."',
													 name='".$name."',
													 address='".$address."',
													 age='".$age."',
													 tell_us_yourself='".$tell_us_yourself."',
													 briefly_tell_us_yourself='".$briefly_tell_us_yourself."',
													 availability='".$availability."',
													 created='".$CreatedOn."',
													 modified='".$updateOn."'";
			exit;
													

            $wpdb->query($sqlString);
			$response_data['status']=1;

	      $response_data['message']="Hiring data saved successfully";
	  }
	}else{
	  $response_data['status']=0;
	  $response_data['message']="request is empty, please try again";
	}

	echo json_encode($response_data);
    exit;

	

}



add_filter('widget_text', 'do_shortcode');

add_shortcode( 'hiring_form', 'hiring_form_func' );
function hiring_form_func(){

  global $wpdb,$table_prefix,$post;

?>
   <form method="post" id="HiringForm" name="HiringForm"  enctype="multipart/form-data" >

 <div class="box">


                      

                        

                            <div class="content">

                                <div class="row">

                                    <div class="col-sm-6">

                                        <div class="form-group">

                                            <label for="firstname">What position are you looking for</label>
                                           <select name="what_position_looking_for" id="what_position_looking_for" class="form-control validate[required]" data-errormessage-value-missing="Please select What position are you looking for">
										    <option value=""></option>
											<option value="Driver">Driver</option>
											<option value="Packer">Packer</option>
											<option value="Dispatch/Receptionist">Dispatch/Receptionist</option>
											<option value="Marketer">Marketer</option>
										
										   </select>
										  
                                        </div>

                                    </div>

                                    <div class="col-sm-6">

                                        <div class="form-group">

                                            <label for="lastname">Name</label>

                                            <input type="text" class="form-control validate[required]" id="name" value="" name="name" placeholder="Enter name" data-errormessage-value-missing="Please enter name" />

					    

                                        </div>

                                    </div>

                                </div>

                                <!-- /.row -->



                                <div class="row">

                                   

                                    <div class="col-sm-6">

                                        <div class="form-group">

                                            <label for="street">Address</label>

											
                                            <input type="text" class="form-control validate[required]"  id="address" name="address" value="" placeholder="Enter Address"  data-errormessage-value-missing="Please enter address" />

					   

                                        </div>

                                    </div>

									<div class="col-sm-6">

                                        <div class="form-group">

                                            <label for="city">Age (must be 18 years old)</label>

                                            <input type="text" class="form-control validate[number]"  id="age" name="age" value="" placeholder="Enter Age" data-errormessage-value-missing="Age must be a number"  />


                                        </div>

                                    </div>


                                </div>

                                <!-- /.row -->



                                <div class="row">

                                    

                                   

                                    <div class="col-sm-6">

                                        <div class="form-group">

                                            <label for="phone">Tell us about yourself</label>

                                            <textarea class="form-control validate[required]" id="tell_us_yourself" name="tell_us_yourself" data-errormessage-value-missing="Please enter Tell us about yourself"></textarea>

					  

                                        </div>

                                    </div>

                                    <div class="col-sm-6">

                                        <div class="form-group">

                                            <label for="phone">Briefly tell us why you'd like this job and why you'd be a good fit</label>

                                            <textarea class="form-control" id="briefly_tell_us_yourself" name="briefly_tell_us_yourself"></textarea>

					  

                                        </div>

                                    </div>

					

                                </div>
								
								<div class="row">

                                   

                                    <div class="col-sm-6">

                                        <div class="form-group">

                                            <label for="street">Upload resume</label>

											
                                            <input type="file" class="form-control validate[required]" data-errormessage-value-missing="Please upload resume"  id="resume" name="resume" />

					   

                                        </div>

                                    </div>

									<div class="col-sm-6">

                                        <div class="form-group">

                                            <label for="street">Cover letter</label>

											
                                            <input type="file" class="form-control"  id="cover_letter" name="cover_letter" />

					   

                                        </div>

                                    </div>


                                </div>
								
								<div class="row">

                                   

                                    <div class="col-sm-6">

                                        <div class="form-group">

                                            <label for="street">What is your availability?</label>

											<input type="hidden" name="action" value="save_hiring" />
                                            <input type="text" class="datepicker form-control validate[required]" data-errormessage-value-missing="Please enter your availability time"  id="availability" name="availability" value=""  />

					   

                                        </div>

                                    </div>

									


                                </div>

								
                            </div>

				
                        

						

 </div>

 <div class="box-footer">

                               

	<div class="pull-right">

		<button type="submit" class="btn btn-primary" id="save_order" >Save <i class="fa fa-chevron-right"></i>

		</button>

	</div>

</div>

 </form>
 <link href="<?php echo get_bloginfo('wpurl');?>/wp-content/plugins/hiring_page/css/validationEngine.jquery.css" rel="stylesheet" type="text/css" media="all" />
 <link href="<?php echo get_bloginfo('wpurl');?>/wp-content/plugins/hiring_page/css/jquery.datetimepicker.css" rel="stylesheet" type="text/css" media="all" />
<script src="<?php echo get_bloginfo('wpurl');?>/wp-content/plugins/hiring_page/js/jquery.validationEngine.js"></script>
<script src="<?php echo get_bloginfo('wpurl');?>/wp-content/plugins/hiring_page/js/jquery.validationEngine-en.js"></script>	
<script src="<?php echo get_bloginfo('wpurl');?>/wp-content/plugins/hiring_page/js/jquery.datetimepicker.full.js"></script>
	<script type="text/javascript">
    jQuery(document).ready(function(){
     jQuery("#HiringForm").validationEngine({promptPosition : "bottomLeft"});
	 jQuery('.datepicker').datetimepicker({format:'m/d/Y H:i:s'});
    });
	
	jQuery( '#HiringForm' ).submit( function( e ) {
	
	  e.preventDefault();
      var looking_for=jQuery.trim(jQuery("#what_position_looking_for").val());
	  var name=jQuery.trim(jQuery("#name").val());
	  var address=jQuery.trim(jQuery("#address").val());
	  var age=jQuery.trim(jQuery("#age").val());
	  var tell_us_yourself=jQuery.trim(jQuery("#tell_us_yourself").val());
	  var resume=jQuery.trim(jQuery("#resume").val());
	  var availability=jQuery.trim(jQuery("#availability").val());
	 
	  if(looking_for!='' && name!='' && address!='' && age!='' && tell_us_yourself!='' && resume!='' && availability!=''){
		  jQuery.ajax({
	
			url : '/medicinemanshop/wp-admin/admin-ajax.php',
	
			type : 'POST',
	
			dataType : "json",
	
			data: new FormData( this ),
            processData: false,
            contentType: false,
			success : function( response ) {
	
				if(response.status==0){
	
				  alert(response.message);
	
				}else{
				  jQuery("#HiringForm")[0].reset();
				   alert(response.message);
				}
	
			}
	
		  });
	  }

	});
	

	</script>

<?php

}
?>