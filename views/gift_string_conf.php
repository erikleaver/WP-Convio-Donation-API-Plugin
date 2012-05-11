<div class="itemRow">
	<?=$level;?>
	<?=json_encode($level);?>
	<?=$level['id'];?>
	<?=$level->id;?>
	<?=$formObj['giving_level'][$i]['id'];?>
	<div class="formRow">
		<label for="level_id">Level Id</label>
		<input type="text" id="level_id" name="ddcd[giving_level][<?=$i;?>]['id']" value="<?=$level['id'];?>" />
	</div>
	<div class="formRow">
		<label for="amount">Amount</label>
		<input type="text" id="amount" name="ddcd[giving_level][<?=$i;?>]['amount']" value="<?=$level['amount'];?>" />
	</div>
	<div class="formRow">
		<label for="handle">Description</label>
		<input type="text" id="handle" name="ddcd[giving_level][<?=$i;?>]['handle']" value="<?=$level['handle'];?>" />
	</div>
	<div class="formRow checkbox">
		<input type="checkbox" class="checkbox" id="other_amount" name="ddcd[giving_level][<?=$i;?>]['other_amount']" value="1" <?if(isset($level['other_amount'])){?>checked="checked"<?}?> />
		<label for="other_amount">User entered value</label>
	</div>
</div>