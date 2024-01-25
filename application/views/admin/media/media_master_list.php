<!-- DataTables -->
<link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/dataTables.bootstrap4.css">
<style>
hr {
    margin: 0px;
}

.card-body .content {
    height: 8em;
    overflow: auto;

}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <!-- For Messages -->
        <?php $this->load->view('admin/includes/_messages.php')?>
        <div class="card">
            <div class="card-header row">
                <div class="col-5">
                    <div class="d-inline-block">
                        <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?=trans('mediamaster_list')?></h3>
                    </div>
                </div>
                <div class="col-md-7 d-flex justify-content-end align-items-center">
                    <div class="px-2">Filter by type: </div>
                    <div class='px-2'>
                        <select class="form-control px-2" name="media" id="media">
                            <option value="">Select Media Type</option>
                            <option value="1">Video</option>
                            <option value="2">Image</option>
                            <option value="3">Audio</option>
                            <option value="8">Youtube Link</option>
                        </select>
                    </div>
                    <a href="<?=base_url('admin/mediamaster/add');?>" class="btn btn-success"><i class="fa fa-plus"></i>
                        <?=trans('upload_media')?></a>
                </div>
            </div>
        </div>
        <div class="hidden  btn btn-success " id="success-msg"> </div>
        <div class="card">
            <div class="card-body row" id="card">
            </div>
        </div>
    </section>
</div>


<!-- DataTables -->
<script src="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>

<script type="text/javascript">

