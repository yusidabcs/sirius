<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-center"><?php echo $term_header ?></h4>

        </div>
        <div class="card-body w-auto">

            <div class="row">
                <div class="col-md-3 md-form">
                    <label for="table_status_search"><?php echo $term_table_select_status ?></label>
                    <select id="table_status_search" name="table_partner_search" class="mdb-select"
                            searchable="Search">
                        <option value=""><?php echo $term_table_select_status; ?></option>
                        <?php

                        $html = '';
                        foreach ($status as $index => $item) {
                            $html .= '<option value="' . $item . '" >' . ucwords(str_replace('_',' ', $item)) . '</option>';
                        }
                        echo $html;
                        ?>
                    </select>
                </div>

                <div class="col-md-3 md-form">
                        <!--The "from" Date Picker -->
                    <input placeholder="Selected starting date" type="text" id="startingDate" class="form-control datepicker">
                    <label for="startingDate">Filter Start Date</label>
                </div>

                <div class="col-md-3 md-form">
                <input placeholder="Selected starting date" type="text" id="endingDate" class="form-control datepicker">
                    <label for="endingDate">Filter End Date</label>
                </div>
            </div>

            <table class="table" id="list_deployment">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Fullname</th>
                    <th>Job</th>
                    <th>Deployment Date</th>
                    <th>LOE File</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- preview modal -->	
