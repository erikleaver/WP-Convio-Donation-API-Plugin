<?php

	class DonorDigital_ConvioDonation_Form{
	
		public $values = array();
		public $errors = array();
		public $settings = array();
		
		public $response = array();	// current transaction
		public $transactions = array();	// all previous successful transactions
		
		public $typage = false;
		
		public function __construct($id=false){
			$this->load_post($id);
			$this->load_current_state();
		}
	
		public function parse_message($message,$object){
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
			global $_SESSION;
			$this->transactions = $_SESSION['ddcd']['transactions'];
			$this->settings = array();
			$this->values = array();
			/* get config file */
			$config_file = $this->get_file_path('/forms/donation_form_config.php');
			
			if($config_file){
				include $config_file;
				if($formObj){
					if(is_array($formObj['values'])){
						$this->values = array_merge($this->values,$formObj['values']);				
					}
					if(is_array($formObj['settings'])){
						$this->settings = array_merge($this->settings,$formObj['settings']);	
					}
				}
			}
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
	
		public function load_post($post_id=false){
			$this->load_default();
			if(!$post_id){
				return false;
			}
			$post_obj = get_post_meta($post_id,'ddcd',true);
			if(is_array($post_obj)){
				if(is_array($post_obj['values'])){
					$this->values = array_merge($this->values,$post_obj['values']);
				}
				if(is_array($post_obj['response'])){
					$this->response = array_merge($this->response,$post_obj['response']);
				}
				if(is_array($post_obj['errors'])){
					$this->errors = array_merge($this->errors,$post_obj['errors']);
				}
				if(is_array($post_obj['settings'])){
					$this->settings = array_merge($this->settings,$post_obj['settings']);
				}
			}
			
			$this->settings['post_id'] = $post_id;
			return array(
				"values" => $this->values,
				"errors" => $this->errors,
				"settings" => $this->settings,
				);
		}
		
		public function load_current_state(){
			global $_SESSION;
			if(is_array($_SESSION['ddcd'])){
				$formObj = $_SESSION['ddcd'];
				if(is_array($formObj['values'])){
					$this->values = array_merge($this->values,$formObj['values']);
				}
				if(is_array($formObj['errors'])){
					$this->errors = array_merge($this->errors,$formObj['errors']);
				}
				if(is_array($formObj['response'])){
					$this->response = array_merge($this->response,$formObj['response']);
				}
			}
		}
		
		private function get_file_path($file,$check_plugin_dir=false){	// checks template for file, then plugin
			$template_dir = TEMPLATEPATH;
			$plugin_dir = WP_PLUGIN_DIR;
			if(!$check_plugin_dir){
				$plugin_dir = $plugin_dir.'/ddcd';
			}
			$path = $template_dir.$form_folder.$file;	// check template
			if(!file_exists($path)){
				$path = $plugin_dir.$file;	//	check plugin
				if(!file_exists($path)){
					return false;
				}
			}
			return $path;
		}
	
		public function get_form($form){
			$form_folder = '/forms/';
			$file = $this->get_file_path('/forms/'.$form.'.php');
			if(!$file){
				return "Error: ".$file." does not exist.  @ddcd";	// stop at error
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
			$form = $this->get_form('donation_form');
			return $form;
		}
		
		public function cache_session($save = true){
			global $_SESSION;
			$formObj = array(
				"errors" => $this->errors,
				"response" =>  $this->response,
				"values" => $this->values,
				);
			if($save){
				$_SESSION['ddcd'] = array_merge($_SESSION['ddcd'],$formObj);	
			}
			return $formObj;
		}
		
		public function save_transaction(){
			global $_SESSION;
			if(!is_array($this->transactions)){
				$this->transactions = array();
			}
			if($this->response['success']&&$this->response['raw']){
				$trans = array_merge($this->values,array(
					"amount" => $this->response['raw']['donation']['amount']['formatted'],
					"form" => $this->values['post_id'],
					"typage" => $this->settings['typage'],
					"card_number" => "",
					"card_cvv" => "",
					"card_exp_month" => "",
					"card_exp_year" => "",
					"raw" => $this->response['raw'],
					));
				array_push($this->transactions,$trans);
				$_SESSION['ddcd']['transactions'] = $this->transactions;
				
				do_action('ddcd_save_transaction',$trans);
				
				return true;
			}
			return false;
		}
		
		public function clean_settings($data,$set=false){
			$formObj = array(
				'include' => false,
				'preview' => false,
				'protected_thankyou_page' => false,
				'skip_convio' => false,
				);

			foreach($data as $key => $value){
				switch($key){
					case 'include':
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
			
			if($set){
				$this->settings = array_merge($this->settings,$formObj);
			}

			return $formObj;
		}
		
		public function is_protected(){
			if($this->settings['protected_thankyou_page']!=false){
				return true;
			}
			return false;
		}
		
		public function get_last_transaction(){
			$transactions = $this->transactions;
			while(is_array($transactions) && $transactions != null){
				$trans = array_pop($transactions);
				if($trans){
					return $trans;
				}
			}
			return false;
		}
	}

?>