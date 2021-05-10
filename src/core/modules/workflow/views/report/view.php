<div class="container">
    <div class="card">
        <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between">
            <h4 class="text-white"><?php echo $term_header ?></h4>
            <a href="#" class="btn btn-sm btn-success float-right btn-create-security"> <i class="fa fa-plus"></i> <?php echo $term_create_new ?></a>
        </div>
        <div class="card-body w-auto">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 ">
                    <form class="form-inline form-inline d-flex justify-content-center">
                        <div class="md-form">
                            <label for="level_type">Filter Level</label>
                            <select name="level_type" id="level_type" class=" mdb-select">
                                <option value="">All Level</option>
                                <option value="team">Team</option>
                                <option value="management">Management</option>
                                <option value="supervisor">Supervisor</option>
                            </select>
                        </div>
                        <div class="md-form ml-2 ">
                            <label for="tracker">Filter Tracker</label>
                            <select name="tracker" id="tracker" class="mdb-select">
                                 <option value="">All Tracker</option>
                                <?php foreach($trackers as $item) {?>
                                <option value="<?php echo $item ?>"><?php echo $item ?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="md-form ml-2 ">
                            <label for="partner">Filter Partner</label>
                            <select name="partner" id="partner" class="mdb-select">
                                 <option value="">All Partner</option>
                                <?php foreach($partners as $item) {?>
                                <option value="<?php echo $item['id'] ?>"><?php echo $item['name'] ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </form>
                    </div>
                </div>
                <hr>
                <table class="table" id="list_interview_security_report" data-url="<?php echo $base_url?>">
                    <thead>
                    <tr>
                        <th><?php echo $term_tr_name ?></th>
                        <th><?php echo $term_tr_level ?></th>
                        <th><?php echo $term_tr_workflow ?></th>
                        <th><?php echo $term_tr_partner ?></th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="interview_security_modal" tabindex="-1" role="dialog" aria-labelledby="interview_security_modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="interview_security_form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLongTitle"><?php echo $term_create_report ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="md-form">
                        <label for="select_partner">Select Partner (Optional)</label>
                        <select class="mdb-select md-form" name="select_partner" id="select_partner" searchable="Search">
                            <option value="" selected>Choose Partner</option>
                            <?php foreach($partners as $key => $item) { ?>
                                <option value="<?php echo $item['id']?>"><?php echo ucfirst($item['name']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <!-- <div class="md-form">
                        <div class="float-right mr-4">
                            <div id="search_ab_spinner" class="not-showing spinner-border position-absolute" role="status" aria-hidden="true"></div>
                        </div>
                        <input type="text" class="form-control" name="" id="search_ab">
                        <label for="search_ab"><?php echo $term_address_book_search?></label>
                        <div class="invalid-feedback">
                            <p class="alert alert-warning"><?php echo $term_address_book_email_not_found?></p>
                        </div>
                    </div> -->

                    <div class="" id="div_ab">
                        <div class="md-form">
                            <label for="address_book_id"><?php echo $term_label_address_book?></label>
                            <select class="mdb-select md-form" id="address_book_id" name="address_book_id" searchable="Search" required>
                                
                            </select>
                        </div>
                    </div>

                    <div class="md-form">
                        <label for="level"><?php echo $term_label_level?></label>
                        <select class="mdb-select md-form" name="level" id="level" searchable="Search" required>
                            <option value="" disabled selected>Choose Level</option>
                            <?php foreach($levels as $key => $item) { ?>
                                <option value="<?php echo $item?>"><?php echo ucfirst($item) ?></option>
                            <?php } ?>
                        </select>
                    </div>


                    <div class="md-form">
                        <label for="workflow_tracker">Report for tracker</label>
                        <select class="mdb-select md-form" id="workflow_tracker" name="workflow_tracker[]" multiple>
                            <option value="" disabled selected>Choose workflow</option>
                            <?php foreach($trackers as $key => $item) { ?>
                                <option value="<?php echo $item?>"><?php echo ucfirst($item) ?></option>
                            <?php } ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Save</button>
                </div>
            </div><!-- modal-content -->
        </form>
    </div>
</div>


<div class="modal fade" id="interview_security_update_modal" tabindex="-1" role="dialog" aria-labelledby="interview_security_update_modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="interview_security_update_form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="modal-title">Update Reports</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="address_book_id" id="address_book_id_update" >
                    <div class="md-form">
                        <label for="level_update"><?php echo $term_label_level?></label>
                        <select class="mdb-select md-form" name="level" id="level_update" searchable="Search" required>
                            <option value="" disabled selected>Choose Level</option>
                            <?php foreach($levels as $key => $item) { ?>
                                <option value="<?php echo $item?>"><?php echo ucfirst($item) ?></option>
                            <?php } ?>
                        </select>
                    </div>


                    <div class="md-form">
                        <label for="workflow_tracker_update">Report for tracker</label>
                        <select class="mdb-select md-form" id="workflow_tracker_update" name="workflow_tracker[]" multiple>
                            <option value="" disabled selected>Choose workflow</option>
                            <?php foreach($trackers as $key => $item) { ?>
                                <option value="<?php echo $item?>"><?php echo ucfirst($item) ?></option>
                            <?php } ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Save</button>
                </div>
            </div><!-- modal-content -->
        </form>
    </div>
</div>