<div class="modal fade" id="deployment_tracker" role="dialog" aria-labelledby="deployment_tracker" aria-hidden="true" data-backdrop="static">
											
    <div class="modal-dialog modal-xl modal-notify modal-info" role="document">
        <div class="content_loading not-showing">
            <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        </div>
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title white-text" id="myModalLabel">Detail Tracker</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>
            <div class="modal-body">
                <input type="hidden" id="address_book" name="address_book" value="">
                <input type="hidden" id="job_application" name="job_application" value="">
                <div class="container-fluid">
                    <div class="alert alert-" role="alert">
                        <div class="row">
                            <div class="col-md-6">
                                <span class="">Visa Tracker (<span id="visa_status">unknown</span>)</span>
                                <div class="progress sm-progress" style="height: 15px">
                                    <div id="visa_percentage" class="progress-bar bg-success" role="progressbar" style="width: 0%; height: 15px">0%</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span class="">OKTB Tracker (<span id="oktb_status">unknown</span>)</span>
                                <div class="progress md-progress" style="height: 15px">
                                    <div id="oktb_percentage" class="progress-bar bg-success" role="progressbar" style="width: 0%; height: 15px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span class="">STCW Tracker (<span id="stcw_status">unknown</span>)</span>
                                <div class="progress md-progress" style="height: 15px">
                                    <div id="stcw_percentage" class="progress-bar bg-success" role="progressbar" style="width: 0%; height: 15px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span class="">Medical Tracker (<span id="medical_status">unknown</span>)</span>
                                <div class="progress md-progress" style="height: 15px">
                                    <div id="medical_percentage" class="progress-bar bg-success" role="progressbar" style="width: 0%; height: 15px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span class="">Vaccination Tracker (<span id="vaccination_status">unknown</span>)</span>
                                <div class="progress md-progress" style="height: 15px">
                                    <div id="vaccination_percentage" class="progress-bar bg-success" role="progressbar" style="width: 0%; height: 15px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span class="">Flight Tracker (<span id="flight_status">unknown</span>)</span>
                                <div class="progress md-progress" style="height: 15px">
                                    <div id="flight_percentage" class="progress-bar bg-success" role="progressbar" style="width: 0%; height: 15px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span class="">Police Tracker (<span id="police_status">unknown</span>)</span>
                                <div class="progress md-progress" style="height: 15px">
                                    <div id="police_percentage" class="progress-bar bg-success" role="progressbar" style="width: 0%; height: 15px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span class="">Seaman Tracker (<span id="seaman_status">unknown</span>)</span>
                                <div class="progress md-progress" style="height: 15px">
                                    <div id="seaman_percentage" class="progress-bar bg-success" role="progressbar" style="width: 0%; height: 15px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span class="">Travelpack Tracker (<span id="travelpack_status">unknown</span>)</span>
                                <div class="progress md-progress" style="height: 15px">
                                    <div id="travelpack_percentage" class="progress-bar bg-success" role="progressbar" style="width: 0%; height: 15px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>

                        </div>
                                            
                    </div>

                    <ul class="nav nav-tabs d-flex" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="visa-tab" data-toggle="tab" href="#visa" role="tab" aria-controls="visa-tab" aria-selected="true">Visa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="oktb-tab" data-toggle="tab" href="#oktb" role="tab" aria-controls="oktb-tb" aria-selected="true">OKTB</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="stcw-tab" data-toggle="tab" href="#stcw" role="tab" aria-controls="stcw-tab" aria-selected="true">STCW</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="medical-tab" data-toggle="tab" href="#medical" role="tab" aria-controls="medical-tab" aria-selected="true">Medical</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="vaccination-tab" data-toggle="tab" href="#vaccination" role="tab" aria-controls="vaccination-tab" aria-selected="true">Vaccination</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="flight-tab" data-toggle="tab" href="#flight" role="tab" aria-controls="flight-tab" aria-selected="true">Flight</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="police-tab" data-toggle="tab" href="#police" role="tab" aria-controls="police-tab" aria-selected="true">Police</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="seaman-tab" data-toggle="tab" href="#seaman" role="tab" aria-controls="seaman-tab" aria-selected="true">Seaman</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="travelpack-tab" data-toggle="tab" href="#travelpack" role="tab" aria-controls="travelpack-tab" aria-selected="true">Travelpack</a>
                        </li>
                        
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show in active" id="visa" role="tabpanel" aria-labelledby="visa-tab">
                            <table width="100%" class="table" id="deployment_list_visa">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Visa Type</th>
                                        <th>Status</th>
                                        <th>Level</th>
                                        <th>Created On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="oktb" role="tabpanel" aria-labelledby="oktb-tab">
                            <table width="100%" class="table" id="deployment_list_oktb">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Oktb Type</th>
                                        <th>Status</th>
                                        <th>Level</th>
                                        <th>Created On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="stcw" role="tabpanel" aria-labelledby="stcw-tab">
                            <table width="100%" class="table" id="deployment_list_stcw">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Level</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="medical" role="tabpanel" aria-labelledby="medical-tab">
                            <table width="100%" class="table" id="deployment_list_medical">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Level</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="vaccination" role="tabpanel" aria-labelledby="vaccination-tab">
                            <table width="100%" class="table" id="deployment_list_vaccine">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Level</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="flight" role="tabpanel" aria-labelledby="flight-tab">
                            <table width="100%" class="table" id="deployment_list_flight">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Level</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="police" role="tabpanel" aria-labelledby="police-tab">
                            <table width="100%" class="table" id="deployment_list_police">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Level</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="seaman" role="tabpanel" aria-labelledby="seaman-tab">
                            <table width="100%" class="table" id="deployment_list_seaman">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Level</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="travelpack" role="tabpanel" aria-labelledby="travelpack-tab">
                            <table width="100%" class="table" id="deployment_list_travelpack">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Level</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- <div class="d-flex justify-content-center">
                    <a href="#" data-ab-id="" class="btn btn-primary mr-2 confirm-oktb">Confirm</a>
                    <a href="#" data-ab-id="" class="btn btn-danger reject-oktb">Reject</a>
                </div> -->
            </div>
        </div>
    </div>
