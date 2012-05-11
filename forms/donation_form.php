<?
if(!function_exists("ddcd_output_options")){
	function ddcd_output_options($options,$selected){
		foreach($options['values'] as $key => $option){	
			if(isset($options['names'][$key])){	$name = $options['names'][$key]; }else{ $name = $option; }?>
			<option value="<?=$option;?>" <?if($selected==$option){?>selected="selected"<?}?>><?=$name?></option>
	<?	}
	}
}
?>

<form id="ddcd" class="donation" action="<?bloginfo('wpurl');?>/wp-content/plugins/ddcd/ddcd_donation_process.php" method="POST">

	<!-- PREVIEW SWITCH -->
	<?	if($this->settings['preview']||$_GET['df_preview']=="true"){	?>
	<input id="df_preview" name="df_preview" type="hidden" value="true" />
	<?	}	?>
	<input type="hidden" name="post_id" value="<?=$this->settings['post_id'];?>">
	<input id="form_id" name="form_id" type="hidden" value="<?=$this->settings['form_id'];?>" />
	<?if($this->errors['message']!=""){?>
		<div id="DonationFormError" class="error"><?=$this->errors['message']?></div>
	<?}?>
	
	<? do_action('ddcd_form_draw',$this->settings['post_id']);	?>
	
	<?=$this->get_form('gift_string');?>
	<fieldset class="personal">
		<legend>Personal Information</legend>
		<div class="formRow">
			<label for="ddcd_title">Title</label>
			<select id="ddcd_title" class="" name="billing.name.title">
				<? ddcd_output_options($this->settings['titles'],$this->values['billing.name.title']);	?>
			</select>
			<label for="ddcd_title" class="error <?if($this->errors['title']==""){?>hidden<?}?>">Title is required.</label>
		</div>
		<div class="formRow">
			<label for="ddcd_first_name">First Name <span class="req">required</span></label>
			<input id="ddcd_first_name" class="required" name="billing.name.first" type="text" value="<?=$this->values['billing.name.first'];?>" />
			<label for="ddcd_first_name" class="error <?if($this->errors['billing.name.first']==""){?>hidden<?}?>">First Name is required.</label>
		</div>
		<div class="formRow">
			<label for="ddcd_last_name">Last Name <span class="req">required</span></label>
			<input id="ddcd_last_name" class="required" name="billing.name.last" type="text" value="<?=$this->values['billing.name.last'];?>" />
			<label for="ddcd_last_name" class="error <?if($this->errors['billing.name.last']==""){?>hidden<?}?>">Last Name is required.</label>
		</div>
		<div class="formRow">
			<label for="ddcd_email">Email Address <span class="req">required</span></label>
			<input id="ddcd_email" class="required email" name="donor.email" type="text" value="<?=$this->values['donor.email'];?>" />
			<label for="ddcd_email" class="error <?if($this->errors['donor.email']==""){?>hidden<?}?>">A valid email address is required.</label>
		</div>
		<div class="formRow checkbox">
			<input type="hidden" name="donor.email_opt_in" value="false" />
			<input id="ddcd_email_opt_in" type="checkbox" class="checkbox" name="donor.email_opt_in" value="true" <? if($this->values['donor.email_opt_in']){?>checked="checked"<?}?> />
			<label for="ddcd_email_opt_in">Please send me email related to this organization.</label>
		</div>
	</fieldset>
	<fieldset class="address">
		<legend>Location Information</legend>
		<div class="formRow">
			<label for="ddcd_street1">Address Line 1 <span class="req">required</span></label>
			<input id="ddcd_street1" class="required" name="billing.address.street1" type="text" value="<?=$this->values['billing.address.street1'];?>" />
			<label for="ddcd_street1" class="error <?if($formObj['billing.address.street1']==""){?>hidden<?}?>">A mailing address is required.</label>
		</div>
		<div class="formRow">
			<label for="ddcd_street2">Address Line 2</label>
			<input id="ddcd_street2" name="billing.address.street2" type="text" value="<?=$this->values['billing.address.street2'];?>" />
		</div>
		<div class="formRow">
			<label for="ddcd_city">City <span class="req">required</span></label>
			<input id="ddcd_city" class="required" name="billing.address.city" type="text" value="<?=$this->values['billing.address.city'];?>" />
			<label for="ddcd_city" class="error <?if($this->errors['billing.address.city']==""){?>hidden<?}?>">City is required.</label>
		</div>
		<div class="formRow">
			<label for="ddcd_state">State/Province <span class="req">required</span></label>
			<select id="ddcd_state" class="required" name="billing.address.state" type="select">
				<?	ddcd_output_options($this->settings['states'],$this->values['billing.address.state']);	?>
			</select>
			<div for="ddcd_state" class="error <?if($this->errors['billing.address.state']==""){?>hidden<?}?>">State is required.</div>
		</div>
		<div class="formRow">
			<label for="ddcd_zip">Zip Code<span class="req">required</span></label>
			<input id="ddcd_zip" class="required" name="billing.address.zip" type="text" value="<?=$this->values['billing.address.zip'];?>" />
			<label class="error <?if($this->errors['billing.address.zip']==""){?>hidden<?}?>">A valid zip code is required.</label>
		</div>
		<div class="formRow">
			<label for="ddcd_country">
				Country <span class="req">required</span>
			</label>
			<select id="ddcd_country" class="required" name="billing.address.country" type="select">
				<?	ddcd_output_options($this->settings['countries'],$this->values['billing.address.country']);	?>
			</select>
			<label for="ddcd_country" class="error <?if($this->errors['billing.address.country']==""){?>hidden<?}?>">Country is required.</label>
		</div>
	</fieldset>
	<fieldset class="billing">
		<legend>Billing Information</legend>
		<div class="formRow card_type">
			<label for="ddcd_card_type">Credit Card Type</label>
			<select id="ddcd_card_type" name="card_type" type="select-one" value="1000" size="1">
				<option value="1000" selected="selected">Visa</option>
				<option value="1006">Discover</option>
				<option value="1004">American Express</option>
				<option value="1002">MasterCard</option>
			</select>
		</div>
		<div class="formRow">
			<label for="ddcd_card_number">Credit Card Number <span class="req">required</span></label>
			<input id="ddcd_card_number" class="required" name="card_number" type="text" value="<?=$this->values['card_number'];?>" />
			<label for="ddcd_card_number" class="error <?if($this->errors['card_number']==""){?>hidden<?}?>">A valid credit card number is required.</label>
		</div>
		<div class="formRow cvv">
			<label for="ddcd_card_cvv">CVV Number<span class="req">required</span> <a class="cvv helplink" href="#cvvLink">What is this?</a></label>
			<input id="ddcd_card_cvv" class="required" name="card_cvv" type="text" value="<?=$this->values['card_cvv'];?>" />
		<?	if($this->errors['card_cvv']==""){	?>
			<label for="ddcd_card_cvv" class="error"><?=$this->errors['card_cvv'];?></label>
		<?	}	?>
		</div>
		<div class="formRow date">
			<label>Expiration Date <span class="req">required</span></label>
			<label class="hidden" for="ddcd_card_exp_month">Expiration Month</label>
			<select id="ddcd_card_exp_month" class="required" name="card_exp_month" type="select-one">
				<?	ddcd_output_options($this->settings['months'],$this->values['card_exp_month']);	?>
			</select>
			<label class="hidden" for="ddcd_card_exp_year">Expiration Year</label>
			<select id="ddcd_card_exp_year" class="required" name="card_exp_year" type="select">
				<?	ddcd_output_options($this->settings['years'],$this->values['card_exp_year']);	?>
			</select>
			<label for="ddcd_card_exp_month" class="error" style="display: none;">A valid expiration date is required.</label>
		</div>
	</fieldset>
	<fieldset class="controls">
		<input id="donate_bttn" type="submit" class="bttn submit" value="Donate Now" />
	</fieldset>
	<br style="clear:both;">
</form>