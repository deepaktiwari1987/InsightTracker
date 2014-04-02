<ul>
	<?php foreach($firms as $firm): ?>
		<li class="autocomplete"><?php echo $firm['Sellingobstacles']['selling_obstacles'] . '(' . $firm['Sellingobstacles']['id'] . ')'; ?></li>
	<?php endforeach; ?>
</ul>
