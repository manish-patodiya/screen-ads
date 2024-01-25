<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    .media-content {
        height: 100%;
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    </style>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/font-awesome/css/font-awesome.min.css">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- jQuery -->
    <script src="<?=base_url()?>/assets/plugins/jquery/jquery.min.js"></script>
</head>

<body>
    <?php if (isset($flash_data) && strtolower($flash_data['position']) == 'top') {?>
    <div class="d-flex align-items-center" id='marquee-div' style='<?=$flash_data['style']?>;overflow:hidden'>
        <?php if ($flash_data['media_file']) {?>
        <img src="<?=$flash_data['media_file']?>" id='logo-img' />
        <?php }?>
        <marquee class='d-flex align-items-center'>
            <?=$flash_data['content']?>
        </marquee>
    </div>
    <?php }?>
    <?php if ($screen_data) {?>
    <div class='row w-100 m-0' style='height:100vh;' id='screen'>
        <?php if ($screen_data->playlist_type_id == 1) {?>
        <div class='col-md-12 media-content p-0' id='media-content1'></div>
        <?php } else if ($screen_data->playlist_type_id == 2) {?>
        <div class="col-md-6 h-100 media-content p-0" id='media-content1'></div>
        <div class="col-md-6 h-100 media-content p-0" id='media-content2'></div>
        <?php } else if ($screen_data->playlist_type_id == 3) {?>
        <div class="col-md-12 h-50 media-content p-0" id='media-content1'></div>
        <div class="col-md-12 h-50 media-content p-0" id='media-content2'></div>
        <?php } else if ($screen_data->playlist_type_id == 4) {?>
        <div class='col-md-6 h-100 media-content p-0' id='media-content1'></div>
        <div class='col-md-6 h-100 p-0'>
            <div class='h-50 media-content' id='media-content2'></div>
            <div class='h-50 media-content' id='media-content3'></div>
        </div>
        <?php } else if ($screen_data->playlist_type_id == 5) {?>
        <div class='col-md-12 row h-50'>
            <div class="col-md-6 h-100 media-content p-0" id='media-content1'>
            </div>
            <div class="col-md-6 h-100 media-content p-0" id='media-content2'>
            </div>
        </div>
        <div class='col-md-12 row h-50'>
            <div class="col-md-6 h-100 media-content p-0" id='media-content3'>
            </div>
            <div class="col-md-6 h-100 media-content p-0" id='media-content4'>
            </div>
        </div>
        <?php }?>
    </div>
    <?php if (isset($flash_data) && strtolower($flash_data['position']) == 'bottom') {?>
    <div class="d-flex align-items-center" id='marquee-div' style='<?=$flash_data['style']?>;overflow:hidden'>
        <?php if ($flash_data['media_file']) {?>
        <img src="<?=$flash_data['media_file']?>" id='logo-img' />
        <?php }?>
        <marquee class='d-flex align-items-center'>
            <?=$flash_data['content']?>
        </marquee>
    </div>
    <?php }?>
    <?php } else {?>
    <div class="alert alert-danger">You have no screen for this user</div>
    <?php }?>
</body>
<script src="<?=base_url()?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>


<script>
let base_url = '<?=base_url()?>'
let playlistContent = [];
var type_id = <?php echo $screen_data->playlist_type_id ?>;
switch (type_id) {
    case 1:
        playlistContent.push(JSON.parse('<?=json_encode($tags[0])?>'));
        break;
    case 2:
        playlistContent.push(
            JSON.parse('<?=json_encode($tags[0])?>')
        );
        playlistContent.push(
            JSON.parse('<?=json_encode(isset($tags[1]) ? $tags[1] : "")?>')
        );
        break;
    case 3:
        playlistContent.push(
            JSON.parse('<?=json_encode($tags[0])?>')
        );
        playlistContent.push(
            JSON.parse('<?=json_encode(isset($tags[1]) ? $tags[1] : "")?>')
        );
        break;
    case 4:
        playlistContent.push(
            JSON.parse('<?=json_encode($tags[0])?>')
        );
        playlistContent.push(
            JSON.parse('<?=json_encode(isset($tags[1]) ? $tags[1] : "")?>')
        );
        playlistContent.push(
            JSON.parse('<?=json_encode(isset($tags[2]) ? $tags[2] : "")?>')
        );
        break;
    case 5:
        playlistContent.push(
            JSON.parse('<?=json_encode($tags[0])?>')
        );
        playlistContent.push(
            JSON.parse('<?=json_encode(isset($tags[1]) ? $tags[1] : "")?>')
        );
        playlistContent.push(
            JSON.parse('<?=json_encode(isset($tags[2]) ? $tags[2] : "")?>')
        );
        playlistContent.push(
            JSON.parse('<?=json_encode(isset($tags[3]) ? $tags[3] : "")?>')
        );
        break;
}

playlistContent.map((p) => {
    showMedia(p);
})


let updatePlaylistContent = (screen_id, field_name, field_value) => {
    playlistContent.map((p, i) => {
        if (p.screen_id == screen_id) {
            playlistContent[i].data = [];
            playlistContent[i][field_name] = field_value;
        }
    });
}

function showMedia(obj) {
    let media = obj.screen_id;
    let mtype = obj.media_type_id;
    switch (mtype) {
        case '1':
            let video_path = obj.data;
            var video = `<video controls autoplay width="100%" height='100%' loop>
                <source src="${video_path}">
                </video>`;
            $('#media-content' + media).html(video);

            break;
        case '2':
            let img_path = obj.data;
            var img = `<img src='${img_path}' width='100%' height='100%'>`;
            $('#media-content' + media).html(img);
            break;
        case '3':
            let audio_path = obj.data;
            var audio = `<audio controls autoplay class='w-100' loop>
                        <source src="${audio_path}">
                        </audio>`
            $('#media-content' + media).html(audio);
            break;
        case '4':
            $.ajax({
                type: "post",
                url: base_url + "admin/templates/get_template",
                data: {
                    tid: obj.template_id,
                },
                dataType: "json",
                success: function(res) {
                    if (res.status == 1) {
                        let list = [];
                        res.data.map(function(item) {
                            list.push(item);
                        })
                        updatePlaylistContent(media, "data", list);
                        let currentScreen = playlistContent.filter((p) => {
                            return p.screen_id == media;
                        });
                        let text_list = currentScreen[0].data;
                        let text_index = 0;

                        let text = `<div id='text${media}' class='h-100 w-100' style='font-size:1.2vw;'>
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
                        }, 15000);
                    }
                }
            });
            break;
        case '5':
            $.ajax({
                type: "post",
                url: base_url + "admin/media_group/get_media_group_contents",
                data: {
                    mgid: obj.group_id,
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
                    mgid: obj.group_id,
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
                    mgid: obj.group_id,
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
            var url = obj.data;
            ifrm_src = (!url.includes('vimeo')) ? "//www.youtube.com/embed/" + url.split(
                    "/")[url.split('/').length - 1] : "//player.vimeo.com/video/" +
                url.split("/")[3];
            console.log(ifrm_src);

            var iframe =
                `<iframe src="${ifrm_src}" name="youtube" height="100%" width='100%' scrolling="no"></iframe>`;
            $('#media-content' + media).html(iframe);
            break;
    }
}
</script>
<script>
function toggleFullscreen(elem) {
    elem = elem || document.documentElement;
    if (!document.fullscreenElement && !document.mozFullScreenElement &&
        !document.webkitFullscreenElement && !document.msFullscreenElement) {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
    }
}

$(function() {
    let m_height = $('#marquee-div').height();
    $('#screen').height(`calc(100vh - ${m_height + 'px'})`)

    $('#logo-img').css('max-height', m_height);

    var font_size = $('#marquee-div').css('font-size');
    if (m_height <= font_size) {
        $('#marquee-div').css('height', font_size + m_height);
    }
})
</script>

</html>