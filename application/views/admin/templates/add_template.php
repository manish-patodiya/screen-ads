  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">
      <!-- <div class='alert <?=$rmning_users ? 'alert-info' : 'alert-danger'?>'>You have <?=$rmning_users?> remaining
          users
      </div> -->
      <!-- Main content -->
      <section class="content">
          <div class="card card-default">
              <div class="card-header">
                  <div class="d-inline-block">
                      <h3 class="card-title"> <i class="fa fa-plus"></i>
                          <?=trans('add_new_template')?> </h3>
                  </div>
                  <div class="d-inline-block float-right">
                      <a href="<?=base_url('admin/templates');?>" class="btn btn-success"><i class="fa fa-list"></i>
                          <?=trans('templates')?></a>
                  </div>
                  <!-- <div class="d-inline-block float-right">
                      <a href="<?=base_url('admin/templates/edit');?>" class="btn btn-success"><i
                              class="fa fa-list"></i>
                          <?=trans('add_new_template')?></a>
                  </div> -->
              </div>
              <div class="card-body">

                  <!-- For Messages -->
                  <?php $this->load->view('admin/includes/_messages.php')?>

                  <?php echo form_open(base_url('admin/templates/add'), ['class' => "form-horizontal", "id" => "frm-add-template"]); ?>

                  <input type="text" name="company_id" value='<?=$this->session->company_id;?>'
                      class="form-control hidden" id="company_id" placeholder="">



                  <div class="form-group">
                      <label class="col-md-2 control-label"><?=trans('title')?><span class="req">
                              *</span></label>

                      <div class="col-md-12">
                          <input type="text" name="title" class="form-control" id="title" placeholder="Enter your title"
                              autocomplete="off">
                      </div>
                  </div>

                  <div class="form-group">
                      <label for="content" class="col-md-2 control-label"><?=trans('content')?><span class="req">
                              *</span></label>

                      <div class="col-md-12">
                          <textarea name="content" class="form-control" id="content" rows="10" cols="80"
                              placeholder="Type your content"></textarea>
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="col-md-12">

                          <input type="submit" name="submit" value="<?=trans('add_new_template')?>"
                              class="btn btn-primary pull-right" id="btn-submit">
                          <!-- <input type="submit" name="preview" value="" class="btn btn-primary pull-right mr-2"> -->
                          <!-- <a title="Preview" class="btn btn-primary pull-right mr-2" uid="" href=""
                              id="preview"><?=trans('preview')?></a> -->


                          <button type="submit" class="btn btn-primary pull-right mr-2" id='preview'><a
                                  class="text-50 fw-bold" style="color: white;"><?=trans('preview')?></a></button>
                      </div>
                  </div>
                  <?php echo form_close(); ?>
              </div>
              <!-- /.box-body -->
          </div>
      </section>
  </div>

  <!-- ck editor -->
  <script src="<?=base_url()?>assets/plugins/ckeditor/ckeditor.js"></script>
  <script>
$(function() {
    CKEDITOR.replace('content');
});
  </script>
  <script>
$(document).ready(function() {
    // $(document).on('click', "#preview", function(e) {
    //     $.ajax({
    //         url: base_url + "admin/templates/view",
    //         dataType: "json",
    //         data: {
    //             'title' => $('#title').val(),
    //             'content' => $("#content").val()
    //         },
    //         success: function(res) {
    //         }
    //     })
    // })

    $('#preview').click(function() {
        let title = $('#title').val();
        let content = $("#content").val();
        $('#frm-add-template').attr('action', base_url +
            `admin/templates/view`);
        $('#frm-add-template').attr('target', '_blank');
        $('form').last().submit();
    });

    $('#btn-submit').click(function() {
        $('#frm-add-template').attr('action', base_url + `admin/templates/add`);
        $('#frm-add-template').attr('target', '');
        $('form').last().submit();
    })
})
  </script>