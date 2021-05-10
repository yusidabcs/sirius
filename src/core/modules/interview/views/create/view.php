<div class="container">
    <div class="card">
        <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between">
            <h4 class="text-white"><?php echo $term_header ?></h4>
        </div>
        <div class="card-body w-auto">
            <div class="card-body">
                <!-- Form -->
                <form id="form_schedule_create" style="color: #757575;" method="POST" action="" enctype="multipart/form-data">

                    <div class="md-form">
                        <label for="title"><?php echo $term_title ?></label>
                        <input type="text" class="form-control" id="title" name="interview_title" required>
                    </div>

                    <div class="md-form">
                        <label for="description"><?php echo $term_description ?></label>
                        <textarea class="form-control" id="description" name="interview_description"></textarea>
                    </div>

                    <!-- Name -->
                    <?php if($show_partner) { ?>
                    <div class="md-form">
                        <label for="organizer_id"><?php echo $term_select_partner ?></label>
                        <select id="organizer_id" name="organizer_id" class="mdb-select md-form" required>
                            <option value="">Select one</option>
                            <?php foreach ($partners['data'] as $partner) {?>
                                <option value="<?php echo $partner['address_book_id']?>"><?php echo $partner['entity_family_name']?></option>
                            <?php }?>
                        </select>
                    </div>
                    <?php } ?>
                    
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="border border-info px-3 pt-3">
                                    <label for="startingDate"><?php echo $term_start_on ?></label>
                                    <div class="row">
                                        <div class="col">
                                            <div class="md-form">
                                                <input placeholder="Select date" type="text" id="start_on_date" name="start_on_date" class="form-control datepicker" required>
                                                <label for="start_on_date">Date Start</label>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="md-form">
                                                <input placeholder="Select Time" type="text" id="start_on_time" name="start_on_time" class="form-control timepicker" required>
                                                <label for="start_on_time">Time Start</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border border-info  px-3 pt-3">
                                    <label for="FinishDate"><?php echo $term_finish_on ?></label>
                                    <div class="row">
                                        <div class="col">
                                            <div class="md-form">
                                                <input placeholder="Select date" type="text" id="finish_on_date" name="finish_on_date" class="form-control datepicker" required>
                                                <label for="finish_on_date">Date Finish</label>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="md-form">
                                                <input placeholder="Select Time" type="text" id="finish_on_time" name="finish_on_time" class="form-control timepicker" required>
                                                <label for="finish_on_time">Time Finish</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div>

                    <!--<div class="row">
                        <div class="col">
                            <div class="md-form">

                                <input placeholder="Date and Time" id="start_on" name="start_on" type="text" data-open="picker2" class="form-control date-time picker-opener" required>
                                <input placeholder="Selected date" type="text" id="picker2"  class="form-control time-date-ghost">
                                <input placeholder="Selected time" data-open="picker2" type="text" class="form-control timepicker time-date-ghost">

                                <label for="startingDate"><?php echo $term_start_on ?></label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="md-form">
                                
                                <input placeholder="Date and Time" id="finish_on" name="finish_on" type="text" data-open="picker1" class="form-control date-time picker-opener" required>
                                <input placeholder="Selected date" type="text" id="picker1"  class="form-control time-date-ghost">
                                <input placeholder="Selected time" data-open="picker1" type="text" class="form-control timepicker time-date-ghost">
                                <label for="finish_on"><?php echo $term_finish_on ?></label>
                            </div>
                        </div>
                    </div>
                    -->

                    <div class="row">
                        <div class="col">
                            <div class="md-form">
                                <label for="countryCode_id"><?php echo $term_select_country ?></label>
                                <select id="countryCode_id" name="countryCode_id" class="mdb-select md-form" required searchable="Search country..">
                                    <option value="">Select one</option>
                                    <?php foreach ($countries as $index => $country) {?>
                                        <option value="<?php echo $index?>"><?php echo $country?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="md-form">
                                <label for="countrySubCode_id"><?php echo $term_select_state ?></label>
                                <select id="countrySubCode_id" name="countrySubCode_id" class="mdb-select md-form" required searchable="Search state..">
                                    <option value="">Select one</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="md-form">
                        <textarea id="address" name="address" class="form-control" required></textarea>
                        <label for="address"><?php echo $term_address ?></label>
                    </div>

                    <div class="md-form">
                        <textarea id="google_map" name="google_map" class="form-control"></textarea>
                        <label for="google_map"><?php echo $term_google_map ?></label>
                    </div>

                    <div class="md-form">
                        <?php echo $term_status ?>
                        <!-- Material unchecked -->
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="materialUnchecked" name="status" value="1" checked>
                            <label class="form-check-label" for="materialUnchecked">Active</label>
                        </div>

                        <!-- Material checked -->
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="materialChecked" name="status" value="0" >
                            <label class="form-check-label" for="materialChecked">Not Active</label>
                        </div>
                    </div>

                    <div class="md-form">
                        <?php echo $term_visible ?>
                        <!-- Material unchecked -->
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="visible1" name="visible" value="1" checked>
                            <label class="form-check-label" for="visible1">Visible</label>
                        </div>

                        <!-- Material checked -->
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="visible0" name="visible" value="0" >
                            <label class="form-check-label" for="visible0">Not Visible</label>
                        </div>
                    </div>

                    <!-- Send button -->
                    <div class="d-flex justify-content-center">
                        <a href="<?php echo $back_link ?>" class="btn back-btn btn-warning btn-rounded waves-effect" id="back_link"><?php echo $term_back_btn?></a>
                        <button class="btn btn-info btn-rounded z-depth-0 waves-effect btn-partner" type="submit"><?php echo $term_save_btn?></button>
                    </div>

                </form>
                <!-- Form -->

            </div>
        </div>
    </div>
</div>