</div>
    <!-- END preview -->

    <!-- ===================VIsa Modal============= -->
    <div class="modal fade" id="visaModal" tabindex="-1" role="dialog" aria-labelledby="educationModal" aria-hidden="true">
											
    <div class="modal-dialog modal-lg modal-notify modal-success" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title white-text" id="myModalLabel">Visa Document</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>
            <div class="modal-body">

                <table id="education_certificate_details" class="table table-bordered" style="white-space: normal">
                    
                    <thead>
                        <tr>
                            <th colspan="2" class="center">Visa File Preview</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td width="30%">Visa Number</td>
                            <td id="visa_id"></td>
                        </tr>
                        <tr>
                            <td width="30%">Visa Type</td>
                            <td id="visa_type"></td>
                        </tr>
                        <tr>
                            <td width="30%">Date of issue</td>
                            <td id="date_of_issue"></td>
                        </tr>
                        <tr>
                            <td width="30%">Expired Date</td>
                            <td id="expired_date"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="file-preview"></td>
                        </tr>
                    
                    </tbody>
                    
                </table>
                
                <div class="d-flex justify-content-center">
                    <a href="#" data-visa-id="" class="btn btn-primary mr-2 visa-confirm-visa">Confirm</a>
                    <a href="#" data-visa-id="" class="btn btn-danger visa-reject-visa">Reject</a>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- ===================ENd Visa Modal============ -->

    <!-- =======================OKTB Modal============== -->
    <div class="modal fade" id="oktbModal" tabindex="-1" role="dialog" aria-labelledby="educationModal" aria-hidden="true">
											
    <div class="modal-dialog modal-lg modal-notify modal-success" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title white-text" id="myModalLabel">OKTB Document</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>
            <div class="modal-body">

                <table id="education_certificate_details" class="table table-bordered" style="white-space: normal">
                    
                    <thead>
                        <tr>
                            <th colspan="2" class="center">OKTB File Preview</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td width="30%">OKTB Number</td>
                            <td id="oktb_number"></td>
                        </tr>
                        <tr>
                            <td width="30%">OKTB Type</td>
                            <td id="oktb_type"></td>
                        </tr>
                        <tr>
                            <td width="30%">Date of Issue</td>
                            <td id="date_of_issue"></td>
                        </tr>
                        <tr>
                            <td width="30%">Valid Until</td>
                            <td id="valid_until"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="file-preview"></td>
                        </tr>
                    
                    </tbody>
                    
                </table>
                
                <div class="d-flex justify-content-center">
                    <a href="#" data-oktb-id="" class="btn btn-primary mr-2 oktb-confirm-oktb">Confirm</a>
                    <a href="#" data-oktb-id="" class="btn btn-danger oktb-reject-oktb">Reject</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===========================End OKTB Modal================== -->

<!--=============================STCW Modal====================  -->
<div class="modal fade" id="stcwModal" tabindex="-1" role="dialog" aria-labelledby="educationModal" aria-hidden="true">
											
    <div class="modal-dialog modal-lg modal-notify modal-success" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title white-text" id="myModalLabel">STCW Document</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>
            <div class="modal-body">

                <table id="education_certificate_details" class="table table-bordered" style="white-space: normal">
                    
                    <thead>
                        <tr>
                            <th colspan="2" class="center">STCW File Preview</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td width="30%">Qualification</td>
                            <td id="qualification"></td>
                        </tr>
                        <tr>
                            <td width="30%">Institution</td>
                            <td id="institution"></td>
                        </tr>
                        <tr>
                            <td width="30%">Certificate Date</td>
                            <td id="certificate_date"></td>
                        </tr>
                        <tr>
                            <td width="30%">Certificate Expiry</td>
                            <td id="certificate_expiry"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="file-preview"></td>
                        </tr>
                    
                    </tbody>
                    
                </table>
                
                <div class="d-flex justify-content-center">
                    <a href="#" data-education-id="" data-address-book-id="" class="btn btn-primary mr-2 stcw-confirm-stcw">Confirm</a>
                    <a href="#" data-education-id="" data-address-book-id="" class="btn btn-danger stcw-reject-stcw">Reject</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- =======================END STCW Modal========================= -->

