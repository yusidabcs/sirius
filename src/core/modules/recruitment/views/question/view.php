<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-center"><?php echo $term_question_header ?></h4>

            <div>
                <a href="#" class="btn btn-success btn-sm add_new_question"><i
                            class="fa fa-plus"></i> <?php echo $term_create_question ?></a>

                <!--<a href="#" class="btn btn-info btn-sm "
                       id="import_job_category_btn"><i class="fa fa-file-excel"></i> <?php /*echo $term_import_job */?></a>-->
            </div>


            <!-- Modal -->
            <div class="modal fade" id="new_question_modal" tabindex="-1" role="dialog" aria-labelledby="new_question_modal"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="new_question_form" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"
                                    id="exampleModalLongTitle"><?php echo $term_create_question ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $term_input_type ?></label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="heading">Heading</option>
                                        <option value="tf">Yes / No</option>
                                        <option value="sa">Short Answer</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $term_input_parent ?></label>
                                    <select name="parent_id" id="parent_id" class="form-control" required>
                                        <option value="0">No Parent</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $term_input_question ?></label>
                                    <textarea name="question" id="question"
                                              class="form-control" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $term_input_relevance ?></label>
                                    <select name="relevance" id="type" class="form-control" required>
                                        <option value="both">Both</option>
                                        <option value="sea">Sea</option>
                                        <option value="land">Land</option>
                                    </select>
                                </div>
                                <div class="form-group not-showing" id="more_div">
                                    <label for="exampleInputEmail1"><?php echo $term_input_more ?></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="more" id="exampleRadios1" value="1" checked>
                                        <label class="form-check-label" for="exampleRadios1">
                                            Yes Answer
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="more" id="exampleRadios2" value="0">
                                        <label class="form-check-label" for="exampleRadios2">
                                            No Answer
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group not-showing" id="show_child_div">
                                    <label for="exampleInputEmail1"><?php echo $term_show_child ?></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="show_child" id="show_child1" value="1" checked>
                                        <label class="form-check-label" for="show_child1">
                                            Yes Answer
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="show_child" id="show_child2" value="0">
                                        <label class="form-check-label" for="show_child2">
                                            No Answer
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $term_input_help ?></label>
                                    <textarea name="help" id="help"
                                              class="form-control" ></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $term_input_answer_heading ?></label>
                                    <textarea name="answer_heading" id="answer_heading"
                                              class="form-control" ></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $term_input_status ?></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status1" value="1" checked>
                                        <label class="form-check-label" for="status1">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status2" value="0">
                                        <label class="form-check-label" for="status2">
                                            Not Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="save_question">Save</button>
                            </div>
                        </div><!-- modal-content -->
                    </form>
                </div>
            </div>

            <div class="modal fade" id="edit_question_modal" tabindex="-1" role="dialog" aria-labelledby="edit_question_modal"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="edit_question_form" enctype="multipart/form-data">
                        <input type="hidden" name="question_id" id="question_id">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"
                                    id="exampleModalLongTitle"><?php echo $term_edit_question ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="type_e"><?php echo $term_input_type ?></label>
                                    <select name="type" id="type_e" class="form-control" required>
                                        <option value="heading">Heading</option>
                                        <option value="tf">Yes / No Answer</option>
                                        <option value="sa">Short Answer</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="parent_id_e"><?php echo $term_input_parent ?></label>
                                    <select name="parent_id" id="parent_id_e" class="form-control" required>
                                        <option value="0">No Parent</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="question_e"><?php echo $term_input_question ?></label>
                                    <textarea name="question" id="question_e"
                                              class="form-control" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="relevance_e"><?php echo $term_input_relevance ?></label>
                                    <select name="relevance" id="relevance_e" class="form-control" required>
                                        <option value="both">Both</option>
                                        <option value="sea">Sea</option>
                                        <option value="land">Land</option>
                                    </select>
                                </div>
                                <div class="form-group" id="more_div_e">
                                    <label for="exampleInputEmail1"><?php echo $term_input_more ?></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="more" id="more1_e" value="1" checked>
                                        <label class="form-check-label" for="more1_e">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="more" id="more0_e" value="0">
                                        <label class="form-check-label" for="more0_e">
                                            No
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group not-showing" id="show_child_div_e">
                                    <label for="exampleInputEmail1"><?php echo $term_show_child ?></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="show_child" id="show_child11" value="1" checked>
                                        <label class="form-check-label" for="show_child11">
                                            Yes Answer
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="show_child" id="show_child22" value="0">
                                        <label class="form-check-label" for="show_child22">
                                            No Answer
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="help_e"><?php echo $term_input_help ?></label>
                                    <textarea name="help" id="help_e"
                                              class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="answer_heading_e"><?php echo $term_input_answer_heading ?></label>
                                    <textarea name="answer_heading" id="answer_heading_e"
                                              class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $term_input_status ?></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status1_e" value="1" checked>
                                        <label class="form-check-label" for="status1_e">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status2_e" value="0">
                                        <label class="form-check-label" for="status2_e">
                                            Disable
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="save_question">Save</button>
                            </div>
                        </div><!-- modal-content -->
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body w-auto table-responsive">
            <ul class="list-group list_question" id="list_question" data-parent="0">
            </ul>
        </div>
    </div>
</div>