<?php
    if ( (!empty($verification)) && ($verification['status'] == 'verified') )
    {
?>

  <h3 class="card-header peach-gradient white-text text-center py-4">
      Available Courses
  </h3>
  <input type="hidden" id="link-education" value="<?php echo $education_application_link?>">
  <div class="card-body">
      <?php if (!empty($course_info)){?>
      <table class="table table-bordered table-responsive-sm">
          <thead>
          <tr>
              <th>Course Name</th>
              <th>Description</th>
              <th></th>
          </tr>
          </thead>
          <tbody>
          <?php foreach($course_info as $key => $item) {?>
              <tr>
                  <td>
                      <?php echo $item['course_name'] ?>
                  </td>
                  <td width="300"><?php echo $item['short_description'] ?></td>
                  <td class="text-center">
                      <a data-id="<?php echo $item['course_id'] ?>" href="#" class="btn btn-sm btn-info btn_join_course"><i class="fa fa-running"></i> Join Now</a>
                  </td>
              </tr>
          <?php }?>
          </tbody>
      </table>
      <?php }else{?>
          <div class="alert alert-danger">
              There are no available course to join.
          </div>
      <?php }?>
      <div class="d-flex justify-content-center">
          <a href="<?php echo $education_application_link?>" class="btn btn-link btn-info btn-sm"><< My Course</a>
          <a href="<?php echo $education_application_link?>/list_course" class="btn btn-link btn-success btn-sm <?php echo (!empty($course_info))?:'disabled'?>">Show All Course >></a>
      </div>

  </div>

  <div class="card-footer">
  </div>

<?php }?>
<style>
    .blockquote p {
    padding: 0;
    font-size: 1rem;
}
</style>
<div class="modal fade" id="join_course_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div style="text-align:center;padding-top:30%" class="content_loading">
            <div style="color: white;width:80px;height:80px" class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Form -->
        <form id="form_request_join_course" method="post">
        <input type="hidden" name="course_id" id="course_id" value="0">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="title_modal"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <h4>Description</h4>
                <p id="short_description"></p>
                <blockquote class="blockquote mb-0">
                    <p id="full_description"></p>
                </blockquote>       
            </div>
            <div class="modal-footer">
                <!-- Send button -->
                <button class="btn btn-warning btn-md" data-dismiss="modal">Cancel</button>
                <button class="btn btn-success btn-md" id="btn_submit_request" type="submit">Request Join</button>
            </div>
        </div>
        </form>
        <!-- Form -->
    </div>
</div>