<!-- =======================Start Medical Modal========================= -->
<div class="modal fade" id="medicalModal" role="dialog" aria-labelledby="educationModal" aria-hidden="true">
											
    <div class="modal-dialog modal-lg modal-notify modal-success" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title white-text" id="myModalLabel">Medical Document</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>
            <div class="modal-body">

                <table id="education_certificate_details" class="table table-bordered" style="white-space: normal">
                    
                    <thead>
                        <tr>
                            <th colspan="2" class="center">Medical File Preview</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td width="30%">Certificate Number</td>
                            <td id="certificate_number"></td>
                        </tr>
                        <tr>
                            <td width="30%">Doctor</td>
                            <td id="doctor"></td>
                        </tr>
                        <tr>
                            <td width="30%">Institution</td>
                            <td id="institution"></td>
                        </tr>
                        <tr>
                            <td width="30%">Certificate Date</td>
                            <td id="certificate_date"></td>
                        </tr>
                        <tr>
                            <td width="30%">Certificate Expiry</td>
                            <td id="certificate_expiry"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="file-preview"></td>
                        </tr>
                    
                    </tbody>
                    
                </table>
                
                <div class="d-flex justify-content-center">
                    <a href="#" data-medical-id="" class="btn btn-primary mr-2 medical-confirm-medical">Confirm</a>
                    <a href="#" data-medical-id="" class="btn btn-danger medical-reject-medical">Reject</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- =======================End Medical Modal========================= -->

<!-- =======================Vaccination Modal========================= -->
<div class="modal fade" id="vaccineModal" role="dialog" aria-labelledby="educationModal" aria-hidden="true">
											
    <div class="modal-dialog modal-lg modal-notify modal-success" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title white-text" id="myModalLabel">Vaccination Document</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>
            <div class="modal-body">

                <table id="education_vaccination_details" class="table table-bordered" style="white-space: normal">
                    
                    <thead>
                        <tr>
                            <th colspan="2" class="center">Vaccination File Preview</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td width="30%">Vaccination Number</td>
                            <td id="vaccination_number"></td>
                        </tr>
                        <tr>
                            <td width="30%">Doctor</td>
                            <td id="doctor"></td>
                        </tr>
                        <tr>
                            <td width="30%">Hospital</td>
                            <td id="institution"></td>
                        </tr>
                        <tr>
                            <td width="30%">Vaccination Date</td>
                            <td id="vaccination_date"></td>
                        </tr>
                        <tr>
                            <td width="30%">Vaccination Expiry</td>
                            <td id="vaccination_expiry"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="file-preview"></td>
                        </tr>
                    
                    </tbody>
                    
                </table>
                
                <div class="d-flex justify-content-center">
                    <a href="#" data-vaccine-id="" class="btn btn-primary mr-2 vaccination-confirm-vaccine">Confirm</a>
                    <a href="#" data-vaccine-id="" class="btn btn-danger vaccination-reject-vaccine">Reject</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="appointmentModal" class="modal fade in" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Set Appointment Date</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form id="appointment" action="#" method="post">
            <input type="hidden" name="address_book_id">
            <input type="hidden" name="user_id">
            <div class="modal-body">
                    <div class="form-group">
                        <label for="date">Select Appointment Date</label>
                        <input type="text" name="appointment_date" id="date" class="flatpickr form-control">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
  </div>
</div>
<!-- =======================END Vaccination Modal========================= -->

<!-- =======================Flight Modal========================= -->
<div class="modal fade" id="flightModal" role="dialog" aria-labelledby="educationModal" aria-hidden="true">
											
    <div class="modal-dialog modal-lg modal-notify modal-success" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title white-text" id="myModalLabel">Flight Document</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>
            <div class="modal-body">

                <table id="education_vaccination_details" class="table table-bordered" style="white-space: normal">
                    
                    <thead>
                        <tr>
                            <th colspan="2" class="center">Flight Document Preview</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td width="30%">Flight Number</td>
                            <td id="flight_number"></td>
                        </tr>
                        <tr>
                            <td width="30%">Departure Date</td>
                            <td id="departure_date"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="file-preview"></td>
                        </tr>
                    
                    </tbody>
                    
                </table>
                
                <div class="d-flex justify-content-center">
                    <a href="#" data-flight-id="" class="btn btn-primary mr-2 flight-confirm-flight">Confirm</a>
                    <a href="#" data-flight-id="" class="btn btn-danger flight-reject-flight">Reject</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- =======================END Flight Modal========================= -->

