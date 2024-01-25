<div class="form-background">
    <?php
    // prd($flag);
    ?>
    <div class="login-box">
        <div class="login-logo">
            <h2><a href="<?=base_url('admin');?>"><?=$this->general_settings['application_name'];?></a></h2>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg"><?=trans('signin_to_start_your_session')?></p>
                <?php $this->load->view('admin/includes/_messages.php')?>

                <?php echo form_open(base_url('user/auth/login'), 'class="login-form" '); ?>
                <div class="form-group has-feedback">
                    <input type="text" name="username" id="name" class="form-control"
                        placeholder="<?=trans('username')?>">
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="<?=trans('password')?>">
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"> <?=trans('remember_me')?>
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <input type="submit" name="submit" id="submit" class="btn btn-primary btn-block btn-flat"
                            flag="<?=isset($flag) ? $flag : ""?>" value="<?=trans('signin')?>">
                    </div>
                    <!-- /.col -->
                </div>
                <?php echo form_close(); ?>

                <!-- <p class="mb-1">
                    <a href="<?=base_url('user/auth/forgot_password');?>"><?=trans('i_forgot_my_password')?></a>
                </p> -->
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
</div>
<script>
$(function() {
    //     $(document).on("click", "#submit", function() {
    //         confirm("Are you sure")
    //     })
    // 
    // let flag = () => {
    //     let data = $("#submit").attr("flag")
    //     if (data == "1") {
    //         $("#mdl-delete").modal("show")
    //         // $('#delete-id').val(id);
    //     }
    // }
    // flag()
    // $(document).on("click", "#submit", function() {
    //     let flag = $("#submit").attr('flag')
    //     console.log(flag)
    // })

    // $(document).on("click", "#delete", function() {
    //     $.ajax({
    //         url: base_url + "user/auth/logout",
    //         data: {
    //             "username": $("#username").val(),
    //             "password": $("#userpassword").val()
    //         },
    //         dataType: "json",
    //         success: function(res) {}
    //     })
    // })

})
</script>