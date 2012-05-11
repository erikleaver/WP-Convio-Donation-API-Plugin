<fieldset class="giftstring formRow">
	<legend>Please select a gift amount</legend>
	<?php
	if(!$this->values['level_id']){
		$this->values['level_id'] = $this->settings['giving_level_default'];
	}
	foreach($this->settings['giving_level'] as $level){
		if($level['other_amount']){
			?>
			<div class="otheramt">
				<? if($this->values['level_'.$level['id'].'_amount']['error']){	?>
					<div class="error"><?=$this->values['level_'.$level['id'].'_amount']['error'];?></div>
				<?	}	?>
				
	        	<input id="ddcd_level_<?=$level['id'];?>" class="radio" type="radio" name="level_id" value="<?=$level['id'];?>" <?if($this->values['level_id']==$level['id']){ ?>checked="checked"<?}?> />
				<label for="ddcd_level_<?=$level['id'];?>"><?=$level['handle'];?></label>
				<label for="ddcd_level_<?=$level['id'];?>_amount" class="hidden"><?=$level['handle'];?></label>
				$<input id="ddcd_level_<?=$level['id'];?>_amount" name="level_<?=$level['id'];?>_amount" value="<?=$this->values['level_'.$level['id'].'_amount'];?>" type="text" size="8"/>
	        </div>
			<?
		}else{	?>
			<label>
				<input name="level_id" type="radio" class="radio" value="<?=$level['id'];?>" <?if($this->values['level_id']==$level['id']){ ?>checked="checked"<?}?> />
				<span class="handle"><?=$level['handle'];?></span>
				<span class="amount"><?=$level['amount']?></span>
			</label>		
	<?	}
	}		?>
	<label for="level_id" class="error <?if($this->errors['level_id']==""){?>hidden<?}?>"><?=$this->values['level_id'];?></label>
</fieldset>