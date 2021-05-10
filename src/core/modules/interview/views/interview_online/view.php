<div class="container">
    <div class="card">
        <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between">
            <h4 class="text-white"><?php echo $term_header ?></h4>
        </div>
        <div class="card-body w-auto">
            <div class="container">
                <!--Grid row-->
                <div class="d-flex justify-content-between border p-1 pl-3 pr-3 mb-3">

                    <!--Grid column-->
                    <div class="mb-4">

                        <div class="md-form">
                            <!--The "from" Date Picker -->
                            <input placeholder="Selected starting date" type="text" id="startingDate" class="form-control datepicker">
                            <label for="startingDate"><?php echo $term_start?></label>
                        </div>

                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="mb-4">

                        <div class="md-form">
                            <!--The "to" Date Picker -->
                            <input placeholder="Selected ending date" type="text" id="endingDate" class="form-control datepicker">
                            <label for="endingDate"><?php echo $term_end?></label>
                        </div>

                    </div>
                    <div class="mb-4">
                        <div class="md-form">

                            <label for="interviewer_filter">Select Interviewer</label>
                            <select id="interviewer_filter" name="interviewer_filter" class="mdb-select md-form"
                                    searchable="Search here.." >
                                <option value="" >Choose Interviewer</option>
                                <?php foreach ($interviewers as $index => $user):?>
                                    <option value="<?php echo $user['address_book_id']?>"><?php echo $user['entity_family_name'] .' '.$user['number_given_name'] ?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <!--Grid column-->

                </div>
                <!--Grid row-->

                <table class="table" id="list_schedule" data-url="<?php echo $base_url?>">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Job</th>
                        <th>Schedule On</th>
                        <th>Interviewer</th>
                        <th>Partner</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="interviewer_modal" tabindex="-1" role="dialog" aria-labelledby="interviewer_modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="interviewer_form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLongTitle"><?php echo $term_list_interviewer ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="md-form">

                        <label for="address_book_id">Select Interviewer</label>
                        <select id="address_book_id" name="address_book_id" class="mdb-select md-form"
                                searchable="Search here.." >
                            <option value="" >Choose Interviewer</option>
                            <?php foreach ($interviewers as $index => $user):?>
                                <option value="<?php echo $user['address_book_id']?>"><?php echo $user['entity_family_name'] .' '.$user['number_given_name'] ?></option>
                            <?php endforeach;?>
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

<!-- Modal -->
<div class="modal fade " id="interview_result_modal" tabindex="-1" role="dialog" aria-labelledby="interview_result_modal"
     aria-hidden="true">
    <div class="modal-dialog modal-notify modal-info" role="document">
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
                </div>

            </div><!-- modal-content -->
    </div>
</div>

