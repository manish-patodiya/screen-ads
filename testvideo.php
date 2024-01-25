<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="http://localhost/saas-digital/assets/plugins/jquery/jquery.min.js"></script>
</head>

<body>
    <div id="media-content" style="width:500px; height:100px; display:block; border:1px solid #ccc;"></div>
    <script>
    $(function() {

        let video_list = [
            "media_file1651571768.mp4",
            "media_file1651571508.mp4",
            "media_file1651571548.mp4"
        ];
        var video_index = 0;
        var video_player = null;

        function playNextVideo() {
            if (video_index < video_list.length - 1) {
                video_index++;
            } else {
                video_index = 0;
            }
            video_player.setAttribute("src",
                `http://localhost/saas-digital/uploads/media_file/${video_list[video_index]}`);
            video_player.play();
        }
        var video = `<video controls autoplay width="100%" height='100%' id="video" >
                <source src="http://localhost/saas-digital/uploads/media_file/${video_list[0]}" >
                </video>`;
        document.getElementById('media-content').innerHTML = video;

        video_player = document.getElementById('video');
        video_player.onended = function() {
            playNextVideo()
        }
    })
    </script>

</body>

</html>