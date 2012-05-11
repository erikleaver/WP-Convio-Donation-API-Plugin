<?

function ddcd_admin_header() {
//	include 'admin/header.php';
}

function ddcd_menu() {
//	add_menu_page('Convio Donation API', 'Convio Donation API', 8, 'dd-conivo-donation', 'ddcd_setup');
	add_submenu_page('options-general.php','Convio Donation API','Convio Donation API',8,'dd-convio-donation','ddcd_setup');
}

function ddcd_setup(){
	//must check that the user has the required capability 
    if(!current_user_can('manage_options')){
		wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // Read in existing option value from database
    $formObj = get_option('ddcd_defaults');
	if(isset($_POST['ddcd'])){
		$formObj = ddcd_admin_filter_settings($_POST['ddcd']);
		update_option('ddcd_defaults',$formObj);
	}

	include 'views/settings_page.php';
}

function ddcd_add_meta_boxes(){
	add_meta_box('ddcd_form_post','Donation Form','ddcd_post_form','post','normal','high');
	do_meta_boxes('ddcd_post_form','normal',null);
	
	add_meta_box('ddcd_form_post','Donation Form','ddcd_post_form','page','normal','high');
	do_meta_boxes('ddcd_post_form','normal',null);
}

function ddcd_post_form($post){
	$formObj = get_post_meta($post->ID,'ddcd',true);
	$defaultObj = get_option('ddcd_defaults');
	if(is_array($formObj) && is_array($defaultObj)){
		$formObj = array_merge($defaultObj,$formObj);
	}
	include 'views/metabox.php';
}

function ddcd_admin_filter_settings($data){
	// changes post data into safe form object
	$formObj = array(
		'include' => false,
		'ajax' => false,
		);
	
	foreach($data as $key => $value){
		switch($key){
			case 'include':
			case 'ajax':
			case 'preview':
				$formObj[$key] = true;
				break;
			case 'giving_level':
				$levels = array();
				foreach($data[$key] as $level){
					if(($level['id']!="")||($level['amount']!="")){
						$levels[] = array(
							"id" => $level['id'],
							"amount" => $level['amount'],
							"handle" => $level['handle'],
							"other_amount" => $level['other_amount'],
						);
					}
				}
				if(sizeof($levels)>0){
					$formObj[$key] = $levels;	
				}
				break;
			default:
				if($value!=""){
					$formObj[$key] = $value;	
				}
				break;
		}
	}
	
	return $formObj;
}

function ddcd_save_postdata($post_id){
	if(isset($_POST['ddcd'])){
		$formObj = ddcd_admin_filter_settings($_POST['ddcd']);
		update_post_meta($post_id,'ddcd',$formObj);
	}
}

?>