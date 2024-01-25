<!-- DataTables -->
<link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/dataTables.bootstrap4.css">
<style>
p {
    margin: 0px;
}

.textoverflow {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <!-- For Messages -->
        <?php $this->load->view('admin/includes/_messages.php')?>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="d-inline-block col-md-8">
                        <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?=trans('doctors_list')?></h3>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-6 input-group input-group-sm bg-white navbar-light border-bottom"
                                style="padding: 0px;">
                                <input class="form-control form-control-navbar" type="text" placeholder="Search"
                                    aria-label="Search" id="doctor_name" onkeyup="doctor_cards(this.value);">
                                <div class=" input-group-append">
                                    <button class="btn btn-navbar">
                                        <i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6 d-inline-block float-right">
                                <?php if ($this->rbac->check_operation_permission('add')): ?>
                                <a href="<?=base_url('admin/doctors/add');?>" class="btn btn-success"><i
                                        class="fa fa-plus"></i>
                                    <?=trans('add_new_doctor')?></a>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden  btn btn-success " id="success-msg">
        </div>
        <div class="card">
            <div class="card-body row" id="card">
            </div>
        </div>
    </section>
</div>
<?php
// $this->load->view("admin/doctors/doctor_view");
?>
<script src="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>
<script>
//---------------------------------------------------
$(document).ready(function() {
    var table = $('#doc_datatable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": "<?=base_url('admin/doctors/datatable_json')?>",
        "order": [
            [4, 'desc']
        ],
        "columnDefs": [{
                "targets": 0,
                "name": "id",
                'searchable': true,
                'orderable': true
            },
            {
                "targets": 1,
                "name": "name",
                'searchable': true,
                'orderable': true
            },
            {
                "targets": 2,
                "name": "doctor_speciality",
                'searchable': true,
                'orderable': true
            },
            {
                "targets": 3,
                "name": "status",
                'searchable': true,
                'orderable': true
            },
            {
                "targets": 4,
                "name": "Action",
                'searchable': false,
                'orderable': false,
                'width': '102px'
            }
        ]
    });
});
</script>
<script>
$("body").on("change", ".tgl_checkbox", function() {
    // console.log('checked');
    $.post('<?=base_url("admin/doctors/change_status")?>', {
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
            id: $(this).data('id'),
            status: $(this).is(':checked') == true ? 1 : 0
        },
        function(data) {
            $.notify("Status Changed Successfully", "success");
        });
})
</script>
<script>
$(document).on("click", ".delete", function() {
    let id = $(this).attr('uid')
    // console.log(id);
    $("#mdl-delete").modal("show")
    $('#delete-id').val(id);

})
$(document).on('click', '#delete', function() {
    let id = $('#delete-id').val()
    let support = {
        url: base_url + 'admin/doctors/delete_doctor',
        method: "post",
        data: {
            'id': id
        },
        dataType: "json",
        success: function(res) {
            if (res.status == 1) {
                // console.log(res.msg);
                $("#mdl-delete").modal("hide")
                $(".modal-backdrop").remove()
                $("#success-msg").html(res.msg)
                $("#success-msg").show()
                setTimeout(function() {
                    window.location.reload()
                    $("#success-msg").hide()
                }, 3000)
            }
        }
    }
    $.ajax(support)
})
$(document).on("click", ".view", function() {
    let id = $(this).attr('uid')
    $("#mdl-view").modal("show")
    let view = {
        url: base_url + "admin/doctors/view/" + id,
        data: {
            "uid": $(this).attr('uid')
        },
        dataType: "json",
        success: function(res) {
            let info = res.info;
            let id = $(this).attr('uid');
            let array = [];
            info.affiliations.map(function(key) {
                array.push(key["title"])
            })
            let string = array.join(', ');
            // let string = array.toString()
            // string = string.split(",");
            // console.log(string);
            $("#affiliations").html(": " + string)

            let array1 = [];
            info.availabilities.map(function(key) {
                array1.push(key["time"])
            })
            let string1 = array1.join(', ');
            console.log(string1);
            $("#availabilities").html(": " + string1)

            let array2 = [];
            info.qualifications.map(function(key) {
                array2.push(key["title"])
            })
            let string2 = array2.join(', ');
            console.log(string2);
            $("#qualificaitons").html(": " + string2)
            $("#department").html(": " + info.department_name)
            $("#name").html(info.name);
            $("#doctor_image").attr('src', info.image == "" ? base_url + 'uploads/no_image.jpg' : info
                .image);
            // console.log(info.image)
            // let path = info.image ? base_url + "uploads/doctors_image/" + info.image : base_url +
            //     "uploads/no_image.jpg"
            // console.log($("#doctor-image").prop("src", path));


        }
    }
    $.ajax(view);
})

$(document).on('click', '.btn-close', function() {
    $("#mdl-view").modal("hide")
    $(".modal-backdrop").remove()
})

doctor_cards = (value) => {
    $.ajax({
        url: base_url + "admin/doctors/doctor_cards",
        dataType: "json",
        data: {
            'value': value
        },
        success: function(res) {
            $('#card').html('');
            if (res.status == 1) {
                res.data.map(function(key) {
                    // console.log(key);
                    let path = key["image"] == "" ? base_url +
                        "uploads/no_image.jpg" : key["image"];
                    let path1 = base_url + "admin/doctors/edit/" + key.did
                    let status = (key.status == 1) ? 'checked' : '';
                    // console.log(key)

                    $("#card").append(`<div class="col-12 col-lg-4 col-sm-2 col-md-4 d-flex align-items-stretch flex-column">
                    <div class="card" >
                         <img class="card-img-top" src="${path}" alt="Card image cap" style="height:200px;object-fit:contain;">
                         <hr>
                        <div class="card-body content ">
                        <div class="row">
                        <div class="col-sm-6">
                        <span><b>Doctor Name</b></span>
                        </div>
                        <div class="col-sm-6">
                        <p class="card-text textoverflow" title="${key.name}">${key.name}</p>
                        </div> </div>
                        <div class="row">
                        <div class="col-sm-6">
                        <span><b>Department</b></span>
                        </div>
                        <div class="col-sm-6">
                        <p class="card-text">${key.department_name}</p>
                        </div> </div>
                        </div>
                        <hr>
                        <div class="card-footer">
                        <div class="row">
                        <div class="col-6">
                        <input class="tgl_checkbox tgl-ios" data-id="${key.did}" id="cb_` + `${key.did}"
                                type="checkbox" ${status}><label for="cb_` + `${key.did}"</label>
                            </div>
                            <div class="col-6 " style="text-align: end;">
                                            <a title="View" class="view btn btn-sm btn-info" uid="${key.did}" href="#"> <i
                                    class="fa fa-eye"></i></a>
                            <a title="Edit" class="update btn btn-sm btn-warning" href="${path1}">
                                <i class="fa fa-pencil-square-o"></i></a>
                            <a title="Delete" class="delete btn btn-sm btn-danger"  uid="${key.did}" href="#"> <i class="fa fa-trash-o"></i></a> </div>
                        </div></div>

                    </div>
                </div>`)

                })
            } else {
                $("#card").append(res.msg)
            }
        }
    })
}
doctor_cards();
</script>