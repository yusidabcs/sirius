
				<!-- start of address_book common view main -->
<?php
	if($main['type'] == 'per')
	{
?>
				<p><?php echo $main['title']; ?> <?php echo $main['entity_family_name']; ?>, <?php echo $main['number_given_name']; ?> <?php echo $main['middle_names']; ?> [<?php echo $main['sex']; ?>, <?php echo $main['age']; ?>]</p>
				
<?php
	} else {
?>
				<p><?php echo $main['entity_family_name']; ?> (<?php echo $main['number_given_name']; ?>)</p>
<?php
	}
?>	
				<!-- end of address_book common view main -->
				