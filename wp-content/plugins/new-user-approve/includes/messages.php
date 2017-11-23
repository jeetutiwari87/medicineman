<?php

/**
 * The default email message that will be sent to users as they are approved.
 *
 * @return string
 */
function nua_default_approve_user_message() {
	$message = __( '<p>You have been approved to access {sitename}</p>', 'new-user-approve' ) . "\r\n\r\n";
	$message .= "<p>{username}</p>";
	$message .= "<p><a  target='_blank' href='".get_permalink( get_option('woocommerce_myaccount_page_id') )."' class='btn'>Click Here To Login</a></p>";
    $message .= __( '<p>To set or reset your password, visit the following address:</p>', 'new-user-approve' ) . "\r\n\r\n";
    $message .= "<p><a target='_blank' href='".get_permalink( get_option('woocommerce_myaccount_page_id') )."' class='btn'>Click Here Reset Password</a></p>";

	$message = apply_filters( 'new_user_approve_approve_user_message_default', $message );

	return $message;
}

/**
 * The default email message that will be sent to users as they are denied.
 *
 * @return string
 */
function nua_default_deny_user_message($user_id) {

	$my_acc = get_permalink(955);
	
	$link = $my_acc.'?denied=true&id='.base64_encode($user_id);

	$message = __( '<p>your ID was invalid you are either under the legal age or you have submitted an incorrect document. Please try again by signing up via medicinemanshop.ca/signup and ensure you are uploading a valid Government ID (license, passport, citizenship card, residency card) </p>', 'new-user-approve' );
	$message .= "<p><a  target='_blank' href='".$link."' class='btn'>Click Here To Upload Your Government  Id again</a></p>";
	$message = apply_filters( 'new_user_approve_deny_user_message_default', $message );

	return $message;
}

/**
 * The default message that will be shown to the user after registration has completed.
 *
 * @return string
 */
function nua_default_registration_complete_message() {
	$message = sprintf( __( 'An email has been sent to the site administrator. The administrator will review the information that has been submitted and either approve or deny your request.', 'new-user-approve' ) );
	$message .= ' ';
	$message .= sprintf( __( 'You will receive an email with instructions on what you will need to do next. Thanks for your patience.', 'new-user-approve' ) );

	$message = apply_filters( 'new_user_approve_pending_message_default', $message );

	return $message;
}

/**
 * The default welcome message that is shown to all users on the login page.
 *
 * @return string
 */
function nua_default_welcome_message() {
	$welcome = sprintf( __( 'Welcome to {sitename}. This site is accessible to approved users only. To be approved, you must first register.', 'new-user-approve' ), get_option( 'blogname' ) );

	$welcome = apply_filters( 'new_user_approve_welcome_message_default', $welcome );

	return $welcome;
}

/**
 * The default notification message that is sent to site admin when requesting approval.
 *
 * @return string
 */
function nua_default_notification_message() {
	$message = __( '<p>{username} ({user_email}) has requested a username at {sitename}</p>', 'new-user-approve' ) . "\n\n";
	$message .= __( '<p>To approve or deny this user access to {sitename} go to</p>', 'new-user-approve' ) . "\n\n";
	$message .= "{admin_approve_url}\n\n";

	$message = apply_filters( 'new_user_approve_notification_message_default', $message );

	return $message;
}

/**
 * The default message that is shown to the user on the registration page before any action
 * has been taken.
 *
 * @return string
 */
function nua_default_registration_message() {
	$message = __( '<p>After you register, your request will be sent to the site administrator for approval. You will then receive an email with further instructions.</p>', 'new-user-approve' );

	$message = apply_filters( 'new_user_approve_registration_message_default', $message );

	return $message;
}
