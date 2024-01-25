  <!-- Content Wrapper. Contains page content -->
  <link rel="stylesheet" href=<?=base_url("assets/plugins/colorpicker-bootstrap4/bootstrap-colorpicker.min.css")?>>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.5/dist/html2canvas.min.js">
  </script>
  <style>
.list {
    height: 50px;
}

.is_bold {
    font-weight: bold;
}

.is_italic {
    font-style: italic;
}

/* .unboldClass {
    font-weight: bold;
} */
  </style>
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
                          <?=trans('edit_template')?> </h3>
                  </div>
                  <div class="d-inline-block float-right">
                      <a href="<?=base_url('admin/templates');?>" class="btn btn-success"><i class="fa fa-list"></i>
                          <?=trans('templates')?></a>
                  </div>
              </div>
          </div>

          <div class="card">
              <div class="card-body table-responsive">
                  <div class="row">
                      <div class="col-12 col-lg-4 col-sm-5 col-md-5">
                          <div class="card">
                              <div class="card-header">
                                  <h3 class="card-title text-center">Tools</h3>
                                  <div class="card-tools">
                                  </div>
                              </div>
                              <div class="card-body collapseable p-0">
                                  <ul class="nav nav-pills flex-column">
                                      <li class="nav-item row list">
                                          <div class="col-md-6"
                                              style="padding:0.5rem 0.7rem; font-size:15px; display:block">
                                              <label for="">Logo on right:</label>
                                          </div>
                                          <div class="col-md-6"
                                              style="padding:0.5rem 0.7rem; font-size:15px; display:block">
                                              <input class="tgl_checkbox tgl-ios hidden" data-id="" id="right_pos"
                                                  type="checkbox">
                                              <label class="pull-right" for="right_pos"> </label>
                                          </div>
                                          <!-- </div> -->
                                      </li>
                                      <li class="nav-item row list">
                                          <div class="col-md-6"
                                              style="padding:0.5rem 0.7rem; font-size:15px; display:block">
                                              <label for="">Qualification:</label>
                                          </div>
                                          <div class="col-md-6"
                                              style="padding:0.5rem 0.7rem; font-size:15px; display:block">
                                              <input class="tgl_checkbox tgl-ios hidden" data-id="" id="quali_togl"
                                                  type="checkbox" value="quali" checked>
                                              <label class="pull-right" for="quali_togl"> </label>
                                          </div>
                                      </li>
                                      <li class="nav-item row list">
                                          <div class="col-md-4"
                                              style="padding:0.5rem 0.7rem; font-size:15px; display:block">
                                              <label for="">Affiliation:</label>
                                          </div>
                                          <div class="col-md-8"
                                              style="padding:0.5rem 0.7rem; font-size:15px; display:block">
                                              <input class="tgl_checkbox tgl-ios hidden" data-id="" id="affli_togl"
                                                  type="checkbox" value="affli" checked>
                                              <label class="pull-right" for="affli_togl"> </label>
                                          </div>
                                      </li>
                                      <li class="nav-item row list">
                                          <div class="col-md-6"
                                              style="padding:0.5rem 0.7rem; font-size:15px; display:block">
                                              <label for="">Morning Time</label>
                                          </div>
                                          <div class="col-md-6"
                                              style="padding:0.5rem 0.7rem; font-size:15px; display:block">
                                              <input class="tgl_checkbox tgl-ios hidden" data-id="" id="mor_togl"
                                                  type="checkbox" value="time" checked>
                                              <label class="pull-right" for="mor_togl"> </label>
                                          </div>
                                      </li>
                                      <li class="nav-item row list d-none" id="addrs_list">
                                          <div class="col-md-6"
                                              style="padding:0.5rem 0.7rem; font-size:15px; display:block">
                                              <label for="">Address</label>
                                          </div>
                                          <div class="col-md-6"
                                              style="padding:0.5rem 0.7rem; font-size:15px; display:block">
                                              <input class="tgl_checkbox tgl-ios hidden" data-id="" id="addrs_tgl"
                                                  type="checkbox" value="address" checked>
                                              <label class="pull-right" for="addrs_tgl"> </label>
                                          </div>
                                      </li>
                                      <li class="nav-item row list d-none" id="phone_list">
                                          <div class="col-md-6"
                                              style="padding:0.5rem 0.7rem; font-size:15px; display:block">
                                              <label for="">Phone</label>
                                          </div>
                                          <div class="col-md-6"
                                              style="padding:0.5rem 0.7rem; font-size:15px; display:block">
                                              <input class="tgl_checkbox tgl-ios hidden" data-id="" id="phone_tgl"
                                                  type="checkbox" value="phone" checked>
                                              <label class="pull-right" for="phone_tgl"> </label>
                                          </div>
                                      </li>
                                      <li class="nav-item row list d-none" id="about_list">
                                          <div class="col-md-6"
                                              style="padding:0.5rem 0.7rem; font-size:15px; display:block">
                                              <label for="">About</label>
                                          </div>
                                          <div class="col-md-6"
                                              style="padding:0.5rem 0.7rem; font-size:15px; display:block">
                                              <input class="tgl_checkbox tgl-ios hidden" data-id="" id="about_tgl"
                                                  type="checkbox" value="about" checked>
                                              <label class="pull-right" for="about_tgl"> </label>
                                          </div>
                                      </li>
                                  </ul>
                              </div>
                              <input type="hidden" value="<?=$template['id']?>" id="tmplt_id">
                          </div>
                      </div>
                      <?php
                        //  $template['content'] = str_replace(
                        //     ['{department}', '{name}', '{qualification}', '{affiliation}', '{morning_time}', '{evening_time}', '{image}'],
                        //     ['Gastroenterologist & Hepatologist', 'Atul Kumar', 'MBBS, MS, MD', 'AIRS, AHP, MCI', '9:00-1:00', '4:00-6:00', base_url('/uploads/doctors_image/doctors_image1651668213.jpg')], $template['content']
                        //     )?>
                      <div class="col-12 col-lg-8 col-sm-7 col-md-7">
                          <div class='border d-flex align-items-stretch flex-column' style="
                                    " id="card">
                              <?php echo $template['content']; ?>
                          </div>
                      </div>
                      <!-- <div class='border align-items-stretch flex-column' style="display:none;
                                    " id="ss_card">
                          <?php echo $template['content']; ?>
                      </div> -->
                      <div class="col-md-12 mt-3 float-right">
                          <button type="submit" class="btn btn-primary pull-right mr-2" id='save_tmplt'><a
                                  class="text-50 fw-bold" style="color: white;">Save</a></button>
                          <button type="submit" class="btn btn-primary pull-right mr-2" id='preview_tmplt'><a
                                  class="text-50 fw-bold" style="color: white;">Preview</a></button>
                      </div>
                  </div>
              </div>
              <?php echo form_open(base_url('admin/templates/view'),["class" => "form-horizontal", "enctype" => "multipart/form-data", 'id' => 'frm-add-view-tmplt', 'target' => '_blank']); ?>
              <textarea name="content" id="content-txt-area" cols="30" rows="10" class='d-none'></textarea>
              <?php echo form_close(); ?>
              <!-- <div class="colorpicker">
                  <input type="color" id="section1ParagraphColor" name="head" value="#fdffff"
                      onInput="updateColor('.heading','color',this.value)">
              </div> -->
          </div>
      </section>
  </div>
  <div id='popover_template'>
      <!-- <a class='btn btn-primary btn-sm btn-edit-tmp tt' title='Edit text' val='1'><i class='fa fa-edit'></i></a> -->
      <a class='btn btn-success btn-sm btn-edit-tmp tt' title='Increase size' val='1'><i class='fa fa-plus'></i></a>
      <a class='btn btn-danger btn-sm btn-edit-tmp tt' title='Reduce size' val='2'><i class='fa fa-minus'></i></a>
      <a class='btn btn-primary btn-sm btn-edit-tmp tt' title='Bold text' val='3'><i class='fa fa-bold'></i></a>
      <a class='btn btn-primary btn-sm btn-edit-tmp tt bold' title='Italic text' val='4'><i
              class='fa fa-italic'></i></a>
      <a class='btn btn-primary btn-sm btn-edit-tmp tt' title='Color picker' val='5'>

          <input type="color" id="" name="head" class="my-colorpicker2">
      </a>
  </div>
  <script src="<?=base_url()?>assets/plugins/colorpicker-bootstrap4/bootstrap-colorpicker.min.js">
  </script>
  <!-- <div id='increaseable_frame'>
      <a class='btn btn-success btn-sm btn-edit-frm tt' title='Increase size' val='1'><i class='fa fa-plus'></i></a>
      <a class='btn btn-danger btn-sm btn-edit-frm tt' title='Reduce size' val='2'><i class='fa fa-minus'></i></a>
  </div> -->
  <!-- ck editor -->
  <!-- <script src="<?=base_url()?>assets/plugins/ckeditor/ckeditor.js"></script> -->
  <script>
