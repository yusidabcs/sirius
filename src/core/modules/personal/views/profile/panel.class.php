<h3 class="card-header blue-gradient white-text text-center py-4">
        <?php echo $term_personal_title; ?>
    </h3>
			
	<div class="card-body">
        <?php
        if (count($profile_info) > 0){ 
        ?>
            <!-- Tab Heading -->
            <ul class="nav nav-tabs d-flex" id="myTab" role="tablist">
            <li class="nav-item">
                    <a class="nav-link active" id="v-pills-notification-tab" data-toggle="tab" href="#notification" role="tab" aria-controls="v-pills-notification" aria-selected="true">Notification</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="v-pills-summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="v-pills-summary" aria-selected="true">Summary</a>
                </li>
            </ul>
            <!-- End Tab heading -->
            <!-- Tab Content -->
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                    <!-- Summary content -->
                    <div class="row">
                        <div class="col-md-6">
                            Created: <?php echo $profile_info['created_on']['value']?>
                        </div>
                        <div class="col-md-6">
                            Modified: <?php echo $profile_info['modified_on']['value']?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <?php
                        foreach($profile_info as $key => $value)
                        {
                            if(in_array($key, ['created_on','modified_on','has_tattoo'])){
                                continue;
                            }
                            ?>
                            <div class="col-md-3 mb-3">
                                <div class="card p-3 text-center">
                                    <i class="fas <?php echo $value['icon']?> fa-3x <?php echo $value['value'] > 0 ? 'text-info' : 'text-warning'?>"></i>
                                    <p class="mb-0">
                                        <?php echo $key?> <br>
                                        <span class="h4"><?php echo $value['value']?></span>
                                    </p>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <!-- end summary content -->
                </div>
                <div class="tab-pane fade show  active" id="notification" role="tabpanel" aria-labelledby="notification-tab">
                    <!--Notification content -->
                    <?php
                        $count = count($data_verification);
                        if($count>0) {
                            if($data_verification[0]['status']=='process' || $data_verification[0]['status']=='rejected') {
                    ?>
                                <button type="button" id="req_verification" class="btn btn-sm btn-success">Request Verification</button>
                    <?php
                            }
                        }
                    ?>  
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Note</th>
                                <th>Created On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(count($data_verification)>0) {
                                    foreach ($data_verification as $key => $value) {
                                        if($value['status']=='request'){$class='info';}
                                        else if($value['status']=='process'){$class='warning';}
                                        else if($value['status']=='verified'){$class='success';}
                                        else {$class='danger';}
                                        echo'
                                            <tr>
                                                <td>
                                                <span class="badge badge-'.$class.'">'.$value['status'].'</span>
                                                </td>
                                                <td>'.$value['verification_info'].'</td>
                                                <td>'.$value['created_on'].'</td>
                                            </tr>
                                        ';
                                    }
                                } else {
                                    echo'
                                        <tr>
                                            <td colspan="3">
                                                <div class="alert alert-warning" role="alert">
                                                Please complete the personal data and make request verification!
                                                </div>
                                            </td>
                                        </tr>
                                    ';
                                }
                            ?>
                        </tbody>
                    </table>
                    <!-- ENd Notification centent -->
                </div>
            </div>
            <!-- End tab content -->
        <?php    
        }else{
            echo '<p class="text-center text-warning"> No Personal Information yet </p>';
        }
        ?>

    </div>
    <div class="card-footer">
        <a class="btn btn-primary btn-sm btn-block" href="<?php echo $personal_link; ?>" role="button">Go to Personal Data >></a>
    </div>

    <!-- Loading Modal -->
<div class="modal fade" id="modal_loading" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="text-center modal-dialog modal-lg" role="document">
    <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
    </div>
</div>
