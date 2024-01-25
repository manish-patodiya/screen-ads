<style>
.screen {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
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
</style>
<?php //prd($_SESSION);?>
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
                    <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?=trans('playlists')?></h3>
                </div>
                <div class="d-inline-block float-right">
                    <?php if ($this->rbac->check_operation_permission('add')): ?>
                    <a href="<?=base_url('admin/playlist/add');?>" class="btn btn-success"><i class="fa fa-plus"></i>
                        <?=trans('add_new_playlist')?></a>
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
                            <th><?=trans('title')?></th>
                            <th><?=trans('user')?></th>
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
    "bAutoWidth": false,
    "aoColumns": [{
            sWidth: '10px'
        },
        {
            sWidth: '200px'
        },
        {
            sWidth: '70px'
        },
        {
            sWidth: '20px'
        }
    ],
    "processing": true,
    "serverSide": false,
    "ajax": "<?=base_url('admin/playlist/datatable_json')?>",
    "order": [
        [3, 'asc']
    ],
    "columnDefs": [{
            "targets": 0,
            "name": "id",
            'searchable': true,
            'orderable': true
        },
        {
            "targets": 1,
            "name": "depart-name",
            'searchable': true,
            'orderable': true,
            'width': '50px'
        },
        {
            "targets": 2,
            "name": "Action",
            'searchable': false,
            'orderable': false,
            // 'width': '10px'
        },
        {
            "targets": 3,
            "name": "language",
            'searchable': false,
            'orderable': false,
            // 'width': '50px'
        }
    ]
});
</script>

<script>
$(function() {
    $(document).on("click", ".delete", function() {
        let id = $(this).attr('playlist_id')
        $("#mdl-delete").modal("show")
        $('#delete-id').val(id);
    })

    $(document).on('click', '#delete', function() {
        let id = $('#delete-id').val();
        $.ajax({
            url: base_url + 'admin/playlist/delete/' + id,
            method: "delete",
            dataType: "json",
            success: function(res) {
                if (res.status == 1) {
                    // $("#mdl-delete").modal("hide");
                    // $(".modal-backdrop").remove();
                    window.location.reload();
                }
            }
        })
    })
})
</script>