<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
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
                        <?=trans('create_department')?> </h3>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('admin/departments');?>" class="btn btn-success"><i class="fa fa-list"></i>
                        <?=trans('department_list')?></a>
                </div>
            </div>
            <div class="card-body">


                <!-- For Messages -->
                <?php $this->load->view('admin/includes/_messages.php')?>

                <?php echo form_open(base_url('admin/departments/add'), ['class' => "form-horizontal", 'id' => 'frm-add-dprtmnt']); ?>
                <div class=" row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="language" class="col-md-12 control-label"><?=trans('language')?></label>
                            <select class="form-control slct-lang" id="language" name="language"></select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="departmentname" class="col-md-12 control-label"><?=trans('depart-name')?><span
                                    class="req"> *</span>
                                <span class="text-grey">(To convert language press space after each
                                    word)</span></label>

                            <div class="col-md-12">
                                <input type="text" name="depart-name" class="form-control" id="depart-name"
                                    placeholder="Enter your department name" />
                            </div>
                        </div>
                    </div>
                    <div class='col-md-12'>
                        <div class="form-group">
                            <label for="remarks" class="col-md-12 control-label"><?=trans('remarks')?></label>

                            <div class="col-md-12">
                                <textarea type="text" name="remarks" class="form-control" id="remarks"
                                    placeholder="Type your remarks" rows='3'></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="submit" name="submit" value="<?=trans('add_depat')?>"
                            class="btn btn-primary pull-right">
                    </div>
                </div>
                <?php echo form_close(); ?>
                <!-- /.box-body -->
            </div>
        </div>
    </section>
</div>

<script type="text/javascript" src="<?=base_url()?>assets/dist/js/plugins/translate/langConvert.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/dist/js/plugins/translate/google_jsapi.js"></script>
<script>
$(function() {
    googleTranslater(["depart-name"])
})
</script>
<script>
$(function() {
    $('#frm-add-dprtmnt').validate({
        rules: {
            language: {
                required: true,
            },
            'depart-name': {
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
    $(document).ready(function() {
        $(window).keydown(function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });
})
</script>