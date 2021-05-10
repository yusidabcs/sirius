<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gradient-card-header blue-gradient">
            <h4 class="text-white text-center"><?php echo $term_header ?></h4>
            <div>
                <a id="update_all_status" href="#" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> <?php echo $term_update_all_request ?></a>
                <a id="epxort_request" href="#" class="btn btn-warning btn-sm"><i class="fas fa-file-excel"></i> <?php echo $term_export_request ?></a>
            </div>
        </div>
        <div class="card-body w-auto">

            <div class="row">
                <div class="col-md-3 md-form">
                    <label for="table_status_search"><?php echo $term_table_select_status ?></label>
                    <select id="table_status_search" class="mdb-select"
                            searchable="Search">
                        <option value=""><?php echo $term_table_select_status; ?></option>
                        <?php

                        $html = '';
                        foreach ($status as $index => $item) {
                            $html .= '<option value="' . $item . '" >' . ucwords(str_replace('_',' ', $item)) . '</option>';
                        }
                        echo $html;
                        ?>
                    </select>
                </div>

                <div class="col-md-3 md-form">
                    <label for="table_lp_search">Filter by LP/LEP</label>
                    <select id="table_lp_search" class="mdb-select"
                            searchable="Search">
                        <option value="">All Partner</option>
                        <option value="" disabled>License Partner</option>
                        <?php

                        $html = '';
                        foreach ($partners as $index => $item) {
                            $html .= '<option value="' . $item['id'] . '" >' . ucwords(str_replace('_',' ', $item['name'])) . '</option>';
                        }

                        $html .= '<option value="" disabled>License Education Partner</option>';

                        foreach ($partners_lep as $index => $item) {
                            $html .= '<option value="' . $item['id'] . '" >' . ucwords(str_replace('_',' ', $item['name'])) . '</option>';
                        }
                        echo $html;
                        ?>
                    </select>
                </div>

                <div class="col-md-3 md-form">
                        <!--The "from" Date Picker -->
                    <input placeholder="Selected starting date" type="text" id="startingDate" class="form-control datepicker">
                    <label for="startingDate">Filter Start Date</label>
                </div>

                <div class="col-md-3 md-form">
                <input placeholder="Selected starting date" type="text" id="endingDate" class="form-control datepicker">
                    <label for="endingDate">Filter End Date</label>
                </div>
            </div>

            <table class="table" id="list_education_course_request">
                <thead>
                <tr>
                    <th><div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input " id="chk_all" name="chk_all">
                            <label class="custom-control-label" for="chk_all">All</label>
                        </div></th>
                    <th>Name</th>
                    <th>Partner</th>
                    <th>Course</th>
                    <th>Status</th>
                    <th>Request On</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

