<?php
/**
 * Plugin Name: Contact Form 7 - ActiveTrail integration
 * Description: An add-on for Contact Form 7 that will integrate the form email to ActiveTrail
 * Version: 0.2
 * Author: Zeev maayan, Moshe harush
 * Author URI: http://www.zeevm.co.il
 * License: GPLv3
 */



/**
 * Verify CF7 dependencies.
 */
function cf7_activetrail_notice() {
	// Verify that CF7 is active and updated to the required version (currently 3.9.0)
	if ( is_plugin_active('contact-form-7/wp-contact-form-7.php') ) {
		$wpcf7_path = plugin_dir_path( dirname(__FILE__) ) . 'contact-form-7/wp-contact-form-7.php';
		$wpcf7_plugin_data = get_plugin_data( $wpcf7_path, false, false);
		$wpcf7_version = (int)preg_replace('/[.]/', '', $wpcf7_plugin_data['Version']);
		// CF7 drops the ending ".0" for new major releases (e.g. Version 4.0 instead of 4.0.0...which would make the above version "40")
		// We need to make sure this value has a digit in the 100s place.
		if ( $wpcf7_version < 100 ) {
			$wpcf7_version = $wpcf7_version * 10;
		}
		// If CF7 version is < 3.9.0
		if ( $wpcf7_version < 390 ) {
			echo '<div class="update-nag"><p><strong>Warning: </strong>Contact Form 7 - activetrail Add-on requires that you have the latest version of Contact Form 7 installed. Please upgrade now.</p></div>';
		}
	}
	// If it's not installed and activated, throw an error
    else {
	    echo '<div class="error"><p>Contact Form 7 is not activated. Contact Form 7 must be installed and activated before you can use the activetrail addon.</p></div>';
	}

	if( !class_exists( 'SoapClient' ) ){
		echo '<div class="error"><p><strong>Warning: </strong>Contact Form 7 - activetrail Add-on requires SoapClient php extension installed. Please add the extension via php.ini file or contact your hosting support.</p></div>';
	}
}
add_action( 'admin_notices', 'cf7_activetrail_notice' );




/**
 * Enable the ActiveTrail tags in the tag generator
 */
function cf7_activetrail_add_tag_generator() {
	if( function_exists('wpcf7_activetrail_tag_generator') ) {
        // Modify callback based on CF7 version
        $callback = function_exists('wpcf7_add_meta_boxes') ? 'wpcf7_tag_generator_activetrail_old' : 'wpcf7_tag_generator_activetrail';
		wpcf7_activetrail_tag_generator( 'activetrail', 'activetrail Fields', 'wpcf7-tg-pane-activetrail', $callback );
	}
	
}
add_action( 'admin_init', 'cf7_activetrail_add_tag_generator', 99 );


/**
 * Adds a box to the main column on the form edit page. 
 *
 * CF7 < 4.2
 */
function cf7_activetrail_tag_add_meta_boxes() {
	add_meta_box( 'cf7-activetrail-settings', 'activetrail Settings', 'cf7_activetrail_addon_metaboxes', null, 'form', 'low');
}
add_action( 'wpcf7_add_meta_boxes', 'cf7_activetrail_tag_add_meta_boxes' );


/**
 * Adds a tab to the editor on the form edit page. 
 *
 * CF7 >= 4.2
 */
function cf7_activetrail_tag_page_panels($panels) {
    $panels['activetrail-panel'] = array( 'title' => 'Active Trail integration', 'callback' => 'cf7_activetrail_addon_panel_meta' );
    return $panels;
}
add_action( 'wpcf7_editor_panels', 'cf7_activetrail_tag_page_panels' );


