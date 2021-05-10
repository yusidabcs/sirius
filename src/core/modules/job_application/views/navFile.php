<div class="card">
	
	<div class="card-header gradient-card-header blue-gradient">
		<h4 class="text-white text-center"><?php echo $commonNavHeading ?></h4>
	</div>
	
	<div class="card-body">
		
		<nav class="nav flex-column nav-pills">
<?php 
		$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$uri_segments = explode('/', $uri_path);
		foreach($commonNavArray as $value)
		{
?>
			<a class="nav-link<?php if($value['a_class'] == 'active') echo " active"; ?>" href="<?php echo $value['link'].(isset($uri_segments[3])? '/'.$uri_segments[3] : '') ?>"><?php echo $value['title'] ?></a>
<?php
		}
?>
		</nav>
	</div>
	
</div>
