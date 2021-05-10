
<a class="nav_link waves-effect arrow-r active text-left pl-2 m-0">
<?php
	if(!empty($avatar))
	{
?>
		<img src="/ab/show/<?php echo $avatar[0]['filename'] ?>" alt="Current Avatar" class="sv-slim-icon rounded-circle ml-0 mt-0 pt-0 img img-fuild" style="width:40px">
<?php
	}
?>		
	<span class="text-center">
	<?php
		if(!empty($main['title'])) echo $main['title'].' ';
			echo $main['number_given_name'].' ';
		if(!empty($main['middle_names']))
			echo $main['middle_names'].' ';
		// echo $main['entity_family_name']; 
	?>
	</span>		
	
</a>

