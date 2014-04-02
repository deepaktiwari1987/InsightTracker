<ul>
	<?php foreach($firms as $firm): ?>
		<li class="autocomplete"><?php echo $firm['Departmentnames']['department_name'] . '(' . $firm['Departmentnames']['dept_id'] . ')'; ?></li>
	<?php endforeach; ?>
</ul>