<!-- =======================Start Police Modal========================= -->
<div class="modal fade" id="policeModal" role="dialog" aria-labelledby="policeModal" aria-hidden="true">
											
    <div class="modal-dialog modal-lg modal-notify modal-success" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title white-text" id="myModalLabel">Police Document</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>
            <div class="modal-body">

                <table id="police_certificate_details" class="table table-bordered" style="white-space: normal">
                    
                    <thead>
                        <tr>
                            <th colspan="2" class="center">Police File Preview</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td width="30%">Place of Issue</td>
                            <td id="place_issued"></td>
                        </tr>
                        <tr>
                            <td width="30%">Police Status</td>
                            <td id="active"></td>
                        </tr>
                        <tr>
                            <td width="30%">Police Date</td>
                            <td id="police_date"></td>
                        </tr>
                        <tr>
                            <td width="30%">Police Expiry</td>
                            <td id="police_expiry"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="file-preview"></td>
                        </tr>
                    
                    </tbody>
                    
                </table>
                
                <div class="d-flex justify-content-center">
                    <a href="#" data-police-id="" class="btn btn-primary mr-2 police-confirm-police">Confirm</a>
                    <a href="#" data-police-id="" class="btn btn-danger police-reject-police">Reject</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- =======================End Police Modal========================= -->

<!-- =======================Seaman Modal========================= -->
    <div class="modal fade" id="seamanModal" tabindex="-1" role="dialog" aria-labelledby="seamanModal" aria-hidden="true">
											
    <div class="modal-dialog modal-lg modal-notify modal-success" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title white-text" id="myModalLabel">Seaman Document</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>
            <div class="modal-body">

                <table id="seaman_details" class="table table-bordered" style="white-space: normal">
                    
                    <thead>
                        <tr>
                            <th colspan="2" class="center">Seaman File Preview</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td width="30%">CODE</td>
                            <td id="code"></td>
                        </tr>
                        <tr>
                            <td width="30%">Fullname</td>
                            <td id="fullname"></td>
                        </tr>
                        <tr>
                            <td width="30%">Nationality</td>
                            <td id="nationality"></td>
                        </tr>
                        <tr>
                            <td width="30%">Date</td>
                            <td id="date"></td>
                        </tr>
                        <tr>
                            <td width="30%">Expiry</td>
                            <td id="to_date"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="file-preview"></td>
                        </tr>
                    
                    </tbody>
                    
                </table>
                
                <div class="d-flex justify-content-center">
                    <a href="#" class="btn btn-primary mr-3 seaman_accept_seaman" data-seaman-id="">Accept</a>
                    <a href="#" class="btn btn-danger seaman_reject_seaman" data-seaman-id="">Reject</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- =======================End Seaman Modal========================= -->

<!-- =======================Travelpack Modal========================= -->
<div class="modal fade" id="generate-invoice-modal" tabindex="-1" role="dialog" aria-labelledby="generate-invoice-modal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-notify modal-success" role="document">
        <form id="generate-invoice-form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title white-text"
                        id="">Generate Invoice</h5>
                    <button type="button" class="close white-text" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="job_application_id">
                    <input type="hidden" name="address_book_id">

                    <div class="md-form">
                        <input placeholder="Invoice Number" type="number" name="invoice_number" id="invoice_number" class="form-control">
                        <label for="invoice_number">Invoice Number</label>
                    </div>
                    <div class="md-form">
                        <input placeholder="Selected date" type="text" id="invoice_expected_on" name="invoice_expected_on" class="form-control datepicker">
                        <label for="invoice_expected_on">Invoice Expected Date</label>
                    </div>

                    <!-- file image -->
                        <div class="mt-3 p-1">

                            <!-- banner photo -->
                            <div id="invoice_image" class="card mb-4">

                                <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                    <i class="fa fa-image"></i> Invoice Image
                                </h4>

                                <div class="card-body">

                                    <div class="text-center not-showing">
                                        <img src="" alt="Current Invoice Image" id="invoice_img" class="img-fluid">
                                        <button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								        <hr>
                                    </div>

                                    <div class="form-group">
                                        <label for="invoice_input">Choose Image</label>
                                        <input type="file" class="col-12" id="invoice_input" accept=".jpg,.png,.gif" >
                                        <input type="hidden" id="invoice_base64" name="invoice_base64">
                                    </div>

                                    <div id="invoice_croppie_wrap" class="mw-100 w-auto mh-100 h-auto not-showing">
                                        <div id="invoice_croppie" data-invoice-width="600" data-invoice-height="400"></div>
                                    </div>

                                    <button class="btn btn-default btn-block not-showing" type="button" id="invoice_result">Crop IT</button>

                                </div>

                            </div>

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

