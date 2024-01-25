<link rel="stylesheet" href=<?=base_url("assets/plugins/colorpicker-bootstrap4/bootstrap-colorpicker.min.css")?>>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="card card-default">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"> <i class="fa fa-pencil"></i>
                        &nbsp; <?=trans('edit_flashcontent')?> </h3>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('admin/flashcontent');?>" class="btn btn-success"><i class="fa fa-list"></i>
                        <?=trans('flashcontent_list')?></a>
                    <a href="<?=base_url('admin/flashcontent/add');?>" class="btn btn-success"><i
                            class="fa fa-plus"></i>
                        <?=trans('add_new')?></a>
                </div>
            </div>
            <div class="card-body">
                <!-- For Messages -->
                <?php $this->load->view('admin/includes/_messages.php')?>

                <?php echo form_open(base_url('admin/flashcontent/edit/' . $flashcontent['id']), ["class" => "form-horizontal", "enctype" => "multipart/form-data", 'id' => 'frm-edit-flsh-cntnt', 'target' => '']) ?>
                <input type="text" class=" id hidden" value='<?=$flashcontent['id']?>'>
                <div class="card-body row">
                    <input type="text" class="id hidden">
                    <div class="col-md-6">


                        <div class="form-group">
                            <label for="language" class="col-md-12 control-label"><?=trans('language')?></label>

                            <div class="col-md-12">
                                <input type="text" name="language" value="<?=$flashcontent['language'];?>"
                                    class="form-control hidden" placeholder="">
                                <select class="form-control slct-lang" id="language" name="language">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="language" class="col-md-12 control-label"><?=trans('title')?><span class="req">
                                    *</span></label>
                            <div class="col-md-12">
                                <input type="text" name="title" class="form-control " id="title"
                                    placeholder="Enter the title of flash content" value='<?=$flashcontent['title']?>'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Flashcontentname" class="col-md-12 control-label"><?=trans('content')?><span
                                    class="req"> *</span></label>

                            <div class="col-md-12">
                                <textarea type="test" name="content"
                                    onkeypress='this.value.trim()==""?this.value="":false' class="form-control"
                                    id="content" placeholder="" rows='3' data-text="Enter your text here"
                                    spellcheck="false"
                                    placeholder='Type your content'><?=$flashcontent['content'];?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Position" class="col-md-12 control-label"><?=trans('position')?><span
                                    class="req"> *</span></label>
                            <div class="col-md-12">
                                <select name="position" class="form-control" id="position">
                                    <option value=""><?=trans('select_status')?></option>
                                    <option value="Top" <?=$flashcontent['position'] == 'Top' ? 'selected' : ''?>>Top
                                    </option>
                                    <option value="Bottom" <?=$flashcontent['position'] == 'Bottom' ? 'selected' : ''?>>
                                        Bottom
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-12"><?=trans('logo')?></label>
                            <div class='col-md-12'>
                                <img src="<?=$flashcontent['media_file'];?>" id="logo" class="logo"
                                    style='max-height:120px;display:<?=$flashcontent['media_file'] ? 'block' : 'none'?>'>
                                <div>
                                    <button type="button" onclick="$('#exampleInputFile').trigger('click');"
                                        class="btn btn-sm btn-success mt-1" style='width:110px'>Choose a File</button>
                                </div>
                                <p><small class="text-success"><?=trans('allowed_types')?>: gif, jpg,
                                        png, jpeg</small><br>
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
                                <!-- Condition for colors -->
                                <?php if (in_array($val['property'], ['color', 'background-color'])) {?>
                                <div class="input-group my-colorpicker2">
                                    <?php $cvalue = isset($json[$val['property']]) ? $json[$val['property']] : ''?>
                                    <input type="text" name="property[]" class="form-control property"
                                        autocomplete="off" value='<?=$cvalue?>'>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-1px fa-square"
                                                style='color:<?=$cvalue?>'></i></span>
                                    </div>
                                </div>
                                <!-- condition for font size -->
                                <?php } else if ($val['property'] == 'font-size') {?>
                                <select class="form-control property" name="property[]"
                                    onchange='disableHeight(this.value)'>
                                    <option value="">Select font size</option>
                                    <?php for ($fs = 16; $fs <= 60; $fs += 2) {?>
                                    <?php $slct = isset($json[$val['property']]) && $json[$val['property']] == $fs . 'px' ? 'selected' : '';?>
                                    <option value='<?=$fs . 'px'?>' <?=$slct?>><?=$fs . 'px'?></option>
                                    <?php }?>
                                </select>
                                <!-- condition for font weight -->
                                <?php } else if ($val['property'] == 'font-weight') {?>
                                <select class="form-control property" name="property[]">
                                    <option value="">Select font weight</option>
                                    <?php for ($fw = 400; $fw <= 800; $fw += 100) {?>
                                    <?php $slct = isset($json[$val['property']]) && $json[$val['property']] == $fw ? 'selected' : '';?>
                                    <option value='<?=$fw?>' <?=$slct?>><?=$fw?></option>
                                    <?php }?>
                                </select>
                                <!-- condition for height -->
                                <?php } else if ($val['property'] == 'height') {?>
                                <select class="form-control property" name="property[]" id='height'>
                                    <option value="">Select height</option>
                                    <?php for ($h = 20; $h <= 120; $h += 5) {?>
                                    <?php $slct = isset($json[$val['property']]) && $json[$val['property']] == $h . 'px' ? 'selected' : '';?>
                                    <option value='<?=$h . 'px'?>' <?=$slct?>><?=$h . 'px'?></option>
                                    <?php }?>
                                </select>
                                <!-- condition for font-family -->
                                <?php } else if ($val['property'] == 'font-family') {?>
                                <select class="form-control property" name="property[]">
                                    <?php $v = isset($json[$val['property']]) ? $json[$val['property']] : ''?>

                                    <option value="">Select font family</option>
                                    <option value='Georgia, serif' <?=$v == "Georgia, serif" ? "selected" : ""?>>
                                        Georgia,
                                        serif</option>
                                    <option value='sans-serif' <?=$v == "sans-serif" ? "selected" : ""?>>
                                        sans-serif
                                    </option>
                                    <option value='serif' <?=$v == "serif" ? "selected" : ""?>>serif</option>
                                    <option value='cursive' <?=$v == "cursive" ? "selected" : ""?>>cursive
                                    </option>
                                    <option value='system-ui' <?=$v == "system-ui" ? "selected" : ""?>>system-ui
                                    </option>
                                    <option value='inherit' <?=$v == "inherit" ? "selected" : ""?>>inherit
                                    </option>
                                    <option value='initial' <?=$v == "initial" ? "selected" : ""?>>initial
                                    </option>
                                    <option value='revert' <?=$v == "revert" ? "selected" : ""?>>revert</option>
                                    <option value='revert-layer' <?=$v == "revert-layer" ? "selected" : ""?>>
                                        revert-layer</option>
                                    <option value='unset' <?=$v == "unset" ? "selected" : ""?>>unset</option>
                                </select>
                                </select>
                                <?php }?>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                    <div class="col-md-12 mt-5">
                        <input type="submit" name="submit" value="<?=trans('update')?>"
                            class="btn btn-primary pull-right ml-2" id='btn-submit'>
                        <button type="submit" class="btn btn-success pull-right" id='view'><a class="text-50 fw-bold"
                                style="color: white;"><?=trans('preview')?></a></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <!-- /.box-body -->
        </div>
    </section>
