<?	if($formObj['miniform']){	?>
	<form class="donation mini" action="<?bloginfo('wpurl');?>/wp-content/plugins/ddcd/ddcd_donation_process.php" method="post">
	<?	if(isset($formObj['page'])){	?>
		<input type="hidden" name="page" value="<?=$formObj['page'];?>" />
	<?	}	?>
<?	}	?>
<fieldset class="giftstring formRow">
	<legend><?	if($formObj['action_copy']&&($formObj['action_copy']!="")){	echo $formObj['action_copy'];	}else{	?>Please select a gift amount<?	}	?></legend>
	<?php
	if(!$formObj['level_id']['value']){
		$formObj['level_id'] = array(
			"value" => $formObj['giving_level_default'],
			);
	}
	foreach($formObj['giving_level'] as $level){
		if($level['other_amount']){
			?>
			<div class="otheramt">
				<? if($formObj['level_'.$level['id'].'_amount']['error']){	?>
					<div class="error"><?=$formObj['level_'.$level['id'].'_amount']['error'];?></div>
				<?	}	?>
				
	        	<input id="ddcd_level_<?=$level['id'];?>" class="radio" type="radio" name="level_id" value="<?=$level['id'];?>" <?if($formObj['level_id']['value']==$level['id']){ ?>checked="checked"<?}?> />
				<label for="ddcd_level_<?=$level['id'];?>"><?=$level['handle'];?></label>
				<label for="ddcd_level_<?=$level['id'];?>_amount" class="hidden"><?=$level['handle'];?></label>
				$<input id="ddcd_level_<?=$level['id'];?>_amount" name="level_<?=$level['id'];?>_amount" value="<?=$formObj['level_'.$level['id'].'_amount']['value'];?>" type="text" size="8"/>
	        </div>
			<?
		}else{	?>
			<label>
				<input name="level_id" type="radio" class="radio" value="<?=$level['id'];?>" <?if($formObj['level_id']['value']==$level['id']){ ?>checked="checked"<?}?> />
				<span class="handle"><?=$level['handle'];?></span>
				<span class="amount"><?=$level['amount']?></span>
			</label>		
	<?	}
	}		?>
	<?	if(!$formObj['miniform']){	?>
	<label for="level_id" class="error <?if($formObj['level_id']['error']==""){?>hidden<?}?>"><?=$formObj['level_id']['error'];?></label>
	<?	}	?>
</fieldset>
<?	if($formObj['miniform']){	?>
		<br style="clear:both;" />
		<fieldset class="controls">
			<input type="submit" class="bttn" value="<?=$formObj['bttn_copy'];?>" />
		</fieldset>
	</form>
	<br style="clear:both;" />
<?	}	?>
