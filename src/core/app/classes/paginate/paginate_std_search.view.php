
<!--Standard Pagination Search Input -->

<div class="input-group mb-3">
  <div class="input-group-prepend">
    <button class="btn btn-default btn-md m-0 px-3 py-2 z-depth-0 dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<?php				                
	if($paginationInfo['search_type'] == 'starts')
	{
?>
		<span id="search_concept"><?php echo $term_search_name_starts; ?></span> <span class="caret"></span>
<?php	
	} else {
?>
		<span id="search_concept"><?php echo $term_search_name_contains; ?></span> <span class="caret"></span>
<?php									
	}
?>
    </button>
<?php
if($paginationInfo['search_type'] == 'starts')
{
?>
	<input id="search_param" type="hidden" name="search_type" value="<?php echo $term_search_title_starts; ?>">
<?php	
} else {
?>
	<input id="search_param" type="hidden" name="search_type" value="<?php echo $term_search_title_contains; ?>">
<?php									
}
?>    
    <div id="search-panel" class="dropdown-menu">
      <a class="dropdown-item" href="#<?php echo $term_search_title_contains; ?>"><?php echo $term_search_name_contains; ?></a>
      <a class="dropdown-item" href="#<?php echo $term_search_title_starts; ?>"><?php echo $term_search_name_starts; ?></a>
    </div>
  </div>
  <input type="text" class="form-control" id="search_text" name="search_text" placeholder="Search term..." <?php if(!empty($paginationInfo['search_text'])) echo 'value="'.$paginationInfo['search_text'].'"'; ?>>
  <div class="input-group-append">
	  <button id="search_button" class="btn btn-default btn-md m-0 px-3 py-2 z-depth-0" type="submit"><i class="fas fa-search"></i></button>
  </div>
</div>

<!-- End of Standard Pagination Search Input -->
