<div class="card">
	
	<div class="card-header gradient-card-header blue-gradient">
		<h4 class="text-white text-center">Mailing Subscriber</h4>
	</div>
	
	<div class="card-body">
		
		<nav class="nav flex-column nav-pills">
<?php
		foreach($commonNavArray as $value)
		{
?>
	<?php if(strtolower($value['title']) !== strtolower('edit template')): ?>
			<a class="nav-link<?php if($value['a_class'] == 'active') echo " active"; ?>" href="<?php echo $value['link'] ?>"><?php echo $value['title'] ?></a>
	<?php endif ?>
<?php
		}
?>
		</nav>
	</div>
	
</div>