// Create the meta boxes (CF7 < 4.2)
function cf7_activetrail_addon_metaboxes11( $post ) {
	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'cf7_activetrail_addon_metaboxes', 'cf7_activetrail_addon_metaboxes_nonce' );
	wp_nonce_field( 'cf7_activetrail_addon_metaboxes11', 'cf7_activetrail_addon_metaboxes_nonce11' );
	wp_nonce_field( 'cf7_activetrail_addon_metaboxes22', 'cf7_activetrail_addon_metaboxes_nonce22' );
	wp_nonce_field( 'cf7_activetrail_addon_metaboxes33', 'cf7_activetrail_addon_metaboxes_nonce33' );
	wp_nonce_field( 'cf7_activetrail_addon_metaboxes44', 'cf7_activetrail_addon_metaboxes_nonce44' );
	wp_nonce_field( 'cf7_activetrail_addon_metaboxes55', 'cf7_activetrail_addon_metaboxes_nonce55' );
	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$activetrail_addon_tag_value = get_post_meta( $post->id(), '_cf7_activetrail_addon_tag_key', true );
	$activetrail_addon_tag_value11 = get_post_meta( $post->id(), '_cf7_activetrail_addon_tag_key11', true );
	$activetrail_addon_tag_value22 = get_post_meta( $post->id(), '_cf7_activetrail_addon_tag_key22', true );
	$activetrail_addon_tag_value33 = get_post_meta( $post->id(), '_cf7_activetrail_addon_tag_key33', true );
	$activetrail_addon_tag_value44 = get_post_meta( $post->id(), '_cf7_activetrail_addon_tag_key44', true );
	$activetrail_addon_tag_value55 = get_post_meta( $post->id(), '_cf7_activetrail_addon_tag_key55', true );
	
	//echo '<label for="cf7_activetrail_addon_tags"><strong>Contact Tags: </strong></label> ';
	//echo '<input type="text" placeholder="activetrail_tag_name" id="cf7_activetrail_addon_tags" name="cf7_activetrail_addon_tags" value="' . esc_attr( $activetrail_addon_tag_value ) . '" size="25" />';
	//echo '<p class="howto">Separate multiple tags with commas. These must already be defined in ActiveTrail.</p>';
}
// Create the panel inputs (CF7 >= 4.2)
function cf7_activetrail_addon_panel_meta( $post ) {
    wp_nonce_field( 'cf7_activetrail_addon_metaboxes', 'cf7_activetrail_addon_metaboxes_nonce' );
	 wp_nonce_field( 'cf7_activetrail_addon_metaboxes11', 'cf7_activetrail_addon_metaboxes_nonce11' );
	 wp_nonce_field( 'cf7_activetrail_addon_metaboxes22', 'cf7_activetrail_addon_metaboxes_nonce22' );
	  wp_nonce_field( 'cf7_activetrail_addon_metaboxes33', 'cf7_activetrail_addon_metaboxes_nonce33' );
 wp_nonce_field( 'cf7_activetrail_addon_metaboxes44', 'cf7_activetrail_addon_metaboxes_nonce44' );
 wp_nonce_field( 'cf7_activetrail_addon_metaboxes55', 'cf7_activetrail_addon_metaboxes_nonce55' );

 
    $activetrail_addon_tag_value = get_post_meta( $post->id(), '_cf7_activetrail_addon_tag_key', true );
	$activetrail_addon_tag_value11 = get_post_meta( $post->id(), '_cf7_activetrail_addon_tag_key11', true );
$activetrail_addon_tag_value22 = get_post_meta( $post->id(), '_cf7_activetrail_addon_tag_key22', true );
$activetrail_addon_tag_value33 = get_post_meta( $post->id(), '_cf7_activetrail_addon_tag_key33', true );
$activetrail_addon_tag_value44 = get_post_meta( $post->id(), '_cf7_activetrail_addon_tag_key44', true );
$activetrail_addon_tag_value55 = get_post_meta( $post->id(), '_cf7_activetrail_addon_tag_key55', true );

    // The meta box content
    echo '<h3>Contact form 7 properties</h3>
          <fieldset>
		  	<legend>Add your form id</legend>
			<input type="text" placeholder="for example: 431" id="cf7_activetrail_addon_tags11" name="cf7_activetrail_addon_tags11" value="' . esc_attr( $activetrail_addon_tag_value11 ) . '" size="25" />
            <legend>Add the email shortcode from the form.</legend>
            <input type="text" placeholder="for example: your-email" id="cf7_activetrail_addon_tags22" name="cf7_activetrail_addon_tags22" value="' . esc_attr( $activetrail_addon_tag_value22 ) . '" size="25" />
			 <legend>Add the approval checkbox.</legend>
            <input type="text" placeholder="for example: checkbox-902" id="cf7_activetrail_addon_tags33" name="cf7_activetrail_addon_tags33" value="' . esc_attr( $activetrail_addon_tag_value33 ) . '" size="25" />
			<hr/>
			<h3>ActiveTrail properties</h3>
			<legend>Add ActiveTrail login email</legend>
			<input type="text" placeholder="for example: your@login.com" id="cf7_activetrail_addon_tags44" name="cf7_activetrail_addon_tags44" value="' . esc_attr( $activetrail_addon_tag_value44 ) . '" size="25" />
			<legend>Add ActiveTrail API password</legend>
			<input type="password" placeholder="" id="cf7_activetrail_addon_tags55" name="cf7_activetrail_addon_tags55" value="' . esc_attr( $activetrail_addon_tag_value55 ) . '" size="25" />
			<legend>Add the Group ID you would like this email to be added to</legend>
			<input type="text" placeholder="for example: 33176" id="cf7_activetrail_addon_tags" name="cf7_activetrail_addon_tags" value="' . esc_attr( $activetrail_addon_tag_value ) . '" size="25" />
			' .

			
		'</fieldset>'; 
		
        // Register the fields i created in the DB

		
function cf7_registertodb() {

	
register_setting( 'cf7_activetrail_notice', 'formid' );
register_setting( 'cf7_activetrail_notice', 'emailshortcode' );
register_setting( 'cf7_activetrail_notice', 'approvalcheckbox' );
register_setting( 'cf7_activetrail_notice', 'activetraillogin' );
register_setting( 'cf7_activetrail_notice', 'apipassword' );
register_setting( 'cf7_activetrail_notice', 'zeevgroupid' );

}
add_action( 'admin_init', 'cf7_registertodb', 99 );


register_setting( 'cf7_activetrail_notice', 'formid' );
update_option( 'formid', $activetrail_addon_tag_value11 );

register_setting( 'cf7_activetrail_notice', 'emailshortcode' );
update_option( 'emailshortcode', $activetrail_addon_tag_value22 );
	
register_setting( 'cf7_activetrail_notice', 'approvalcheckbox' );
update_option( 'approvalcheckbox', $activetrail_addon_tag_value33 );

register_setting( 'cf7_activetrail_notice', 'activetraillogin' );
update_option( 'activetraillogin', $activetrail_addon_tag_value44 );

register_setting( 'cf7_activetrail_notice', 'apipassword' );
update_option( 'apipassword', $activetrail_addon_tag_value55 );

register_setting( 'cf7_activetrail_notice', 'zeevgroupid' );
update_option( 'zeevgroupid', $activetrail_addon_tag_value );


}

