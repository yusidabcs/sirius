<div class="card">
	<div class="card-header gradient-card-header blue-gradient">
		<h4 class="text-white text-center"><?php echo $term_page_header ?></h4>
	</div>
	<div class="card-body table-responsive">

        <div id="table_search" class="container">
            <div class="row">
            <?php if($entity == false) { ?>
                <div class="col-md-4 md-form">
                    <select id="table_partner_search" name="table_partner_search" class="mdb-select" searchable="Search">
                        <option value=""><?php echo $term_table_select_all_partner; ?></option>
                        <?php

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
                <div class="col-md-4 md-form">
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
                        <select id="table_register_method" class="mdb-select">
                            <option value=""><?php echo $term_table_register_method_all ?></option>
                            <option value="0">From Public</option>
                            <option value="-1">From Admin Inputed</option>
                        </select>
                        <label for="table_register_method"><?php echo $term_table_register_method_filter ?></label>
                    </div>
            </div>
        </div>

        <table id="list_verification" class="table table-sm table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Country</th>
                <th>Partner</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>


        <div class="modal fade bd-example-modal-lg" id="summary_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title w-100" id="myModalLabel"><?php echo $term_recruitment_summary_title?></h4>
                    </div>
                    <div class="modal-body">
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
                        <ul class="nav nav-tabs" id="tab-partner" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="lp-tab" data-toggle="tab" href="#lp" role="tab" aria-controls="lp"
                                aria-selected="true">License Partner</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="lep-tab" data-toggle="tab" href="#lep" role="tab" aria-controls="lep"
                                aria-selected="false">License Education Partner</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="lp" role="tabpanel" aria-labelledby="lp">
                                <!-- tab license partner -LP -->
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
                            <div class="tab-pane fade" id="lep" role="tabpanel" aria-labelledby="lep">
                                <!-- tab license Education partner -LEP -->
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

                        
                    </div>
                    </form>
                </div>
            </div>
        </div>
          <!-- EditModal -->
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
                                    <div class="form-group">
                                        <label for="verification_info"
                                        class="control-label"><?php echo $term_verification_info_label?></label>
                                        <textarea id="verification_info" name="verification_info" maxlength="255" class="form-control" placeholder="Enter message..." required></textarea>
                                        <span id="charactersRemaining"></span>
                                    </div>
                                </div>
                                <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect" id="edit_verification_btn" type="button">Edit </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- EditModal -->

            <!-- HistoryModal -->
            <div class="modal fade" id="history_verification_modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="HistoryModal"><?php echo $term_legend_history ?></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">

                        </div>
                    </div>
                </div>
            </div>
            <!-- HistoryModal -->
	</div>
</div>