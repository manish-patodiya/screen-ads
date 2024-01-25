<!-- <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css"> -->
<style>
.form-check-input {
    position: absolute;
    margin-top: 0.3rem;
    margin-left: 0.75rem;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="card card-default">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"> <i class="fa fa-plus"></i>
                        <?=trans('create_new_support_tickets')?></h3>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('admin/support_tickets');?>" class="btn btn-success"><i class="fa fa-list"></i>
                        <?=trans('support_tickets_list')?></a>
                </div>
            </div>
            <div class="card-body">

                <!-- For Messages -->
                <?php $this->load->view('admin/includes/_messages.php')?>

                <?php echo form_open(base_url('admin/support_tickets/add'), ["class" => "form-horizontal", 'id' => 'frm-add-support']); ?>

                <input type='text' class='hidden' name='company_id' value='<?=$this->session->company_id;?>' />

                <div class="form-group">
                    <label for="subject" class="col-md-2 control-label"><?=trans('subject')?><span class="req">
                            *</span></label>

                    <div class="col-md-12">
                        <input type="text" name="subject" class="form-control " id="subject"
                            placeholder="Enter your subject">
                    </div>
                </div>

                <div class='form-group'>
                    <label for="mediafile" class="col-md-2 control-label"><?=trans('description')?></label>

                    <div class="col-md-12 mb-12">
                        <textarea type="text" name="description" class="form-control" id="description" rows='10'
                            placeholder="Type your description"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" name="submit" value="<?=trans('update')?>"
                            class="btn btn-primary pull-right">
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
            <!-- /.box-body -->
        </div>
    </section>
</div>
<script>
$(function() {

    $('#frm-add-support').validate({
        rules: {
            subject: {
                required: true,

            },
            description: {
                required: true,
            },
            status: {
                required: true,
            },
        },
        messages: {

        },

        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback col font-100');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
})
</script>