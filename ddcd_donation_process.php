<?
session_start();
if (!function_exists('add_action')){
    require_once("../../../wp-config.php");
}

$page = $_SERVER['HTTP_REFERER'];

$ddcd_defaults = get_option('ddcd_defaults');
if(isset($_POST['post_id'])){
	$postObj = get_post_meta($_POST['post_id'],'ddcd',true);
	if(is_array($postObj) && is_array($ddcd_defaults)){
		$ddcd_defaults = array_merge($ddcd_defaults,$postObj);
	}
}


$url = $ddcd_defaults['api_location'];
$api_key = $ddcd_defaults['api_key'];

$ajax = False;
if($_POST['ajax']){
	$ajax=true;
	unset($_POST['ajax']);
}


if($_POST['df_preview']){
	$url .= "?df_preview=true";
	unset($_POST['df_preview']);
}

$complete = True;
$formObj = array();
if($_POST){
	// Check Level Ids
	$level_amount_field="";
	$other_amount_value="";
	if($_POST['level_id']!=""){
		$formObj['level_id']['value']=$_POST['level_id'];
		$level_amount_field = "level_".$_POST['level_id']."_amount";
		if(isset($_POST[$level_amount_field])){
			$other_amount_value = $_POST[$level_amount_field];
			if($other_amount_value==""){
				$complete = false;
				$formObj[$level_amount_field]['error'] = "Please specify amount";
			}
		}
	}else{
		$formObj['level_id']['error'] = "Please choose a gift amount";
	}
	// Check other fields
	$fields = array_keys($_POST);
	// unset any level_xxx fields
	foreach($fields as $field){
		if(preg_match('/level_/',$field)>0){
			unset($_POST[$field]);
		}
	}
	$fields = array_keys($_POST);
	for($i=0;$i<sizeof($fields);$i++){
		$key = $fields[$i];
		$line = Array();
		$line['value'] = $_POST[$key];
		switch($key){
			case 'street2':
				break;
			default:
				if($_POST[$key]==""){
					$complete = False;
					$line['error'] = 'Required field';
				}
		}
		$formObj[$key] = $line;
		
	}
}

if($complete){
	
	$vars = Array();
	array_push($vars,"api_key=".$api_key);
	array_push($vars,"v=1.0");
	array_push($vars,"method=donate");
	array_push($vars,"response_format=json");
	array_push($vars,"send_recipt=true");
	array_push($vars,"send_registration_email=false");
	
	array_push($vars,"form_id=".$formObj['form_id']['value']);
	array_push($vars,"level_id=".$formObj['level_id']['value']);
	array_push($vars,"other_amount=".urlencode($other_amount_value));
	
	array_push($vars,"card_number=".urlencode($formObj['card_number']['value']));
	array_push($vars,"card_cvv=".urlencode($formObj['card_cvv']['value']));
	array_push($vars,"card_exp_date_month=".urlencode($formObj['card_exp_month']['value']));
	array_push($vars,"card_exp_date_year=".urlencode($formObj['card_exp_year']['value']));
	
	array_push($vars,"billing.address.street1=".urlencode($formObj['street1']['value']));
	array_push($vars,"billing.address.street2=".urlencode($formObj['street2']['value']));
	array_push($vars,"billing.address.city=".urlencode($formObj['city']['value']));
	array_push($vars,"billing.address.state=".urlencode($formObj['state']['value']));
	array_push($vars,"billing.address.zip=".urlencode($formObj['zip']['value']));
	array_push($vars,"billing.address.country=".urlencode($formObj['country']['value']));
	
	array_push($vars,"billing.name.first=".urlencode($formObj['first_name']['value']));
	array_push($vars,"billing.name.last=".urlencode($formObj['last_name']['value']));
	array_push($vars,"billing.name.title=".urlencode($formObj['title']['value']));
	
	array_push($vars,"donor.email=".urlencode($formObj['email']['value']));
	if($_POST['optin']){
		array_push($vars,"donor.email_opt_in=true");
	}else{
		array_push($vars,"donor.email_opt_in=false");
	}
	
	
	$vars = implode('&',$vars);
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt( $ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$res = curl_exec($ch);

	$result = json_decode($res,true);
		
	if($result['donationResponse']['errors']){
		$formObj['success'] = false;
		$formObj['error'] = $result['donationResponse']['errors']['message'];
		$formObj['response'] = $result['donationResponse'];
		
		// do more processing to find actual cause (cc decline ect)
		
	}else if($result['donationResponse']['donation']){
		$formObj['success'] = true;
		$formObj['response'] = $result['donationResponse'];
		$typage = $ddcd_defaults['typage'];
		$formObj['ty'] = $typage;
		$personObj = array(
			"first_name" => $formObj['first_name']['value'],
			"email" => $formObj['email']['value'],
			);
		$_SESSION['ddcd']['personObj'] = $personObj;
		if($typage){
			$page = get_permalink($typage);
			$formObj['page'] = $page;
			$transObj = array(
				"first_name" => $formObj['first_name']['value'],
				"amount" => $formObj['response']['donation']['amount']['formatted'],
				"page" => $page,
				);
			$_SESSION['ddcd']['transObj'] = $transObj;
		}
	}else{
		echo $res;
	}
}else{
	if(!$formObj['error']){
		$formObj['error'] = 'There are errors in the form below';	
	}
}

$_SESSION['ddcd']['donationForm'] = $formObj;

if($ajax){
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	echo json_encode($formObj);
	exit;
}
header('location: '.$page);
?>