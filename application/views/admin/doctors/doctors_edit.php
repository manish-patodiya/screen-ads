  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
          <div class="card card-default">
              <div class="card-header">
                  <div class="d-inline-block">
                      <h3 class="card-title"> <i class="fa fa-pencil"></i>
                          &nbsp; <?=trans('edit_doctor')?> </h3>
                  </div>
                  <div class="d-inline-block float-right">
                      <a href="<?=base_url('admin/doctors');?>" class="btn btn-success"><i class="fa fa-list"></i>
                          <?=trans('users_list')?></a>
                      <a href="<?=base_url('admin/doctors/add');?>" class="btn btn-success"><i class="fa fa-plus"></i>
                          <?=trans('add_new_doctor')?></a>
                  </div>
              </div>
              <div class="card-body row">
                  <div class="col-md-6 col-sm-12">

                      <!-- For Messages -->
                      <?php $this->load->view('admin/includes/_messages.php')?>

                      <?php echo form_open(base_url('admin/doctors/edit/' . $doctor['did']), ["class" => "form-horizontal", "enctype" => "multipart/form-data", 'id' => 'frm-edit-doctor'])
?>
                      <div class="form-group">
                          <label for="username" class="col-md-12 control-label"><?=trans('doctor_name')?><span
                                  class="req"> *</span></label>

                          <div class="col-md-12">
                              <input type="text" name="name" value="<?=$doctor["name"]?>" class="form-control"
                                  id="doc_name" placeholder="Enter doctor name">
                          </div>
                      </div>
                      <div class="form-group">
                          <p class="m-0">
                              <label class="col-md-12 control-label"><?=trans('doctor_speciality')?><span class="req">
                                      *</span></label>
                              <select class="selectpicker form-control text-dark" name="department_id"
                                  id="department_id" data-live-search="true" required>
                                  <option value="">Select your Speciality</option>
                                  <?php foreach ($departments as $k => $v) {?>
                                  <option value="<?=$v['id']?>"
                                      <?=$v['id'] == $doctor['department_id'] ? "selected" : false?>>
                                      <?=$v['department_name']?></option>
                                  <?php }?>
                              </select>
                          </p>
                      </div>
                      <div class="form-group">
                          <label class="col-md-12 control-label"><?=trans('doctor_qualification')?><span class="req">
                                  *</span></label>
                          <div class="col-md-12">
                              <select class="form-control select2" multiple="multiple" name="qualification_id[]"
                                  style="width: 100%;">
                                  <option> Select your Qualification </option>
                                  <?php foreach ($qualifications as $k => $v) {
    $qids = [];
    foreach ($doctor["qualifications"] as $q) {
        $qids[] = $q["id"];
    }?>
                                  <option value="<?=$v['id']?>" <?=in_array($v['id'], $qids) ? "selected" : false?>>
                                      <?=$v['title']?></option>
                                  <?php }?>
                              </select>
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="email" class="col-md-12 control-label"><?=trans('doctor_affiliation')?><span
                                  class="req">
                                  *</span></label>
                          <div class="col-md-12">
                              <select class="form-control select2" name="affiliation_id[]" multiple="multiple"
                                  style="width: 100%;">
                                  <option> Select your Affiliation </option>
                                  <?php foreach ($affiliations as $k => $v) {
    $af_id = [];
    foreach ($doctor["affiliations"] as $a) {
        $af_id[] = $a["id"];
    }?>
                                  <option value="<?=$v['id']?>" <?=in_array($v['id'], $af_id) ? "selected" : false?>>
                                      <?=$v['title']?></option>
                                  <?php }?>
                              </select>
                          </div>
                      </div>


                      <div class="form-group">
                          <label class="col-md-2 control-label"><?=trans('doctor_shift')?></label>
                          <div class="col-md-12">

                              <select class="form-control select2" name="day[]" id="time" multiple="multiple"
                                  style="width: 100%;">
                                  <option value="">Select your availability time</option>
                                  <?php foreach ($availabilities as $k => $v) {
    // prd($v['id']);
    $av_id = [];
    foreach ($doctor["availabilities"] as $a) {
        $av_id[] = $a["availabilities_id"];
    }?>
                                  <option value="<?=$v['id']?>" <?=in_array($v['id'], $av_id) ? "selected" : false?>>
                                      <?=$v['time']?></option>
                                  <?php }?>
                              </select>
                          </div>
                          <br>
                      </div>
                  </div>
                  <div class="col-md-6 col-sm-12">
                      <label for="lastname" class="control-label"><?=trans('doctor_image')?></label>
                      <div class="col-md-12" id='div-content'>
                          <div class="d-flex flex-column justify-content-center align-items-center m-3">
                              <div class="d-flex justify-content-center align-items-center"
                                  style='border:3px dashed #ccc; border-radius:10px; height:300px;width:100%;background:#f3f1f1;'
                                  id='img_video'>
                                  <div class="my-3 position-relative" style='height:90%;width:85%'>
                                      <div class='h-100 w-100' id='div-all-content'><img
                                              src="<?=$doctor['image'] == "" ? base_url("uploads/no_image.jpg") : $doctor['image'];?>"
                                              alt=" Admin" class="w-100 h-100" id="image"> </div>
                                      <div id='add-file'
                                          class='position-absolute h-100 w-100 justify-content-center align-items-center'
                                          style='top:0; left:0; display:flex'>
                                          <span style="font-size: 35px; color:#ccc; cursor:pointer"> Drop Your File
                                              Here<span>
                                      </div>
                                  </div>
                              </div>
                              <button type="button" onclick="$('#doctors_image').trigger('click');"
                                  class="btn btn-sm btn-success mt-1" style='width:110px'>Choose a File</button>
                          </div>
                          <input type="file" name="doctors_image" id="doctors_image" accept="image/*" class="hidden">
                      </div>
                  </div>
              </div>

              <div class="form-group">
                  <div class="col-md-12">
                      <input type="submit" name="submit" value="<?=trans('update_doctor')?>"
                          class="btn btn-primary pull-right">
                  </div>
              </div>
              <?php echo form_close(); ?>
              <!-- /.box-body -->
          </div>
      </section>
  </div>

  <script>
$(document).ready(function() {
    $('.multi-select').selectpicker();
    $('.select2').select2({
        tags: true,
    })

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

    $('#frm-edit-doctor').validate({
        rules: {
            name: {
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
            status: {
                required: true
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
    $("#doctors_image").change(function() {
        // id = $('#extension').val();
        // console.log(id);
        var file = this.files[0];
        // let image =
        //     `<img src="${URL.createObjectURL(file)}" alt=" Admin" class="w-100 h-100"    id="image">`
        // $('#div-all-content').html(image);
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
  <script>
$("#image").click(function() {
    $("#doctors_image").click();
})
$("#doctors_image").change(function() {
    var file = this.files[0];
    if (file) {
        $("#member-profile-photo").attr('src', URL.createObjectURL(file));
    }
    // console.log($('#member-profile-photo').width());
    $("#member-profile-photo").prop("height", $('#member-profile-photo').width());
})

$("#add-member-profile").hover(function() {
    if ($("#member-profile-photo").attr('src')) {
        $('#change-photo').fadeIn(80).css('display', 'flex');
    }
}, function() {
    if ($("#member-profile-photo").attr('src')) {
        $('#change-photo').fadeOut(80);
    }
});
  </script>