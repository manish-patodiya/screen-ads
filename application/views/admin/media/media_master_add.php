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
                        <?=trans('upload_new_media')?> </h3>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('admin/mediamaster');?>" class="btn btn-success"><i class="fa fa-list"></i>
                        <?=trans('mediamaster_list')?></a>
                </div>
            </div>
            <div class="card-body">
                <!-- For Messages -->
                <?php $this->load->view('admin/includes/_messages.php')?>
                <!-- <form class="form-horizontal row" enctype="multipart/form-data" id='frm-add-mdia-mstr'> -->
                <?php echo form_open(base_url('admin/mediamaster/add'), ["class" => "form-horizontal row", "enctype" => "multipart/form-data", 'id' => 'frm-add-mdia-mstr']); ?>
                <div class="col-md-4 col-sm-12">

                    <div class="form-group">
                        <label for="language" class="col-md-12 control-label"><?=trans('media_type')?><span class="req">
                                *</span></label>

                        <div class="col-md-12">
                            <input type="text" name="media_type" class="form-control hidden" id="media_type"
                                placeholder="">
                            <select class="form-control" id="extension" name="media_type">
                                <option value=''>Select</option>
                                <?php foreach ($mt as $val) {$array = [1, 2, 3, 8];?>
                                <?php if (in_array($val['id'], $array)) {?>
                                <option value="<?php echo $val['id'] ?>" ext='<?=$val['extension']?>'>
                                    <?php echo $val['media_type'] ?></option>
                                <?php }?>
                                <?php }?>
                            </select>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="medianame" class="col-md-12 control-label"><?=trans('media_name')?><span
                                class="req"> *</span></label>

                        <div class="col-md-12">
                            <input type="test" name="media_name" class="form-control" id="media_name"
                                placeholder="Enter media name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="remarks" class="col-md-12 control-label"><?=trans('remarks')?></label>
                        <div class="col-md-12"><textarea type="text" name="remarks" class="form-control" id="remarks"
                                placeholder="Type your remarks" rows='5'></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-sm-12" style="display:block">
                    <div class="col-md-12" id='div-content'>
                        <div class="d-flex flex-column justify-content-center align-items-center m-3">
                            <div id='alw-ext'><span class='text-info'>Please select media type first</span></div>
                            <div class="d-flex justify-content-center align-items-center"
                                style='border:3px dashed #ccc; border-radius:10px; height:300px;width:495px;background:#f3f1f1;'
                                id='img_video'>
                                <div class="my-3 position-relative" style='height:95%;width:95%'>
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
                        <div class="progress m-3" style="display:none;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bar" aria-valuenow="75"
                                aria-valuemin="0" aria-valuemax="100" style="width: 40%;"></div>
                        </div>
                        <div class="input-group justify-content-center">
                            <input type="file" class="custom-file-input" name="media_file" id="media_file"
                                accept="image/video/audio/*" style="display:none;">
                            <button type="button" onclick="$('#media_file').trigger('click');"
                                class="btn btn-success">Choose
                                a File</button>
                        </div>
                    </div>
                    <div class="col-md-12" id='div-youtube' style='display:none;'>
                        <div class="d-flex justify-content-center align-items-center m-3">
                            <div class="d-flex justify-content-center align-items-center"
                                style='border:3px dashed #ccc; border-radius:10px; height:300px;width:495px;background:#f3f1f1;'
                                id="play_here">
                                <span style=" font-size: 20px; "> YouTube Video Play Here </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class='row justify-content-center'>
                                <input type="text" name='youtube' class="form-control col-md-9" id="link-youtube"
                                    placeholder="Paste youtube link here">
                                <div class='err col-md-9 p-0 row'></div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card-footer">
                <div class="col-md-12">
                    <input type="submit" name="submit" value="<?=trans('add_media_more')?>"
                        class="btn btn-success pull-right">
                </div>
            </div>
            </form>
            <!-- <?php echo form_close(); ?> -->
        </div>
    </section>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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
    $(document).ready(function($) {
        // $(function() {
        //     $("#frm-add-mdia-mstr").ajaxForm({
        //         beforeSend: function() {
        //             $(".progress").css('display', "");
        //             var percentVal = "0%";
        //             $(".bar").css("width", percentVal);
        //         },
        //         uploadProgress: function(event, position, total, percentComplete) {
        //             // $(".progress").css('display', "");
        //             var percentVal = percentComplete + "%";
        //             $(".bar").css("width", percentVal);
        //         },
        //         complete: function() {}
        //     })
        // })
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

    $("#div-content").hover(function() {
        if ($("#image").attr('src') || $("#video").attr('src') || $("#audio").attr('src')) {
            $('#add-file').fadeOut(80);
        }
    });


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
    //................................

    //....................
    $('#frm-add-mdia-mstr').validate({
        rules: {
            media_type: {
                required: true,
            },
            media_name: {
                required: true,
            },
            media_file: {
                required: true,
            },
            youtube: {
                required: true,
                youtube: true
            },
            status: {
                required: true,
            }
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback col font-100');
            if (element[0].name == 'youtube') {
                $(element).next('.err').append(error);
            } else {
                element.closest('.form-group').append(error);
            }
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

    jQuery.validator.addMethod("youtube", function(value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || validateYouTubeUrl(value);
    }, 'Youtube URL is wrong');
})
</script>