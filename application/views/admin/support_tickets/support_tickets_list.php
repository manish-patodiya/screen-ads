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
                    <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?=trans('support_tickets_list')?></h3>
                </div>
                <div class="d-inline-block float-right">
                    <?php if ($this->rbac->check_operation_permission('add')): ?>
                    <a href="<?=base_url('admin/support_tickets/add');?>" class="btn btn-success"><i
                            class="fa fa-plus"></i>
                        <?=trans('create_new_support_tickets')?></a>
                    <?php endif;?>
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
                            <th><?=trans('sn')?></th>
                            <th><?=trans('subject')?></th>
                            <th><?=trans('status')?></th>
                            <th width="100" class="text-right"><?=trans('action')?></th>
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
    "ajax": "<?=base_url('admin/support_tickets/datatable_json')?>",
    "columnDefs": [{
            "targets": 0,
            "name": "id",
            'searchable': true,
            'orderable': true
        },
        {
            "targets": 1,
            "name": "subject",
            'searchable': true,
            'orderable': true
        },
        {
            "targets": 2,
            "name": "position",
            'searchable': true,
            'orderable': false
        },
        {
            "targets": 3,
            "name": "status",
            'searchable': true,
            'orderable': false
        },

    ]
});
</script>


<script type="text/javascript">
$("body").on("change", ".tgl_checkbox", function() {
    console.log('checked');
    $.post('<?=base_url("admin//support_tickets/change_status")?>', {
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
$(document).on("click", ".description", function() {
    let id = $(this).attr('uid')
    $("#support_tickets_view").modal("show")
    let support = {
        url: base_url + 'admin/support_tickets/get_all_support',
        method: "post",
        data: {
            'id': id
        },
        dataType: "json",
        success: function(res) {
            if (res.status == 1) {
                $("#description").html(res.name);
            }
        }
    }
    $.ajax(support)
})
$(document).on("click", ".sup_delete", function() {
    let id = $(this).attr('uid')
    $("#mdl-delete").modal("show")
    $('#delete-id').val(id);
})

$(document).on('click', '#delete', function() {
    let id = $('#delete-id').val();
    let support = {
        url: base_url + 'admin/support_tickets/delete_Support',
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
</script>