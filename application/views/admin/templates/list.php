<!-- DataTables -->
<link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/dataTables.bootstrap4.css">
<!-- Content Wrapper. Contains page content -->
<style>
table,
th,
td {
    border: 1px solid black;
    border-collapse: collapse;
}
</style>
<div class="content-wrapper">
    <section class="content">
        <!-- For Messages -->
        <?php $this->load->view('admin/includes/_messages.php')?>
        <div class="card">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?=trans('templates')?></h3>
                </div>
                <!-- <div class="d-inline-block float-right">
                    <a href="<?=base_url('admin/templates/add');?>" class="btn btn-success"><i class="fa fa-plus"></i>
                        <?=trans('add_new_template')?></a>
                </div> -->
            </div>
        </div>
        <div class="hidden  btn btn-success " id="success-msg">
        </div>


        <!-- General Setting -->
        <div class="card">
            <div class="card-body table-responsive">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#doctors_templates"
                            role="tab" aria-controls="main" aria-selected="true"><?=trans('doctor_templates')?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#patient_appointment"
                            role="tab" aria-controls="email"
                            aria-selected="false"><?=trans('patient_appointments')?></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="doctors_templates">
                        <div class="row">
                            <?php foreach ($templates as $k => $v) {
    $v['content'] = str_replace(
        ['{department}', '{name}', '{qualification}', '{affiliation}', '{morning_time}', '{evening_time}', '{image}'],
        ['Gastroenterologist & Hepatologist', 'Atul Kumar', 'MBBS, MS, MD', 'AIRS, AHP, MCI', '9:00-1:00', '4:00-6:00', base_url('/uploads/doctors_image/doctors_image1651668213.jpg')], $v['content']
    )?>
                            <div class="col-12 col-lg-4 col-sm-6 col-md-6">
                                <div class='border d-flex align-items-stretch flex-column' style="
                                    height: 300px; overflow:hidden;">
                                    <?php echo $v['content']; ?>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-12 " style="text-align: end;">
                                                    <!-- <a title="View" class="view btn btn-sm btn-info" uid="<?=$v['id']?>"
                                                href="#"> <i class="fa fa-eye"></i></a> -->
                                                    <a title="Edit" class="update btn btn-sm btn-warning"
                                                        href="<?=base_url('admin/templates/edit/' . $v['id'])?>">
                                                        <i class="fa fa-pencil-square-o"></i></a>
                                                    <a title="Delete" class="delete btn btn-sm btn-danger"
                                                        uid="<?=$v['id']?>" href="#"> <i class="fa fa-trash-o"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="patient_appointment">
                        <div class="row">
                            <?php foreach ($patient_appointments as $k => $v) {?>
                            <div class="col-12 col-lg-4 col-sm-6 col-md-6">
                                <div class='border d-flex align-items-stretch flex-column' style="
                            height: 300px; overflow:hidden;">
                                    <?=$v["content"]?>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>


<!-- DataTables -->
<script src=" <?=base_url()?>assets/plugins/datatables/jquery.dataTables.js">
</script>
<script src="<?=base_url()?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>

<script>
// $(document).ready(function() {
//     $(".draghere").draggable({
//         containment: "parent",
//         scroll: false
//     });
// });

//---------------------------------------------------
// var table = $('#templates_datatable').DataTable({
//     "processing": true,
//     "serverSide": false,
//     "ajax": "<?=base_url('admin/templates/datatable_json')?>",
//     "columnDefs": [{
//             "targets": 0,
//             "name": "sn",
//             'searchable': true,
//             'orderable': true
//         },
//         {
//             "targets": 1,
//             "name": "title",
//             'searchable': false,
//             'orderable': false,
//             'width': '702px'

//         },
//         {
//             "targets": 2,
//             "name": "status",
//             'searchable': true,
//             'orderable': true,
//             'width': '50px'

//         },
//         {
//             "targets": 3,
//             "name": "action",
//             'searchable': false,
//             'orderable': false,
//         }
//     ]
// });
</script>


<script type="text/javascript">
$("body").on("change", ".tgl_checkbox", function() {
    console.log('checked');
    $.post('<?=base_url("admin/templates/change_status")?>', {
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
            id: $(this).data('id'),
            status: $(this).is(':checked') == true ? 1 : 0
        },
        function(data) {
            $.notify("Status Changed Successfully", "success");
        });
});

$(function() {
    $(document).on("click", ".delete", function() {
        let id = $(this).attr('uid')
        $("#mdl-delete").modal("show")
        $('#delete-id').val(id);
    })

    $(document).on('click', '#delete', function() {
        let id = $('#delete-id').val();
        let support = {
            url: base_url + 'admin/templates/delete_template',
            method: "post",
            data: {
                'id': id
            },
            dataType: "json",
            success: function(res) {
                if (res.status == 1) {
                    console.log(res.msg);
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
})
</script>