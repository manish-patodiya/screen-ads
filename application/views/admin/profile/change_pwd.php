<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="card card-default color-palette-bo">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"> <i class="fa fa-pencil"></i>
                        &nbsp; <?=trans('change_password')?> </h3>
                </div>
            </div>
            <div class="card-body">
                <!-- For Messages -->
                <?php $this->load->view('admin/includes/_messages.php')?>

                <?php echo form_open(base_url('admin/profile/change_pwd'), ['class' => "form-horizontal", 'id' => 'frm-chng-pass']); ?>
                <div class="form-group">
                    <label for="password" class="col-sm-3 control-label"><?=trans('new_password')?></label>

                    <div class="col-md-12">
                        <input type="password" name="password" class="form-control" id="password"
                            placeholder="Enter new password">
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_pwd" class="col-sm-3 control-label"><?=trans('confirm_password')?></label>

                    <div class="col-md-12">
                        <input type="password" name="confirm_pwd" class="form-control" id="confirm_pwd"
                            placeholder="Confirm your new password">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" name="submit" value="<?=trans('change_password')?>"
                            class="btn btn-info pull-right">
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
    $('#frm-chng-pass').validate({
        rules: {
            password: {
                required: true,
            },
            confirm_pwd: {
                required: true,
                equalTo: '#password',
            },
        },
        messages: {
            confirm_pwd: {
                equalTo: 'Password does not match'
            }
        },

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