// Store ActiveTrail tag
function cf7_activetrail_addon_save_contact_form( $contact_form ) {
	$contact_form_id = $contact_form->id();
    if ( !isset( $_POST ) || empty( $_POST ) || !isset( $_POST['cf7_activetrail_addon_tags'] ) || !isset( $_POST['cf7_activetrail_addon_metaboxes_nonce'] ) ) {
        return;
    }
	if ( !isset( $_POST ) || empty( $_POST ) || !isset( $_POST['cf7_activetrail_addon_tags11'] ) || !isset( $_POST['cf7_activetrail_addon_metaboxes_nonce11'] ) ) {
        return;
    }
if ( !isset( $_POST ) || empty( $_POST ) || !isset( $_POST['cf7_activetrail_addon_tags22'] ) || !isset( $_POST['cf7_activetrail_addon_metaboxes_nonce22'] ) ) {
        return;
    }
	if ( !isset( $_POST ) || empty( $_POST ) || !isset( $_POST['cf7_activetrail_addon_tags33'] ) || !isset( $_POST['cf7_activetrail_addon_metaboxes_nonce33'] ) ) {
        return;
    }
	if ( !isset( $_POST ) || empty( $_POST ) || !isset( $_POST['cf7_activetrail_addon_tags44'] ) || !isset( $_POST['cf7_activetrail_addon_metaboxes_nonce44'] ) ) {
        return;
    }
	if ( !isset( $_POST ) || empty( $_POST ) || !isset( $_POST['cf7_activetrail_addon_tags55'] ) || !isset( $_POST['cf7_activetrail_addon_metaboxes_nonce55'] ) ) {
        return;
    }

	if ( isset( $_POST['cf7_activetrail_addon_tags'] ) ) {
        update_post_meta( $contact_form_id,
           '_cf7_activetrail_addon_tag_key',
            $_POST['cf7_activetrail_addon_tags']
        );
    }
	if ( isset( $_POST['cf7_activetrail_addon_tags11'] ) ) {
        update_post_meta( $contact_form_id,
           '_cf7_activetrail_addon_tag_key11',
            $_POST['cf7_activetrail_addon_tags11']
        );
    }
	if ( isset( $_POST['cf7_activetrail_addon_tags22'] ) ) {
        update_post_meta( $contact_form_id,
           '_cf7_activetrail_addon_tag_key22',
            $_POST['cf7_activetrail_addon_tags22']
        );
    }
	if ( isset( $_POST['cf7_activetrail_addon_tags33'] ) ) {
        update_post_meta( $contact_form_id,
           '_cf7_activetrail_addon_tag_key33',
            $_POST['cf7_activetrail_addon_tags33']
        );
    }
	if ( isset( $_POST['cf7_activetrail_addon_tags44'] ) ) {
        update_post_meta( $contact_form_id,
           '_cf7_activetrail_addon_tag_key44',
            $_POST['cf7_activetrail_addon_tags44']
        );
    }
	if ( isset( $_POST['cf7_activetrail_addon_tags55'] ) ) {
        update_post_meta( $contact_form_id,
           '_cf7_activetrail_addon_tag_key55',
            $_POST['cf7_activetrail_addon_tags55']
        );
    }
}
add_action( 'wpcf7_after_save', 'cf7_activetrail_addon_save_contact_form' );


