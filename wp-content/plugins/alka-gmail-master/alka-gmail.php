<?php
/**
 * Plugin Name:       Alka Gmail
 * Description:       Login and Register your users using gmail's API
 
 */

 
include (plugin_dir_path( __FILE__ ) . 'lib/Google_Client.php');
include (plugin_dir_path( __FILE__ ) . 'lib/Google_Oauth2Service.php');
session_start();

$base_url= filter_var('http://medicinemanshop.ca/', FILTER_SANITIZE_URL);

// Visit https://code.google.com/apis/console to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.

 

define('CLIENT_ID','404032081223-rn0vu30irghl9ggb94m0d76o9k0csipf.apps.googleusercontent.com');
define('CLIENT_SECRET','1sMX7rL5NM8AiEYBWb1dbdZ0');
define('REDIRECT_URI','http://medicinemanshop.ca/sign-up/');
define('APPROVAL_PROMPT','auto');
define('ACCESS_TYPE','offline');


 
/**
 * Class AlkaFacebook
 */
class AlkaGmail{

    
    public function __construct()
    {

        // We register our shortcode
        add_shortcode( 'alka_gmail', array($this, 'renderGShortcode') );
 
    }

    /**
     * Render the shortcode [alka_gmail/]
     *
     * It displays our Login / Register button
     */
    public function renderGShortcode() {
		$client = new Google_Client();
			$client->setApplicationName("Google UserInfo PHP Starter Application");

			$client->setClientId(CLIENT_ID);
			$client->setClientSecret(CLIENT_SECRET);
			$client->setRedirectUri(REDIRECT_URI);
			$client->setApprovalPrompt(APPROVAL_PROMPT);
			$client->setAccessType(ACCESS_TYPE);

			$oauth2 = new Google_Oauth2Service($client);

			if (isset($_GET['code'])) {
			  $client->authenticate($_GET['code']);
			  $_SESSION['token'] = $client->getAccessToken();
			  echo '<script type="text/javascript">window.close();</script>'; exit;
			}

			if (isset($_SESSION['token'])) {
			 $client->setAccessToken($_SESSION['token']);
			}

			if (isset($_REQUEST['error'])) {
			 echo '<script type="text/javascript">window.close();</script>'; exit;
			}
			
			if ($client->getAccessToken()) {
			   $user = $oauth2->userinfo->get();
			   $_SESSION['token'] = $client->getAccessToken();
				 
				$username = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
				$email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
				$name = $user['name'];
			    $user_id = username_exists( $username );
				if ( !$user_id and email_exists($username) == false ) { 
						$new_user = wp_create_user($username, wp_generate_password(), $username); 
						update_user_meta( $new_user, 'first_name', $new_user['given_name'] );
						update_user_meta( $new_user, 'last_name', $new_user['family_name'] ); 
						update_user_meta( $new_user, 'alka_gmain_id', $user['id'] );
						wp_update_user( array ('ID' => $new_user, 'role' => 'customer') ) ;
						
						$my_acc = get_permalink(955);
						
						
						wp_redirect( $my_acc.'?gove=false&id='.$userId );
						
				} else {
					     $user = get_user_by( 'email', $email );
						 $userId = $user->ID;
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
					 
				}


			 
						
			  // These fields are currently filtered through the PHP sanitize filters.
			  // See http://www.php.net/manual/en/filter.filters.sanitize.php
			   
			  //$img = filter_var($user['picture'], FILTER_VALIDATE_URL);
			 // $personMarkup = "$email<div><img src='$img?sz=50'></div>";

			  // The access token may have been updated lazily.
			

			} else {
			  $authUrl = $client->createAuthUrl();
			}
			
	
	?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
	<script>
	
	
(function ($) {
		/* For popup */	
			$.fn.oauthpopup = function (options) {
			 this.click(function(){	
					options.windowName = options.windowName || 'ConnectWithOAuth';
					options.windowOptions = options.windowOptions || 'location=0,status=0,width='+options.width+',height='+options.height+',scrollbars=1';
					options.callback = options.callback || function () {
						window.location.reload();
					};
					var that = this;
					that._oauthWindow = window.open(options.path, options.windowName, options.windowOptions);
					that._oauthInterval = window.setInterval(function () {
						if (that._oauthWindow.closed) {
							window.clearInterval(that._oauthInterval);
							options.callback();
						}
					},10);
			  });
			};
	
		/* For Google account logout */	
			$.fn.googlelogout=function(options){
				options.google_logout= options.google_logout || "true";
				options.iframe= options.iframe || "ggle_logout";
				
					 if(this.length && options.google_logout=='true'){
						 this.after('<iframe name="'+options.iframe+'" id="'+options.iframe+'" style="display:none"></iframe>');		             }
					 if(options.iframe){
						 options.iframe='iframe#'+options.iframe;
					  }else{
						 options.iframe='iframe#ggle_logout';
					  }
				   this.click(function(){
						if(options.google_logout=='true'){				   
							$(options.iframe).attr('src','https://mail.google.com/mail/u/0/?logout');	 
							 var interval=window.setInterval(function () {
								   $(options.iframe).load(function() {
										window.clearInterval(interval);
										window.location=options.redirect_url;
								  });	
							});	
						}
						else{
							window.location=options.redirect_url;					
						}
						 
				   });
			};
		})(jQuery);



		$(document).ready(function(){
			 $('a.login').oauthpopup({
					path: '<?php if(isset($authUrl)){echo $authUrl;}else{ echo '';}?>',
					width:650,
					height:350,
				});
			});
	</script>
	<?php
	
		/*if(isset($personMarkup)){ 
		 print $personMarkup;  
		}
		 
		  if(isset($authUrl)) {
			//echo '<div id="alka-gmail-wrapper"><a class="login btn"  id="alka-gmail-button" href="javascript:void(0);"><img src="'.get_template_directory_uri('template_url').'/images/loginwithgoogle.png"  style="width: 50%;"/></a></div>';
		  } else {
		   die; "<a class='logout' href='javascript:void(0);'>Logout</a>";
		  } */
		 
    }
  

}

/*
 * Starts our plugins, easy!
 */
new AlkaGmail();