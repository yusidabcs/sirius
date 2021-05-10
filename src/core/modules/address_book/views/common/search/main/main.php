
<form method="post" action="<?php echo $modelURL; ?>" >		
<?php								
	include($paginationInfo['paginate_standard_search_file']);				
?>	
	<table class="table table-striped table-responsive-sm" id="user_list_table" summary="Paginated list of users">	
		<thead>
			<tr>
				<th scope="col" class="row-title"><?php echo $term_table_name; ?></th>
				<th scope="col" class="row-email"><?php echo $term_table_email; ?></th>
				<th scope="col" class="row-created"><?php echo $term_table_created_on; ?></th>
				<th scope="col" class="row-modified"><?php echo $term_table_modified_on; ?></th>
				<th scope="col" class="row-buttons">&nbsp;</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">						
<?php								
					include($paginationInfo['paginate_standard_nav_file']);				
?>				
				</td>
			</tr>
		</tfoot>
		<tbody>	
<?php				
		foreach($address_book_array as $address_book_id => $value)
		{
?>
			<tr>
				<td>
					<span><?php echo $value['address_book_name']; ?></span>
				</td>
				<td>
					<span><?php echo $value['main_email']; ?></span>
				</td>
				<td>
					<span><?php echo $value['created_on']; ?></span>
				</td>
				<td>
					<span><?php echo $value['modified_on']; ?></span>
				</td>
				<td>
												
					<!-- edit -->
					&nbsp;
					<a href="<?php echo $link_edit.'/'.$address_book_id; ?>">
						<i class="fas fa-wrench" title="Edit"></i>
					</a>
					
					<!-- preview -->
					<a data-toggle="modal" data-target="#myModal_<?php echo $address_book_id; ?>" href="#">
						<i class="fas fa-eye" title="Preview"></i>
					</a>
					
					<div class="modal fade" id="myModal_<?php echo $address_book_id; ?>" tabindex="-1" role="dialog" ria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog cascading-modal" role="document">
							
							<div class="modal-content">
								
								<!--Header-->
								<div class="modal-header light-blue darken-3 white-text">
<?php
							if($value['main']['type'] == 'per')
							{
?>
									<h4 class="title"><?php echo $value['main']['title']; ?> <?php echo $value['main']['number_given_name']; ?> <?php echo $value['main']['middle_names']; ?> <?php echo $value['main']['entity_family_name']; ?></h4>	
<?php
							} else {
?>
									<h4 class="title"><?php echo $value['main']['entity_family_name']; ?></h4>
<?php
							}
?>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								</div>
		
								<!--Body-->
<?php
							if(!empty($value['avatar'])) {
?>										
								<div class="text-center mb-4">
									<img src="/ab/show/<?php echo $value['avatar'][0]['filename'] ?>" alt="Avatar for <?php echo $value['address_book_name'] ?>" height="100" width="100"> 
								</div>
<?php
							}
?>
								<!-- Information table -->
								<div class="mx-3">
									<table class="table table-sm text-left">
										
										<tbody>
<?php
									if($value['main']['type'] == 'per')
									{
										if(empty($value['main']['username']))
										{
?>						
											<tr>
												<th>Username</th>
												<td>Not Set</td>
											</tr>
<?php
										} else {
?>	
											<tr>
												<th>Username</th>
												<td><?php echo $value['main']['username']; ?></td>
											</tr>
<?php
										}
										
										if(empty($value['main']['sex']))
										{
?>						
											<tr>
												<th>Gender</th>
												<td>Not Set</td>
											</tr>
<?php
										} else {
?>	
											<tr>
												<th>Gender</th>
												<td><?php echo ucfirst($value['main']['sex']); ?></td>
											</tr>
<?php
										}
	
										if(empty($value['main']['dob']) || $value['main']['dob'] == '0000-00-00')
										{
?>						
											<tr>
												<th>Born</th>
												<td>Not Set</td>
											</tr>
<?php
										} else {
?>	
											<tr>
												<th>Born</th>
												<td><?php echo date('d F Y', strtotime($value['main']['dob'])); ?> / Age: <?php echo $value['main']['age']; ?></td>
											</tr>
<?php
										}
	
									} else {
	
										if(empty($value['main']['number_given_name']))
										{
?>						
											<tr>
												<th>Company Number</th>
												<td>Not Set</td>
											</tr>
<?php
										} else {
?>	
											<tr>
												<th>Company Number</th>
												<td><?php echo $value['main']['number_given_name']; ?></td>
											</tr>
<?php
										}
	
										if(empty($value['main']['ent_admin_details']))
										{
?>
											<tr>
												<th><?php echo $term_modal_main_ent_admin_details; ?></th>
												<td>
<?php
												foreach($value['main']['ent_admin_details'] as $ent_admin_detail)
												{
?>
													<p><?php echo $ent_admin_detail['full_name'] ?> (<?php echo $ent_admin_detail['email'] ?>)</p>
<?php
												}
?>	
												</td>
											</tr>
<?php										
											} else {
?>	
											<tr>
												<th><?php echo $term_modal_main_ent_admin_details; ?></th>
												<td>No Key Contact Listed</td>
											</tr>
<?php
										}
										
									}
									
									if(empty($value['main']['main_email']))
									{
?>							
											<tr>
												<th>Email</th>
												<td>Not Set</td>
											</tr>
<?php
									} else {
?>
											<tr>
												<th>Email</th>
												<td id="main_email">
													<a href="mailto:<?php echo $value['main']['main_email'];?>"><?php echo $value['main']['main_email'];?></a></td>
											</tr>				
<?php
									}
									
									if(empty($value['address']['main']))
									{
?>							
											<tr>
												<th>Address</th>
												<td>Not Set</td>
											</tr>
<?php
									} else {
?>
											<tr>
												<th>Address</th>
												<td>
<?php
													if($value['address']['main']['physical_pobox'] == 'physical')
													{
														if(!empty($value['address']['main']['care_of'])) echo 'c\- '.$value['address']['main']['care_of'].'<br>';
														if(!empty($value['address']['main']['line_1'])) echo $value['address']['main']['line_1'].'<br>';
														if(!empty($value['address']['main']['line_2'])) echo $value['address']['main']['line_2'].'<br>';
														if(!empty($value['address']['main']['suburb'])) echo $value['address']['main']['suburb'].' ';
														if(!empty($value['address']['main']['state_full'])) echo $value['address']['main']['state_full'].' ';
														if(!empty($value['address']['main']['postcode'])) echo $value['address']['main']['postcode'];
														if(!empty($value['address']['main']['country_full'])) echo '<br>'.$value['address']['main']['country_full'];
														
													} else {
														if(!empty($value['address']['main']['care_of'])) echo 'c\- '.$value['address']['main']['care_of'].'<br>';
														if(!empty($value['address']['main']['line_1'])) echo 'PO Box '.$value['address']['main']['line_1'].'<br>';
														if(!empty($value['address']['main']['suburb'])) echo $value['address']['main']['suburb'].' ';
														if(!empty($value['address']['main']['state_full'])) echo $value['address']['main']['state_full'].' ';
														if(!empty($value['address']['main']['postcode'])) echo $value['address']['main']['postcode'];
														if(!empty($value['address']['main']['country_full'])) echo '<br>'.$value['address']['main']['country_full'];
																
													}
?>	
												</td>
											</tr>
<?php
									}
	
									if(isset($value['address']['postal']))
									{
?>
											<tr>
												<th>Postal</th>
												<td>
<?php
													if($value['address']['postal']['physical_pobox'] == 'physical')
													{
														if(!empty($value['address']['postal']['care_of'])) echo 'c\- '.$value['address']['postal']['care_of'].'<br>';
														if(!empty($value['address']['postal']['line_1'])) echo $value['address']['postal']['line_1'].'<br>';
														if(!empty($value['address']['postal']['line_2'])) echo $value['address']['postal']['line_2'].'<br>';
														if(!empty($value['address']['postal']['suburb'])) echo $value['address']['postal']['suburb'].' ';
														if(!empty($value['address']['postal']['state_full'])) echo $value['address']['postal']['state_full'].' ';
														if(!empty($value['address']['postal']['postcode'])) echo $value['address']['postal']['postcode'];
														if(!empty($value['address']['postal']['country_full'])) echo '<br>'.$value['address']['postal']['country_full'];
														
													} else {
														if(!empty($value['address']['postal']['care_of'])) echo 'c\- '.$value['address']['postal']['care_of'].'<br>';
														if(!empty($value['address']['postal']['line_1'])) echo 'PO Box '.$value['address']['postal']['line_1'].'<br>';
														if(!empty($value['address']['postal']['suburb'])) echo $value['address']['postal']['suburb'].' ';
														if(!empty($value['address']['postal']['state_full'])) echo $value['address']['postal']['state_full'].' ';
														if(!empty($value['address']['postal']['postcode'])) echo $value['address']['postal']['postcode'];
														if(!empty($value['address']['postal']['country_full'])) echo '<br>'.$value['address']['postal']['country_full'];
																
													}
?>	
												</td>
											</tr>
<?php
									}
									
									if(empty($value['pots']))
									{
?>							
											<tr>
												<th>Telephone</th>
												<td>Not Set</td>
											</tr>
<?php
									} else {
?>
											<tr>
												<th>Telephone</th>
												<td>
<?php
													foreach($value['pots'] as $key => $pots_value) 
													{
														
														if(!empty($pots_value['number'])) 
														{
															echo $pots_value['number'].' ('.$pots_value['type'].')<br>';
														}
													}
?>	
												</td>
											</tr>
<?php
									}
	
									if(empty($value['internet']))
									{
?>							
											<tr>
												<th>Internet</th>
												<td>Not Set</td>
											</tr>
<?php
									} else {
?>
											<tr>
												<th>Internet</th>
												<td>
<?php
													foreach($value['internet'] as $key => $internet_value) 
													{
														
														if(!empty($internet_value['id'])) 
														{
															echo $internet_value['id'].' ('.$internet_value['type'].')<br>';
														}
													}
?>	
												</td>
											</tr>
<?php
									}
?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</td>
			</tr>
<?php
		}
?>
		</tbody>
	</table>
</form>
			