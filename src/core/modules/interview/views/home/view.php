<div class="container">
    <div class="card">
        <!-- Card content -->
        <div class="card-body">

            <!-- Title -->
            <div id="calendar-container">
                <div id='calendar'></div>   
            </div>
            
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-center"><?php echo $term_header ?></h4>

        </div>
        <div class="card-body w-auto">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <!-- Card content -->
                                <div class="card-body">

                                    <p class="card-text">Need Schedule</p>

                                    <!-- Title -->
                                    <h4 class="card-title"><?php echo $total_interview?></h4>
                                    <!-- Text -->

                                </div>

                            </div>
                            
                        </div>
                        <div class="col">
                            <div class="card">
                                <!-- Card content -->
                                <div class="card-body">
                                    <p class="card-text">Ready Interview</p>

                                    <h4 class="card-title"><?php echo $total_schedule?></h4>

                                </div>

                            </div>
                            
                        </div>
                        <div class="col">
                            <div class="card">
                                <!-- Card content -->
                                <div class="card-body">

                                    <!-- Title -->
                                    <p class="card-text">Hire</p>
                                    <!-- Text -->
                                    <h4 class="card-title"><?php echo $total_hire?></h4>

                                </div>

                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <!-- Card content -->
                                <div class="card-body">

                                    <!-- Title -->

                                    <p class="card-text">Not Hire</p>
                                    <h4 class="card-title"><?php echo $total_not_hire?></h4>
                                    <!-- Text -->

                                </div>

                            </div>
                        </div>
                        <div class="col-md-12 pt-4">
                            <!-- Card -->
                            <div class="card">
                                <!-- Card content -->
                                <div class="card-body">

                                    <!-- Title -->
                                    <h4 class="card-title">Offline Interview Date</h4>

                                    <table class="table">
                                        <tr>
                                            <td>Organizer</td>
                                            <td>Interviewer</td>
                                            <td>Date</td>
                                            <td></td>
                                        </tr>
                                        <?php foreach($ongoing_location as $index => $schedule) { ?>
                                            <tr>
                                                <td><?php echo $schedule['organizer'] ?></td>
                                                <td><?php echo $schedule['address'] ?></td>
                                                <td><?php echo date('d M Y H:i',strtotime($schedule['start_on'])) ?></td>
                                                <td>
                                                    <a class="btn-sm btn-info text-white" href="<?php echo $base_url?>/detail_location/<?php echo $schedule['interview_location_id']?>" title=""><i class="fas fa-users"></i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                    </table>

                                </div>

                            </div>
                            <!-- Card -->
                        </div>

                    </div>
                </div>
                


            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_event" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="text-center modal-dialog modal-notify modal-info modal-xl" role="document">
        <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        <div id="modal_event_content" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title white-text" id="">Physical Interview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-left">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                        id="exampleModalLongTitle">List Interviewer</h5>
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