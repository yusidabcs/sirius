<!-- Paginate : Pagination Standard Bar -->

<nav aria-label="Page navigation">
	
  <ul class="pagination">
	
    <li class="page-item <?php if(empty($paginationInfo['start_page'])) echo 'disabled'; ?>">
      <a class="page-link" href="<?php echo $modelURL.'/'.$paginationInfo['start_page'] ?>" aria-label="First Page">
        <i class="fas fa-fast-backward" aria-hidden="true"></i>
        <span class="sr-only">First Page</span>
      </a>
    </li>

    <li class="page-item <?php if(empty($paginationInfo['previous_page'])) echo 'disabled'; ?>">
      <a class="page-link" href="<?php echo $modelURL.'/'.$paginationInfo['previous_page'] ?>" aria-label="Previous Page">
        <i class="fas fa-angle-left" aria-hidden="true"></i>
        <span class="sr-only">Previous Page</span>
      </a>
    </li>
    
    <li class="page-item disabled">
		<a class="page-link" href="#" aria-label="Pagination Information">
			Page: <?php echo $paginationInfo['page_on'].' of '.$paginationInfo['total_pages']; ?> (Total Records: <?php echo $paginationInfo['total_records'] ?>)
		</a>
    </li>
    
    <li class="page-item <?php if(empty($paginationInfo['next_page'])) echo 'disabled'; ?>">
      <a class="page-link" href="<?php echo $modelURL.'/'.$paginationInfo['next_page'] ?>" aria-label="Next Page">
        <i class="fas fa-angle-right" aria-hidden="true"></i>
        <span class="sr-only">Next Page</span>
      </a>
    </li>
    
    <li class="page-item <?php if(empty($paginationInfo['last_page'])) echo 'disabled'; ?>">
      <a class="page-link" href="<?php echo $modelURL.'/'.$paginationInfo['last_page'] ?>" aria-label="Last Page">
        <i class="fas fa-fast-forward" aria-hidden="true"></i>
        <span class="sr-only">Last Page</span>
      </a>
    </li>

  </ul>
  
</nav>

<!-- End Paginate : Pagination Standard Bar -->
