<div class="modal fade" id="mdl-delete">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Are you sure, you want to delete it?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer p-2">
                <form method="post" id="frm-delete" onsubmit="return false">
                    <input name="id" type="hidden" id="delete-id">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">CANCEL</button>
                    <button type="button" class="btn btn-sm btn-danger" id='delete'>YES DELETE</button>
                </form>
            </div>
        </div>
    </div>
</div>
