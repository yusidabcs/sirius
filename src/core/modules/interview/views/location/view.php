<div class="container">
    <div class="card">
        <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between">
            <h4 class="text-white"><?php echo $term_header ?></h4>
            <a href="<?php echo $base_url?>/create" class="btn btn-sm btn-success float-right"> <i class="fa fa-plus"></i> <?php echo $term_create_new ?></a>
        </div>
        <div class="card-body w-auto">
            <div class="container">
                <!--Grid row-->
                <div class="row">

                    <!--Grid column-->
                    <div class="col-md-6 mb-4">

                        <div class="md-form">
                            <!--The "from" Date Picker -->
                            <input placeholder="Selected starting date" type="text" id="startingDate" class="form-control datepicker">
                            <label for="startingDate"><?php echo $term_start?></label>
                        </div>

                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-md-6 mb-4">

                        <div class="md-form">
                            <!--The "to" Date Picker -->
                            <input placeholder="Selected ending date" type="text" id="endingDate" class="form-control datepicker">
                            <label for="endingDate"><?php echo $term_end?></label>
                        </div>

                    </div>
                    <!--Grid column-->

                </div>
                <!--Grid row-->

                <table class="table" id="list_location" data-url="<?php echo $base_url?>">
                    <thead>
                    <tr>
                        <th>Period</th>
                        <th>Location</th>
                        <th>Organizer</th>
                        <th>Total Candidate</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>