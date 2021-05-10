<div class="container">
    <div class="card">
        <div class="card-header card-info gradient-card-header blue-gradient">
            <h4 class="text-center text-white"><?php echo $term_header ?></h4>

        </div>
        <div class="card-body w-auto">
            <?php
            if (isset($errors) && is_array($errors)) {
                ?>
                <div class="iow-callout iow-callout-warning">
                    <h2 class="text-warning"><?php echo $term_error_legend ?></h2>
                    <?php
                    foreach ($errors as $key => $value) {
                        $tname = 'term_' . $key . '_label';
                        $title = isset($$tname) ? $$tname : $key;
                        echo "				<p class=\"text-warning\"><strong>{$title}</strong> {$value}</p>\n";
                    }
                    ?>
                </div>

                <?php
            }
            ?>

            <form method="post" id="prescreen_answer" action="<?php echo $myURL ?>">

                <div class="card-body">
                    <h4 class="text-center">Candidate Detail</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="info lighten-2">
                            <tr>
                                <td width="60%"><?php echo $term_prescreen_header_1 ?></td>
                                <td width="40%" id="header_lp_name"><?php echo $applicant['partner_name'] ?></td>
                            </tr>
                            <tr>
                                <td width="60%"><?php echo $term_prescreen_header_2 ?></td>
                                <td width="40%"
                                    id="header_lp_prescreener"><?php echo $applicant['prescreener_full_name'] ?></td>
                            </tr>
                            <tr>
                                <td width="60%"><?php echo $term_prescreen_header_3 ?></td>
                                <td width="40%" id="header_lp_interview_date"><?php echo date('d M Y'); ?></td>
                            </tr>
                            <tr>
                                <td width="60%"><?php echo $term_prescreen_header_4 ?></td>
                                <td width="40%" id="header_full_name">
                                    <?php echo $applicant['full_name'] ?><br>
                                    <?php echo $applicant['email'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="60%"><?php echo $term_prescreen_header_5 ?></td>
                                <td width="40%" id="header_position"><?php echo $applicant['job_position'] ?></td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <hr>
                    <div class=" table-personal-checklist">
                        <div class="row header text-center mb-3 ">
                            <div class="col-md-8"><b><?php echo $term_checklist_question_heading; ?></b></div>
                            <div class="col-md-4 d-none d-md-block"><b><?php echo $term_checklist_answer_heading; ?></b>
                            </div>
                        </div>
                        <input type="hidden" id="job_application_id" name="job_application_id"
                               value="<?php echo $job_application['job_application_id'] ?>">
                        <input type="hidden" id="type" name="type" value="<?php echo $type ?>">
                        <input type="hidden" name="redirect_to" value="<?php echo $redirect_to ?>">
                        <?php
                        foreach ($questions as $key => $value) {
                            ?>
                            <div class="question_row pt-3 pb-3 align-items-center border-top <?php echo $value['type'] == 'heading' ? 'peach-gradient text-white p-3' : '' ?>">
                                <div class="row">
                                    <div class="col-md-7">

                                        <?php if ($value['type'] == 'heading') { ?>
                                            <h5><?php echo $value['question'] ?></h5>
                                        <?php } else { ?>
                                            <?php if ($value['parent_id'] != 0) { ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php } ?>
                                            <?php echo $value['question'] ?>
                                            <a href="#" data-toggle="tooltip" title="<?php echo $value['help'] ?>"
                                               data-placement="right"><i class="fas fa-info-circle"
                                                                         aria-hidden="true"></i></a>
                                        <?php } ?>

                                    </div>
                                    <div class="col-md-5">
                                        <?php
                                        switch ($value['type']) {
                                            case "tf":
                                                ?>
                                                <input type="hidden"
                                                       name="<?php echo $checklist_type; ?>[<?php echo $value['question_id'] ?>][question_text]"
                                                       value="<?php echo $value['question'] ?>">
                                                <input type="hidden"
                                                       name="<?php echo $checklist_type; ?>[<?php echo $value['question_id'] ?>][more]"
                                                       value="<?php echo $value['more'] ?>">
                                                <input type="hidden"
                                                       name="<?php echo $checklist_type; ?>[<?php echo $value['question_id'] ?>][child]"
                                                       value="<?php echo $value['childs'] ? '1' : 0 ?>">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="iow-ck-button">
                                                            <label><input type="radio"
                                                                       class="parent_radio"
                                                                       name="<?php echo $checklist_type; ?>[<?php echo $value['question_id'] ?>][answer]"
                                                                       value="yes"
                                                                       hidden="hidden" <?php echo isset($answers[$value['question_id']]) ? $answers[$value['question_id']]['answer'] == 'yes' ? 'checked' : '' : '' ?>>
                                                                <span id="yes_<?php echo $value['question_id'] ?>"
                                                                      class="yes true_false <?php echo  isset($answers[$value['question_id']]) ?  ($answers[$value['question_id']]['answer'] == 'yes' && ($value['more'] == '1') ) ? 'bg-warning' : ($answers[$value['question_id']]['answer'] == 'yes' && ($value['more'] == '0')) ? 'bg-success' : '' : '' ?>"
                                                                      data-id="<?php echo $value['question_id'] ?>"
                                                                      data-more="<?php echo $value['more'] ?>"
                                                                      data-value="1"
                                                                      data-child="<?php echo $value['childs'] ? 1 : 0 ?>"><?php echo $term_answer_button_yes; ?></span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-6">
                                                        <div class="iow-ck-button">
                                                            <label>
                                                                <input type="radio"
                                                                       name="<?php echo $checklist_type; ?>[<?php echo $value['question_id'] ?>][answer]"
                                                                       class="parent_radio"
                                                                       value="no"
                                                                       hidden="hidden" <?php echo isset($answers[$value['question_id']]) ? ($answers[$value['question_id']]['answer'] == 'no') ? 'checked' : '' : '' ?>>
                                                                <span id="no_<?php echo $value['question_id'] ?>"
                                                                      class="no true_false <?php echo isset($answers[$value['question_id']]) ? ($answers[$value['question_id']]['answer'] == 'no' && $value['more'] == 0) ? 'bg-warning' : ($answers[$value['question_id']]['answer'] == 'no' && ($value['more'] == '1')) ? 'bg-success' : '' : '' ?>"
                                                                      data-id="<?php echo $value['question_id'] ?>"
                                                                      data-more="<?php echo $value['more'] ?>"
                                                                      data-value="0"
                                                                      data-child="<?php echo $value['childs'] ? 1 : 0 ?>"><?php echo $term_answer_button_no; ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (!$value['childs']) { ?>
                                                <div class="form-group not-showing"
                                                     id="more_<?php echo $value['question_id'] ?>">
                                                    <label for="comment"><?php echo $term_answer_more_info; ?></label>
                                                    <textarea class="form-control" rows="5"
                                                              name="<?php echo $checklist_type; ?>[<?php echo $value['question_id'] ?>][text]"><?php echo(isset($answers[$value['question_id']]) ? $answers[$value['question_id']]['text'] : '') ?></textarea>
                                                </div>
                                            <?php } ?>
                                                <?php
                                                break;

                                            case "sa":
                                                ?>
                                                <input type="hidden"
                                                       name="<?php echo $checklist_type; ?>[<?php echo $value['question_id'] ?>][question_text]"
                                                       value="<?php echo $value['question'] ?>">
                                                <textarea class="form-control"
                                                          name="<?php echo $checklist_type; ?>[<?php echo $value['question_id'] ?>][text]"
                                                          required><?php echo(isset($answers[$value['question_id']]) ? $answers[$value['question_id']]['text'] : '') ?></textarea>


                                                <?php
                                                break;

                                            default:
                                                ?>


                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <?php if ($value['childs']) { ?>
                                <div class="grey lighten-5" id="child_<?php echo $value['question_id'] ?>">
                                    <?php foreach ($value['childs'] as $child) { ?>

                                        <div class="question_row  <?php echo (isset($answers[$value['question_id']]) && $answers[$value['question_id']]['answer'] == ($child['show_child'] == 1 ? 'yes' : 'no')) ? '' : 'not-showing' ?>  child_<?php echo $child['parent_id'] ?>_<?php echo $child['show_child'] ?> pt-3 pb-3 align-items-center border-top <?php echo $child['type'] == 'heading' ? 'peach-gradient text-white p-3' : '' ?>">
                                            <div class="row">
                                                <div class="col-md-7 ">
                                                    <div class="pl-3">
                                                        <?php if ($child['type'] == 'heading') { ?>
                                                            <h5><?php echo $child['question'] ?></h5>
                                                        <?php } else { ?>
                                                            <?php echo $child['sequence'] ?>. <?php echo $child['question'] ?>
                                                            <a href="#" data-toggle="tooltip"
                                                               title="<?php echo $child['help'] ?>"
                                                               data-placement="right"><i class="fas fa-info-circle"
                                                                                         aria-hidden="true"></i></a>
                                                        <?php } ?>
                                                    </div>

                                                </div>
                                                <div class="col-md-5">
                                                    <?php
                                                    switch ($child['type']) {
                                                        case "tf":
                                                            ?>
                                                            <input type="hidden"
                                                                   name="<?php echo $checklist_type; ?>[<?php echo $child['question_id'] ?>][question_text]"
                                                                   value="<?php echo $child['question'] ?>">
                                                            <input type="hidden"
                                                                   name="<?php echo $checklist_type; ?>[<?php echo $child['question_id'] ?>][more]"
                                                                   value="<?php echo $child['more'] ?>">
                                                            <input type="hidden"
                                                                   name="<?php echo $checklist_type; ?>[<?php echo $child['question_id'] ?>][parent_id]"
                                                                   value="<?php echo $child['parent_id'] ?>">
                                                            <input type="hidden"
                                                                   name="<?php echo $checklist_type; ?>[<?php echo $child['question_id'] ?>][show_child]"
                                                                   value="<?php echo $child['show_child'] ?>">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="iow-ck-button">
                                                                        <label>
                                                                            <input type="radio"
                                                                                   name="<?php echo $checklist_type; ?>[<?php echo $child['question_id'] ?>][answer]"
                                                                                   value="yes"
                                                                                   hidden="hidden" <?php echo (isset($answers[$child['question_id']]) && $answers[$child['question_id']]['answer'] == 'yes') ? 'checked' : '' ?>>
                                                                            <span id="yes_<?php echo $child['question_id'] ?>"
                                                                                  class="yes true_false"
                                                                                  data-id="<?php echo $child['question_id'] ?>"
                                                                                  data-more="<?php echo $child['more'] ?>"
                                                                                  data-value="1"><?php echo $term_answer_button_yes; ?></span>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-6">
                                                                    <div class="iow-ck-button">
                                                                        <label>
                                                                            <input type="radio"
                                                                                   name="<?php echo $checklist_type; ?>[<?php echo $child['question_id'] ?>][answer]"
                                                                                   value="no"
                                                                                   hidden="hidden" <?php echo (isset($answers[$child['question_id']]) && $answers[$child['question_id']]['answer'] == 'no') ? 'checked' : '' ?>>
                                                                            <span id="no_<?php echo $child['question_id'] ?>"
                                                                                  class="no true_false"
                                                                                  data-id="<?php echo $child['question_id'] ?>"
                                                                                  data-more="<?php echo $child['more'] ?>"
                                                                                  data-value="0"><?php echo $term_answer_button_no; ?></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group not-showing"
                                                                 id="more_<?php echo $child['question_id'] ?>">
                                                                <label for="comment"><?php echo $term_answer_more_info; ?></label>
                                                                <textarea class="form-control" rows="5"
                                                                          name="<?php echo $checklist_type; ?>[<?php echo $child['question_id'] ?>][text]"><?php echo(isset($answers[$child['question_id']]) ? $answers[$child['question_id']]['text'] : '') ?></textarea>
                                                            </div>

                                                            <?php
                                                            break;

                                                        case "sa":
                                                            ?>
                                                            <input type="hidden"
                                                                   name="<?php echo $checklist_type; ?>[<?php echo $child['question_id'] ?>][question_text]"
                                                                   value="<?php echo $child['question'] ?>">
                                                            <input type="hidden"
                                                                   name="<?php echo $checklist_type; ?>[<?php echo $child['question_id'] ?>][parent_id]"
                                                                   value="<?php echo $child['parent_id'] ?>">
                                                            <input type="hidden"
                                                                   name="<?php echo $checklist_type; ?>[<?php echo $child['question_id'] ?>][show_child]"
                                                                   value="<?php echo $child['show_child'] ?>">
                                                            <textarea class="form-control child_sa"
                                                                      name="<?php echo $checklist_type; ?>[<?php echo $child['question_id'] ?>][text]"
                                                                      ><?php echo(isset($answers[$child['question_id']]) ? $answers[$child['question_id']]['text'] : '') ?></textarea>


                                                            <?php
                                                            break;

                                                        default:
                                                            ?>


                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>

                        <?php } ?>

                    </div>

                    <div class="card-footer text-center mt-5">
                        <a href="<?php echo $back_url ?>" class="btn btn-warning" id="go_back" role="button"><i
                                    class="fas fa-thumbs-down"></i> <?php echo $term_go_back; ?></a>
                        &nbsp;
                        <button type="submit" class="btn btn-primary"><i
                                    class="fas fa-thumbs-up"></i> <?php echo $term_save_checklist; ?></button>
                    </div>
                </div>

            </form>


        </div>
    </div>
</div>

<!-- Pre Screening Interview Modal -->
<div class="modal fade" id="pre-screening-interview" tabindex="-1" role="dialog"
     aria-labelledby="pre-screening-interviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-notify modal-info">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 text-center">
                <h4 class="modal-title w-100 text-white" id="myModalLabel"><?php echo $term_prescreen_form_header ?>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body " id="pre-screen-modal-body">

            </div>
            <div class="modal-footer d-flex justify-content-center">
                <?php if($can_by_pass == 1): ?>
                <div class="form-check form-check-inline" data-children-count="1">
                    <input class="form-check-input" id="by_pass" name="by_pass" type="checkbox" value="1">
                    <label class="form-check-label" for="by_pass">By Pass Candidate Proses</label>
                </div>
                <?php endif ?>

                <p class="border lead p-3"><span class="text-warning">IMPORTANT!</span> Send the pre screen interview
                    result to candidate.The candidat must accept the result if already correct or can give revision
                    again if the result not correct.
                    <br>
                    The candidate cannot move to interview process if the prescreen not complete.
                </p>
                <button type="button" class="btn btn-success"
                        id="ps-send-btn"><i class="fas fa-paper-plane"></i> <?php echo $term_prescreen_interview_btn ?></button>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Pre Screening Interview Modal -->