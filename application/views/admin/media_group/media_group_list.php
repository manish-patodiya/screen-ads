<!-- DataTables -->
<link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/dataTables.bootstrap4.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <!-- For Messages -->
        <?php $this->load->view('admin/includes/_messages.php')?>
        <div class="card">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?=trans('media_group_list')?></h3>
                </div>
                <div class="d-inline-block float-right">
                    <?php // if ($this->rbac->check_operation_permission('add')): ?>
                    <a href="<?=base_url('admin/media_group/add');?>" class="btn btn-success"><i class="fa fa-plus"></i>
                        <?=trans('create_media_group')?></a>
                    <?php // endif;?>
                </div>
            </div>
        </div>
        <div class="hidden  btn btn-success " id="success-msg">
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table id="na_datatable" class="table table-bordered table-striped" width="100%">
                    <thead>
                        <tr>
                            <th style='width: 5%;'><?=trans('sn')?></th>
                            <th style='width: 12%;'><?=trans('media_type')?></th>
                            <th style='width:15%'><?=trans('group_name')?></th>
                            <th style='width: 20%;'><?=trans('remarks')?></th>
                            <th style='width: 5%;'><?=trans('status')?></th>
                            <th style='width: 12%;' class="text-right"><?=trans('action')?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>


<!-- DataTables -->
<script src="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>

<script>
//---------------------------------------------------
var table = $('#na_datatable').DataTable({
    "processing": true,
    "serverSide": false,
    "ajax": "<?=base_url('admin/media_group/datatable_json')?>",
    "order": [
        ['0', 'asc']
    ],
    "columnDefs": [{
            "targets": 0,
            "name": "id",
            'searchable': true,
            'orderable': false
        },
        {
            "targets": 1,
            "name": "group_name",
            'searchable': true,
            'orderable': true
        },
        {
            "targets": 2,
            "name": "media_type",
            'searchable': true,
            'orderable': true
        },

        {
            "targets": 3,
            "name": "Action",
            'searchable': false,
            'orderable': true,
            'width': '102px'
        },
        {
            "targets": 4,
            "name": "media_type",
            'searchable': false,
            'orderable': false
        },

        {
            "targets": 5,
            "name": "Action",
            'searchable': false,
            'orderable': false,
        }
    ]
});
</script>


<script type="text/javascript">
$("body").on("change", ".tgl_checkbox", function() {
    console.log('checked');
    $.post('<?=base_url("admin/media_group/change_status")?>', {
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
            id: $(this).data('id'),
            status: $(this).is(':checked') == true ? 1 : 0
        },
        function(data) {
            $.notify("Status Changed Successfully", "success");
        });
});
</script>
<script>
$(document).on("click", ".delete", function() {
    let id = $(this).attr('uid')
    console.log(id);
    $("#mdl-delete").modal("show")
    $('#delete-id').val(id);

})
$(document).on('click', '#delete', function() {
    let id = $('#delete-id').val()
    let support = {
        url: base_url + 'admin/media_group/delete_media_group',
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
                }, 2000)

            }
        }
    }
    $.ajax(support)
})
</script>
<script>
$(document).on("click", ".view_mediagroup", function() {
    let id = $(this).attr('uid')
    $("#mdl-view").modal("show");
})
$(document).on("click", ".view_mediagroup", function() {
    let support = {
        url: base_url + 'admin/media_group/get_all_media_group',
        method: "post",
        data: {
            'id': $(this).attr('uid')
        },
        dataType: "json",
        success: function(res) {
            if (res.status == 1) {
                let data = res.records.data;
                let data1 = res.records.data1;
                $('#group_name').html(data.group_name);
                $('#media_type').html(data.media_type);
                $('#remarks').html(':' + data.remarks);
                let array = [];
                data1.map(function(key) {

                    console.log(key);
                    array.push(key['media_file']);
                })
                let media_name = array.toString();
                $('#media').html(':' + media_name);


            }
        }
    }
    $.ajax(support)
})
</script>