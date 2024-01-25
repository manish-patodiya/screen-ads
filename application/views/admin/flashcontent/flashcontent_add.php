<link rel="stylesheet" href=<?=base_url("assets/plugins/colorpicker-bootstrap4/bootstrap-colorpicker.min.css")?>>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<style>
.form-check-input {
    position: absolute;
    margin-top: 0.3rem;
    margin-left: 0.75rem;
}

.mt-2,
.my-2 {
    margin-top: 2rem !important;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="card card-default">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"> <i class="fa fa-plus"></i>
                        <?=trans('add_new_flashcontent')?> </h3>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('admin/flashcontent');?>" class="btn btn-success"><i class="fa fa-list"></i>
                        <?=trans('flashcontent_list')?></a>
                </div>
            </div>
            <!-- For Messages -->
            <?php $this->load->view('admin/includes/_messages.php')?>

            <?php echo form_open(base_url('admin/flashcontent/add'), ["class" => "form-horizontal", "enctype" => "multipart/form-data", 'id' => 'frm-add-flsh-cntnt', 'target' => '']); ?>
            <div class="card-body row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="language" class="col-md-12 control-label"><?=trans('language')?></label>

                        <div class="col-md-12">
                            <input type="text" name="language" class="form-control hidden" id="language" placeholder="">
                            <select class="form-control slct-lang" id="language" name="language">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="language" class="col-md-12 control-label"><?=trans('title')?><span class="req">
                                *</span></label>
                        <div class="col-md-12">
                            <input type="text" name="title" class="form-control " id="title"
                                placeholder="Enter the title of flash content">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="flashcontentname" class="col-md-12 control-label"><?=trans('content')?><span
                                class="req"> *</span></label>

                        <div class="col-md-12">
                            <textarea type="test" name="content" class="form-control"
                                onkeypress='this.value.trim()==""?this.value="":false' id="content"
                                style="background:white;" data-text="Enter your text here" spellcheck="false"
                                placeholder='Type your content' rows='3'></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Position" class="col-md-12 control-label"><?=trans('position')?><span class="req">
                                *</span></label>

                        <div class="col-md-12">
                            <select name="position" class="form-control" id="position">
                                <option value="">Select position</option>
                                <option>Top</option>
                                <option>Bottom</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-12"><?=trans('logo')?></label>
                        <div class='col-md-12 '>
                            <img src="" id="logo" class="logo" style='display:none;max-height:120px'>
                            <div>
                                <button type="button" onclick="$('#exampleInputFile').trigger('click');"
                                    class="btn btn-sm btn-success mt-1" style='width:110px'>Choose a File</button>
                            </div>
                            <p><small class="text-success"><?=trans('allowed_types')?>: gif, jpg, png,
                                    jpeg</small><br>
                                <small class="text-success">Max Height: 120px</small><br>
                            </p>
                            <input type="file" name="logo" class='' id="exampleInputFile"
                                accept=".png, .jpg, .jpeg, .gif, .svg" style='display:none'>
                        </div>
                    </div>
                </div>
                <div class='col-md-6'>
                    <label for="property" class="col-md-12 control-label">Settings</label>
                    <?php foreach ($property as $val) {?>
                    <div class='form-group col-md-12 row'>
                        <input type="hidden" name='label[]' class='form-control' readonly
                            value='<?=$val['property']?>' />
                        <label for="" class='col-md-4'><?=$val['labels']?></label>
                        <div class="col-md-6 d-flex flex-row">
                            <span class='mr-2'>: </span>
                            <?php if (in_array($val['property'], ['color', 'background-color'])) {?>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="property[]" class="form-control property" autocomplete="off">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-1px fa-square"></i></span>
                                </div>
                            </div>
                            <?php } else if ($val['property'] == 'font-size') {?>
                            <select class="form-control property" name="property[]"
                                onchange='disableHeight(this.value)'>
                                <option value="">Select font size</option>
                                <?php for ($fs = 16; $fs <= 60; $fs += 2) {?>
                                <option value='<?=$fs . 'px'?>'><?=$fs . 'px'?></option>
                                <?php }?>
                            </select>
                            <?php } else if ($val['property'] == 'font-weight') {?>
                            <select class="form-control property" name="property[]">
                                <option value="">Select font weight</option>
                                <?php for ($fw = 400; $fw <= 800; $fw += 100) {?>
                                <option value='<?=$fw?>'><?=$fw?></option>
                                <?php }?>
                            </select>
                            <?php } else if ($val['property'] == 'height') {?>
                            <select class="form-control property" name="property[]" id='height'>
                                <option value="">Select height</option>
                                <?php for ($h = 20; $h <= 120; $h += 5) {?>
                                <option value='<?=$h . 'px'?>'><?=$h . 'px'?></option>
                                <?php }?>
                            </select>
                            <?php } else if ($val['property'] == 'font-family') {?>
                            <select class="form-control property" name="property[]">
                                <option value="">Select font family</option>
                                <option value='Georgia, serif'>Georgia, serif</option>
                                <option value='sans-serif'>sans-serif</option>
                                <option value='serif'>serif</option>
                                <option value='cursive'>cursive</option>
                                <option value='system-ui'>system-ui</option>
                                <option value='inherit'>inherit</option>
                                <option value='initial'>initial</option>
                                <option value='revert'>revert</option>
                                <option value='revert-layer'>revert-layer</option>
                                <option value='unset'>unset</option>
                            </select>
                            <?php }?>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <div class="col-md-12 mb-3">
                    <input type="submit" name="submit" value="<?=trans('add_flash')?>"
                        class="btn btn-primary pull-right ml-2" id='btn-submit'>

                    <button type="submit" class="btn btn-success pull-right" id='view'><a class="text-50 fw-bold"
                            style="color: white;"><?=trans('preview')?></a></button>
                </div>
            </div>

            <?php echo form_close(); ?>
            <!-- /.box-body -->
        </div>
    </section>
</div>

<script type="text/javascript" src="<?=base_url()?>assets/dist/js/plugins/translate/langConvert.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/dist/js/plugins/translate/google_jsapi.js"></script>
<script src="<?=base_url()?>assets/plugins/colorpicker-bootstrap4/bootstrap-colorpicker.min.js">
</script>
<!-- <script src="<?=base_url()?>assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script> -->


<script>
$(function() {

    $('.my-colorpicker2').colorpicker();
    $(document).on('colorpickerChange', '.my-colorpicker2', function(event) {
        $(this).find('.fa-square').css('color', event.color
            .toString());
    })

    //...................
    // .........
    var property_arr = [];
    //Language Translate function........................
    $(function() {
        googleTranslater(["content"])
    })

    //....................................
    $('#view').click(function() {
        let image = $("#logo").attr('src')
        $('#frm-add-flsh-cntnt').attr('action', base_url +
            `admin/flashcontent/view_flashcontent?img=` + image);
        $('#frm-add-flsh-cntnt').attr('target', '_blank');
        $('form').last().submit();
    });

    $('#btn-submit').click(function() {
        $('#frm-add-flsh-cntnt').attr('action', base_url + `admin/flashcontent/add`);
        $('#frm-add-flsh-cntnt').attr('target', '');
        $('form').last().submit();
    })

    $('#status').click(function() {
        $('#status').val('1');
    })

    $('#frm-add-flsh-cntnt').validate({
        rules: {
            title: {
                required: true,
            },
            language: {
                required: true,
            },
            content: {
                required: true,
            },
            media_file: {
                required: true
            },
            position: {
                required: true
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
})
</script>
<script>
let pro = 1;
$(function() {
    $(document).on("click", "#plus", function() {
        if ($('.close_window').length + 1 != $('.prop').val()) {
            let property = {
                url: base_url + "admin/flashcontent/properties",
                method: "post",
                dataType: "json",
                success: function(res) {
                    if (res.status == "1") {
                        let data = res.data
                        let option = '';
                        res.data.map(function(key) {

                            option +=
                                `<option value="${key.property}">${ key.property}</option>`
                        });
                        $("#property_value").append(`
            <div class="row m-2 close_window" id="close_window${pro}">
            <div class='col-md-5'>
                                <select class="form-control label" onchange='setColorPicker(this.value,${pro})' name="label[]" id=''>
                                    <option value='' >Select</option>
                                          ${option}
                                     </select>
                            </div>
                            <div class='col-md-5'>
                            <div id='div-property${pro}'>
                                <input type="text" name="property[]" class="form-control property" placeholder="">
                            </div>
                            </div>
                            <div class='col-md-2 text-right'>
                                <button id="close" onclick='deleteDiv(${pro++})' type="button" class="btn btn-danger pull-right"> <i
                                        class="fa fa-window-close " aria-hidden="true"></i></button>

                            </div></div>`);
                    }
                }
            }
            $.ajax(property);
        }
    })
    $(document).ready(function() {
        $('select').on('change', function(event) {
            var prevValue = $(this).data();
            $('select').not(this).find('option[value="' + prevValue + '"]').show();
            var value = $(this).val();
            $(this).data('previous', value);
            $('select').not(this).find('option[value="' + value + '"]').hide();
        });
    });
    $("#exampleInputFile").change(function() {
        var file = this.files[0];
        if (file) {
            $("#logo").attr('src',
                URL.createObjectURL(file)
            );
            $('#logo').show();
        }
    })
})

function disableHeight(val) {
    let fs = Number(val.replace("px", ""));
    if (Number($(`#height`).val().replace("px", "")) <= fs) {
        $(`#height`).val('');
    }
    if (val) {
        $('#height option').map(function(e) {
            h = Number(this.value.replace("px", ""));
            if (h > fs || !h) {
                $(`#height option[value='${this.value}']`).attr('disabled', false);
            } else {
                $(`#height option[value='${this.value}']`).attr('disabled', true);
            }
        });
        $(`#height`).closest('.row').slideDown();
    } else {
        $(`#height`).closest('.row').slideUp();
    }
}

disableHeight('');

// function deleteDiv(id) {
//     $('#close_window' + id).detach();
// }
</script>