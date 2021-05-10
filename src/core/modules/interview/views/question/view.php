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
                                    <!-- Material inline 1 -->
                                    <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" id="type1" name="type" value="general" checked>
                                        <label class="form-check-label" for="type1">General Question</label>
                                    </div>

                                    <!-- Material inline 2 -->
                                    <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" id="type2" name="type" value="specific">
                                        <label class="form-check-label" for="type2">Specific Question</label>
                                    </div>
                                </div>
                                <div class="md-form not-showing" id="jobs">

                                    <div class="md-form">

                                        <label for="job_speedy_code_create">Select Job</label>
                                        <select id="job_speedy_code_create" name="job_speedy_code[]" class="mdb-select md-form" multiple
                                                searchable="Search here.." >
                                            <option value="" disabled>Choose job</option>
                                            <?php foreach ($jobs as $index => $job):?>
                                                <option value="<?php echo $job['job_speedy_code']?>"><?php echo $job['job_speedy_code'] ?> - <?php echo $job['job_title'] ?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $term_input_question ?></label>
                                    <textarea name="question" id="question"
                                              class="form-control" required></textarea>
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
                                    <!-- Material inline 1 -->
                                    <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" id="type11" name="type" value="general" checked>
                                        <label class="form-check-label" for="type11">General Question</label>
                                    </div>

                                    <!-- Material inline 2 -->
                                    <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" id="type22" name="type" value="specific">
                                        <label class="form-check-label" for="type22">Specific Question</label>
                                    </div>
                                </div>
                                <div class="md-form not-showing" id="jobs">

                                    <div class="md-form">

                                        <label for="job_speedy_code">Select Job</label>
                                        <select id="job_speedy_code" name="job_speedy_code[]" class="mdb-select md-form" multiple
                                                searchable="Search here.." >
                                            <option value="" disabled>Choose job</option>
                                            <?php foreach ($jobs as $index => $job):?>
                                                <option value="<?php echo $job['job_speedy_code']?>"><?php echo $job['job_speedy_code'] ?> - <?php echo $job['job_title'] ?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $term_input_question ?></label>
                                    <textarea name="question" id="question"
                                              class="form-control" required></textarea>
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
                                        <input class="form-check-input" type="radio" name="status" id="status11" value="1" checked>
                                        <label class="form-check-label" for="status11">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status22" value="0">
                                        <label class="form-check-label" for="status22">
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

        </div>
        <div class="card-body w-auto table-responsive">

            <div class="row">
                <div class="col-md-4 ">
                    <div class="md-form">
                        <label for="table_job_search"><?php echo $term_table_job_filter ?></label>
                        <select id="table_job_search" name="table_job_search" class="mdb-select"
                                searchable="Search">
                            <option value=""><?php echo $term_table_select_please; ?></option>
                            <?php

                            $html = '';
                            foreach ($jobs as $job) {
                                $html .= '<option value="' . $job['job_speedy_code'] . '" >' .$job['job_speedy_code'].' - '.$job['job_title'] . '</option>';
                            }
                            echo $html;
                            ?>
                        </select>
                    </div>

                </div>


                <div class="col-md-4 md-form">
                    <label for="table_type_search"><?php echo $term_table_status_filter ?></label>
                    <select id="table_type_search" class="mdb-select">
                        <option value=""><?php echo $term_table_select_all ?></option>
                        <?php
                        $html = '';
                        foreach($list_status as $key => $status)
                        {
                            $html.= '<option value="'.$status.'" '.($active_status == $status ? 'selected' : '').'>'.ucwords($status).'</option>';
                        }
                        echo $html;
                        ?>
                    </select>
                </div>
            </div>

            <table class="table" id="list_question">
                <thead>
                    <tr>
                        <td>Question</td>
                        <td>Type</td>
                        <td>Job Title</td>
                        <td></td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>