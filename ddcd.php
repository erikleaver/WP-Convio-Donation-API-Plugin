<?php
/*
Plugin Name: DD Convio Donation API Plugin
Plugin URI: http://donordigital.com
Description: Makes Donating from WordPress possible
Version: 0.01
Author: Nick Reid
Author URI: http://nickreid.com
*/

session_start();

include 'ddcd_admin.php';
// admin hooks
add_action("admin_head","ddcd_admin_header");
add_action('admin_menu', 'ddcd_menu');
add_action('add_meta_boxes','ddcd_add_meta_boxes');
add_action('save_post','ddcd_save_postdata');

$ddcd = array();
// site hooks
add_filter( "the_posts", "ddcd_post_insert" );
add_filter("the_content","ddcd_write_form");
add_action('donate_process', 'ddic_donation_process');
//add_action('shutdown','ddcd_clear');

add_shortcode('ddcdonation','ddcd_draw_form');


function ddcd_add_dpage_format(){
	$styleUrl = WP_PLUGIN_URL . '/ddcd/assets/css/style.css';
	$styleFile = WP_PLUGIN_DIR . '/ddcd/assets/css/style.css';
	if ( file_exists($styleFile) ) {
		wp_register_style('ddcd_css', $styleUrl);
		wp_enqueue_style( 'ddcd_css');
	}
	$scriptUrl = WP_PLUGIN_URL.'/ddcd/assets/js/donation_page.js';
	$scriptFile = WP_PLUGIN_DIR.'/ddcd/assets/js/donation_page.js';
	if(file_exists($scriptFile)){
		   wp_register_script('donation_page_script',$scriptUrl);
		   wp_enqueue_script('donation_page_script');		
	}
	$scriptUrl = WP_PLUGIN_URL.'/ddcd/assets/js/jquery.validate.js';
	$scriptFile = WP_PLUGIN_DIR.'/ddcd/assets/js/jquery.validate.js';
	if(file_exists($scriptFile)){
		   wp_register_script('jquery_validate',$scriptUrl);
		   wp_enqueue_script('jquery_validate');		
	}
	$scriptUrl = WP_PLUGIN_URL.'/ddcd/assets/js/jquery.scrollTo.js';
	$scriptFile = WP_PLUGIN_DIR.'/ddcd/assets/js/jquery.scrollTo.js';
	if(file_exists($scriptFile)){
		   wp_register_script('jquery_scrollTo',$scriptUrl);
		   wp_enqueue_script('jquery_scrollTo');		
	}
}

function ddcd_get_view($page,$formObj){
	$myfile = WP_PLUGIN_DIR . '/ddcd/views/'.$page;
	if(file_exists($myfile)){
		ob_start();
		include $myfile;
		$page = ob_get_contents();
		ob_end_clean();
		return $page;
	}else{
		return "File does not exist @ ".$myfile;
	}
}