// $(function() {
//     CKEDITOR.replace('content');
// });

$(document).ready(function() {
    let ss;
    display_toggle = () => {
        let id = document.getElementById('address');
        let phone = document.getElementById('phone');
        let about = document.getElementById('about');
        if (id != null) {
            if ($("#addrs_list").hasClass("d-none")) {
                $("#addrs_list").removeClass('d-none')
            }
        }
        if (phone != null) {
            if ($("#phone_list").hasClass("d-none")) {
                $("#phone_list").removeClass('d-none')
            }
        }
        if (about != null) {
            if ($("#about_list").hasClass("d-none")) {
                $("#about_list").removeClass('d-none')
            }
        }
    }
    display_toggle()

    $(document).on("click", "#save_tmplt", function() {
        let card_html = $("#card").html()
        let tmplt_id = $("#tmplt_id").val()
        // console.log(card_html);
        $.ajax({
            url: base_url + "admin/templates/edit",
            data: {
                "content": card_html,
                "id": tmplt_id
            },
            method: "post",
            dataType: 'json',
            success: function(res) {
                if (res.status == "1") {
                    window.location = base_url + "admin/templates"
                    // console.log(res.msg)
                }
            }
        })
    })
    $(document).on('click', '.btn-edit-tmp', function(e) {
        e.stopPropagation();
        $('.tt').tooltip('hide');
        let val = $(this).attr('val');
        if (val == 1) {
            // let size = $(pop_ele).attr('size');
            // console.log(pop_ele)
            $(pop_ele).animate({
                "font-size": '+=10px'
            });
        } else if (val == 2) {
            // let size = $(pop_ele).attr('size');
            $(pop_ele).animate({
                "font-size": '-=10px'
            });
        } else if (val == 3) {
            $(pop_ele).toggleClass("is_bold").toggleClass("")
        } else if (val == 4) {
            $(pop_ele).toggleClass("is_italic").toggleClass("")

        } else if (val == 5) {

        }
    })
    // $(".colorPicker").oninput = () => {
    //     let newColor = $(".colorPicker").val()
    //     let rule = document.querySelector('.editable');
    //     rule.style.color = `${newColor}`;
    // }

    $(document).on('input', '.my-colorpicker2', function() {
        col = $(this).val();
        $(pop_ele).css('color', col);
    })

    $('.editable').popover({
        trigger: 'manual',
        placement: 'left',
        sanitize: false,
        customClass: "",
        html: true,
        content: function() {
            // console.log("test")
            var $buttons = $('#popover_template').html();
            return $buttons;
        }
    }).on("mouseenter", function() {
        var _this = this;
        // console.log(this)
        $(this).popover("show");
        pop_ele = _this;
        $(".popover").on("mouseleave", function() {
            $(_this).popover('hide');
        });
    }).on("mouseleave", function() {
        var _this = this;
        setTimeout(function() {
            if (!$(".popover:hover").length) {
                $(_this).popover("hide");
            }
        }, 100);
    }).on('shown.bs.popover', function() {
        $('.tt').tooltip();
    });

    $(".sortable").sortable({
        placeholder: "ui-state-highlight"
    });

    $('input[type="checkbox"]').map(function() {
        val = $(this).val();
        // console.log(val)
        if ($('#' + val).hasClass('d-none')) {
            $(this).attr('checked', false);
        }
    })

    $('input[type="checkbox"]').change(function() {
        val = $(this).val();
        is_chkd = $(this).is(":checked");
        if (is_chkd) {
            $("#" + val).removeClass('d-none');
        } else {
            $("#" + val).addClass('d-none');
        }
    })

    $(document).on("change", '#right_pos', function() {
        val = $(this).is(':checked') == true ? 1 : 0;
        // console.log(val)
        if (val == 0) {
            $(".text-portion").before($(".img-portion"));
            $(".img-portion").attr('right', '0');
        } else {
            $(".text-portion").after($(".img-portion"));
            $(".img-portion").attr('right', '1');
        }
    })
    if ($(".img-portion").attr('right') == 1) {
        $('#right_pos').prop('checked', true);
        $("#right_pos").val('1');
        // console.log($("#right_pos").val())
    }

    $("#preview_tmplt").click(function() {
        let card_html = $("#card").html()
        $('#content-txt-area').val(card_html)
        $('#frm-add-view-tmplt').submit();
    })

    // $(document).on("click", "#save_tmplt", function() {
    //     html2canvas($('#ss_card')
    //         // {
    //         //     onrendered: function(canvas) {
    //         //         var img = canvas.toDataURL()
    //         //         window.open(img);
    //         //     }
    //     ).then(canvas => {
    //         document.body.appendChild(canvas);
    //     })

    // function takeshot() {
    // let mydiv = $("#card").html()
    // console.log(mydiv);

    // html2canvas(mydiv, {
    //     onclone: function(clonedDoc) {
    //         // console.log(clonedDoc)
    //         $(clonedDoc).find('#ss_card').css('display', 'block');
    //     }
    // }).then(function(canvas) {
    //     //your onrendered function code here
    //     document
    //         .getElementById('ss_card')
    //         .appendChild(canvas);
    //     ss = canvas
    // })
    // console.log(ss)
    // }
    // })
})
  </script>