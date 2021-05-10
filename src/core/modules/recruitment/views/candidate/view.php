<div class="card">
	
	<div class="card-header gradient-card-header blue-gradient d-flex align-item-center justify-content-between">
        <h4 class="text-white text-center"><?php echo $term_page_header ?></h4>
        <div>
            <a href="/<?php echo $add_ab_link?>" class="btn btn-success btn-sm "><i class="fa fa-plus"></i> <?php echo $term_add_new?></a>
            <a href="#" class="btn btn-warning btn-sm" id="export_candidate"><i class="fas fa-file-excel"></i> <?php echo $term_export_excel?></a>
        </div>
    </div>

	<div class="card-body">
        <div id="table_search" class="container">
                <div class="row justify-content-center">
                    <?php if($entity == false) { ?>
                    <div class="col-md-3 md-form">
                        <!-- <input type="text" class="form-control" id="table_partner_search">-->
						<select id="table_partner_search" name="table_partner_search" class="mdb-select" searchable="Search">
                            <option value=""><?php echo $term_table_select_all_partner; ?></option>
<?php
                           
                            $html = '';
                            $html = '';
                            if(count($partners)>0) {
                                $html .='<optgroup label="License Partner">';
                                foreach($partners as $key => $partner)
                                {
                                    $html.= '<option value="lp_'.$key.'" >'.$partner['name'].'</option>';
                                }
                                $html .='</optgroup>';
                            }
                            if(count($partners_lep)>0) {
                                $html .='<optgroup label="License Education Partner">';
                                foreach($partners_lep as $key => $partner)
                                {
                                    $html.= '<option value="lep_'.$key.'" >'.$partner['name'].'</option>';
                                }
                                $html .='</optgroup>';
                            }
                            echo $html;
?>
                        </select>
                        <label for="table_partner_search"><?php echo $term_table_partner_filter?></label>
                    </div>
                    <?php } ?>
                    <div class="col-md-3 md-form">
                        <select id="table_country_search" name="table_country_search" class="mdb-select" searchable="Search">
                            <option value=""><?php echo $term_table_select_all_country; ?></option>
<?php
                            $html = '';
                            foreach($countryCodes as $id => $country)
                            {
                                $html.= '<option value="'.$id.'" >'.$country.'</option>';
                            }
                            echo $html;