function ddcd_default_form(){
	$values = array(
		"months" => array("01","02","03","04","05","06","07","08","09","10","11","12"),
		"card_exp_month" => array("value" => "10"),
		"years" => ddcd_get_next_years(2010,10),
		"card_exp_year" => array("value" => "2010"),
		"countries" => array("United States", "Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia-Herzegovina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Cook Islands", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands", "Faroe Islands", "Fiji", "Finland", "Former Czechoslovakia", "Former USSR", "France", "French Guyana", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Great Britain", "Greece", "Greenland", "Grenada", "Guadeloupe (French)", "Guam (USA)", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and McDonald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Ivory Coast (Cote D'Ivoire)", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyz Republic (Kyrgyzstan)", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique (French)", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia", "Moldavia", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia (French)", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "North Korea", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn Island", "Poland", "Polynesia (French)", "Portugal", "Puerto Rico", "Qatar", "Reunion (French)", "Romania", "Russian Federation", "Rwanda", "Saint Helena", "Saint Kitts & Nevis Anguilla", "Saint Lucia", "Saint Pierre and Miquelon", "Saint Tome (Sao Tome) and Principe", "Saint Vincent & Grenadines", "Samoa", "San Marino", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "S. Georgia & S. Sandwich Isls.", "Sierra Leone", "Singapore", "Slovak Republic", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syria", "Tadjikistan", "Taiwan", "Tanzania", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "Uruguay", "USA Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (USA)", "Wallis and Futuna Islands", "West Bank - Gaza", "Western Sahara", "Yemen", "Zaire", "Zambia", "Zimbabwe"),
		"country" => array("value" => "United States"),
		"states" => array("", "AK", "AL", "AR", "AZ", "CA", "CO", "CT", "DC", "DE", "FL", "GA", "HI", "IA", "ID", "IL", "IN", "KS", "KY", "LA", "MA", "MD", "ME", "MI", "MN", "MO", "MS", "MT", "NC", "ND", "NE", "NH", "NJ", "NM", "NV", "NY", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VA", "VT", "WA", "WI", "WV", "WY", "AS", "FM", "GU", "MH", "MP", "PR", "PW", "VI", "AA", "AE", "AP", "AB", "BC", "MB", "NB", "NL", "NS", "NT", "NU", "ON", "PE", "QC", "SK", "YT", "None"),
		"state" => array("value" => ""),
		"titles" => array("","Dr.","Father","Miss.","Misses","Mr.","Mr. and Mrs.","Mrs.","Ms.","Rev.","Sr.","Msgr.","Most Rev.","Brother","Rev. Mr.","Dr. and Mrs.","Dr. and Mr.","Friends","Deacon"),
		"title" => array("value" => ""),
		);
	$defaultForm = get_option('ddcd_defaults');
	if(!is_array($defaultForm)){
		$defaultForm = array();
	}
	return array_merge($defaultForm,$values);
}

function ddcd_get_obj($post_id){
	$defaults = ddcd_default_form();
	$postObj = get_post_meta($post_id,'ddcd',true);
	if(is_array($postObj)){
		$formObj = array_merge($defaults,$postObj);
	}else{
		$formObj = $postObj;
	}
	$formObj['post_id'] = $post_id;
	return $formObj;
}

function ddcd_get_next_years($start,$until){
	$years = array();
	for($i=0;$i<$until;$i++){
		$years[]=$start+$i;
	}
	return $years;
}

function ddcd_post_insert($posts){
	global $ddcd,$_SESSION;
	$ddcd['session'] = $_SESSION['ddcd']['donationForm'];
	$ddcd['trans'] = $_SESSION['ddcd']['transObj'];
	$_SESSION['ddcd']['donationForm'] = array(); // empty it out so sensitive data is erased
	foreach($posts as $post){
		if(is_array($ddcd['trans'])&&(get_permalink($post->ID)==$ddcd['trans']['page'])){	// check if df session obj // check if typage
			$ddcd['typage'] = true;
		}
		if((is_single()||is_page())&&ddcd_post_has_form($post->ID)){
//			if($_SERVER['https']){
				$formObj = ddcd_make_formObj($post->ID);
				$ddcd['form']=ddcd_draw_form($formObj);				
//			}
		}
	}
	return $posts;
}

function ddcd_write_form($content){
	global $ddcd;
	if($ddcd['form']){
		if($ddcd['formObj']['position']=='bottom'){
			$content .= $ddcd['form'];	
		}	
	}
	if($ddcd['typage']){
		$content = ddcd_parse_typage($content);
	}
	return $content;
}

function ddcd_make_formObj($post_id){
	global $ddcd;
	$donationObj = get_post_meta($post_id,'ddcd',true);
	if(is_array($donationObj)){
		$formObj = array_merge(ddcd_default_form(),$donationObj);		
	}else{
		$formObj = ddcd_default_form();
	}
	if(is_array($ddcd['session'])){
		$formObj = array_merge($formObj,$ddcd['session']);	
	}
	$formObj['post_id'] = $post_id;
	$ddcd['formObj'] = $formObj;
	return $formObj;
}

function ddcd_draw_gift_string($formObj=false,$miniform=false){
	global $ddcd;
	if(!$formObj){
		$formObj = $ddcd['formObj'];
	}
	if(is_array($miniform)){
		$formObj['miniform'] = true;
		if($miniform['page']){
			$formObj['page'] = $miniform['page'];			
		}
		if($miniform['bttn_copy']){
			$formObj['bttn_copy'] = $miniform['bttn_copy'];	
		}
	}
	ddcd_add_dpage_format(); // make sure scripts are on page
	return ddcd_get_view('gift_string.php',$formObj);
}

function ddcd_draw_form($formObj){
	global $ddcd,$_GET;
	ddcd_add_dpage_format();
//	if($_GET['convio']=="true"){
		return ddcd_get_view('donation_form_convio.php',$formObj);
//	}
//	return ddcd_get_view('donation_form.php',$formObj);
}

function ddcd_parse_typage($content){
	global $ddcd;
	
	$search = array("[[first_name]]","[[amount]]");
	$replace = $_SESSION['ddcd']['transObj'];
	return str_replace($search,$replace,$content);
		
}

function ddcd_post_has_form($post_id){
	$ddcdObj = get_post_meta($post_id,'ddcd',true);
	if($ddcdObj['include']){
		return true;
	}
	return false;
}

function ddcd_clear(){
	
}

?>