/*** Active Trial Api ***/



	
add_action( 'wpcf7_before_send_mail', 'my_conversion' );
function my_conversion( $cf7 )
{
	$formid = get_option('formid');
$crmFormsId = array($formid);

$approvalcheckbox = get_option('approvalcheckbox');
	
	if(in_array($_POST['_wpcf7'], $crmFormsId) && !empty($_POST[$approvalcheckbox])){
	
	$name = trim($_POST['your-name']);
		
		
		$emailshortcode = get_option('emailshortcode');
		$email = trim($_POST[$emailshortcode]);
		
		list($fname, $lname) = explode(' ',$name,2);
		// Check that the class exists before trying to use it
if (!class_exists('Active_Trail')) {
		include 'ActiveTrail_Class.php';
}
		// Set Login Details		
		$activetraillogin = get_option('activetraillogin');
		$apipassword = get_option('apipassword');
		
		$activetrail_obj = new Active_Trail($activetraillogin, $apipassword, '');
		
		$zeevgroupid = get_option('zeevgroupid');
		$GroupID = $zeevgroupid;

		// --------- Add/Import emails to group (if emails exist on system, will add them to group, if not will create them as clients and add them)---------
		$emails_array = array($email);
		
		
		$webCustomer = new WebCustomer();
		$webCustomer -> Email = sanitize_email( $email );
		$webCustomer -> FirstName = sanitize_text_field ( $fname );
		$webCustomer -> LastName = sanitize_text_field ( $lname );
		$webCustomer -> Phone2 = sanitize_text_field ( $phone );
		
		
		$webCustomers = new webCustomers();
		$webCustomers -> WebCustomer[] = $webCustomer;
		
		$mailinglistName = new ListEmails($email);
		$import_response = $activetrail_obj->ImportCustomers($webCustomers,$GroupID,"1");

		//$import_response = $activetrail_obj->ImportCustomersEmail($GroupID, $emails_array);
	}
}
