<div class="container">
    <div class="card">
        <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between">
            <h4 class="text-white"><?php echo $term_header ?></h4>
            <?php if ($schedule['type'] == 'physical') { ?>
                <a href="<?php echo $base_url ?>/detail_location/<?php echo $schedule['interview_location_id'] ?>"
                   class="btn btn-sm btn-success float-right btn-back"> <i class="fa fa-arrow-left"></i> Back</a>
            <?php }
            if ($schedule['type'] == 'online') { ?>
                <a href="<?php echo $base_url ?>/interview_online" class="btn btn-sm btn-success float-right btn-back">
                    <i class="fa fa-arrow-left"></i> Back</a>
            <?php } ?>

        </div>
        <div class="card-body w-auto">
            <div class="container">
                <?php if ($schedule['type'] == 'physical') {
                    ?>
                    <!--physical-->
                    <div class="border mb-5 p-3 grey lighten-5">
                        <div class="row">

                            <!--Grid column-->
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td>Organizer</td>
                                        <td>:</td>
                                        <td><?php echo $location['organizer'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Interviewer</td>
                                        <td>:</td>
                                        <td><?php echo $interviewer['title'] . ' ' . $interviewer['entity_family_name'] . ' ' . $interviewer['number_given_name'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Location</td>
                                        <td>:</td>
                                        <td>
                                            <?php echo $location['country'] ?> - <?php echo $location['subcountry'] ?>
                                            <br>
                                            <?php echo $location['address'] ?> <br>
                                            <?php echo $location['google_map'] ?>
                                        </td>
                                    </tr>


                                </table>

                            </div>
                            <!--Grid column-->
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td>Start</td>
                                        <td>:</td>
                                        <td><?php echo date('d M Y H:i', strtotime($location['start_on'])) ?></td>
                                    </tr>

                                    <tr>
                                        <td>Finish</td>
                                        <td>:</td>
                                        <td><?php echo date('d M Y H:i', strtotime($location['finish_on'])) ?></td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                    </div>
                    <!--end physical-->
                <?php } elseif ($schedule['type'] == 'online') { ?>
                    <!--physical-->
                    <div class="border mb-5 p-3 grey lighten-5">
                        <div class="row">

                            <!--Grid column-->
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td>Interviewer</td>
                                        <td>:</td>
                                        <td><?php echo $interviewer['title'] . ' ' . $interviewer['entity_family_name'] . ' ' . $interviewer['number_given_name'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Schedule On</td>
                                        <td>:</td>
                                        <td>
                                            <?php echo $schedule['schedule_on'] ?> - <?php echo $schedule['timezone'] ?>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Google Meet Code</td>
                                        <td>:</td>
                                        <td>
                                            <?php echo $schedule['google_meet_code'] ?> <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Type</td>
                                        <td>:</td>
                                        <td>
                                            <?php echo $schedule['type'] ?><br>
                                        </td>
                                    </tr>


                                </table>

                            </div>
                            <!--Grid column-->
                        </div>
                    </div>
                    <!--end physical-->
                <?php } ?>
                <div class="border grey lighten-5 p-3">

                    <h4 class="mt-3 mb-3 text-center">Candidate Detail</h4>
                    <table class="table">
                        <tr>
                            <td>Candidate Name</td>
                            <td>:</td>
                            <td><?php echo $address_book['title'] ?> <?php echo $address_book['entity_family_name'] ?> <?php echo $address_book['number_given_name'] ?> <?php echo $address_book['middle_names'] ?> <a href="<?php echo $personal_link;?>"> <span class="badge badge-info">See Detail</span></a>
                                <br>
                                (<?php echo $address_book['main_email'] ?>)
                            </td>
                        </tr>
                        <tr>
                            <td>Position Interviewed For</td>
                            <td>:</td>
                            <td><?php echo $job_application['job_speedy_code'] ?>
                                - <?php echo $job_application['job_title'] ?></td>
                        </tr>
                    </table>
                </div>
                <form action="" id="interview_form">


                    <div class="border p-5 mt-5 mb-5 ">

                        <h4 class="text-center mb-3">General Question</h4>
                        <ul class="list-group list-group-flush" id="general-questions">
                            <?php foreach ($general_question as $index => $question) { ?>
                                <li class="list-group-item">
                                    <span><?php echo $index + 1 ?></span>. <?php echo $question['question'] ?> <small><a href="javascript:;" data-id="<?php echo $question['question_id'] ?>" class="link_delete_question text-danger">Delete Question?</a></small>
                                    <textarea name="answer[<?php echo $question['question_id'] ?>]['general']" id="<?php echo $question['question_id'] ?>"
                                              class="form-control mt-3" required></textarea>
                                </li>
                            <?php } ?>
                        </ul>
                        <center><button type="button" class="btn btn-info" id="other-general-question" data-job="<?php echo $job_application['job_speedy_code'] ?>" data-type="general">Add Another Question</button></center>
                        <!-- Modal -->
                        <div class="modal fade" id="add-general-questions" tabindex="-1" role="dialog" aria-labelledby="add-general-questions" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add General Question</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div id="general-questions-select">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="other_question" id="exampleRadios2" value="000">
                                        <label class="form-check-label" for="exampleRadios2">
                                            New Question
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group hide" id="new_general_question_form" style="display:none">
                                    <hr>
                                    <label for="">Question</label>
                                    <textarea class="form-control" name="new_general_question" id="new_general_question"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="save_question_btn">Add Question</button>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="border p-5 ">

                        <h5 class="text-center mb-3">Job Specific Question</h5>
                        <ul class="list-group list-group-flush" id="specific-questions">
                            <?php foreach ($specific_question as $index => $question) { ?>
                                <li class="list-group-item">
                                    <span><?php echo $index + 1 ?></span>. <?php echo $question['question'] ?> <small><a href="javascript:;" data-id="<?php echo $question['question_id'] ?>" class="link_delete_question text-danger">Delete Question?</a></small>
                                    <textarea name="answer[<?php echo $question['question_id'] ?>]['specific']"  id="<?php echo $question['question_id'] ?>"
                                              class="form-control mt-3" required></textarea>
                                </li>
                            <?php } ?>
                        </ul>
                        <center><button type="button" class="btn btn-info" id="other-specific-question" data-job="<?php echo $job_application['job_speedy_code'] ?>" data-type="specific" >Add Question</button></center>

                        <div class="modal fade" id="add-specific-questions" tabindex="-1" role="dialog" aria-labelledby="add-specific-questions" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Specific Question</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <div id="specific-questions-select">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="other_specific_question" id="other_specific_question" value="000">
                                            <label class="form-check-label" for="other_specific_question">
                                                New Question
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group hide" id="new_specific_question_form" style="display:none">
                                        <hr>
                                        <label for="">Question</label>
                                        <textarea class="form-control" name="new_specific_question" id="new_specific_question"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="save_specific_question_btn">Add Question</button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border p-5 mt-3 ">

                        <center><p class="text-center">Communication Level Skill</p></center>
                        <div class="d-flex justify-content-center align-items-center">
                            <?php foreach ($status as $index => $status) { ?>
                                <!-- Material inline 1 -->
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="status<?php echo $index ?>"
                                           value="<?php echo $status ?>" name="communication_level_skill" required>
                                    <label class="form-check-label"
                                           for="status<?php echo $index ?>"><?php echo $status ?></label>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="md-form">
                            <label for="interview_comment">Interview Comment & Notes</label>
                            <textarea name="interview_comment" id="interview_comment"
                                      class="form-control mt-3"></textarea>
                        </div>


                        <div class=" border border-info my-3 p-5" >
                            <label for="interview_comment">Change Job Speedy</label>
                            <div class="md-form mt-0">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="change_job_speedy"
                                           id="change_job_speedy1" value="1">
                                    <label class="form-check-label" for="change_job_speedy1"> Yes, Change!</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="change_job_speedy"
                                           id="change_job_speedy2" value="0" checked>
                                    <label class="form-check-label" for="change_job_speedy2">No</label>
                                </div>
                            </div>
                            <div class="row not-showing" id="change_job_speedy_place">
                                <div class="col-md-12">
                                    <div class="p-5">
                                        <label for="job_speedy_code">Please select job</label>

                                        <select id="job_speedy_code" name="job_speedy_code" class="mdb-select"
                                                searchable="Search">
                                            <option value="">Please select job</option>
                                            <?php foreach ($similar_job_speedy as $key => $job) {?>
                                                <option value="<?php echo $job['job_speedy_code'] ?>"><?php echo $job['job_speedy_code'] ?> - <?php echo $job['job_title'] ?></option>
                                            <?php }?>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border border-danger my-3 p-5">
                            <label for="interview_comment">Need Job Recomendation?</label>
                            <div class="md-form mt-0">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="job_master_prefer"
                                           id="job_master_prefer1" value="1">
                                    <label class="form-check-label" for="job_master_prefer1"> Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="job_master_prefer"
                                           id="job_master_prefer2" value="0" checked>
                                    <label class="form-check-label" for="job_master_prefer2">No</label>
                                </div>
                            </div>

                            <div class="row not-showing" id="job_master_prefer_place">
                                <div class="col-md-12">
                                    <div class="p-5">
                                        <div class="md-form">
                                            <label for="job_master_id">Please select master job</label>
                                            <select id="job_master_id" name="job_master_id" class="mdb-select"
                                                    searchable="Search">
                                                <option value="">Please select job</option>
                                                <?php foreach ($job_master as $key => $job) {?>
                                                    <option value="<?php echo $job['job_master_id'] ?>"><?php echo $job['principal_code'] ?> <?php echo $job['brand_code'] ?> - <?php echo $job['job_code'] ?> - <?php echo $job['job_title'] ?></option>
                                                <?php }?>

                                            </select>

                                        </div>

                                        <div class="md-form mt-0">

                                            <p>Who want this recommendation? </p>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="prefer_type"
                                                       id="prefer_type" value="interviewer">
                                                <label class="form-check-label" for="prefer_type"> Interviewer</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="prefer_type"
                                                       id="prefer_type2" value="candidate" checked>
                                                <label class="form-check-label" for="prefer_type2">Candidate</label>
                                            </div>
                                        </div>

                                        <div class="md-form mt-0">

                                            <p>The candidate only accept job for job master recomendation? </p>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="fixed"
                                                       id="fixed1" value="1">
                                                <label class="form-check-label" for="fixed1"> Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="fixed"
                                                       id="fixed2" value="0" checked>
                                                <label class="form-check-label" for="fixed2">No, accept other job if available</label>
                                            </div>
                                        </div>

                                        <div class="md-form">
                                            <label for="interview_comment">What the reason?</label>
                                            <textarea name="reason" id="reason"
                                                      class="form-control mt-3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-center align-items-center">
                            <input type="hidden" name="job_application_id"
                                   value="<?php echo $job_application['job_application_id'] ?>">
                            <input type="hidden" name="schedule_id" value="<?php echo $schedule['schedule_id'] ?>">
                            <input type="hidden" name="interviewer_id"
                                   value="<?php echo $interviewer['address_book_id'] ?>">
                            <input type="hidden" name="type" value="<?php echo $schedule['type'] ?>">

                            <input type="submit" class="btn btn-info btn-submit" name="submit" value="hire">
                            <input type="submit" class="btn btn-warning btn-submit" name="submit" value="not hire">
                            <input type="submit" class="btn btn-secondary btn-submit" name="submit" value="pending">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>