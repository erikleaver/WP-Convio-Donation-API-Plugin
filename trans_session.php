<?
session_start();
if (!function_exists('add_action')){
    require_once("../../../wp-config.php");
}
/* need some sort of hand shake */

$ddcd = false;
if($_GET['post_id']){
	$ddcd = new DonorDigital_ConvioDonation_Form($_GET['post_id']);
}

if($ddcd){
	// get any data available
	$ddcd->response = array(
		"success" => true,
		"raw" => array(),
		"post_id" => $_GET['post_id'],
	);
	if(isset($_GET['ect'])){	// this is not carrying over
		$ddcd->response['ecard_template'] = $_GET['ect'];		
	}

	if($ddcd->settings['typage']){
		$page = get_permalink($ddcd->settings['typage']);
		$ddcd->response['page'] = $page;
	}
	// log transaction
	$ddcd->save_transaction();

	header('location: '.get_permalink($ddcd->settings['typage']));
	exit;
}

header("location: ".get_bloginfo('url'));
exit;
?>