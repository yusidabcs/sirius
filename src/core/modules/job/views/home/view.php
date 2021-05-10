<section app="">
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-center"><?php echo $term_local_job_header ?></h4>
                <div>
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                            data-target="#importJobModal"><?php echo $term_import_job ?></button>
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                            data-target="#addJobModal"><?php echo $term_add_job ?></button>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                            data-target="#importJobDemandModal"> <?php echo $term_import_job_demand ?></button>
                </div>

            </div>

            <!-- Modal -->
            <div class="modal fade" id="importJobModal" tabindex="-1" role="dialog" aria-labelledby="exportJobModal"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="importjobmaster" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $term_import_job ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $term_input_job_master_file?></label>
                                    <input type="file" name="file" id="job_master_file" accept=".xls, .xlsx" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="import_job_master">Update</button>
                            </div>
                        </div><!-- modal-content -->
                    </form>
                </div>
            </div>

            <!-- Add Job Master Modal -->
            <div class="modal fade" id="addJobModal" tabindex="-1" role="dialog" aria-labelledby="exportJobModal"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <form id="addjobmaster" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $term_add_job ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="jobTitle"><?php echo $term_job_title ?></label>
                                            <input type="text" class="form-control" name="job_title" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="md-form">
                                            <select id="principalCode" name="principal_code" class="mdb-select md-form" searchable="Search here.." required>
                                                <option value="" selected><?php echo $term_select_principal ?></option>
                                                <?php foreach ($principal as $key => $value) {
                                                    ?>
                                                    <option value="<?php echo $value['code']; ?>"><?php echo $value['code']; ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                            <label for="principalCode"><?php echo $term_input_principal_code ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="md-form">
                                            <select id="brandCode" name="brand_code" class="mdb-select md-form" searchable="Search here..">
                                                <option value="" selected><?php echo $term_select_brand_code ?></option>
                                            </select>
                                            <label for="brandCode"><?php echo $term_input_brand_code ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jobCode"><?php echo $term_input_job_code ?></label>
                                            <input type="text" name="job_code" id="jobCode" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="costCenter"><?php echo $term_input_cost_code ?></label>
                                            <input type="text" name="cost_center" id="costCenter" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="md-form">
                                            <select id="select_job_speedy" name="job_speedy_code" class="mdb-select md-form" searchable="Search here..">
                                                <option value="" selected><?php echo $term_select_job_speedy?></option>
                                                <?php foreach ($job_speedy as $key => $value) {
                                                    ?>
                                                    <option value="<?php echo $value['job_speedy_code']; ?>"><?php echo $value['job_speedy_code']; ?>
                                                        - <?php echo $value['job_title']; ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                            <label class="mdb-main-label"><?php echo $term_select_job_speedy ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="minimumSalary"><?php echo $term_minimum_salary ?></label>
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">USD</span>
                                            </div>
                                            <input type="number" id="minimumSalary" name="minimum_salary" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="mediumSalary"><?php echo $term_medium_salary ?></label>
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">USD</span>
                                            </div>
                                            <input type="number" id="mediumSalary" name="mid_salary" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="maximumSalary"><?php echo $term_maximum_salary ?></label>
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">USD</span>
                                            </div>
                                            <input type="number" id="maximumSalary" name="max_salary" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="add_job_master">Save</button>
                            </div>
                        </div><!-- modal-content -->
                    </form>
                </div>
            </div>

            <!-- Demand Modal -->
            <div class="modal fade" id="importJobDemandModal" tabindex="-1" role="dialog"
                 aria-labelledby="importJobDemandModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="importjobdemand" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $term_import_job_demand?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="month" class="mdb-main-label"><?php echo $term_select_month ?></label>
                                    <select id="month" name="month" class="custom-select custom-select-sm form-control form-control-sm">
                                        <option value="" disabled>Choose Month</option>
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="year" class="mdb-main-label"><?php echo $term_select_year ?></label>
                                    <select id="year" name="year" class="custom-select custom-select-sm form-control form-control-sm">
                                        <option value="<?php echo date("Y"); ?>" selected><?php echo date("Y"); ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="job_demand_file"><?php echo $term_input_job_demand_file?></label>
                                    <input type="file" name="file" id="job_demand_file" accept=".xls, .xlsx" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="expire_on" class="mdb-main-label"><?php echo $term_expire_on ?></label>
                                    <input placeholder="Select date" type="text" id="expire_on" name="expiry_on" class="form-control datepicker">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="import_job_demand">Import</button>
                            </div>
                        </div><!-- modal content -->
                    </form>
                </div>
            </div>

            <!-- Udate Job Modal -->
            <div class="modal fade" id="edit_job_master_modal" tabindex="-1" role="dialog"
                 aria-labelledby="importJobDemandModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="edit_job_form" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $term_edit_job ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="job_code" id="job_code">
                                <input type="hidden" name="multi_select" id="multi_select">
                                
                                <div class="md-form">
                                    <label for="job_speedy_code" class="mdb-main-label"><?php echo $term_select_job_speedy ?></label>
                                    <select id="job_speedy_code" name="job_speedy_code" class="mdb-select" searchable="Search here..">>
                                        <option value=""><?php echo $term_select_job_speedy?></option>

                                        <?php foreach ($job_speedy as $key => $value) {
                                            ?>
                                            <option value="<?php echo $value['job_speedy_code']; ?>"><?php echo $value['job_speedy_code']; ?>
                                                - <?php echo $value['job_title']; ?></option>
                                            <?php
                                        } ?>

                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="save_job">Save</button>
                            </div>
                        </div><!-- modal content -->
                    </form>
                </div>
            </div>

            <div class="modal fade" id="edit_job_master_modal_single" tabindex="-1" role="dialog"
                 aria-labelledby="importJobDemandModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="edit_job_form_single" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $term_edit_job ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <input type="hidden" name="multi_select" id="multi_select">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="jobTitleEdit"><?php echo $term_job_title ?></label>
                                            <input type="text" class="form-control" name="job_title" id="jobTitleEdit" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="md-form">
                                            <select id="principalCodeEdit" name="principal_code" class="mdb-select md-form" searchable="Search here.." required>
                                                <option value="" selected><?php echo $term_select_principal ?></option>
                                                <?php foreach ($principal as $key => $value) {
                                                    ?>
                                                    <option value="<?php echo $value['code']; ?>"><?php echo $value['code']; ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                            <label for="principalCodeEdit"><?php echo $term_input_principal_code ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="md-form">
                                            <select id="brandCodeEdit" name="brand_code" class="mdb-select md-form" searchable="Search here..">
                                                <option value="" selected><?php echo $term_select_brand_code ?></option>
                                            </select>
                                            <label for="brandCodeEdit"><?php echo $term_input_brand_code ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jobCodeEdit"><?php echo $term_input_job_code ?></label>
                                            <input type="text" name="job_code" id="jobCodeEdit" class="form-control" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="costCenterEdit"><?php echo $term_input_cost_code ?></label>
                                            <input type="text" name="cost_center" id="costCenterEdit" class="form-control" maxlength="3" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                    
                                        <div class="md-form">
                                            <label for="jobSpeedyCodeEdit" class="mdb-main-label"><?php echo $term_select_job_speedy ?></label>
                                            <select id="jobSpeedyCodeEdit" name="job_speedy_code" class="mdb-select" searchable="Search here..">>
                                                <option value=""><?php echo $term_select_job_speedy?></option>

                                                <?php foreach ($job_speedy as $key => $value) {
                                                    ?>
                                                    <option value="<?php echo $value['job_speedy_code']; ?>"><?php echo $value['job_speedy_code']; ?>
                                                        - <?php echo $value['job_title']; ?></option>
                                                    <?php
                                                } ?>

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="minimumSalaryEdit"><?php echo $term_minimum_salary ?></label>
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">USD</span>
                                            </div>
                                            <input type="number" id="minimumSalaryEdit" name="minimum_salary" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="mediumSalaryEdit"><?php echo $term_medium_salary ?></label>
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">USD</span>
                                            </div>
                                            <input type="number" id="mediumSalaryEdit" name="mid_salary" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="maximumSalaryEdit"><?php echo $term_maximum_salary ?></label>
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">USD</span>
                                            </div>
                                            <input type="number" id="maximumSalaryEdit" name="max_salary" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="save_job_single">Save</button>
                            </div>
                        </div><!-- modal content -->
                    </form>
                </div>
            </div>
            <!-- Demand List Modal -->
            <div class="modal fade" id="job_master_demand_modal" tabindex="-1" role="dialog"
                 aria-labelledby="job_master_demand_modal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $term_list_demand ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <table class="table" id="demand_table">
                                <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Demand</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div><!-- modal content -->
                </div>
            </div>

            <!-- Card content -->
            <div class="card-body table-responsive">
                <div id="job-checked" class="row border p-3 m-0 mb-5">

                    <div class="col-md-3">
                        <div class="md-form">
                            <select id="table_job_speedy_search" class="mdb-select md-form" searchable="Search here..">
                                <option value="" selected><?php echo $term_select_job_speedy?></option>
                                <?php foreach ($job_speedy as $key => $value) {
                                    ?>
                                    <option value="<?php echo $value['job_speedy_code']; ?>"><?php echo $value['job_speedy_code']; ?>
                                        - <?php echo $value['job_title']; ?></option>
                                    <?php
                                } ?>
                            </select>
                            <label class="mdb-main-label"><?php echo $term_filter_job_speedy ?></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="md-form">
                            <select id="filterPrincipalCode" class="mdb-select md-form" searchable="Search here.." required>
                                <option value="" selected><?php echo $term_select_principal ?></option>
                                <?php foreach ($principal as $key => $value) {
                                    ?>
                                    <option value="<?php echo $value['code']; ?>"><?php echo $value['code']; ?></option>
                                    <?php
                                } ?>
                            </select>
                            <label for="filterPrincipalCode" class="mdb-main-label"><?php echo $term_filter_principal ?></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="filterBrandCode" class="mdb-select md-form" searchable="Search here..">
                            <option value="" selected><?php echo $term_select_brand_code ?></option>
                        </select>
                        <label for="filterBrandCode" class="mdb-main-label"><?php echo $term_filter_brand ?></label>
                    </div>
                    <div class="col-md-3 d-flex align-items-center justify-content-end">
                        <button id="update_selected_btn"
                                class="btn btn-sm btn-success not-showing"><?php echo $term_btn_update_selected ?></button>
                    </div>
                </div>
                <table id="list_job" class="table" cellspacing="0" style="width: 100%">
                    <thead>
                    <tr>
                        <th class="th-sm text-center">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="cb_select_all">
                                <label class="custom-control-label" for="cb_select_all"></label>
                            </div>
                        </th>
                        <th class="th-sm">Brand</th>
                        <th class="th-sm">Job Code</th>
                        <th class="th-sm"></th><!-- will not be displayed, for searched only -->
                        <th class="th-sm">Description</th>
                        <th class="th-sm">Salary</th>
                        <th class="th-xs" width="50">Demand</th>
                        <th class="th-sm"></th>
                    </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</section>