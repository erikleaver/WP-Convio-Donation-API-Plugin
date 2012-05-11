<?php

	class DonorDigital_ConvioDonation_Form{
	
		public $values = array();
		public $errors = array();
		public $settings = array();
		public $transactions = array();
	
		public function __construct($id){
			$this->load_post($id);
		}
	
		private function parse_message($message,$object){
			$search = array();
			$keys = array_keys($object);
			foreach($keys as $key){
				$search[] = '[['.$key.']]';
			}		
			return str_replace($search,$object,$message);
		}
	
		private function make_numbers($start,$until){
			$numbers = array();
			for($i=0;$i<$until;$i++){
				$numbers[]=$start+$i;
			}
			return $numbers;
		}
	
		public function load_default(){
			$this->settings = array(
				"months" => array(
					"values" => array("01","02","03","04","05","06","07","08","09","10","11","12"),
					"names" => array("January","February","March","April","May","June","July","August","September","October","November","December"),
					),
				"years" => $this->make_numbers(date("Y"),10),
				"countries" => array(
					"values" => array("United States", "Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia-Herzegovina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Cook Islands", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands", "Faroe Islands", "Fiji", "Finland", "Former Czechoslovakia", "Former USSR", "France", "French Guyana", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Great Britain", "Greece", "Greenland", "Grenada", "Guadeloupe (French)", "Guam (USA)", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and McDonald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Ivory Coast (Cote D'Ivoire)", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyz Republic (Kyrgyzstan)", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique (French)", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia", "Moldavia", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia (French)", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "North Korea", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn Island", "Poland", "Polynesia (French)", "Portugal", "Puerto Rico", "Qatar", "Reunion (French)", "Romania", "Russian Federation", "Rwanda", "Saint Helena", "Saint Kitts & Nevis Anguilla", "Saint Lucia", "Saint Pierre and Miquelon", "Saint Tome (Sao Tome) and Principe", "Saint Vincent & Grenadines", "Samoa", "San Marino", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "S. Georgia & S. Sandwich Isls.", "Sierra Leone", "Singapore", "Slovak Republic", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syria", "Tadjikistan", "Taiwan", "Tanzania", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "Uruguay", "USA Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (USA)", "Wallis and Futuna Islands", "West Bank - Gaza", "Western Sahara", "Yemen", "Zaire", "Zambia", "Zimbabwe"),
					),
				"states" => array(
					"values" => array("", "AK", "AL", "AR", "AZ", "CA", "CO", "CT", "DC", "DE", "FL", "GA", "HI", "IA", "ID", "IL", "IN", "KS", "KY", "LA", "MA", "MD", "ME", "MI", "MN", "MO", "MS", "MT", "NC", "ND", "NE", "NH", "NJ", "NM", "NV", "NY", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VA", "VT", "WA", "WI", "WV", "WY", "AS", "FM", "GU", "MH", "MP", "PR", "PW", "VI", "AA", "AE", "AP", "AB", "BC", "MB", "NB", "NL", "NS", "NT", "NU", "ON", "PE", "QC", "SK", "YT", "None"),
					),
				"titles" => array(
					"values" => array("","Dr.","Father","Miss.","Misses","Mr.","Mr. and Mrs.","Mrs.","Ms.","Rev.","Sr.","Msgr.","Most Rev.","Brother","Rev. Mr.","Dr. and Mrs.","Dr. and Mr.","Friends","Deacon"),
					),
				);
			$this->values = array(
				"month" => date("m"),
				"year" => date("Y"),
				"country" => "United States",
				"state" => "",
				"titles" => "",
				);
			$defaults = get_option('ddcd_defaults');
			if(is_array($defaults['values'])){
				$this->values = array_merge($this->values,$defaults['values']);				
			}
			if(is_array($defaults['settings'])){
				$this->settings = array_merge($this->settings,$defaults['settings']);	
			}
			
			return array(
				"values" => $this->values,
				"errors" => $this->errors,
				"settings" => $this->settings,
				);
		}
	
		public function load_post($post_id){
			$this->load_default();
			$post_obj = get_post_meta($post_id,'ddcd',true);
			if(is_array($post_obj)){
				if(is_array($post_obj['values'])){
					$this->values = array_merge($this->values,$post_obj['values']);
				}
				if(is_array($post_obj['errors'])){
					$this->errors = array_merge($this->errors,$post_obj['errors']);
				}
				if(is_array($post_obj['settings'])){
					$this->settings = array_merge($this->settings,$post_obj['settings']);
				}
			}
			$this->transactions = wp_cache_get('transactions','ddcd');
			$this->settings['post_id'] = $post_id;
			return array(
				"values" => $this->values,
				"errors" => $this->errors,
				"settings" => $this->settings,
				);
		}
	
		private function get_form($form){
			$form_folder = '/forms/';
			$template_dir = TEMPLATEPATH;
			$plugin_dir = WP_PLUGIN_DIR . '/ddcd';
			$file = $template_dir.$form_folder.$form.'.php';	// check template
			if(!file_exists($file)){
				$file = $plugin_dir.$form_folder.$form.'.php';	//	check plugin
				if(!file_exists($file)){
					return "Error: ".$file." does not exist.  @ddcd";	// stop at error
				}
			}
			ob_start();
			include $file;
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
	
		private function add_page_format(){
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
	
		public function draw(){
			$this->add_page_format();
			return $this->get_form('donation_form');
		}
	}

?>