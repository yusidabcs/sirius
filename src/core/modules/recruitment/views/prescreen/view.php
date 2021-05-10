<div class="container">
    <div class="card">
        <div class="card-header ">
            <h4 class="text-center"><?php echo $term_header ?></h4>

        </div>
        <div class="card-body w-auto">
            <div id="table_search" class="container">
                <div class="row">
                    <div class="col-md-3 md-form">
                        <!-- <input type="text" class="form-control" id="table_partner_search">-->
                        <select id="table_partner_search" name="table_partner_search" class="mdb-select" searchable="Search">
                            <option value=""><?php echo $term_table_select_all_partner; ?></option>
                            <?php

                            $html = '';
                            foreach($partners as $key => $partner)
                            {
                                $html.= '<option value="'.$key.'" >'.$partner['name'].'</option>';
                            }
                            echo $html;
                            ?>
                        </select>
                        <label for="table_partner_search"><?php echo $term_table_partner_filter?></label>
                    </div>
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
                <table id="prescreens" class="table table-sm table-striped table-bordered " cellspacing="0" width="100%" data-url="
                <?php echo $base_url?>">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Country</th>
                        <th>Status</th>
                        <th>Sending On</th>
                        <th>Accepted On</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!--Prescreen Modal-->
<div class="modal fade" id="pre-screening-interview" tabindex="-1" role="dialog"
     aria-labelledby="pre-screening-interviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-notify modal-info">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 text-center">
                <h4 class="modal-title w-100 text-white" id="myModalLabel"><?php echo $term_prescreen_form_header ?>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">
                <div id="pre-screen-modal-body"></div>
                <div class="row">
                    <div class="col-md-12 table-choose-principal">
                        <div class="pt-3 pb-3 align-items-center border-top peach-gradient text-white p-3">
                            <div class="pl-3">
                                <h5>Principal</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3 pt-4 text-center">
                                <div class="form-group">
                                    <label class="control-label">Please choose Principal</label>
                                    <select name="principal" id="principal" class="form-control">
                                        <option value="">Select Principal</option>
                                        <?php foreach($principals as $principal): ?>
                                            <option value="<?php echo $principal['code'] ?>"><?php echo $principal['code'] ?> - <?php echo $principal['entity_family_name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- <table class="table table-choose-principal">
                            <tr>
                                <td width="60%">Principal</td>
                                <td>
                                    <select name="principal" id="principal" class="form-control">
                                        <option value="">Select Principal</option>
                                        <?php foreach($principals as $principal): ?>
                                            <option value="<?php echo $principal['code'] ?>"><?php echo $principal['code'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                            </tr>
                        </table> -->
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button class="btn btn-info" id="update-interview-btn"> Accept candidate and Update status to interview!</button>
            </div>
        </div>
    </div>
</div>
<!--End Prescreen Modal-->