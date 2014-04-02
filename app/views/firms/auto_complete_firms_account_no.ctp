<ul>
	<?php foreach($accounts as $account): ?>
		<li class="autocomplete"><?php echo $account['Firm']['account_number']; ?></li>
	<?php endforeach; ?>
</ul>
