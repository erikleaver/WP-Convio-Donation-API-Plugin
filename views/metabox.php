<p>Use the form fields below to configure your donation form.</p>
<div class="formRow checkbox">
	<input type="checkbox" class="checkbox" id="ddcd_include" name="ddcd[include]" value="1" <?if($formObj['include']!=false){?>checked="checked"<?}?> />
	<label for="ddcd_include">Include a donation form on this entry</label>
</div>
<?	include 'form_settings.php';	?>