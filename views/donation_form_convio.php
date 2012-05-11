<form id="ddcd" class="donation" action="https://secure.crs.org/site/CRDonationAPI" method="POST">
	<input type="hidden" name="post_id" value="<?=$formObj['post_id'];?>" />
	
	<input type="hidden" name="success_redirect" value="<?=get_permalink($formObj['typage']);?>" />
	<?
	$error_string = "";
	
	$error_string .= "&fid=".$formObj['form_id'];
	$error_string .= "&SP=".urlencode(get_permalink($formObj['typage']));
	$error_string .= "&T=".str_replace("+","%20",urlencode(get_the_title($formObj['post_id'])));
	
	$num = 1;
	foreach($formObj['giving_level'] as $level){
		if($level['other_amount']){
			$qnum = $num;
			$num = "O";
		}
		$error_string .= "&L".$num."=".urlencode($level['id']);
		if($level['amount']&&$level['amount']!=""){
			$error_string .= "&L".$num."A=".urlencode($level['amount']);			
		}
		if($level['handle']&&$level['handle']!=""){
			$error_string .= "&L".$num."H=".urlencode($level['handle']);			
		}
		if($level['other_amount']){
			$num = $qnum;
		}else{
			$num++;	
		}
	}
	?>
	<input type="hidden" name="error_redirect" value="<?=$formObj['error_page'];?>" />
	
	<!-- Hidden inputs - API common fields -->
	<input type="hidden" name="api_key" value="<?=$formObj['api_key'];?>" />

	<input id="v" name="v" type="hidden" value="1.0" />
	<input id="method" name="method" type="hidden" value="donate" />
	<input id="response_format" name="response_format" type="hidden" value="json" />

	<!-- PREVIEW SWITCH -->
	<?	if($formObj['preview']||($_GET['df_preview']=='true')){	?>
	<input id="df_preview" name="df_preview" type="hidden" value="true" />
	<?	}	?>

	<!-- Hidden inputs - processing options -->
	<input id="validate" name="validate" type="hidden" value="true" />
	<input type="hidden" name="send_reciept" value="true" />
	<input type="hidden" name="send_registration_email" value="false" />
	
	<!-- Hidden inputs - form_id and redirect URLs-->
	<input id="form_id" name="form_id" type="hidden" value="<?=$formObj['form_id'];?>" />
	<div id="DonationFormError" class="error <?if($formObj['error']==""){?>hidden<?}?>"><?=$formObj['error']?></div>
	<?	if($formObj['action_copy']&&($formObj['action_copy']!="")){	?>
	<div class="action_copy">
		
	</div>
	<?	}	?>
	<?=ddcd_draw_gift_string();?>
	<fieldset class="personal">
		<legend>Personal Information</legend>
		<div class="formRow">
			<label for="ddcd_title">Title <span class="req">required</span></label>
			<select id="ddcd_title" class="required" name="billing.name.title">
				<?	foreach($formObj['titles'] as $title){	?>
				<option value="<?=$title;?>" <?if($formObj['title']['value']==$title){ echo 'selected="selected"';}?> ><?=$title;?></option>
				<?	}	?>
			</select>
			<label for="ddcd_title" class="error <?if($formObj['title']['error']==""){?>hidden<?}?>">Title is required.</label>
		</div>
		<div class="formRow">
			<label for="ddcd_first_name">First Name <span class="req">required</span></label>
			<input id="ddcd_first_name" class="required" name="billing.name.first" type="text" value="<?=$formObj['first_name']['value'];?>" />
			<label for="ddcd_first_name" class="error <?if($formObj['first_name']['error']==""){?>hidden<?}?>">First Name is required.</label>
		</div>
		<div class="formRow">
			<label for="ddcd_last_name">Last Name <span class="req">required</span></label>
			<input id="ddcd_last_name" class="required" name="billing.name.last" type="text" value="<?=$formObj['last_name']['value'];?>" />
			<label for="ddcd_last_name" class="error <?if($formObj['last_name']['error']==""){?>hidden<?}?>">Last Name is required.</label>
		</div>
		<div class="formRow">
			<label for="ddcd_email">Email Address <span class="req">required</span></label>
			<input id="ddcd_email" class="required email" name="donor.email" type="text" value="<?=$formObj['email']['value'];?>" />
			<label for="ddcd_email" class="error <?if($formObj['email']['error']==""){?>hidden<?}?>">A valid email address is required.</label>
		</div>
		<input type="hidden" name="donor.email_opt_in" value="true" />
	</fieldset>
	<fieldset class="address">
		<legend>Location Information</legend>
		<div class="formRow">
			<label for="ddcd_street1">Address Line 1 <span class="req">required</span></label>
			<input id="ddcd_street1" class="required" name="billing.address.street1" type="text" value="<?=$formObj['street1']['value'];?>" />
			<label for="ddcd_street1" class="error <?if($formObj['street1']['error']==""){?>hidden<?}?>">A mailing address is required.</label>
		</div>
		<div class="formRow">
			<label for="ddcd_street2">Address Line 2</label>
			<input id="ddcd_street2" name="billing.address.street2" type="text" value="<?=$formObj['street2']['value'];?>" />
		</div>
		<div class="formRow">
			<label for="ddcd_city">City <span class="req">required</span></label>
			<input id="ddcd_city" class="required" name="billing.address.city" type="text" value="<?=$formObj['city']['value'];?>" />
			<label for="ddcd_city" class="error <?if($formObj['city']['error']==""){?>hidden<?}?>">City is required.</label>
		</div>
		<div class="formRow">
			<label for="ddcd_state">State/Province <span class="req">required</span></label>
			<select id="ddcd_state" class="required" name="billing.address.state" type="select-one">
				<?	foreach($formObj['states'] as $state){	?>
				<option value="<?=$state?>" <?if($state==$formObj['state']['value']){?>selected="selected"<?}?>><?=$state;?></option>
				<?	}	?>
			</select>
			<div for="ddcd_state" class="error <?if($formObj['state']['error']==""){?>hidden<?}?>">State is required.</div>
		</div>
		<div class="formRow">
			<label for="ddcd_zip">Zip Code<span class="req">required</span></label>
			<input id="ddcd_zip" class="required" name="billing.address.zip" maxlength="11" type="text" value="<?=$formObj['zip']['value'];?>" />
			<label class="error <?if($formObj['zip']['error']==""){?>hidden<?}?>">A valid zip code is required.</label>
		</div>
		<div class="formRow">
			<label for="ddcd_country">
				Country <span class="req">required</span>
			</label>
			<select id="ddcd_country" class="required" name="billing.address.country" type="select-one">
				<?	foreach($formObj['countries'] as $country){	?>
				<option value="<?=$country?>" <?if($country==$formObj['country']['value']){?>selected="selected"<?}?>><?=$country;?></option>
				<?	}	?>
			</select>
			<label for="ddcd_country" class="error <?if($formObj['country']['error']==""){?>hidden<?}?>">Country is required.</label>
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
			<input id="ddcd_card_number" class="required" name="card_number" type="text" value="<?=$formObj['card_number']['value'];?>" />
			<label for="ddcd_card_number" class="error <?if($formObj['card_number']['error']==""){?>hidden<?}?>">A valid credit card number is required.</label>
		</div>
		<div class="formRow cvv">
			<label for="ddcd_card_cvv"> CVV Number <span class="req">required</span> <a id="cvvLink" class="helplink" title="What is this? Opens new window." target="_blank" href="http://help.convio.net/site/PageServer?pagename=user_donation_cvv">What is this?</a></label>
			<input id="ddcd_card_cvv" class="required" name="card_cvv" type="text" value="<?=$formObj['card_cvv']['value'];?>" />
			<label for="ddcd_card_cvv" class="error" style="display: none;">A valid CVV Number is required.</label>
		</div>
		<div class="formRow date">
			<label>Expiration Date <span class="req">required</span></label>
			<label class="hidden" for="ddcd_card_exp_month">Expiration Month</label>
			<select id="ddcd_card_exp_month" class="required" name="card_exp_date_month" type="select-one">
				<? foreach($formObj['months'] as $month){	?>
					<option value="<?=$month;?>" <?if($month==$formObj['card_exp_month']['value']){?>selected="selected"<?}?>><?=$month?></option>
				<?	}	?>
			</select>
			<label class="hidden" for="ddcd_card_exp_year">Expiration Year</label>
			<select id="ddcd_card_exp_year" class="required" name="card_exp_date_year" type="select-one">
				<? foreach($formObj['years'] as $year){	?>
					<option value="<?=$year;?>" <?if($year==$formObj['card_exp_year']['value']){?>selected="selected"<?}?>><?=$year?></option>
				<?	}	?>
			</select>
			<label for="ddcd_card_exp_month" class="error" style="display: none;">A valid expiration date is required.</label>
		</div>
	</fieldset>
	<fieldset class="controls">
		<?	if($formObj['expanding']){	?>
		<a href="#" class="close bttn">Close</a>
		<input id="continue_bttn" type="submit" class="bttn continue" value="Continue" />
		<?	}	?>
		<p class="warning">After completing a donation, you will have the option to send an e-card.</p>
		<input id="donate_bttn" type="submit" class="bttn submit" value="Donate Now" />
		<div class="security_seal"  style="margin:5px auto;width:135px;">
			<script type="text/javascript" src="https://seal.verisign.com/getseal?host_name=gifts.crs.org&size=S&use_flash=NO&use_transparent=NO&lang=en"></script><br />
			<a href="http://www.verisign.com/ssl-certificate/" target="_blank"  style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;">ABOUT SSL CERTIFICATES</a>
		</div>
	</fieldset>
	<br style="clear:both;">
</form>