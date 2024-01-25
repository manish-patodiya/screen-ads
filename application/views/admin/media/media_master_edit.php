<style>
.form-check-input {
    position: absolute;
    margin-top: 0.3rem;
    margin-left: 0.75rem;
}

.sp {
    color: #0056b3;

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
                        <?=trans('edit_mediamaster')?> </h3>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('admin/mediamaster');?>" class="btn btn-success"><i class="fa fa-list"></i>
                        <?=trans('mediamaster_list')?></a>
                </div>
            </div>
            <div class="card-body">
                <!-- For Messages -->
                <?php $this->load->view('admin/includes/_messages.php')?>
                <?php echo form_open(base_url('admin/mediamaster/edit/' . $media['id']), ["class" => "form-horizontal row", "enctype" => "multipart/form-data", 'id' => 'frm-edit-mdia-mstr']) ?>

                <div class="col-md-4 col-sm-12">

                    <div class="form-group">
                        <label for="language" class="col-md-12 control-label"><?=trans('media_type')?><span class="req">
                                *</span></label>


                        <div class="col-md-12">
                            <input type="text" name="media_type" value="<?=$media['media_type_id'];?>"
                                class="form-control hidden" id="media_type" placeholder="">
                            <select class="form-control" id="extension" name="media_type">
                                <option value=''>Select</option>
                                <?php foreach ($mt as $val) {?>
                                <?php if (in_array($val['id'], [1, 2, 3, 8])) {?>
                                <option value="<?php echo $val['id'] ?>"
                                    <?=$val['id'] == $media['media_type_id'] ? 'selected' : ''?>
                                    ext='<?=$val['extension']?>'>
                                    <?php echo $val['media_type'] ?>
                                </option>
                                <?php }?>
                                <?php }?>

                            </select>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="medianame" class="col-md-12 control-label"><?=trans('media_name')?><span
                                class="req"> *</span></label>

                        <div class="col-md-12">
                            <input type="text" name="media_name" value="<?=$media['media_name'];?>" class="form-control"
                                id="media_name" placeholder="Enter your media name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="remarks" class="col-md-12 control-label"><?=trans('remarks')?></label>
                        <div class="col-md-12">
                            <textarea type="text" name="remarks" value="<?=$media['remarks'];?>" class="form-control"
                                id="remarks" placeholder="Type your remarks" rows='5'><?=$media['remarks'];?></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 col-sm-12" style="display:block">
                    <div class="col-md-12" id='div-content'
                        style='display:<?=$media['media_type_id'] != 8 ? 'block' : 'none'?>;'>
                        <div class="d-flex flex-column justify-content-center align-items-center m-3">
                            <div id='alw-ext'>
                                <span class='text-success'><?=$mt[$media['media_type_id'] - 1]['extension']?></span>
                            </div>
                            <div class="d-flex justify-content-center align-items-center"
                                style='border:3px dashed #ccc; border-radius:10px; height:300px;width:495px;background:#f3f1f1;'
                                id='img_video'>
                                <div class="my-3 position-relative" style='height:95%;width:95%'>
                                    <div class='h-100 w-100' id='div-all-content'> </div>
                                    <div id='add-file'
                                        class='position-absolute h-100 w-100 justify-content-center align-items-center'
                                        style='top:0; left:0; display:flex'>

                                        <div id="img"
                                            class="h-100 w-100 d-flex justify-content-center align-items-center">

                                            <?php if ($media['media_type_id'] == 2) {?>
                                            <img src="<?=$media['media_file']?>" alt=" Admin" class="w-100 h-100"
                                                id="image">
                                            <?php } else if ($media['media_type_id'] == 1) {?>
                                            <video class="w-100 h-100" controls>
                                                <source src="<?=$media['media_file']?>" type="video/mp4" id='video'> .
                                            </video>
                                            <?php } else if ($media['media_type_id'] == 3) {?>
                                            <audio class="w-100 " style='height: 110px ' controls preload="auto">
                                                <source src="<?=$media['media_file']?>" type="audio/ogg" id='audio'>
                                            </audio>
                                            <?php }?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group justify-content-center">
                            <input type="file" class="custom-file-input" name="media_file" id="media_file"
                                accept="image/video/audio/*" style="display:none;">
                            <button type="button" onclick="$('#media_file').trigger('click');"
                                class="btn btn-success">Choose
                                a File</button>
                        </div>
                    </div>
                    <div class="col-md-12" id='div-youtube'
                        style=' display:<?=$media['media_type_id'] == 8 ? 'block' : 'none'?>;'>
                        <div class="d-flex justify-content-center align-items-center m-3">
                            <div class="d-flex justify-content-center align-items-center"
                                style='border:3px dashed #ccc; border-radius:10px; height:300px;width:495px;background:#f3f1f1;'
                                id="play_here">
                                <?php if ($media['media_type_id'] == 8) {$urlarr = explode("/", $media['media_file']);?>
                                <?php $iframesrc = '//www.youtube.com/embed/' . end($urlarr)?>
                                <iframe src="<?=$iframesrc?>" name="youtube" width="95%" height="95%" scrolling="no"
                                    id='youtube-src'></iframe>
                                <?php } else {?>
                                <span style=" font-size: 20px; "> YouTube Video Play Here </span>
                                <?php }?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class='row justify-content-center'>
                                <input type="text" name='youtube' class="form-control col-md-9" id="link-youtube"
                                    placeholder="Paste youtube link here" value='<?=$media['media_file']?>'>
                                <div class='err col-md-9 p-0 row'></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <div class="col-md-12">
                    <input type="submit" name="submit" value="<?=trans('update_media')?>"
                        class="btn btn-success pull-right">
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </section>
</div>
<script src="<?=base_url()?>assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
function validateYouTubeUrl(url) {
    if (url != undefined || url != '') {
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
        var match = url.match(regExp);
        if (match && match[2].length == 11) {
            return 'https://www.youtube.com/embed/' + match[2] + '?autoplay=1&enablejsapi=1';
        } else {
            return false;
        }
    }
}

$(function() {
    //..........................
    $(document).ready(function($) {
        var videobox = document.getElementById('div-youtube');
        $('#link-youtube').on('keyup', function() {
            if ($(this).val() === "") {
                $('#play_here').html(
                    `<span style=" font-size: 20px; "> YouTube Video Play Here </span>`);
            } else {
                var url = $(this).val();
                var src = validateYouTubeUrl(url);
                if (src) {
                    var ifrm = document.createElement('iframe');
                    ifrm.src = src;
                    // ifrm.src = (!url.includes('vimeo')) ? "//www.youtube.com/embed/" + url.split(
                    //         "/")[url.split('/').length - 1] : "//player.vimeo.com/video/" +
                    //     url.split("/")[3];
                    ifrm.name = 'youtube';
                    ifrm.width = "95%";
                    ifrm.height = "95%";
                    ifrm.frameborder = "0";
                    ifrm.scrolling = "no";
                    ifrm.id = 'youtube';
                    $('#play_here').html(ifrm);
                } else {
                    $('#play_here').html(
                        `<span style=" font-size: 20px; "> YouTube Video Play Here </span>`);
                }
            }
        });
    })


    //........................
    $(document).on('change', '#extension', function() {
        id = $(this).val();
        $('#media_file').val('');
        $('#link-youtube').val('');

        if (!id) {
            $('#alw-ext').html(
                `<span class='text-info'>Please select media type first</span>`);
            $('#div-content').show();
            $('#div-youtube').hide();
        } else if (id == 8) {
            $('#play_here').html(
                `<span style=" font-size: 20px; "> YouTube Video Play Here </span>`);
            $('#div-content').hide();
            $('#div-youtube').show();
        } else {
            exts = $('option:selected', this).attr('ext');
            $('#alw-ext').html(
                `<span class='text-success'>Allowed Types: ${exts.toLowerCase()}</span>`);
            $('#div-youtube').hide();
            $('#div-content').show();
            $('#div-all-content').html('');
            $('#add-file').fadeIn(80).css('display', 'flex');
        }
        $('#extensions').html('');
        let list = {
            url: base_url + 'admin/mediamaster/getMediaTypeExtension',
            method: "post",
            data: {
                'id': id
            },
            dataType: "json",
            success: function(res) {
                res.result.map(function(list) {
                    $('#extensions').append(
                        `<span > Supported File Format ${list.extension}</span>`
                    )
                })
            }
        }
        $.ajax(list)
    })
    //...................................
    $("#change-photo").click(function() {
        $("#media_file").click();
    })
    $("#media_file").change(function() {
        id = $('#extension').val();
        var file = this.files[0];
        if (file) {
            // console.log(file);
            switch (file.type = id) {
                case '1':

                    let video =
                        `<video class="w-100 h-100" controls>  <source src="${URL.createObjectURL(file)}" type="video/mp4" id='video'> </video>`
                    $('#img').html(video);
                    break;
                case '2':

                    let image =
                        `<img src="${URL.createObjectURL(file)}" alt=" Admin" class="w-100 h-100"    id="image">`
                    $('#img').html(image);
                    break;
                case '3':

                    let audio = `<audio class="w-100 " style='height: 110px ' controls preload="auto">
                       <source src="${URL.createObjectURL(file)}" type="audio/ogg" id='audio'>  </audio>`
                    $('#img').html(audio);
                    break;
            }
        }
    })


    // $("#media-image").hover(function() {
    //     if ($("#image").attr('src') || $("#video").attr('src') || $("#audio").attr('src')) {
    //         $('#change-photo').fadeIn(80).css('display', 'flex');
    //     }
    // }, function() {
    //     if ($("#image").attr('src') || $("#video").attr('src') || $("#audio").attr('src')) {
    //         $('#change-photo').fadeOut(20);
    //     }
    // });

    $('#frm-edit-mdia-mstr').validate({
        rules: {
            media_type: {
                required: true,
            },
            media_name: {
                required: true,
            },
            youtube: {
                required: true,
                youtube: true
            },
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
    $("#media_file").change(function() {
        id = $('#extension').val();
        var file = this.files[0];
        if (file) {
            switch (file.type = id) {
                case '1':
                    let video =
                        `<video class="w-100 h-100" controls >  <source src="${URL.createObjectURL(file)}" type="video/mp4" id='video'> </video>`
                    $('#div-all-content').html(video);
                    break;
                case '2':
                    let image =
                        `<img src="${URL.createObjectURL(file)}" alt=" Admin" class="w-100 h-100"    id="image">`
                    $('#div-all-content').html(image);
                    break;
                case '3':
                    let audio = `<audio class="w-100 " style='height: 110px ' controls preload="auto">
                       <source src="${URL.createObjectURL(file)}" type="audio/ogg" id='audio'>  </audio>`
                    $('#div-all-content').html(audio);
                    break;
            }
        }
    })

    jQuery.validator.addMethod("youtube", function(value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || validateYouTubeUrl(value);
    }, 'Youtube URL is wrong');
})
</script>