<!--Pay Invoice Modal-->
<div class="modal fade" id="pay-invoice-modal" tabindex="-1" role="dialog" aria-labelledby="payW-invoice-modal"
     aria-hidden="true">
    <div class="modal-dialog modal-notify modal-success modal-lg" role="document">
        <form id="pay-invoice-form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title white-text"
                        id="">Pay Invoice</h5>
                    <button type="button" class="close white-text" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="job_application_id">
                    <input type="hidden" name="address_book_id">
                    <div class="md-form">
                        <p>Payment Status</p>
                        <!-- Material inline 1 -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="materialInline1" name="status" value="paid" required>
                            <label class="form-check-label" for="materialInline1">Paid</label>
                        </div>

                        <!-- Material inline 2 -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="materialInline2" name="status" value="cancelled" required>
                            <label class="form-check-label" for="materialInline2">Cancelled</label>
                        </div>

                    </div>
                    <br>
                    <div class="md-form">
                        <input placeholder="Put some notes.." type="text" id="notes" name="notes" class="form-control" required>
                        <label for="notes">Notes</label>
                    </div>
                    <!-- file image -->
                    <div class="mt-3 p-1">

                        <!-- banner photo -->
                        <div id="pay_image" class="card mb-4">

                            <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                <i class="fa fa-image"></i> Pay Image
                            </h4>

                            <div class="card-body">

                                <div class="text-center not-showing">
                                    <img src="" alt="Current Pay Image" id="pay_img" class="img-fluid">
                                    <button class="btn btn-default btn-block not-showing mt-2" type="button" id="pay_update_crop">Crop Photo</button>
                                    <hr>
                                </div>

                                <div class="form-group">
                                    <label for="pay_input">Choose Image</label>
                                    <input type="file" class="col-12" id="pay_input" accept=".jpg,.png,.gif" >
                                    <input type="hidden" id="pay_base64" name="pay_base64">
                                </div>

                                <div id="pay_croppie_wrap" class="mw-100 w-auto mh-100 h-auto not-showing">
                                    <div id="pay_croppie" data-pay-width="600" data-pay-height="400"></div>
                                </div>

                                <button class="btn btn-default btn-block not-showing" type="button" id="pay_result">Crop IT</button>

                            </div>

                        </div>

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
<!-- =======================End Travelpack Modal========================= -->


<div class="modal fade" id="deploymentModal" tabindex="-1" role="dialog" aria-hidden="true">
											
    <div class="modal-dialog modal-notify modal-primary" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title white-text" id="myModalLabel">Update Deployment Status</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>
            <div class="modal-body">

                <form id="editDeployment" action="#" class="form">
                    <input type="hidden" name="address_book_id">
                    <div class="form-group md-form">
                        <label for="deployment_status">Deployment Status</label>
                        <select name="deployment_status" id="deployment_status" class="mdb-select">
                            <option value="deployed">Deployed</option>
                            <option value="canceled">Canceled</option>
                        </select>
                    </div>
                </form>
                
                <div class="d-flex justify-content-center">
                    <a href="#" data-visa-id="" class="btn btn-primary mr-2 update-deployment">Save</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- =======================Deployment Modal Modal========================= -->