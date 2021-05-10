<div class="container">
    <div class="card">
        <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between">
            <h4 class="text-white"><?php echo $term_header ?></h4>
        </div>
        <div class="card-body w-auto">
            <div class="container">
                <!--Grid row-->
                <div class="row">

                    <!--Grid column-->
                    <div class="col-md-4">

                        <div class="md-form">
                            <!--The "from" Date Picker -->
                            <input placeholder="Start From" type="text" id="startingDate" class="form-control datepicker">
                            <label for="startingDate"><?php echo $term_start?></label>
                        </div>

                        <div class="md-form">
                            <!--The "to" Date Picker -->
                            <input placeholder="End to" type="text" id="endingDate" class="form-control datepicker">
                            <label for="endingDate"><?php echo $term_end?></label>
                        </div>

                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-md-4">

                        <div class="md-form">

                            <label for="organizer"><?php echo $term_organizer?></label>
                            <select id="organizer" class="mdb-select md-form">
                                <option value=""  selected>All</option>
                                <?php foreach($partners as $key => $organizer) { ?>
                                    <option value="<?php echo  $organizer['address_book_id'] ?>"><?php echo  $organizer['entity_family_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="md-form">

                            <label for="job"><?php echo $term_job?></label>
                            <select id="job" class="mdb-select md-form" searchable="Search job..">
                                <option value=""  selected>All</option>
                                <?php foreach($jobs as $key => $job) { ?>
                                    <option value="<?php echo  $job['job_speedy_code'] ?>"><?php echo  $job['job_speedy_code'] ?> <?php echo  $job['job_title'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php 
                            if($filter_principal) {
                        ?>
                        <div class="md-form">

                            <label for="principal"><?php echo $term_principal?></label>
                            <select id="principal" name="principal" class="mdb-select md-form" searchable="Search Principal..">
                                <option value=""  selected>All</option>
                                <?php foreach($principals as $key => $principal) { ?>
                                    <option value="<?php echo  $principal['address_book_id'] ?>"><?php echo  $principal['principal_fullname'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php
                            }
                        ?>

                    </div>

                    <div class="col-md-4">

                        <div class="md-form">

                            <label for="type"><?php echo $term_type?></label>
                            <select id="type" class="mdb-select md-form">
                                <option value=""  selected>All</option>
                                <option value="online">Online</option>
                                <option value="physical">Physical</option>
                            </select>
                        </div>

                        <div class="md-form">

                            <label for="status"><?php echo $term_status?></label>
                            <select id="status" class="mdb-select md-form">
                                <option value=""  selected>All</option>
                                <option value="hired">Hired</option>
                                <option value="not_hired">Not Hired</option>
                                <option value="allocated">Allocated</option>
                            </select>
                        </div>

                    </div>

                </div>
                <!--Grid row-->
                <br>
                <table class="table" id="list_interview_principal" data-url="<?php echo $base_url?>">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Job Title</th>
                        <th>Organizer</th>
                        <th>Principal</th>
                        <th>Result</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="interview_result_modal" tabindex="-1" role="dialog" aria-labelledby="interview_result_modal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-notify modal-info" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title text-white"
                    id="exampleModalLongTitle"><?php echo $term_interview_result ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <center><h5>Question & Answer</h5></center>
                <table class="table" id="interview_answer">

                </table>
                <center><h5>Interview Result</h5></center>
                <table class="table" id="interview_result">

                </table>
                <div class="text-center">
                    <button data-id='' id="btn_export_modal" type="button" class="btn btn-warning" data-dismiss="modal"><i class="fas fa-file-pdf"></i> Export to PDF</button>
                </div>
            </div>
            
        </div><!-- modal-content -->
    </div>
</div>