<div class="row">
<div class="container col-md-8">

    <div class="row">
        <div class="col">
            <div class="card">

                <h1 class="card-header blue-gradient white-text text-center py-4">
                    <strong>Reference Check</strong>
                </h1>

                <!--Card content-->
                <div class="card-body px-lg-5 pt-0">
                    <h5 class="text-center m-3">Referee's Details</h5>
                    <table class="table border">
                        <tr>
                            <td>Company/Organisation:</td>
                            <td><?php echo $reference['entity_name']?></td>
                        </tr>
                        <tr>
                            <td>Name:</td>
                            <td><?php echo $reference['given_names'].' '.$reference['family_name']?></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><?php echo $reference['email'] ?></td>
                        </tr>
                        <tr>
                            <td>Phone:</td>
                            <td><?php echo $reference['number'] ?></td>
                        </tr>
                        
                    </table>

                    <h5 class="text-center m-3">Candidate Detail</h5>
                    <table class="table border">
                        <tr>
                            <td>Name:</td>
                            <td><?php echo $full_name?></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><?php echo $address_book_detail['main_email'] ?></td>
                        </tr>
                        <tr>
                            <td>Relation to you:</td>
                            <td><?php echo $reference['relationship'] ?></td>
                        </tr>
                    </table>

                    <h2 class="text-center my-5">Question List</h2>
                    <p class="text-center "><i>Please answer this question with honest answer.</i></p>

                    <!-- Form -->
                    <form id="reference-check" method="post" action="<?php echo $myURL; ?>">
                        <input type="hidden" name="question_type" value="<?php echo $reference_check['question_type']?>">
                        <input type="hidden" name="candidate_name" value="<?php echo $reference['given_names'].' '.$reference['family_name']?>">
                        <table class="table">
                            <?php foreach($questions as $key => $item) {?>
                            <tr>
                                <td width="40%"><?php echo $item['question'] ?></td>
                                <td>
                                    <?php if($item['answer_type'] == 'text') {?>
                                        <textarea class="form-control" name="answer[<?php echo $item['question_id']?>]" required></textarea>
                                    <?php }?>

                                    <?php if($item['answer_type'] == 'number') {?>
                                        <input class="form-control" name="answer[<?php echo $item['question_id']?>]" type="number" required>
                                    <?php }?>

                                    <?php if($item['answer_type'] == 'point') {?>

                                        <?php foreach(['No Comment','Poor','Fair','Good','Excellent'] as $i => $radio) {?>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="checkbox<?php echo $key.''.$radio ?>" value="<?php echo $radio?>" name="answer[<?php echo $item['question_id']?>]" required>
                                            <label class="custom-control-label" for="checkbox<?php echo $key.''.$radio ?>"><?php echo $radio ?></label>
                                        </div>
                                        <?php }?>
                                    <?php }?>
                                </td>
                            </tr>
                            <?php }?>
                        </table>
                        <center>
                            <button type="submit" class="btn btn-info" id="submit_button">Submit Data</button>
                        </center>
                    </form>

                </div>

            </div>
        </div>
    </div>

</div>