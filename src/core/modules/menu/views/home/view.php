
<!-- start of menu home -->

<div class="row mt-3">
	<div class="col">
		
		<div class="card border-dark">
			
			<div class="card-header">
				<h1 class="card-title"><?php echo $term_page_header ?></h1>
			</div>
			
			<div class="card-body">
		
				<ul class="list-group">
					<li class="list-group-item"><h4>Menu Root</h4></li>
					
					<ul class="list-group">
<?php	
					function outputLi($a,$baseURL)
					{
						$out = "";
						foreach($a as $id => $value)
						{
							$menu_title = $value['menu_title'];
							$seq = 'Sequence: <strong>'.$value['sequence_no'].'</strong>';
							$lid = 'Link: <strong>'.$value['link_id'].'</strong>';
							$m = 'Module: <strong>'.$value['module_id'].'</strong>';
							$s = 'Security: <strong>'.$value['security_level_id'].'</strong>';
							$g = 'Group: <strong>'.$value['group_id'].'</strong>';
							
							$l = 'Linked: <strong>';
							if($value['main_link'] == 1) $l .= '<span style="color:Green">Main </span>';
							if($value['quick_link'] == 1) $l .= '<span style="color:DarkOrange">Quick </span>';
							if($value['bottom_link'] == 1) $l .= '<span style="color:MediumOrchid">Bottom </span>';
							if($value['main_link'] == 0 && $value['quick_link'] == 0 && $value['bottom_link'] == 0 ) $l .= '<span style="color:FireBrick">Not Linked </span>';
							if($value['sitemap'] == 1) $l .= '<span style="color:DarkTurquoise">Sitemap </span>';
							$l .= '</strong>';
						
							$st = $value['status'] == 1 ? '' : '- <strong style="color:Red">Status OFF</strong>';
							
							if(empty($value['redirect_url']))
							{
								$out .= '<li class="list-group-item"><span class="spacer"></span><i class="fas fa-chevron-right"></i> <a href="'.$baseURL.'/edit/'.$value['link_id'].'"><strong style="color:blue">'.$menu_title."</strong></a> - $seq $lid $m $s $g $l $st</li>\n";
							} else {
								$u = 'URL: <strong><span style="color:red">'.$value['redirect_url'].'</span></strong>';
								$out .= '<li class="list-group-item"><span class="spacer"></span><i class="fas fa-chevron-right"></i> <a href="'.$baseURL.'/edit/'.$value['link_id'].'"><strong style="color:blue">'.$menu_title."</strong></a> - $seq $lid $u $s $g $l $st</li>\n";
							}
							
							
							$n = $value['children'];
							if( !empty($n) )
							{
								$out .= "<ul>";
								$out .= outputLi($n,$baseURL);
								$out .= "</ul>";
							}
						}
						return $out;
					}
					
					echo outputLi($fullMenu,$baseURL);
					
?>
					</ul>
				</ul>
				
				<div class="mt-3">
					<a href="<?php echo $baseURL; ?>/add"><button type="button" class="btn btn-default btn-block"><?php echo $term_add_button ?></button></a>
				</div>
				
				<div class="mt-3">
					<a href="<?php echo $updateURL; ?>/add"><button type="button" class="btn btn-warning btn-block"><?php echo $term_update_button ?></button></a>
				</div>
				
			</div>
		</div>
		
	</div>	
</div>

<!-- end of menu home -->
