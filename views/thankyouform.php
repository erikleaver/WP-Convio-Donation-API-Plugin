<div class="formRow checkbox">
	<input type="checkbox" class="checkbox" id="ddcd_thankyou_page" name="ddcd[settings][protected_thankyou_page]" value="1" <?if($ddcd->settings['protected_thankyou_page']!=false){?>checked="checked"<?}?> />
	<label for="ddcd_thankyou_page">Make this page a protect thank you page that will only be visible if the user has an active transaction.</label>
</div>