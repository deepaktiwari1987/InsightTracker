<ul>
	<?php foreach($firms as $firm): ?>
		<li class="autocomplete"><?php echo $firm['Firm']['firm_name'] . '(' . $firm['Firm']['parent_id'] . ')'; ?></li>
	<?php endforeach; ?>
</ul>
