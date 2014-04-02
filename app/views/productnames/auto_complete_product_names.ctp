<ul>
	<?php foreach($productnames as $productname): ?>
		<li class="autocomplete"><?php echo $productname['Productname']['product_name'] . '(' . $productname['Productname']['product_code'] . ')'; ?></li>
	<?php endforeach; ?>
</ul>
