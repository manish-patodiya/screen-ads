<link rel="stylesheet" href=<?=base_url("assets/plugins/select2/select2.min.css")?>>
<link rel="stylesheet" href=<?=base_url("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")?>>
<style>
.selectpicker {
    background: #FFF;
    color: #000;
}
</style>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="card card-default">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"> <i class="fa fa-pencil"></i>
                        &nbsp; <?=trans('add_new_doctor')?> </h3>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('admin/doctors');?>" class="btn btn-success"><i class="fa fa-list"></i>
                        <?=trans('users_list')?></a>
                </div>
            </div>
            <div class="card-body ">

                <!-- For Messages -->
                <?php $this->load->view('admin/includes/_messages.php')?>

                <?php echo form_open(base_url('admin/doctors/add/'), ["class" => "form-horizontal", "enctype" => "multipart/form-data", 'id' => 'frm-add-doctor']) ?>
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-12 control-label"><?=trans('doctor_name')?><span class="req">
                                    *</span></label>

                            <div class="col-md-12">
                                <input type="text" name="username" readonly value="" class="form-control" id="doc_name"
                                    placeholder="Enter doctor name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label
                                class="col-md-12 control-label"><?=trans('doctor_speciality') . "/" . trans('dept')?><span
                                    class="req"> *</span></label>
                            <div class="col-md-12">

                                <select class="form-control" style="width: 100%;" name="department_id"
                                    id="department_id">
                                    <option value="">Select your Speciality</option>
                                    <?php
foreach ($departments as $k => $v) {
    ?>
                                    <option value="<?=$v['id']?>">
                                        <?=$v['department_name']?></option>
                                    <?php
}
?>
                                </select>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="col-md-12 control-label"><?=trans('doctor_qualification')?><span class="req">
                                    *</span></label>
                            <div class="col-md-12">
                                <select class="form-control select2 multiple-select" multiple="multiple"
                                    name="qualification_id[]" style="width: 100%;">
                                    <option> Select your Qualification </option>
                                    <?php
foreach ($qualifications as $k => $v) {
    ?>
                                    <option value="<?=$v['id']?>"> <?=$v['title']?></option>
                                    <?php
}
?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 control-label"><?=trans('doctor_affiliation')?>
                                <span class="req"> *</span></label>
                            <div class="col-md-12">
                                <select class="form-control select2" name="affiliation_id[]" multiple="multiple"
                                    style="width: 100%;">
                                    <option> Select your Affiliation </option>
                                    <?php
foreach ($affiliations as $k => $v) {
    ?>
                                    <option value="<?=$v['id']?>">
                                        <?=$v['title']?></option>
                                    <?php
}
?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label"><?=trans('doctor_shift')?></label>

                            <!-- <?php
// $day = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
// foreach ($day as $day) {?>
-->
                            <div class="col-md-12">
                                <select class="form-control select2" name="day[]" id="time" multiple="multiple"
                                    style="width: 100%;">
                                    <option value="">Select your availability time</option>
                                    <?php
foreach ($availabilities as $k => $v) {
    ?>
                                    <option value="<?=$v['id'];?>"><?=$v['time'];?></option>
                                    <?php
}
?>
                                </select>
                            </div>
                        </div><br>
                    </div>

                    <div class="col-md-5">

                        <label for="lastname" class="control-label"><?=trans('doctor_image')?></label>
                        <div class="col-md-12" id='div-content'>
                            <div class="d-flex flex-column justify-content-center align-items-center m-3">
                                <div class="d-flex justify-content-center align-items-center"
                                    style='border:3px dashed #ccc; border-radius:10px; height:300px;width:100%;background:#f3f1f1;'
                                    id='img_video'>
                                    <div class="my-3 position-relative" style='height:90%;width:85%'>
                                        <div class='h-100 w-100' id='div-all-content'> </div>
                                        <div id='add-file'
                                            class='position-absolute h-100 w-100 justify-content-center align-items-center'
                                            style='top:0; left:0; display:flex'>
                                            <span style="font-size: 35px; color:#ccc; cursor:pointer"> Drop Your File
                                                Here<span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group justify-content-center">
                                <input type="file" name="doctors_image" id="doctors_image" accept="image/*"
                                    class="hidden">
                                <button type="button" onclick="$('#doctors_image').trigger('click');"
                                    class="btn btn-sm btn-success mt-1" style='width:110px'>Choose a File</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <input type="submit" name="submit" value="<?=trans('add_doctor')?>" class="btn btn-primary pull-right">
            </div>
        </div>
        <?php echo form_close(); ?>
</div>
</div>
</section>
</div>

<script>
$(document).ready(function() {
    $("#add-file").click(function() {
        $("#doctors_image").click();
    })

    $("#doctors_image").change(function() {
        var file = this.files[0];
        console.log(file);
        if (file) {
            $("#media-file").attr('src', URL.createObjectURL(file));
        }
        $('#media-file').height('200');
        $('#media-file').width('200');
    })

    $("#div-content").hover(function() {
        if ($("#media-file").attr('src')) {
            $('#add-file').fadeIn(80).css('display', 'flex');
        }
    }, function() {
        if ($("#media-file").attr('src')) {
            $('#add-file').fadeOut(80);
        }
    });

})
</script>
<script>
$(document).ready(function() {
    $('.multi-select').selectpicker();
    $('.select2').select2({
        tags: true,
    })

    $('#frm-add-doctor').validate({
        rules: {
            username: {
                required: true,
            },
            department_id: {
                required: true,
            },
            'qualification_id[]': {
                required: true
            },
            'affiliation_id[]': {
                required: true
            },
            'time[]': {
                required: true
            },
            status: {
                required: true,
            },
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
    $("#doctors_image").change(function() {
        var file = this.files[0];
        let image =
            `<img src="${URL.createObjectURL(file)}" alt=" Admin" class="w-100 h-100"    id="image">`
        $('#div-all-content').html(image);
        let path =
            console.log(file);
        if (file) {
            $("#image").attr('src',
                URL.createObjectURL(file)
            );
        }
    })
    $("#div-content").hover(function() {
        if ($("#image").attr('src') || $("#video").attr('src') || $("#audio").attr('src')) {
            $('#add-file').fadeOut(80);
        }
    });
})
</script>