?>
                        </select>
                        <label for="table_country_search"><?php echo $term_table_country_filter?></label>
                    </div>
                    <div class="col-md-3 md-form">
                        <select id="table_status_search" class="mdb-select">
                            <option value=""><?php echo $term_table_select_all?></option>
                            <?php
                            $html = '';
                            foreach($list_status as $key => $status)
                            {
                                $html.= '<option value="'.$status.'">'.ucwords($status).'</option>';
                            }
                            echo $html;
                            ?>
                        </select>
                        <label for="table_status_search"><?php echo $term_table_status_filter?></label>
                    </div>
                    <div class="col-md-3 md-form">
                        <select id="table_job_category_search" name="table_category_search" class="mdb-select"
                                    searchable="Search">
                            <option value=""><?php echo $term_filter_job_category; ?></option>
                            <?php foreach ($job_categories as $category) { ?>
                                <?php if ($category['parent_id'] == 0) { ?>
                                    <option value="<?php echo $category['job_speedy_category_id'] ?>"><?php echo $category['name'] ?></option>
                                        <?php foreach ($job_categories as $category2) { ?>
                                            <?php if ($category2['parent_id'] == $category['job_speedy_category_id']) { ?>
                                                <option value="<?php echo $category2['job_speedy_category_id'] ?>"> &nbsp;&nbsp;<?php echo $category2['name'] ?></option>
                                            <?php } ?>
                                        <?php } ?>

                                <?php } ?>
                            <?php } ?>
                        </select>
                        <label for="table_job_category_search"><?php echo $term_filter_job_category_label ?></label>
                    </div>
                    <div class="col-md-3 md-form">
                        <select id="table_register_method" class="mdb-select">
                            <option value=""><?php echo $term_table_register_method_all ?></option>
                            <option value="0">From Public</option>
                            <option value="-1">From Admin Inputed</option>
                        </select>
                        <label for="table_register_method"><?php echo $term_table_register_method_filter ?></label>
                    </div>
                </div>
            </div>
        <div class="table-responsive">
            
            <table id="recruitments" class="table table-sm table-striped table-bordered " cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Country</th>
                        <th>Partner</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- Summary Modal -->
        <div class="modal fade bd-example-modal-lg" id="summary_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title w-100" id="myModalLabel"><?php echo $term_recruitment_summary_title?></h4>
                    </div>
                    <div class="modal-body table-responsive">
                        <div class="row container" id="summary">
                            <p class="text-center text-warning"><?php echo $term_empty_personal_data?></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Partner Modal -->
        <div class="modal fade" id="partner_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form >
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel"><?php echo $term_title_edit_partner ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">

                    <div class="border border-rounded py-3 px-5">
                        <h5 class="text-center">License Partner</h5>  
                        <div class="form-group pt-1">
                            <select id="partner_new" name="partner_new" class="mdb-select " searchable="Search here..">
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 text-center">
                                <button class="partner_delete btn btn-sm btn-danger" data-type="lp" type="button"><?php echo $term_delete_partner_button; ?></button>
                            </div>
                            <div class="col-md-6 text-center">
                                <button class="partner_change btn btn-sm btn-info" data-type="lp" type="button"><?php echo $term_update_partner_button; ?></button>
                            </div>
                        </div>
                    </div>

                    <div class="border border-rounded mt-3 py-3 px-5">
                        <h5 class="text-center">License Education Partner</h5>  
                        <div class="form-group pt-1">
                                    <select id="partner_lep" name="partner_lep" class="mdb-select " searchable="Search here..">
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6 text-center">
                                        <button class="partner_delete btn btn-sm btn-danger" data-type="lep"  type="button"><?php echo $term_delete_partner_button; ?></button>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <button class="partner_change btn btn-sm btn-info"  data-type="lep" type="button"><?php echo $term_update_partner_button; ?></button>
                                    </div>
                                </div>
                    </div>
                       
                        
                    </div>
                    </form>
                </div>
            </div>
        </div>
	 <!-- Edit Verification Modal -->
     <div class="modal fade" id="edit_verification_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="EditModal"><?php echo $term_legend_edit ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                        <input type="hidden" id="edit_id" name="edit_id"/>
                        <!-- Verification Select Description -->
                        <div class="mt-3 text-left">
                            <select id="verification_status" name="verification_status" class="form-control mb-3">
                                <?php
                                $html = '';
                                foreach($list_status as $key => $status)
                                {
                                    $html.= '<option value="'.$status.'" >'.ucfirst($status).'</option>';
                                }
                                echo $html;
                                ?>
                            </select>
                            <div class="md-form">
                                <input type="text" id="verification_info" name="verification_info" class="form-control"/>
                                <label for="verification_info"><?php echo $term_verification_info_label?></label>
                            </div>
                        </div>
                        <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect" id="edit_verification_btn" type="button">Edit </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Verification Modal -->

    <!-- HistoryModal -->
    <div class="modal fade" id="history_verification_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo $term_legend_history ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body p-3">

                </div>
            </div>
        </div>
    </div>
    <!-- HistoryModal -->

     <!-- Premium Service Modal -->
     <div class="modal fade" id="show_premium_service_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="PremiumServiceModal"><?php echo $term_premium_title ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div clas="d-flex justify-content-between container">
                        <div class="mb-3">
                            Premium Status : <span class="p-2 badge badge-primary text-capitalize" id="premium-status">No Data</span>
                        </div>
                        <div id="premium-info" class="alert alert-primary not-showing" role="alert"></div>
                    </div>
                    <div class="d-flex justify-content-between" id="premium_file">
                        <a class="btn btn-primary" id="premium_file_show_btn" target="_blank"><i class="fa fa-eye"></i> <?php echo $term_premium_show_button?></a>
                        <a class="btn btn-success" id="premium_file_download_btn" target="_blank"><i class="fa fa-download"></i> <?php echo $term_premium_download_button?></a>
                    </div>
                    
                    <div class="mt-3 text-left" id="premium-send-form">    
                        <form id="premium_service">
                            <input type="hidden" id="address_book_id" name="address_book_id" />
                            <input type="hidden" id="job_application_id" name="job_application_id" />
                            <input type="hidden" id="status" name="status" />
                            
                            <div class="form-group">
                                <label for="type"><?php echo $term_premium_type?></label>
                                <select id="type" name="type" class="form-control mb-3">
                                    <option value="early">Early</option>
                                    <option value="late">Late</option>
                                </select>
                            </div>
                            <div class="md-form">
                                <input type="text" id="premium_email" name="premium_email" class="form-control" readonly/>
                                <label for="email"><?php echo $term_premium_email?></label>
                            </div>
                        </form>
                        <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect" id="offer_premium_service_btn" type="button"><?php echo $term_premium_button?></button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Premium Service Modal -->

</div>