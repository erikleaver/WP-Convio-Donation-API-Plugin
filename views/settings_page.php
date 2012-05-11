<form action="" method="post">
	<fieldset>
		<legend>Convio connection settings</legend>
		<div class="formRow">
			<label for="api_location">Location of API</label>
			<input type="text" name="ddcd[api_location]" id="api_location" value="<?=$formObj['api_location'];?>" />
		</div>
		<div class="formRow">
			<label for="api_key">API Key</label>
			<input type="text" name="ddcd[api_key]" id="api_key" value="<?=$formObj['api_key'];?>" />
		</div>
	</fieldset>
	<?	include 'form_settings.php';	?>
	<div class="formRow">
		<input type="submit" value="save" />
	</div>
</form>