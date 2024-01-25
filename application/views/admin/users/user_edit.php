  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
          <div class="card card-default">
              <div class="card-header">
                  <div class="d-inline-block">
                      <h3 class="card-title"> <i class="fa fa-pencil"></i>
                          &nbsp; <?=trans('edit_user')?> </h3>
                  </div>
                  <div class="d-inline-block float-right">
                      <a href="<?=base_url('admin/users');?>" class="btn btn-success"><i class="fa fa-list"></i>
                          <?=trans('users_list')?></a>
                      <a href="<?=base_url('admin/users/add');?>" class="btn btn-success"><i class="fa fa-plus"></i>
                          <?=trans('add_new_user')?></a>
                  </div>
              </div>
              <div class="card-body">

                  <!-- For Messages -->
                  <?php $this->load->view('admin/includes/_messages.php')?>

                  <?php echo form_open(base_url('admin/users/edit/' . $user['id']), ['class' => "form-horizontal", 'id' => 'frm-edit-user']) ?>

                  <input type="text" name="company_id" value='<?=$this->session->company_id;?>'
                      class="form-control hidden" id="company_id" placeholder="">
                  <div class="form-group">
                      <label for="username" class="col-md-2 control-label"><?=trans('username')?><span class="req">
                              *</span></label>

                      <div class="col-md-12">
                          <input type="text" name="username" value="<?=$user['username'];?>" class="form-control"
                              id="username" placeholder="Enter your username">
                      </div>
                  </div>

                  <div class="form-group">
                      <label for="remarks" class="col-md-12 control-label"><?=trans('remarks')?></label>

                      <div class="col-md-12">
                          <input type="text" name="remarks" class="form-control" value="<?=$user['remarks'];?>"
                              id="remarks" placeholder="Enter your remarks">
                      </div>
                  </div>

                  <div class="form-group">
                      <div class="col-md-12">
                          <input type="submit" name="submit" value="<?=trans('update_user')?>"
                              class="btn btn-primary pull-right">
                      </div>
                  </div>
                  <?php echo form_close(); ?>
              </div>
              <!-- /.box-body -->
          </div>
      </section>
  </div>

  <script>
$(function() {
    $('#frm-edit-user').validate({
        rules: {
            username: {
                required: true,
                userUsernameExist: [<?=$user['id']?>],
            },
            firstname: {
                required: true,
            },
            lastname: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            mobile_no: {
                required: true
            },
            password: {
                required: true
            },
            address: {
                required: true
            },
            status: {
                required: true
            },
        },
        messages: {},

        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback col font-100');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
})
  </script>