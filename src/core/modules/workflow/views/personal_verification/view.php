<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-center"><?php echo $term_header ?></h4>

        </div>
        <div class="card-body w-auto">

            <div class="row">
                <div class="col-md-3 md-form">
                    <label for="table_status_search"><?php echo $term_table_select_status ?></label>
                    <select id="table_status_search" name="table_partner_search" class="mdb-select"
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
                    <label for="table_level_search"><?php echo $term_table_select_level ?></label>
                    <select id="table_level_search" name="table_partner_search" class="mdb-select"
                            searchable="Search">
                        <option value=""><?php echo $term_table_select_level; ?></option>
                        <?php

                        $html = '';
                        foreach ($level as $index => $item) {
                            $html .= '<option value="' . $index . '" >' . ucfirst($item) . '</option>';
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

            <table class="table" id="list_personal_verification">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Level</th>
                    <th>Created On</th>
                    <th>Family Name</th>
                    <th>Given Name</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

