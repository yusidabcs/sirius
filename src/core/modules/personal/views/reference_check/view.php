<?php
if (isset($errors) && is_array($errors)) {
    ?>
    <div class="iow-callout iow-callout-warning">
        <h2 class="text-warning"><?php echo $term_error_legend; ?></h2>
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

<div class="row">
    <div class="col-12 col-md-12">

        <!-- Main Card -->
        <div class="card mb-4">

            <h3 class="card-header blue-gradient white-text text-center py-4">
                Reference Check
                <a href="<?php echo $back_url?>" class="btn btn-sm btn-info float-right"><i class="fa fa-arrow-left"></i> Back to Reference</a>
            </h3>

            <div class="card-body">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <td>Full Name</td>
                            <td>Country</td>
                            <td>Phone</td>
                            <td>Skype</td>
                            <td>Requested on</td>
                            <td>Completed on</td>
                            <td>Confirmed on</td>
                            <td>Rejected on</td>
                            <td>Status</td>
                            <td>Option</td>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if(count($reference_check_list) === 0): ?>
                        <tr>
                            <td id="personal_fullname">
                                <?php echo $reference['fullname']; ?><br/>
                                <strong style="font-weight: bold"><?php echo $reference['email']; ?></strong> <br>
                                <strong style="font-weight: bold">Relationship: <?php echo $reference['relationship']; ?></strong>
                            </td>
                            <td id="personal_phone"><?php echo $reference['country']; ?></td>
                            <td id="personal_phone">+<?php echo $reference['phone_number']; ?></td>
                            <td id="personal_skype"><?php echo $reference['skype']; ?></td>
                            <td>
                                -
                            </td>
                            <td>
                                -
                            </td>
                            <td>
                                -
                            </td>
                            <td>
                                -
                            </td>
                            <td id="personal_relationship">
                                -
                            </td>
                            <td id="option">
                                -
                            </td>
                        </tr>
                    <?php endif ?>
                    
                    <?php foreach($reference_check_list as $key => $rf): ?>
                    <tr>
                        <td id="personal_fullname">
                            <?php echo $reference['fullname']; ?><br/>
                            <strong style="font-weight: bold"><?php echo $reference['email']; ?></strong> <br>
                            <strong style="font-weight: bold">Relationship: <?php echo $reference['relationship']; ?></strong>
                        </td>
                        <td id="personal_phone"><?php echo $reference['country']; ?></td>
                        <td id="personal_phone">+<?php echo $reference['phone_number']; ?></td>
                        <td id="personal_skype"><?php echo $reference['skype']; ?></td>
                            <td>
                        <?php if($rf['requested_on'] !== '0000-00-00 00:00:00' && !empty($rf['user_requested'])): ?>
                                <?php echo date('M d, Y h:i:s A', strtotime($rf['requested_on'])) ?> <br>
                                By: <?php echo $rf['ab_requested'] ?>
                            <?php else: echo '-' ?>
                        <?php endif ?>
                            </td>
                            <td>
                        <?php if($rf['completed_on'] !== '0000-00-00 00:00:00' && !empty($rf['user_completed'])): ?>
                                <?php echo date('M d, Y h:i:s A', strtotime($rf['completed_on'])) ?> <br>
                                By: <?php echo $rf['ab_completed'] ?>
                            <?php else: echo '-' ?>
                        <?php endif ?>
                            </td>
                            <td>
                        <?php if($rf['confirmed_on'] !== '0000-00-00 00:00:00' && !empty($rf['user_confirmed'])): ?>
                                <?php echo date('M d, Y h:i:s A', strtotime($rf['confirmed_on'])) ?> <br>
                                By: <?php echo $rf['ab_confirmed'] ?>
                            <?php else: echo '-' ?>
                        <?php endif ?>
                            </td>
                            <td>
                        <?php if($rf['rejected_on'] !== '0000-00-00 00:00:00' && !empty($rf['user_rejected'])): ?>
                                <?php echo date('M d, Y h:i:s A', strtotime($rf['rejected_on'])) ?> <br>
                                By: <?php echo $rf['ab_rejected'] ?>
                            <?php else: echo '-' ?>
                        <?php endif ?>
                            </td>
                        <td id="personal_relationship">
                            <label class="badge <?php echo $rf['status'] == 'sending' ? 'badge-warning' : ($rf['status'] == 'completed' ? 'btn-info' : ($rf['status'] == 'confirmed' ? 'btn-success' : 'badge-warning')) ?>"><?php echo $rf['status'] ?? 'Pending' ?></label>
                        </td>
                        <td id="option">
                        <?php if($rf || $rf['status'] === 'rejected'): ?>
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                data-target="#personal_reference_check_<?php echo $rf['id'] ?>">
                                    <i class="fa fa-search-plus"></i> Check Summary
                                </button>

                                <div class="modal fade"
                                    id="personal_reference_check_<?php echo $rf['id'] ?>"
                                    tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg modal-notify modal-info" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header text-center">
                                                <h4 class="modal-title white-text" id="EditModal">Reference Check
                                                    Summary</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close"><span
                                                            aria-hidden="true">&times;</span></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="border p-3 m-3">
                                                    <table class="table">
                                                        <tr>
                                                            <td width="25%">Contact method:</td>
                                                            <td>
                                                                <?php echo ucfirst($rf['contact_method']) ?>
                                                                <?php if ($rf['contact_method'] == 'email' && ($rf['status'] == 'pending' || $rf['status'] === 'request')) { ?>
                                                                    <button class="btn btn-sm btn-info float-right"
                                                                            id="resend_link"
                                                                            data-id="<?php echo $rf['reference_id'] ?>">
                                                                        <i class="fas fa-paper-plane"></i> Resend the link
                                                                    </button>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Created on:</td>
                                                            <td><?php echo $rf['created_on'] ?>
                                                                by <?php echo $rf['ab_created'] ?></td>
                                                        </tr>
                                                    </table>
                                                </div>

                                                <?php if($rf['status'] === 'completed' || $rf['status'] === 'rejected' || $rf['status'] === 'confirmed'): ?>
                                                <h4 class="text-center">Question List</h4>
                                                <div class="border p-3 m-3">
                                                    <table class="table">
                                                        <?php foreach ($questions as $key => $item) { ?>
                                                            <tr>
                                                                <td width="50%"><?php echo $item['question'] ?></td>
                                                                <td>
                                                                    <?php echo $answers[$rf['id']][$item['question_id']]['answer'] ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </table>
                                                </div>
                                                <?php endif ?>

                                                <?php if($rf['status'] === 'completed') {?>
                                                    <center>
                                                        <button type="button" class="btn btn-info" data-reference-type="<?php echo $rf['question_type']; ?>" id="confirm_reference" data-id="<?php echo $rf['reference_id']?>">Confirm</button>
                                                        <button type="button" class="btn btn-danger" data-reference-type="<?php echo $rf['question_type']; ?>" id="reject_reference" data-id="<?php echo $rf['reference_id']?>">Reject</button>
                                                    </center>
                                                <?php } ?>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>

                <div class="text-center">
                <?php if($able_to_reference_check && ($reference_check['status'] === 'pending' || $reference_check['status'] === 'rejected' || $reference_check['status'] === 'request' || !$reference_check)): ?>
                    <button type="button" class="btn btn-success btn-sm validate-reference"
                    data-id="<?php echo $reference['reference_id'] ?>"
                    data-method="phone"><i class="fa fa-phone"></i>
                        Validate By Phone
                    </button>

                    <?php if ($reference_check['status'] === 'pending' || $reference_check['status'] === 'rejected' || !$reference_check) { ?>
                    <button type="button" class="btn btn-info btn-sm validate-reference"
                            data-id="<?php echo $reference['reference_id'] ?>"
                            data-method="email">
                        <i class="fa fa-envelope"></i>
                        Validate By Email
                    </button>
                    <?php } ?>
                <?php endif ?>

                    </div>
                </div>

                <!--Modal Reference Check-->
                <div class="modal fade" id="reference_check_phone" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="EditModal">Validate by Phone</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <p>You need fill this form while call the reference with the selected
                                    questions</p>

                                <div class="border p-3">
                                    <form action="" id="reference_form_phone">

                                        <input type="hidden" name="reference_id"
                                               value="<?php echo $reference['reference_id'] ?>">
                                        <input type="hidden" name="contact_method" value="phone">
                                        <input type="hidden" name="question_type" value="<?php echo $reference['type']?>">
                                        <table class="table" id="question_placeholder">
                                            <?php foreach ($questions as $key => $question) { ?>
                                                <tr>
                                                    <td width="50%"><?php echo $question['question'] ?></td>
                                                    <td>
                                                        <?php if ($question['answer_type'] == 'text') { ?>
                                                            <textarea
                                                                    name="answer[<?php echo $question['question_id'] ?>]"
                                                                    class="form-control" max="255" required></textarea>
                                                        <?php } else if ($question['answer_type'] == 'point') { ?>

                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input"
                                                                       id="checkbox1<?php echo $key.''.$question['question_id']?>"
                                                                       value="No Comment"
                                                                       name="answer[<?php echo $question['question_id']?>]" required>
                                                                <label class="custom-control-label"
                                                                       for="checkbox1<?php echo $key.''.$question['question_id']?>">No Comment</label>
                                                            </div>

                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input"
                                                                       id="checkbox2<?php echo $key.''.$question['question_id']?>"
                                                                       value="Poor"
                                                                       name="answer[<?php echo $question['question_id']?>]" required>
                                                                <label class="custom-control-label"
                                                                       for="checkbox2<?php echo $key.''.$question['question_id']?>">Poor</label>
                                                            </div>

                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input"
                                                                       id="checkbox3<?php echo $key.''.$question['question_id']?>"
                                                                       value="Fair"
                                                                       name="answer[<?php echo $question['question_id']?>]" required>
                                                                <label class="custom-control-label"
                                                                       for="checkbox3<?php echo $key.''.$question['question_id']?>">Fair</label>
                                                            </div>

                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input"
                                                                       id="checkbox4<?php echo $key.''.$question['question_id']?>"
                                                                       value="Good"
                                                                       name="answer[<?php echo $question['question_id']?>]" required>
                                                                <label class="custom-control-label"
                                                                       for="checkbox4<?php echo $key.''.$question['question_id']?>">Good</label>
                                                            </div>

                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input"
                                                                       id="checkbox5<?php echo $key.''.$question['question_id']?>"
                                                                       value="Excellent"
                                                                       name="answer[<?php echo $question['question_id']?>]" required>
                                                                <label class="custom-control-label"
                                                                       for="checkbox5<?php echo $key.''.$question['question_id']?>">Excellent</label>
                                                            </div>

                                                        <?php } ?>

                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                        <hr>
                                        <center>
                                            <button type="submit" class="btn btn-info">Save</button>
                                        </center>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="reference_check_email" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="EditModal">Send reference check form by email</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">

                                <div class="border p-3">
                                    <form action="" id="reference_form_email">

                                        <div class="form-group">
                                            <label for="question_group_name">Reference Email</label>
                                            <input type="email" class="form-control"
                                                   value="<?php echo $reference['email'] ?>" readonly>
                                        </div>
                                        <input type="hidden" name="question_type" value="<?php echo $reference['type']?>">
                                        <input type="hidden" name="reference_id"
                                               value="<?php echo $reference['reference_id'] ?>">
                                        <input type="hidden" name="contact_method" value="email">

                                        <p>An email contain reference form will be sent to this email. Once the person
                                            complete the form, the reference check will complete.</p>
                                        <center>
                                            <button type="submit" class="btn btn-info">Submit</button>
                                        </center>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
