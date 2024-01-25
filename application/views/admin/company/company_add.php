<style>
.form-check-input {
    position: absolute;
    margin-top: 0.3rem;
    margin-left: 0.75rem;
}
</style>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="card card-default">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"> <i class="fa fa-plus"></i>
                        <?=trans('add_new_company')?> </h3>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('admin/company');?>" class="btn btn-success"><i class="fa fa-list"></i>
                        <?=trans('company_list')?></a>
                </div>
            </div>
            <div class="card-body ">

                <!-- For Messages -->
                <?php $this->load->view('admin/includes/_messages.php')?>
                <?php echo form_open(base_url('admin/company/add'), ["class" => "form-horizontal row", "enctype" => "multipart/form-data", 'id' => 'frm-add-cmpny']) ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="logo" class="control-label "><?=trans('company_logo')?></label>
                        <div class='col-md-12 d-flex flex-column align-items-center'>
                            <div class='d-flex justify-content-center align-items-center position-relative'
                                style='width:250px;height:190px;background:#f3f1f1;border-radius:10px;'>
                                <span style='font-size:45px;color:#ccc'>40 X 40</span>
                                <img class='position-absolute' height='150' width='150' src="" id="logo" class="logo"
                                    style='display:none;'>
                            </div>
                            <button type="button" onclick="$('#exampleInputFile').trigger('click');"
                                class="btn btn-sm btn-success mt-1" style='width:110px'>Choose a File</button>
                            <p style="margin: 0px;"><small class="text-success"><?=trans('allowed_types')?>: gif, jpg,
                                    png, jpeg</small></p>
                            <input type="file" name="logo" class='' id="exampleInputFile"
                                accept=".png, .jpg, .jpeg, .gif, .svg" style='display:none'>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="companyname" class="control-label"><?=trans('companyname')?></label>
                        <input type="text" name="companyname" class="form-control" id="companyname"
                            placeholder="Enter company name">
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email" class="control-label"><?=trans('email')?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    <input type="email" name="email" class="form-control" id="email"
                                        placeholder="Enter your email">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="mobile_no"><?=trans('mobile_no')?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                    </div>
                                    <input type="number" name="mobile_no" class="form-control" id="mobile_no"
                                        data-inputmask='"mask": "999-999-9999"' data-mask
                                        placeholder="Enter your mobile number">
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password" class="control-label"><?=trans('licenseno')?></label>
                                <input type="number" name="license" class="form-control" id="license"
                                    placeholder="eg: 4">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <!-- <div class="form-group">
                                        <label for="license_date"><?=trans('license_date')?></label>
                                        <div class="input-group date" id="license_date" data-target-input="nearest">
                                            <input type="text" name="license_date" id="license_date"
                                                class="form-control datetimepicker-input" data-target="#license_date" />
                                            <div class="input-group-append" data-target="#license_date"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div> -->
                            <div class="form-group">
                                <label for="license_date">Expiry Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <input type="text" name="license_date" id="license_date" class="form-control"
                                        placeholder="Select expiry date" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address" class="control-label"><?=trans('address')?></label>
                        <textarea name="address" class="form-control" id="address"
                            placeholder="Type your address"></textarea>
                    </div>
                    <!-- <div class="form-group">
                                <label for="lastname" class="col-md-12 control-label"><?=trans('status')?></label>
                                <select name="status" class="form-control">
                                    <option value="" selected><?=trans('select_status')?></option>
                                    <option value="1">Active</option>
                                    <option value="0">Deactive</option>
                                </select>
                            </div> -->
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username" class="control-label"><?=trans('username')?></label>

                        <input type="text" name="username" readonly class="form-control" id="username"
                            placeholder="Enter your username">
                    </div>
                    <div class="form-group">
                        <label for="firstname" class="control-label"><?=trans('firstname')?></label>

                        <input type="text" name="firstname" class="form-control" id="firstname"
                            placeholder="Enter your first name">
                    </div>

                    <div class="form-group">
                        <label for="lastname" class="control-label"><?=trans('lastname')?></label>

                        <input type="text" name="lastname" class="form-control" id="lastname"
                            placeholder="Enter your last name">
                    </div>

                    <div class="form-group">
                        <label for="password" class="control-label"><?=trans('password')?></label>

                        <input type="password" readonly name="password" class="form-control" id="password"
                            placeholder="Enter your password">
                    </div>
                    <div class="alert alert-info alert-dismissible">
                        <ul style="padding: 0 15px; margin:0px">
                            <li> Must be of 8 character or more</li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <input type="submit" name="submit" value="<?=trans('add_company')?>"
                            class="btn btn-primary pull-right">
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <!-- /.box-body -->
</div>
</section>
</div>
<script>
$("#add-logo").click(function() {
    $("#logo").click();
})

$("#logo").change(function() {
    var file = this.files[0];
    if (file) {
        $("#logo-file").attr('src', URL.createObjectURL(file));
    }
})

$("#div-content").hover(function() {
    if ($("#logo-file").attr('src')) {
        $('#add-logo').fadeIn(80).css('display', 'flex');
    }
}, function() {
    if ($("#logo-file").attr('src')) {
        $('#add-logo').fadeOut(80);
    }
});
</script>
<script>
$(function() {
    $('#status').click(function() {
        $('#status').val('1');
    })
    $('#frm-add-cmpny').validate({
        rules: {
            companyname: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            mobile_no: {
                required: true
            },
            license: {
                required: true,
            },
            license_date: {
                required: true,
            },
            address: {
                required: true,
                minlength: 5
            },
            logo: {
                required: true,
            },
            username: {
                required: true,
            },
            firstname: {
                required: true,
            },
            lastname: {
                required: true,
            },
            status: {
                required: true,
            },
            password: {
                required: true,
                minlength: 8
            }
        },
        messages: {

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
    //Datemask dd/mm/yyyy
    $('#license_date').datepicker({
        dateFormat: "dd/mm/yy",
        changeYear: true,
        changeMonth: true,
        minDate: new Date(),
    });
    $("#exampleInputFile").change(function() {
        // id = $('#extension').val();
        // console.log(id);
        var file = this.files[0];
        let path =
            console.log(file);
        if (file) {
            $("#logo").attr('src',
                URL.createObjectURL(file)
            );
            $('#logo').show();
        }
    })
})
</script>