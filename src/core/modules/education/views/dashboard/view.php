<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gradient-card-header blue-gradient">
            <h4 class="text-white text-center"><?php echo $term_header ?></h4>
            <div>
                <a id="export_tracker" href="javascript:;" class="btn btn-warning btn-sm"><i class="fas fa-file-excel"></i> <?php echo $term_export_tracker ?></a>
            </div>
        </div>
        <div class="card-body w-auto">

            <div class="row">
                <div class="col-md-4 md-form">
                    <label for="table_period_search"><?php echo $term_table_select_period ?></label>
                    <select id="table_period_search" class="mdb-select">
                        <?php

                        $html = '<option value="">All</option>';
                        foreach ($period as $index => $item) {
                            $html .= '<option value="' . $item . '" >' . ucwords(str_replace('_',' ', $item)) . '</option>';
                        }
                        echo $html;
                        ?>
                    </select>
                </div>
                <div class="col-md-4 md-form">
                        <!--The "from" Date Picker -->
                    <input placeholder="Start Date" type="text" id="startingDate" class="form-control datepicker">
                    <label for="startingDate">Filter Start Date</label>
                </div>

                <div class="col-md-4 md-form">
                <input placeholder="End Date" type="text" id="endingDate" class="form-control datepicker">
                    <label for="endingDate">Filter End Date</label>
                </div>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#education_tracker" role="tab" aria-controls="education_tracker"
                    aria-selected="true">Education Tracker</a>
                </li>

            </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="education_tracker" role="tabpanel" aria-labelledby="tracker-tab">
                        <div class="row">
                            <div class="col-md-3">
                                <div id="count_tracker" class="justify-content-between border">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">All Level <span class="badge badge-pill badge-success" id="all_level">0</span></li>
                                        <li class="list-group-item">Normal <span class="badge badge-pill badge-success" id="normal">0</span></li>
                                        <li class="list-group-item">Soft Warning <span class="badge badge-pill badge-warning" id="soft_warning">0</span></li>
                                        <li class="list-group-item">Hard Warning <span class="badge badge-pill badge-warning" id="hard_warning">0</span></li>
                                        <li class="list-group-item">Deadline <span class="badge badge-pill badge-danger" id="deadline">0</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <table width="100%" class="table" id="list_education_tracker">
                                    <thead>
                                    <tr>
                                        <th width='5%'>No</th>
                                        <th width='20%'>Name</th>
                                        <th width='25%'>Course</th>
                                        <th width='15%'>Status</th>
                                        <th width='15%'>Level</th>
                                        <th width='20%'>Created On</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <a href="/education/request" class="btn btn-sm btn-primary  waves-effect waves-light">View Details &gt;&gt;</a>
                </div>
        </div> <!-- end card body -->
    </div>
</div>


