<div class="container">
    <div class="card">
        <div class="card-header d-flex align-items-center gradient-card-header blue-gradient">
            <h4 class="text-center"><?php echo $term_page_header ?></h4>

            <a href="#" data-toggle="modal" data-target="#modalCreateTemplate" class="btn btn-sm btn-success ml-auto">
              <i class="fa fa-plus"></i> Create new template
            </a>
        </div>
        <div class="card-body w-auto">

            <table class="table" id="list_template">
                <thead>
					<tr>
						<th style="width: 10%">Template Name</th>
						<th style="width: 10%">Title</th>
						<th style="width: 10%">Subject</th>
						<th style="width: 10%">Type</th>
						<th style="width: 10%">Action</th>
					</tr>
                </thead>
				
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="preview_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Preview</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <iframe style="width:100%;height:80vh" id="iframe_id" ></iframe>
      </div>
      
    </div>
  </div>
</div>
</div>

<div class="modal fade" id="modalCreateTemplate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create Template</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row form-group">
          <div class="col-md-12">
          
            <label for="name">Template Name</label>
            <input type="text" name="name" class="form-control">
          </div>
        </div>
        <div class="row form-group">
          <div class="col-md-12">
            <label for="title">Title</label>
            <input type="text" name="title" class="form-control">
          </div>
        </div>
        <div class="row form-group">
          <div class="col-md-12">
          
            <label for="subject">Subject</label>
            <input type="text" name="subject" class="form-control">
          </div>
        </div>
        <div class="row form-group">
          <div class="col-md-12">
            <label class="control-label">Type</label>
            <select name="template_type" id="template_type" class="form-control select2">
              <option value="">Select Template Type</option>
              <option value="system">System</option>
              <option value="marketing">Marketing</option>
              <option value="template_part">Template Part</option>
            </select>
            
          </div>
        </div>
        <div class="row form-group d-none">
          
          <div class="col-md-4">
            <label class="control-label">Select Main Template</label>
            <select name="main_template" id="main_template" class="form-control select2-with-search">
              
            </select>
            
          </div>
          <div class="col-md-4">
            <label class="control-label">Select Header Template</label>
            <select name="header_template" id="header_template" class="form-control select2-with-search">
              
            </select>
            
          </div>

          <div class="col-md-4">
            <label class="control-label">Select Footer Template</label>
            <select name="footer_template" id="footer_template" class="form-control select2-with-search">
              
            </select>
            
          </div>
        </div>
        <div class="row form-group">
          <div class="col-md-12">
            <label for="content">Template Content</label>
            <textarea name="content" id="content" class="form-control" style="height: 380px"></textarea>
          </div>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="createTemplate">Create</button>
      </div>
    </div>
  </div>
</div>
</div>

<div class="modal fade" id="modalEditTemplate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Template</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="template_id">
        <div class="form-group">
          <label for="name">Template Name</label>
          <input type="text" name="name" class="form-control">
        </div>
        <div class="row form-group">
          <div class="col-md-12">
            <label for="title">Title</label>
            <input type="text" name="title" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label for="subject">Subject</label>
          <input type="text" name="subject" class="form-control">
        </div>
        <div class="row form-group">
          <div class="col-md-12">
            <label class="control-label">Type</label>
            <select name="template_type" id="template_type_edit" class="form-control select2">
              <option value="">Select Template Type</option>
              <option value="system">System</option>
              <option value="marketing">Marketing</option>
              <option value="template_part">Template Part</option>
            </select>
            
          </div>
        </div>
        <div class="row form-group d-none">
          <div class="col-md-4">
            <label class="control-label">Select Main Template</label>
            <select name="main_template" id="main_template_edit" class="form-control select2-with-search">
              
            </select>
            
          </div>
          <div class="col-md-4">
          <label class="control-label">Select Header Template</label>
            <select name="header_template" id="header_template_edit" class="form-control select2-with-search">
              
            </select>
            
          </div>

          <div class="col-md-4">
          <label class="control-label">Select Footer Template</label>
            <select name="footer_template" id="footer_template_edit" class="form-control select2-with-search">
              
            </select>
            
          </div>
        </div>
        <div class="form-group">
          <label for="content">Template Content</label>
          <textarea name="content" id="content" class="form-control" style="height: 380px"></textarea>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="updateTemplate">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalSendEmail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Test Send Email</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="template_name">
        <div class="form-group">
          <label for="name">From Email</label>
          <input type="email" name="from_email" class="form-control">
        </div>
        <div class="form-group">
          <label for="subject">To Email</label>
          <input type="email" name="to_email" class="form-control">
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="sendEmailTemplate">Send</button>
      </div>
    </div>
  </div>
</div>
</div>