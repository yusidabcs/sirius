<div class="container">
    <div class="card">
        <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between">
            <h4 class="text-white"><?php echo $term_header ?></h4>
            <a href="#" class="btn btn-sm btn-success float-right btn-add-interviewer"> <i class="fa fa-plus"></i> Add New</a>
        </div>
        <div class="card-body w-auto">
            <div class="container">
                <table class="table" id="list_interviewer">
                    <thead>
                    <tr>
                        <td>Name</td>
                        <td></td>
                    </tr>
                    </thead>
                </table>
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
                            id="exampleModalLongTitle"><?php echo $term_create_interviewer ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="md-form">

                            <label for="address_book_id">Select Person</label>
                            <select id="address_book_id" name="address_book_id" class="mdb-select md-form"
                                    searchable="Search here.." >
                                <option value="" disabled>Choose Person</option>
                                <?php foreach ($users as $index => $user):?>
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
</div>