<style>
table {
    width: 100%;
}

table,
th,
td {
    border: 1px solid black;
    border-collapse: collapse;
}

.screen {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: end;
    justify-content: right;
    border: 1px solid #dee2e6 !important;
    position: relative;
    background-color: #6c757d !important;
    color: #fff2f2 !important;
    padding: 0 !important;
}

.media-content {
    height: 100%;
    width: 100%;
    position: absolute;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.bootstrap-select a,
.bootstrap-select button,
.bootstrap-select .dropdown-menu,
.bootstrap-select .button:hover {
    color: white;
    background: black;
    border: none;
}

.bootstrap-select a:hover {
    color: black !important;
    background: white !important;
}
</style>
<?php
$plid = $this->input->get('plid');
$uid = $this->input->get('uid');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="card card-default">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title "> <i class="fa fa-plus"></i>
                        <?=trans('add_playlist')?> </h3>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('admin/playlist');?>" class="btn btn-success"><i class="fa fa-list"></i>
                        <?=trans('all_playlists')?></a>
                </div>
            </div>
            <div class="card-body">
                <!-- For Messages -->
                <?php $this->load->view('admin/includes/_messages.php')?>

                <?php echo form_open(base_url('admin/playlist/add'), ['class' => "login-form", 'id' => 'frm-add-playlist']); ?>
                <div class='row'>
                    <div class="form-group col-md-2 ml-2">
                        <label class=" control-label"><?=trans('user')?><span class="req">
                                *</span></label>
                        <select name="user" class='form-control' data-live-search="true" id="user">
                            <option value="">Select User</option>
                            <?php foreach ($users_list as $u) {?>
                            <option value="<?=$u['id']?>" <?=$u['id'] == $uid ? 'selected' : ''?>><?=$u['username']?>
                            </option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label"><?=trans('playlist_type')?><span class="req">
                                *</span></label>
                        <select name="playlist_type" class='form-control' data-live-search="true" id="playlist_type">
                            <option value="">Select Playlist Type</option>
                            <?php foreach ($playlist_type_list as $p) {?>
                            <option value="<?=$p->id?>" <?=$plid == $p->id ? 'selected' : ''?>><?=$p->title?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="control-label"><?=trans('flashcontent')?></label>
                        <select name="flash_content" class='form-control' data-live-search="true" id="flash_content">
                            <option value="">Select Flash Content</option>
                            <?php foreach ($flash_content_list as $f) {?>
                            <option value="<?=$f['id']?>"><?=$f['title']?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="col-md-12 control-label"><?=trans('title')?><span class="req">
                                *</span></label>
                        <div class="col-md-12">
                            <input name='title' type="text" class='form-control'
                                placeholder="Type your playlist title" />
                        </div>
                    </div>
                    <div class="form-group col mr-2">
                        <label class="col-md-12 control-label d-block">&nbsp</label>
                        <input type="submit" name="submit" value="<?=trans('add_playlist')?>"
                            class="btn btn-primary pull-right">
                    </div>
                </div>
                <div class='form-group <?=$plid ? '' : 'hidden'?>' style="height:100vh">
                    <div class="col-md-12 row mx-0 h-100">
                        <!-- Single Screen -->
                        <?php if ($plid == 1) {?>
                        <div class="col-md-12 screen">
                            <div class='w-25'>
                                <input type="hidden" name='order[]' value='1'>
                                <select name="media_type[]" class="form-control selectpicker my-3"
                                    onchange="getcontent(this.value , 1)" data-live-search="true" id="media-type1">
                                    <option value="">Select Type</option>
                                    <?php foreach ($media_type_list as $k => $value) {?>
                                    <option value='<?=$value['id']?>'><?=$value['media_type']?></option>
                                    <?php }?>
                                </select>
                                <select name="media_content[]" class="form-control selectpicker" data-live-search="true"
                                    id="media1" onchange='showMedia(this,1)'>
                                    <option value="">Select Media</option>
                                </select>
                            </div>
                            <div id='media-content1' class='media-content'>
                            </div>
                        </div>

                        <!-- veritical split screen -->
                        <?php } else if ($plid == 2) {?>
                        <div class="col-md-6 screen">
                            <div class='w-50'>
                                <input type="hidden" name='order[]' value='1'>
                                <select name="media_type[]" class="form-control my-3 selectpicker"
                                    onchange="getcontent(this.value , 1)" data-live-search="true" id="media-type1">
                                    <option value="">Select Type</option>
                                    <?php foreach ($media_type_list as $k => $value) {?>
                                    <option value='<?=$value['id']?>'><?=$value['media_type']?></option>
                                    <?php }?>
                                </select>
                                <select name="media_content[]" class="form-control selectpicker" data-live-search="true"
                                    id="media1" onchange='showMedia(this,1)'>
                                    <option value="">Select Media</option>
                                </select>
                            </div>
                            <div id='media-content1' class='media-content'>
                            </div>
                        </div>
                        <div class="col-md-6 screen">
                            <div class="w-50">
                                <input type="hidden" name='order[]' value='2'>
                                <select name="media_type[]" class="form-control my-3 selectpicker"
                                    onchange="getcontent(this.value,2)" data-live-search="true" id="media-type2">
                                    <option value="">Select Type</option>
                                    <?php foreach ($media_type_list as $k => $value) {?>
                                    <option value='<?=$value['id']?>'><?=$value['media_type']?></option>
                                    <?php }?>
                                </select>
                                <select name="media_content[]" class="form-control selectpicker" data-live-search="true"
                                    id="media2" onchange='showMedia(this,2)'>
                                    <option value="">Select Media</option>
                                </select>
                            </div>
                            <div id='media-content2' class='media-content'>
                            </div>
                        </div>

                        <!-- horzontal split screen -->
                        <?php } else if ($plid == 3) {?>
                        <div class="col-md-12 h-50 screen">
                            <div class='w-25'>
                                <input type="hidden" name='order[]' value='1'>
                                <select name="media_type[]" class="form-control my-3 selectpicker"
                                    onchange="getcontent(this.value , 1)" data-live-search="true" id="media-type1">
                                    <option value="">Select Type</option>
                                    <?php foreach ($media_type_list as $k => $value) {?>
                                    <option value='<?=$value['id']?>'><?=$value['media_type']?></option>
                                    <?php }?>
                                </select>
                                <select name="media_content[]" class="form-control selectpicker" data-live-search="true"
                                    id="media1" onchange='showMedia(this,1)'>
                                    <option value="">Select Media</option>
                                </select>
                            </div>
                            <div id='media-content1' class='media-content'>
                            </div>
                        </div>
                        <div class="col-md-12 h-50 screen">
                            <div class="w-25">
                                <input type="hidden" name='order[]' value='2'>
                                <select name="media_type[]" class="form-control my-3 selectpicker"
                                    onchange="getcontent(this.value,2)" data-live-search="true" id="media-type2">
                                    <option value="">Select Type</option>
                                    <?php foreach ($media_type_list as $k => $value) {?>
                                    <option value='<?=$value['id']?>'><?=$value['media_type']?></option>
                                    <?php }?>
                                </select>
                                <select name="media_content[]" class="form-control selectpicker" data-live-search="true"
                                    id="media2" onchange='showMedia(this,2)'>
                                    <option value="">Select Media</option>
                                </select>
                            </div>
                            <div id='media-content2' class='media-content'>
                            </div>
                        </div>

                        <!-- Three section screen -->
                        <?php } else if ($plid == 4) {?>
                        <div class="col-md-6 screen">
                            <div class=' w-50'>
                                <input type="hidden" name='order[]' value='1'>
                                <select name="media_type[]" class="form-control my-3 selectpicker "
                                    onchange="getcontent(this.value , 1)" data-live-search="true" id="media-type1">
                                    <option value="">Select Type</option>
                                    <?php foreach ($media_type_list as $k => $value) {?>
                                    <option value='<?=$value['id']?>'><?=$value['media_type']?></option>
                                    <?php }?>
                                </select>
                                <select name="media_content[]" class="form-control selectpicker" data-live-search="true"
                                    id="media1" onchange='showMedia(this,1)'>
                                    <option value="">Select Media</option>
                                </select>
                            </div>
                            <div id='media-content1' class='media-content'>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class='h-50 screen row'>
                                <div class="w-50">
                                    <input type="hidden" name='order[]' value='2'>
                                    <select name="media_type[]" class="form-control my-3 selectpicker"
                                        onchange="getcontent(this.value,2)" data-live-search="true" id="media-type2">
                                        <option value="">Select Type</option>
                                        <?php foreach ($media_type_list as $k => $value) {?>
                                        <option value='<?=$value['id']?>'><?=$value['media_type']?></option>
                                        <?php }?>
                                    </select>
                                    <select name="media_content[]" class="form-control selectpicker"
                                        data-live-search="true" id="media2" onchange='showMedia(this,2)'>
                                        <option value="">Select Media</option>
                                    </select>
                                </div>
                                <div id='media-content2' class='media-content'>
                                </div>
                            </div>
                            <div class='h-50 screen row'>
                                <div class="w-50">
                                    <input type="hidden" name='order[]' value='3'>
                                    <select name="media_type[]" class="form-control my-3 selectpicker"
                                        onchange="getcontent(this.value,3)" data-live-search="true" id="media-type3">
                                        <option value="">Select Type</option>
                                        <?php foreach ($media_type_list as $k => $value) {?>
                                        <option value='<?=$value['id']?>'><?=$value['media_type']?></option>
                                        <?php }?>
                                    </select>
                                    <select name="media_content[]" class="form-control selectpicker"
                                        data-live-search="true" id="media3" onchange='showMedia(this,3)'>
                                        <option value="">Select Media</option>
                                    </select>
                                </div>
                                <div id='media-content3' class='media-content'>
                                </div>
                            </div>
                        </div>

                        <!-- four section screen -->
                        <?php } else if ($plid == 5) {?>
                        <div class="col-md-12 row h-50 pr-0">
                            <div class="col-md-6 screen">
                                <div class='w-50'>
                                    <input type="hidden" name='order[]' value='1'>
                                    <select name="media_type[]" class="form-control  my-3 selectpicker"
                                        onchange="getcontent(this.value , 1)" data-live-search="true" id="media-type1">
                                        <option value="">Select Type</option>
                                        <?php foreach ($media_type_list as $k => $value) {?>
                                        <option value='<?=$value['id']?>'><?=$value['media_type']?></option>
                                        <?php }?>
                                    </select>
                                    <select name="media_content[]" class="form-control selectpicker"
                                        data-live-search="true" id="media1" onchange='showMedia(this,1)'>
                                        <option value="">Select Media</option>
                                    </select>
                                </div>
                                <div id='media-content1' class='media-content'>
                                </div>
                            </div>
                            <div class='col-md-6 screen'>
                                <div class="w-50">
                                    <input type="hidden" name='order[]' value='2'>
                                    <select name="media_type[]" class="form-control my-3 selectpicker"
                                        onchange="getcontent(this.value, 2)" data-live-search="true" id="media-type2">
                                        <option value="">Select Type</option>
                                        <?php foreach ($media_type_list as $k => $value) {?>
                                        <option value='<?=$value['id']?>'><?=$value['media_type']?></option>
                                        <?php }?>
                                    </select>
                                    <select name="media_content[]" class="form-control selectpicker"
                                        data-live-search="true" id="media2" onchange='showMedia(this,2)'>
                                        <option value="">Select Media</option>
                                    </select>
                                </div>
                                <div id='media-content2' class='media-content'>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 row h-50 pr-0">
                            <div class='col-md-6 screen'>
                                <div class="w-50">
                                    <input type="hidden" name='order[]' value='3'>
                                    <select name="media_type[]" class="form-control my-3 selectpicker"
                                        onchange="getcontent(this.value,3)" data-live-search="true" id="media-type3">
                                        <option value="">Select Type</option>
                                        <?php foreach ($media_type_list as $k => $value) {?>
                                        <option value='<?=$value['id']?>'><?=$value['media_type']?></option>
                                        <?php }?>
                                    </select>
                                    <select name="media_content[]" class="form-control selectpicker"
                                        data-live-search="true" id="media3" onchange='showMedia(this,3)'>
                                        <option value="">Select Media</option>
                                    </select>
                                </div>
                                <div id='media-content3' class='media-content'>
                                </div>
                            </div>
                            <div class='col-md-6 screen'>
                                <div class="w-50">
                                    <input type="hidden" name='order[]' value='4'>
                                    <select name="media_type[]" class="form-control my-3 selectpicker"
                                        onchange="getcontent(this.value,4)" data-live-search="true" id="media-type4">
                                        <option value="">Select Type</option>
                                        <?php foreach ($media_type_list as $k => $value) {?>
                                        <option value='<?=$value['id']?>'><?=$value['media_type']?></option>
                                        <?php }?>
                                    </select>
                                    <select name="media_content[]" class="form-control selectpicker"
                                        data-live-search="true" id="media4" onchange='showMedia(this,4)'>
                                        <option value="">Select Media</option>
                                    </select>
                                </div>
                                <div id='media-content4' class='media-content'>
                                </div>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </section>
</div>
<script>
var playlistContent = [];

let updatePlaylistContent = (screen_id, field_name, field_value) => {
    playlistContent.map((p, i) => {
        if (p.screen_id == screen_id) {
            playlistContent[i].data = [];
            playlistContent[i][field_name] = field_value;
        }
    });
}
$(function() {

    var type_id = <?php echo $plid ?: 0; ?>;
    switch (type_id) {
        case 1:
            playlistContent.push({
                "screen_id": 1
            });
            break;
        case 2:
            playlistContent.push({
                "screen_id": 1
            });
            playlistContent.push({
                "screen_id": 2
            });
            break;
        case 3:
            playlistContent.push({
                "screen_id": 1
            });
            playlistContent.push({
                "screen_id": 2
            });
            break;
        case 4:
            playlistContent.push({
                "screen_id": 1
            });
            playlistContent.push({
                "screen_id": 2
            });
            playlistContent.push({
                "screen_id": 3
            });
            break;
        case 5:
            playlistContent.push({
                "screen_id": 1
            });
            playlistContent.push({
                "screen_id": 2
            });
            playlistContent.push({
                "screen_id": 3
            });
            playlistContent.push({
                "screen_id": 4
            });
            break;
    }

    // playlistContent.map((p) => {
    //     if (p.type_id && p.type_id == 5) {
    //         showMedia(p.selected_id, p.screen_id)
    //     }
    // })

    $('#playlist_type').change(function() {
        $type = $(this).val();
        $user = $('#user').val();
        window.location = location.protocol + '//' + location.host + location.pathname + '?uid=' +
            $user + '&plid=' + $type
    });
    $('#frm-add-playlist').validate({
        rules: {
            title: {
                required: true,
            },
            user: {
                required: true,
            },
            playlist_type: {
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
    })
})



function getcontent(val, media) {
    updatePlaylistContent(media, "media_type_id", val);
    $('#media-content' + media).html('');
    $('#media' + media).html('').selectpicker('refresh');
    if (val) {
        $.ajax({
            type: "get",
            url: base_url + "admin/mediamaster/getmediabytype",
            data: {
                id: val,
            },
            dataType: "json",
            success: function(res) {
                var option = '';
                if (res.status == 1) {
                    if ($.inArray(val, ['1', '2', '3', '8']) != -1) {
                        option += '<option value="">Select Media</option>';
                        res.data.map(function(ele) {
                            option +=
                                `<option value='${ele.id}' name='${ele.media_file}'>${ele.media_name}</option>`
                        });
                    } else if (val == '4' || val == '9') {
                        option += '<option value="">Select Template</option>';
                        res.data.map(function(item) {
                            option +=
                                `<option value='${item.id}'>${item.title}</option>`
                        });
                    } else if (val == '5' || val == '6' || val == '7') {
                        option += '<option value="">Select Group</option>';
                        res.data.map(function(item) {
                            option +=
                                `<option value='${item.id}'>${item.group_name}</option>`
                        });
                    }
                }
                $('#media' + media).html(option).selectpicker('refresh');
            }
        });
    }
}

function showMedia(ele, media) {
    let val = $(ele).val();

    updatePlaylistContent(media, "selected_media_id", val);
    if (val) {
        let mtype = $('#media-type' + media).val();
        switch (mtype) {
            case '1':
                var media_path = $(ele).children(`option[value=${val}]`).attr('name');
                updatePlaylistContent(media, "data", media_path);
                var currentScreen = playlistContent.filter((p) => {
                    return p.screen_id == media;
                });
                let video_path = currentScreen[0].data;
                var video = `<video controls autoplay loop width="100%" height='100%'>
                <source src="${video_path}">
                </video>`;
                $('#media-content' + media).html(video);

                break;
            case '2':
                var media_path = $(ele).children(`option[value=${val}]`).attr('name');
                updatePlaylistContent(media, "data", media_path);
                var currentScreen = playlistContent.filter((p) => {
                    return p.screen_id == media;
                });
                let img_path = currentScreen[0].data;
                var img = `<img src='${img_path}' width='100%' height='100%'>`;
                $('#media-content' + media).html(img);
                break;
            case '3':
                var media_path = $(ele).children(`option[value=${val}]`).attr('name');
                updatePlaylistContent(media, "data", media_path);
                var currentScreen = playlistContent.filter((p) => {
                    return p.screen_id == media;
                });
                let audio_path = currentScreen[0].data;
                var audio = `<audio controls autoplay loop class='w-100'>
                        <source src="${audio_path}">
                        </audio>`
                $('#media-content' + media).html(audio);
                break;
            case '4':
                $.ajax({
                    type: "post",
                    url: base_url + "admin/templates/get_template",
                    data: {
                        tid: val,
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.status == 1) {
                            let list = [];
                            res.data.map(function(item) {
                                list.push(item);
                                // console.log(item)
                            })
                            updatePlaylistContent(media, "data", list);

                            let currentScreen = playlistContent.filter((p) => {
                                return p.screen_id == media;
                            });
                            let text_list = currentScreen[0].data;
                            let text_index = 0;
                            let text = `<div id='text${media}' class='h-100 w-100'>
                                             ${text_list[0]}
                                         </div>`;

                            document.getElementById(`media-content${media}`).innerHTML = text;

                            let text_div = document.getElementById(`text${media}`);
                            setInterval(function() {
                                if (text_index >= text_list.length - 1) {
                                    text_index = 0;
                                } else {
                                    text_index++;
                                }
                                text_div.innerHTML = text_list[text_index];
                            }, 5000);
                        }
                    }
                });
                break;
            case '5':
                $.ajax({
                    type: "post",
                    url: base_url + "admin/media_group/get_media_group_contents",
                    data: {
                        mgid: val,
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.status == 1) {
                            let list = [];
                            res.data.map(function(item) {
                                list.push(item.media_file);
                            })

                            updatePlaylistContent(media, "data", list);

                            let currentScreen = playlistContent.filter((p) => {
                                if (p.screen_id == media) {
                                    return p.data;
                                }
                            });
                            let video_list = currentScreen[0].data;
                            let video_index = 0;
                            let video = `<video controls autoplay width="100%" height='100%' id="video${media}" >
                                <source src="${video_list[0]}" >
                                1</video>`;
                            document.getElementById(`media-content${media}`).innerHTML = video;

                            let video_player = document.getElementById(`video${media}`);
                            video_player.onended = function() {
                                if (video_index < video_list.length - 1) {
                                    video_index++;
                                } else {
                                    video_index = 0;
                                }
                                video_player.setAttribute("src",
                                    `${video_list[video_index]}`
                                );
                                video_player.play();
                            }
                        }
                    }
                });
                break;

            case '6':
                $.ajax({
                    type: "post",
                    url: base_url + "admin/media_group/get_media_group_contents",
                    data: {
                        mgid: val,
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.status == 1) {
                            let list = [];
                            res.data.map(function(item) {
                                list.push(item.media_file);
                            })

                            updatePlaylistContent(media, "data", list);

                            let currentScreen = playlistContent.filter((p) => {
                                return p.screen_id == media;
                            });
                            let image_list = currentScreen[0].data;
                            let image_index = 0;
                            let image =
                                `<img src = '${image_list[image_index]}' width='100%' height='100%' id='image${media}'>`;

                            document.getElementById(`media-content${media}`).innerHTML = image;

                            let image_tag = document.getElementById(`image${media}`);
                            setInterval(function() {
                                if (image_index >= image_list.length - 1) {
                                    image_index = 0;
                                } else {
                                    image_index++;
                                }
                                image_tag.setAttribute('src', `${image_list[image_index]}`);
                            }, 15000);
                        }
                    }
                });

                break;
            case '7':
                $.ajax({
                    type: "post",
                    url: base_url + "admin/media_group/get_media_group_contents",
                    data: {
                        mgid: val,
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.status == 1) {
                            let list = [];
                            res.data.map(function(item) {
                                list.push(item.media_file);
                            })

                            updatePlaylistContent(media, "data", list);

                            let currentScreen = playlistContent.filter((p) => {
                                if (p.screen_id == media) {
                                    return p.data;
                                }
                            });
                            let audio_list = currentScreen[0].data;
                            let audio_index = 0;
                            let audio = `<audio controls autoplay class='w-100' id='audio${media}'>
                                           <source src="${audio_list[0]}">
                                         </audio>`;
                            document.getElementById(`media-content${media}`).innerHTML = audio;

                            let audio_player = document.getElementById(`audio${media}`);
                            audio_player.onended = function() {

                                if (audio_index < audio_list.length - 1) {
                                    audio_index++;
                                } else {
                                    audio_index = 0;
                                }
                                audio_player.setAttribute("src",
                                    `${audio_list[audio_index]}`
                                );
                                audio_player.play();
                            }
                        }
                    }
                });
                break;
            case '8':
                var url = $(ele).children(`option[value=${val}]`).attr('name');

                ifrm_src = (!url.includes('vimeo')) ? "//www.youtube.com/embed/" + url.split(
                        "/")[url.split('/').length - 1] : "//player.vimeo.com/video/" +
                    url.split("/")[3];
                console.log(ifrm_src);
                updatePlaylistContent(media, "data", ifrm_src);
                var currentScreen = playlistContent.filter((p) => {
                    return p.screen_id == media;
                });
                let iframe_path = currentScreen[0].data;
                var iframe =
                    `<iframe src="${iframe_path}" name="youtube" height="100%" width='100%' scrolling="no"></iframe>`;
                $('#media-content' + media).html(iframe);
                break;
            case '9':
                $.ajax({
                    type: "post",
                    url: base_url + "admin/templates/get_appointment_table",
                    data: {
                        tid: val,
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.status == 1) {
                            let list = [];
                            res.data.map(function(item) {
                                list.push(item);
                                // console.log(item)
                            })
                            updatePlaylistContent(media, "data", list);

                            let currentScreen = playlistContent.filter((p) => {
                                return p.screen_id == media;
                            });
                            let text_list = currentScreen[0].data;
                            let text_index = 0;
                            let text = `<div id='text${media}' class='h-100 w-100'>
                                             ${text_list[0]}
                                         </div>`;

                            document.getElementById(`media-content${media}`).innerHTML = text;

                            let text_div = document.getElementById(`text${media}`);
                            setInterval(function() {
                                if (text_index >= text_list.length - 1) {
                                    text_index = 0;
                                } else {
                                    text_index++;
                                }
                                text_div.innerHTML = text_list[text_index];
                            }, 5000);
                        }
                    }
                });
                break;
        }
    } else {
        $('#media-content' + media).html('');
    }
}
</script>