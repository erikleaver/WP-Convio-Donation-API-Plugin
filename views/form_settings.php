<div class="formRow">
	<label for="ddcd_position">Position of Donation Form</label>
	<select name="ddcd[position]">
		<option value="">Select a location</option>
		<option <?	if($formObj['position']=="bottom"){ echo 'selected="selected"'; }?> value="bottom">Bottom of post</option>
		<option <?	if($formObj['position']=="other"){ echo 'selected="selected"'; }?> value="other">Place it yourself</option>
	</select>
</div>
<div class="formRow">
	<label for="ddcd_form_id">Form Id</label>
	<input id="ddcd_form_id" type="text" name="ddcd[form_id]" value="<?=$formObj['form_id'];?>" />
</div>
<div class="formRow">
	<label for="ddcd_action_copy">Action Copy</label>
	<input type="text" id="ddcd_action_copy" name="ddcd[action_copy]" value="<?=$formObj['action_copy'];?>" />	
</div>
<fieldset>
	<legend>Enter giving levels for display here</legend>
	<?	
		$ddcd_default_giving_levels = array(
			array(
				"id"=>"",
				"amount" => "",
				"handle" => "",
				"other_amount" => ""
				),
			);
		if(is_array($formObj['giving_level'])&&sizeof($formObj['giving_level'])>0){
			$formObj['giving_level'] = array_merge($formObj['giving_level'],$ddcd_default_giving_levels);
		}else{
			$formObj['giving_level'] = $ddcd_default_giving_levels;
		}
		for($i=0;$i<sizeof($formObj['giving_level']);$i++){
			$level = $formObj['giving_level'][$i];
			//include 'gift_string_conf.php';
			?>
			<div class="itemRow">
				<div class="formRow">
					<label for="ddcd_<?=$i;?>_level_id">Level Id</label>
					<input type="text" id="ddcd_<?=$i;?>_level_id" name="ddcd[giving_level][<?=$i;?>][id]" value="<?=$level['id'];?>" />
				</div>
				<div class="formRow">
					<label for="ddcd_<?=$i;?>_amount">Amount</label>
					<input type="text" id="ddcd_<?=$i;?>_amount" name="ddcd[giving_level][<?=$i;?>][amount]" value="<?=$level['amount'];?>" />
				</div>
				<div class="formRow">
					<label for="ddcd_<?=$i;?>_handle">Description</label>
					<input type="text" id="ddcd_<?=$i;?>_handle" name="ddcd[giving_level][<?=$i;?>][handle]" value="<?=$level['handle'];?>" />
				</div>
				<div class="formRow checkbox">
					<input type="checkbox" class="checkbox" id="ddcd_<?=$i;?>_other_amount" name="ddcd[giving_level][<?=$i;?>][other_amount]" value="1" <?if($level['other_amount']){?>checked="checked"<?}?> />
					<label for="ddcd_<?=$i;?>_other_amount">User entered value</label>
				</div>
				<?	if($level['id']){	?>
				<div class="formRow radio">
					<input type="radio" class="radio" id="ddcd_<?=$i;?>_default" name="ddcd[giving_level_default]" value="<?=$level['id'];?>" <?if($level['id']==$formObj['giving_level_default']){?>checked="checked"<?}?> />
					<label for="ddcd_<?=$i;?>_default">Default giving level</label>
				</div>
				<?	}	?>
			</div>
			<?
		}
	?>

</fieldset>
<div class="formRow">
	<label for="ddcd_typage">Enter Page ID of confirmation page</label>
	<input type="text" id="ddcd_typage" name="ddcd[typage]" value="<?=$formObj['typage'];?>" />
</div>
<div class="formRow">
	<label for="ddcd_error_page">Enter <strong>Full URL</strong> for error page</label>
	<input type="text" id="ddcd_error_page" name="ddcd[error_page]" value="<?=$formObj['error_page'];?>" />
</div>
<div class="formRow checkbox">
	<input type="checkbox" class="checkbox" id="ddcd_preview" name="ddcd[preview]" value="1" <?if($formObj['preview']){?>checked="checked"<?}?> />
	<label for="ddcd_preview">Send donations in preview mode</label>
</div>