</script>
<script>
$(document).ready(function() {
    $("body").on("change", ".tgl_checkbox", function() {
        $.post('<?=base_url("admin/mediamaster/change_status")?>', {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                id: $(this).data('id'),
                status: $(this).is(':checked') == true ? 1 : 0
            },
            function(data) {
                $.notify("Status Changed Successfully", "success");
            });
    });
    $(document).on("click", ".delete", function() {
        let id = $(this).attr('uid')
        $("#mdl-delete").modal("show")
        $('#delete-id').val(id);
    })
    $(document).on('click', '#delete', function() {
        let id = $('#delete-id').val()
        let media = {
            url: base_url + 'admin/mediamaster/delete_media',
            method: "post",
            data: {
                'id': id
            },
            dataType: "json",
            success: function(res) {
                if (res.status == 1) {

                    $("#mdl-delete").modal("hide")
                    $(".modal-backdrop").remove()
                    $("#success-msg").html(res.msg)
                    $("#success-msg").show()
                    setTimeout(function() {
                        window.location.reload()
                        $("#success-msg").hide()
                    }, 2000)

                }
            }
        }
        $.ajax(media)
    })
    media_cards = () => {
        cards = {
            url: base_url + "admin/mediamaster/media_cards",
            data: {
                "media": $("#media").val()
            },
            dataType: "json",
            success: function(res) {
                if (res.status == 1) {
                    let data = res.data["media"]
                    res.data.map(function(key) {
                        let data = key["media_file"];
                        let result = data.split(".")
                        let extension = result[1]
                        let path = key["media_file"] == "" ? base_url +
                            "uploads/no_image.jpg" : key["media_file"];
                        let path1 = base_url + "admin/mediamaster/edit/" + key.id
                        let status = (key.is_active == 1) ? 'checked' : '';
                        if (key["media_type"] == "Image") {
                            $("#card").append(`<div class="col-12 col-lg-3 col-sm-2 col-md-4 d-flex align-items-stretch flex-column">
                    <div class="card" >
                         <img class="card-img-top" src="${path}" alt="Card image cap" style="height:200px;">
                         <hr>
                         <span class="text-right" style="height:0px; margin-right:10px;font-size:12px;">Image</span>
                        <div class="card-body content">
                        <h4>` + key.media_name.charAt(0).toUpperCase() + key.media_name.slice(1) + `</h4>
                        </div>
                        <div class="card-footer row">
                        <div class="col-4 text-left">
                        <input class="tgl_checkbox tgl-ios" data-id="${key.id}" id="cb_` + `${key.id}"
    type="checkbox" ${status}><label for="cb_` + `${key.id}"</label>
</div>
<div class="col-8 text-right">
                <a title="View" class="view btn btn-sm btn-info" href="${path1}"> <i
        class="fa fa-eye"></i></a>
<a title="Edit" class="update btn btn-sm btn-warning" href="${path1}">
    <i class="fa fa-pencil-square-o"></i></a>
<a title="Delete" class="delete btn btn-sm btn-danger"  uid="${key.id}"href="#"> <i class="fa fa-trash-o"></i></a> </div>
                        </div>
                    </div>
                </div>`)
                        } else if (key["media_type"] == "Video") {
                            $("#card").append(`<div class="col-12 col-lg-3 col-sm-2 col-md-4 d-flex align-items-stretch flex-column">
                    <div class="card">
                        <video controls mute loop id="myVideo" style="height:200px">
                            <source src="${path}" type="video/mp4">
                            Your browser does not support HTML5 video.
                        </video>
                        <span class="text-right" style="height:0px; margin-right:10px;font-size:12px;">Video</span>
                        <div class="card-body content">
                        <h4>` + key.media_name.charAt(0).toUpperCase() + key.media_name.slice(1) + `</h4>
                        </div>
                        <div class="card-footer row">
                        <div class="col-4 text-left">
                        <input class="tgl_checkbox tgl-ios" data-id="${key.id}" id="cb_` + `${key.id}"
    type="checkbox" ${status}><label for="cb_` + `${key.id}"</label>
</div>
<div class="col-8 text-right">
                <a title="View" class="view btn btn-sm btn-info" href="${path1}"> <i
        class="fa fa-eye"></i></a>
<a title="Edit" class="update btn btn-sm btn-warning" href="${path1}">
    <i class="fa fa-pencil-square-o"></i></a>
<a title="Delete" class="delete btn btn-sm btn-danger"  uid="${key.id}"href="#"> <i class="fa fa-trash-o"></i></a> </div>
                        </div>
                    </div>
                </div>`)
                        } else if (key["media_type"] == "Audio") {
                            let img = base_url + "uploads/audio_bg.gif"
                            $("#card").append(`<div class="col-12 col-lg-3 col-sm-2 col-md-4  align-items-stretch flex-column">
                    <div class="card" >
                        <img class="card-img-top" src="${img}" alt="Card image cap"
                            style="height:150px">
                        <audio class='w-100'controls  >
                            <source src="${path}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                        <hr>
                        <span class="text-right" style="height:0px; margin-right:10px;font-size:12px;">Audio</span>
                        <div class="card-body content ">
                        <h4>` + key.media_name.charAt(0).toUpperCase() + key.media_name.slice(1) + `</h4>
                        </div>
                        <div class="card-footer row">
                        <div class="col-4 text-left">
                        <input class="tgl_checkbox tgl-ios" data-id="${key.id}" id="cb_` + `${key.id}"
    type="checkbox" ${status}><label for="cb_` + `${key.id}"</label>
</div>
<div class="col-8 text-right">
                <a title="View" class="view btn btn-sm btn-info" href="${path1}"> <i
        class="fa fa-eye"></i></a>
<a title="Edit" class="update btn btn-sm btn-warning" href="${path1}">
    <i class="fa fa-pencil-square-o"></i></a>
<a title="Delete" class="delete btn btn-sm btn-danger"  uid="${key.id}"href="#"> <i class="fa fa-trash-o"></i></a> </div>
                        </div>
                    </div>
                </div>`)
                        } else {
                            let urlarr = key["media_file"].split("/")
                            let url
                            let iframesrc
                            console.log(urlarr);
                            if (urlarr[2] == "www.youtube.com") {
                                url = urlarr[3];
                                if (url.length != 11) {
                                    url = url.substring(8, 19)
                                    console.log(url)
                                    iframesrc = '//www.youtube.com/embed/' + url;

                                } else {
                                    url = url.substring(8)
                                    iframesrc = '//www.youtube.com/embed/' + url;
                                }
                            } else {
                                iframesrc = '//www.youtube.com/embed/' + urlarr[3];
                            }
                            $("#card").append(`<div class="col-12 col-lg-3 col-sm-2 col-md-4  align-items-stretch flex-column">

                    <div class="card">
                                    <iframe src="${iframesrc}" name="youtube"  height="200" scrolling="no"
                                        id='youtube-src'></iframe>
                                        <hr>
                                        <span class="text-right" style="height:0px; margin-right:10px;font-size:12px;">Youtube</span>
                        <div class="card-body content ">
                        <h4>` + key.media_name.charAt(0).toUpperCase() + key.media_name.slice(1) + `</h4>
                        </div>
                        <div class="card-footer row">
                        <div class="col-4 text-left">
                        <input class="tgl_checkbox tgl-ios" data-id="${key.id}" id="cb_` + `${key.id}"
    type="checkbox" ${status}><label for="cb_` + `${key.id}"</label>
</div>
<div class="col-8 text-right">
                <a title="View" class="view btn btn-sm btn-info" href="${path1}"> <i
        class="fa fa-eye"></i></a>
<a title="Edit" class="update btn btn-sm btn-warning" href="${path1}">
    <i class="fa fa-pencil-square-o"></i></a>
<a title="Delete" class="delete btn btn-sm btn-danger"  uid="${key.id}"href="#"> <i class="fa fa-trash-o"></i></a> </div>
                        </div>
                    </div>
                </div>`)
                        }
                    })
                } else {
                    $("#card").append(res.msg);
                }
            }
        }
        $.ajax(cards)
    }
    media_cards()
    let recreateCard = () => {
        $("#card").html("");
        media_cards();
    };
    $(document).on("change", "#media", function() {
        recreateCard();
    })
})
</script>