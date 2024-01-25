<!-- Modal -->
<div class="modal fade " id="support_tickets_view" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title">Modal title</h5> -->
                <label for="mediafile" class="col-md-2 control-label"><?=trans('description')?></label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type='text' class='hidden' name='compnay_id' />
            <div class="modal-body">
                <div class='form-group'>
                    <div class="col-md-12 mb-12">
                        <span id="description" style="word-wrap: break-word;"></span>
                    </div>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            <!-- </div> -->
        </div>
    </div>
</div>