</div>
<script type="text/javascript" src="<?=base_url()?>assets/dist/js/plugins/translate/langConvert.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/dist/js/plugins/translate/google_jsapi.js"></script>
<script src="<?=base_url()?>assets/plugins/colorpicker-bootstrap4/bootstrap-colorpicker.min.js">
</script>
<script>
$(function() {
    //Language Translate function........................
    $(function() {
        googleTranslater(["content"])
    })
    $('#content').keyup(function() {
        this.value = this.value.toUpperCase();
    });
    //.................
    $('#frm-edit-flsh-cntnt').validate({
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
$(function() {

    $('#view').click(function() {
        let image = $("#logo").attr('src')
        $('#frm-edit-flsh-cntnt').attr('action', base_url +
            `admin/flashcontent/view_flashcontent?img=` + image);
        $('#frm-edit-flsh-cntnt').attr('target', '_blank');
        $('form').last().submit();
    });

    $('#btn-submit').click(function() {
        let id = $('.id').val();
        $('#frm-edit-flsh-cntnt').attr('action', base_url + `admin/flashcontent/edit/` + id);
        $('#frm-edit-flsh-cntnt').attr('target', '');
        $('form').last().submit();
    })

    $('.my-colorpicker2').colorpicker({
        default: '#FDFDFD',
    });
    $(document).on('colorpickerChange', '.my-colorpicker2', function(event) {
        $(this).find('.fa-square').css('color', event.color
            .toString());
    })
    $("#exampleInputFile").change(function() {
        var file = this.files[0];
        let path =
            console.log(file);
        if (file) {
            $("#logo").attr('src',
                URL.createObjectURL(file)
            );
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
disableHeight('<?=isset($json['font-size']) ? $json['font-size'] : 0?>');
</script>