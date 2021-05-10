<!-- main tab -->	
<div id="main" class="tab-pane fade show active" role="tabpanel">
	<div class="row">
		<!-- profil info -->
		<div class="col-sm-12 col-md-5 col-lg-4 col-xl-4 m-0 p-0 px-2">

        <!-- end of verification status card -->
        <!-- profile card -->
        <div class="card testimonial-card mb-3">

            <!-- Bacground color -->
            <div class="card-up indigo lighten-1"></div>
            <?php
            if (!empty($avatar)) {
                ?>
                <!-- Avatar -->
                <div class="avatar mx-auto white"><img src="/ab/show/<?php echo $avatar[0]['filename'] ?>" alt="Current Avatar" class="rounded-circle"></div>
                <?php
            }
            ?>
            <div class="card-body">
                <h4 class="card-title">
                    <?php
                    if (!empty($main['title']))
                        echo $main['title'] . ' ';
                    echo ' '.$main['number_given_name'];
                    if (!empty($main['middle_names']))
                        echo ' '.$main['middle_names'];
                    echo ' '.$main['entity_family_name'];

                    if ( $verification['status'] == 'verified' )
                    {
                        ?>
                        <span class="fa-stack" style="font-size: 0.6rem" title="Verified Member">
							<i class="fa fa-certificate text-success fa-stack-2x"></i>
							<i class="fa fa-check fa-stack-1x fa-inverse"></i>
						</span>
                        <?php
                    }
                    ?>
                </h4>
                <table class="table text-left table-responsive-sm">
                    <tr>
                        <th>Username</th>
                        <td class="text-left"><?php echo $user_info['username'] ?></td>
                    </tr>
                    <tr>
                        <th>Born</th>
                        <td class="text-left">
                            <?php
                            echo (empty($main['dob']) || $main['dob'] == '0000-00-00')
                                ? 'Not Set'
                                : date('d F Y', strtotime($main['dob'])); ?> / Age: <?php echo $main['age'] ;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td class="text-left">
                            <?php
                            echo (empty($main['sex']))
                                ? 'Not Set'
                                : ucfirst($main['sex']) ;
                            ?>
                        </td>
                    </tr>

                    <?php
                    if (isset($address['postal']))
                    {
                        ?>
                        <tr>
                            <th>Postal</th>
                            <td class="text-left">
                                <?php
                                if ($address['postal']['physical_pobox'] == 'physical')
                                {
                                    if (!empty($address['postal']['line_1'])) echo $address['postal']['line_1'];
                                    if (!empty($address['postal']['line_2'])) echo '<br>' . $address['postal']['line_2'];
                                    if (!empty($address['postal']['suburb'])) echo '<br>' . $address['postal']['suburb'] . ' ';
                                    if (!empty($address['postal']['state_full'])) echo $address['postal']['state_full'] . ' ';
                                    if (!empty($address['postal']['postcode'])) echo $address['postal']['postcode'];
                                    if (!empty($address['postal']['country_full'])) echo '<br>' . $address['postal']['country_full'];

                                } else {

                                    if (!empty($address['postal']['line_1'])) echo 'PO Box ' . $address['postal']['line_1'] . '<br>';
                                    if (!empty($address['postal']['suburb'])) echo $address['postal']['suburb'] . ' ';
                                    if (!empty($address['postal']['state_full'])) echo $address['postal']['state_full'] . ' ';
                                    if (!empty($address['postal']['postcode'])) echo $address['postal']['postcode'];
                                    if (!empty($address['postal']['country_full'])) echo '<br>' . $address['postal']['country_full'];

                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>

                    <tr>
                        <th>Email</th>
                        <td class="text-left">
                            <a href="mailto:<?php echo $main['main_email']; ?>"><?php echo $main['main_email']; ?></a>
                        </td>
                    </tr>

                    <tr>
                        <th>Telephone</th>
                        <td class="text-left">
                            <?php
                            if (empty($pots))
                            {
                                echo 'Not Set';
                            } else {
                                foreach ($pots as $key => $value)
                                {
                                    if (!empty($value['number']))
                                    {
                                        echo '+'.$value['dialInfo']['dialCode'].$value['number'] . ' (' . $value['type'] . ')<br>';
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Internet</th>
                        <td class="text-left">
                            <?php
                            if (empty($internet))
                            {
                                echo 'Not Set';
                            } else {
                                foreach ($internet as $key => $value)
                                {
									$bHasLink = strpos($value['id'], 'http') !== false || strpos($value['id'], 'www.') !== false;

									if($bHasLink){
										$value['id'] = '<a href="'.$value['id'].'" target="_blank" class="badge badge-default">Open '.$value['type'].'</a>';
									}
                                    if (!empty($value['id'])) {
                                        echo $icon_internet[$value['type']].' '.$value['id'].'<br>';
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>

                </table>

            </div>
            <div class="card-footer text-center">
                <?php
                if ($mode == 'personal')
                {
                    ?>
                    <a href="<?php echo $profile_link;  ?>" class="btn btn-sm btn-primary" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_main_goto_profile; ?>"><i class="fas fa-user"></i> <?php echo $term_main_goto_profile; ?></a>
                    <?php if(!empty($cv_link) &&  $verification['status'] == 'verified'): ?>
						<a href="<?php echo $cv_link; ?>" id="show-cv" class="btn btn-info btn-sm" target="_blank"> <i class="fa fa-eye"></i> <?php echo $term_see_cv ?></a>
					<?php endif ?>
					<?php
                }else{
                    ?>

                    <a href="<?php echo $recruitment_home; ?>" class="btn btn-sm btn-primary" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_main_goto_recruitment; ?>"><i class="fas fa-arrow-left"></i> <?php echo $term_main_goto_recruitment; ?></a>
                    <a href="<?php echo $address_book_link.'/edit/'.$address_book_id; ?>" class="btn btn-sm btn-primary" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_main_goto_profile; ?>"><i class="fas fa-user"></i> <?php echo $term_main_goto_profile; ?></a>
                    <?php if(!empty($cv_link) &&  $verification['status'] == 'verified'): ?>
						<a href="<?php echo $cv_link; ?>" id="show-cv" class="btn btn-info btn-sm" target="_blank"> <i class="fa fa-eye"></i> <?php echo $term_see_cv ?></a>
					<?php endif ?>
					<?php
                }
                ?>

            </div>
        </div>
        <!-- end of profile card -->
    </div>
		<!-- end profil info -->
		<!-- general information panel -->
		<div class="col-sm-12 col-md-7 col-lg-8 col-xl-8 m-0 p-0 px-2">
				<div class="card card-cascade mb-4">	
					<div class="view view-cascade gradient-card-header blue-gradient">
						<!-- Title -->
						<h3 class="card-header-title mb-3"><?php echo $term_general_title; ?></h3>
					</div>
					<div class="card-body card-body-cascade">
<?php
					if(empty($general))
					{
						echo $term_general_no_data;
					} else {
?>	
						<div class="row">
							
							<!-- image -->
							<div class="col-md-4 text-center mb-3">
<?php 
							if(!empty($general['filename']))
							{
?>
								<a href="/ab/show/<?php echo $general['filename']; ?>" data-toggle="lightbox" data-gallery="<?php echo $general['filename']; ?>" data-footer="<?php echo $term_general_image_caption; ?>" data-type="image">
									<img src="/ab/show/<?php echo $general['thumb']; ?>"  class="img-fluid z-depth-1" alt="<?php echo $term_general_image_caption; ?>" title="<?php echo $term_general_image_caption.' - Click to Enlarge'; ?>">
								</a>
<?php
							} else {
								echo "<?php echo $term_general_image_none; ?>";
							}
?>

							</div>
														
							<div class="col-md-8"> 
								
								<!-- info -->
								<table class="table table-general-information table-sm table-responsive-md">
									<tbody>
										<tr>
											<th><?php echo $term_general_overview; ?></th>
										</tr>
										<tr>
											<td>
<?php
											switch ($general['employment']) 
											{
											    case 'unemployed':
											        echo $term_general_employment_unemployed;
											        break;
											    case 'casual':
											        echo $term_general_employment_casual;
											        break;
											    case 'part_time':
											        echo $term_general_employment_part_time;
											        break;
											    case 'full_time':
											        echo $term_general_employment_full_time;
											        break;
											}
?>
, 
<?php
											if($general['job_hunting'] == 'yes')
											{
												if($general['employment'] == 'unemployed')
												{
													echo $term_general_job_looking_unemployed;
												} else {
													echo $term_general_job_looking_employed;
												}
											} else {
												
												if($general['employment'] == 'unemployed')
												{
													echo $term_general_job_not_looking_unemployed;
												} else {
													echo $term_general_job_not_looking_employed;
												}
											}
?>
, 
<?php
											if($general['seafarer'])
											{
												echo $term_general_seafarer_yes;
											} else {
												echo $term_general_seafarer_no;
											}
?>
 
<?php
											if($general['migration'])
											{
												echo $term_general_migration_yes;
											} else {
												echo $term_general_migration_no;
											}
?>											
											</td>
										</tr>
										<tr>
											<th><?php echo $term_general_countries; ?></th>
										</tr>
										<tr>
											<td>
									<?php echo $term_general_countries_born; ?> <?php echo $general['country_born']; ?>
, 
									<?php echo $term_general_countries_live; ?> <?php echo $general['country_residence']; ?>
, 					
<?php
											if($general['passport'])
											{
												echo $term_general_passport_yes;
											} else {
												echo $term_general_passport_no;
											}
?>
 and 
<?php
											if($general['travelled_overseas'])
											{
												echo $term_general_overseas_yes;
											} else {
												echo $term_general_overseas_no;
											}
?>									
											</td>
										</tr>
									</tbody>
								</table>
								
								<!-- personal -->
								<table class="table table-general-information table-sm table-responsive-md">
									<tbody>
										<tr>
											<th><?php echo $term_general_personal; ?></th>
										</tr>
										<tr>
											<td>
<?php
									switch ($general['relationship']) 
									{
									    case 'committed':
									        echo $term_general_relationship_committed;
									        break;
									        
									    case 'divorced':
									        echo $term_general_relationship_divorced;
									        break;
									        
									    case 'married':
									        echo $term_general_relationship_married;
									        break;
									        
									    case 'single':
									        echo $term_general_relationship_single;
									        break;
									        
									    case 'separated':
									        echo $term_general_relationship_separated;
									        break;
									}
?>

<?php
									if($general['children'])
									{
										echo $term_general_children_yes;
									} else {
										echo $term_general_children_no;
									}
?>

<?php
									if($general['tattoo'])
									{
										echo $term_general_tattoo_yes;
									} else {
										echo $term_general_tattoo_no;
									}
?>

<?php
									if($general['height_weight'] == 'me')
									{
										echo $term_general_height.' '.$general['height_cm'].' cm '.$term_general_weight.' '.$general['weight_kg'].' kg '.$term_general_bmi.' '.$general['bmi'].'.';   
									} else {
										echo $term_general_height.' '.$general['height_in'].' in '.$term_general_weight.' '.$general['weight_lb'].' lbs '.$term_general_bmi.' '.$general['bmi'].'.';
									}
?>
											
											</td>
										</tr>
										<tr>
											<th>Digital Signature</th>
										</tr>
										<tr>
											<td>
												<?php 
																			if(!empty($general['signature_filename']))
																			{
												?>
																				<a href="/ab/show/<?php echo $general['signature_filename']; ?>" data-toggle="lightbox" data-gallery="<?php echo $general['signature_filename']; ?>" data-footer="<?php echo $term_general_image_caption; ?>" data-type="image">
																					<img src="/ab/show/<?php echo $general['signature_filename']; ?>"  class="img-fluid mt-3" alt="<?php echo $term_general_image_caption; ?>" title="<?php echo $term_general_image_caption.' - Click to Enlarge'; ?>">
																				</a>
												<?php
																			} else {
																				echo $term_general_signature_none;
																			}
												?>
											</td>
										</tr>
									</tbody>
								</table>
							</div>							
						</div>
						
						<!-- nok table -->
						<div class="row">	
							<div class="col-12">
								<table class="table table-general-information table-sm table-responsive-md">
									<tbody>
										<tr>
											<th><?php echo $term_general_nok; ?></th>
										</tr>				
										<tr>
											<td>
												<table class="table table-sm table-borderless">
													<tbody>
<?php
													if( empty($general['nok_name']) )
													{
?>
														<tr>
															<td colspan="2">
			
																<?php echo $term_general_nok_no; ?>
															</td>
														</tr>
<?php	
													} else {									
?>	
														<tr>
															<th>
																<?php echo $term_general_nok_name; ?> 
															</th>
															<td>
																<?php echo $general['nok_name']; ?>
															</td>
														</tr>
<?php												
													}

													if( !empty($general['nok_relationship']) )
													{
?>														
														<tr>
															<th>
																<?php echo $term_general_nok_relationship; ?> 
															</th>
															<td>
																<?php echo $general['nok_relationship']; ?>
															</td>
														</tr>
<?php												
													}

													if( !empty($general['nok_address']) )
													{
?>																												
														<tr>
															<th>
																<?php echo $term_general_nok_address; ?>
															</th>
															<td>
																<?php echo $general['nok_address']; ?>
															</td>
														</tr>
<?php
													}
										
													if( !empty($general['nok_email']) )
													{
?>
														<tr>
															<th>
																<?php echo $term_general_nok_email; ?>
															</th>
															<td>
																<?php echo $general['nok_email']; ?> 
															</td>
														</tr>	
<?php
													}
										
													if( !empty($general['nok_skype']) )
													{
?>
														<tr>
															<th>
																<?php echo $term_general_nok_skype; ?>
															</th>
															<td>
																<?php echo $general['nok_skype']; ?> 
															</td>
														</tr>	
<?php
													}

													if( !empty($general['nok_phone']) )
													{
?>
														<tr>
															<th>
																<?php echo $term_general_nok_phone; ?> 
															</th>
															<td>
																<?php echo '+'.$general['nok_phone']; ?>
															</td>
														</tr>
<?php
													}
?>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
<?php
					}
?>					
					</div>
					<div class="card-footer text-center">
						<a href="<?php echo $general_link;  ?>" class="btn btn-md btn-primary" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_general_edit; ?>"><i class="fas fa-edit"></i> <?php echo $term_general_edit; ?></a>
					</div>
				</div>
				
			</div>
			<!-- end of bio panel -->
	</div>
</div>
<!-- end main tab -->