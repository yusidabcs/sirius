<div class="card">
	
	<div class="card-header gradient-card-header blue-gradient">
		<h4 class="text-white text-center"><?php echo $commonNavHeading ?></h4>
	</div>
	
	<div class="card-body">
		
		<nav class="nav flex-column nav-pills">
<?php
		foreach($commonNavArray as $value)
		{
?>
			<a class="nav-link<?php if($value['a_class'] == 'active') echo " active"; ?>" href="<?php echo $value['link'] ?>"><?php echo $value['title'] ?></a>
<?php
		}
?>
		</nav>
	</div>
	
</div>
