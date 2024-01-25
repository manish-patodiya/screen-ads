<style>
.form-check-input {
    position: absolute;
    margin-top: 0.3rem;
    margin-left: 0.75rem;
}

.w-100 {
    width: 100% !important;
}

.w-45 {
    width: 45% !important;
}

.w-10 {
    width: 10% !important;
}

.custom-header {
    background-color: #4b545c;
    ;
    color: #fff;
    text-align: center;
    width: 100%;
}

.scroll-li-items {
    height: 220px !important;
    overflow: auto !important;
    font-size: 16px;
}

.d-block {
    display: block !important;
}

.pointer {
    cursor: pointer !important;
}

.pointer:hover {
    background-color: #01A9AC;
    color: #fff;
}
</style>
<?php
$media_group_list = explode(',', $group['media_group_list']);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="card card-default">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"> <i class="fa fa-plus"></i>
                        <?=trans('edit_media_group')?> </h3>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('admin/media_group');?>" class="btn btn-success"><i class="fa fa-list"></i>
                        <?=trans('media_group_list')?></a>
                </div>
            </div>

            <div class="card-body">
                <!-- For Messages -->
                <?php $this->load->view('admin/includes/_messages.php')?>

                <?php echo form_open(base_url('admin/media_group/edit/' . $group['id']), ['class' => "form-horizontal", 'id' =>
    'frm-add-dprtmnt']); ?>


                <div class="form-group">
                    <label for="language" class="col-md-2 control-label"><?=trans('group_name')?><span class="req">
                            *</span></label>
                    <div class="col-md-12">
                        <input type="test" name="group_name" class="form-control" value="<?=$group['group_name'];?>"
                            id="group_name" placeholder="Enter media group name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="media_type" class="col-md-2 control-label"><?=trans('media_type')?><span class="req">
                            *</span></label>

                    <div class="col-md-12">
                        <input type="text" name="media_type" class="form-control hidden" id="type_media" placeholder="">
                        <select class="form-control" id="type_media" name="media_type">
                            <option value=''>Select</option>

                            <?php foreach ($mt as $val) {$array = [1, 2, 3, 8];?>
                            <?php if (in_array($val['id'], $array)) {?>
                            <option value="<?php echo $val['id'] ?>"
                                <?=$val['id'] == $group['media_type'] ? 'selected' : ''?>>
                                <?php echo $val['media_type'] ?>
                            </option>
                            <?php }?>
                            <?php }?>
                        </select>
                    </div>
                </div>



                <!-- <label for="lastname" class="col-md-2 control-label"><?=trans('media_selected')?></label> -->
                <!-- <div class="card ml-2 mr-2">
                    <div class="card-body pt-0">
                        <select name="media_group[]" class='form-control duallistbox' multiple='multiple'
                            id="media-group">
                            <?php foreach ($list as $val) {?>
                            <option value="<?=$val['id']?>"
                                <?=in_array($val['id'], $media_group_list) ? 'selected' : ''?>>
                                <?=$val['media_name']?></option>
                            <?php }?>
                        </select>
                    </div>
                </div> -->

                <div class="col-sm-12">
                    <!-- <h4 class="sub-title">Lab Profile Details</h4> -->
                    <div class="w-100 d-flex">
                        <div class="w-45">
                            <div class="custom-header ">
                                <span class="w-100 ">Selectable Media Group</span>
                            </div>
                            <ul class="border border-secondary d-block scroll-li-items" id="list-services"
                                style="padding:0px;">
                                <?php foreach($list as $k=>$v) {?>
                                <li class="pointer px-1" data-id="<?=$v['id']?>"
                                    style="list-style:none;<?=in_array($v['id'], $media_group_list) ? 'display:none;' : ''?>">
                                    <?= $v['media_name'];?>
                                </li> <?php }?>
                            </ul>
                            <div class="custom-header ">
                                <span class="w-100 ">Selectable footer</span>
                            </div>
                        </div>
                        <div class="w-10 ">
                        </div>
                        <div class="w-45">
                            <div class="custom-header ">
                                <span class="w-100 ">Selection Media Group</span>
                            </div>
                            <ul class="border border-secondary d-block scroll-li-items" id="list-services-slctd"
                                style="padding:0px;">
                                <?php foreach($list as $k=>$v) {
                                    if(in_array($v['id'], $media_group_list)){
                                    ?>
                                <li class="pointer px-1" data-id="<?=$v['id']?>" style="list-style:none;">
                                    <?=$v['media_name'];?> </li>
                                <?php }}?>

                            </ul>
                            <div class=" custom-header ">
                                <span class=" w-100 ">Selection footer</span>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="media_group" id="slct-srvc-id"
                        value="<?php //echo implode(",", $parr) ?>">
                </div>

                <div class="form-group">
                    <label for="remarks" class="col-md-2 control-label"><?=trans('remarks')?></label>

                    <div class="col-md-12">
                        <input type="text" name="remarks" class="form-control" value="<?=$group['remarks'];?>"
                            id="remarks" placeholder="Enter your remarks">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 ">
                        <input type="submit" name="submit" value="<?=trans('update_media_group')?>"
                            class="btn btn-primary pull-right mb-3">
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>

    </section>
</div>
<script>
$(function() {

    $(document).on('change', '#media_type', function() {
        id = $(this).val();
        let list = {
            url: base_url + 'admin/media_group/content_list',
            method: "post",
            data: {
                'id': id
            },
            dataType: "json",
            success: function(res) {
                $('#media-group').html('');
                res.result.map(function(list) {
                    $('#media-group').append(
                        `<option value="${list.id}">${list.media_name}</option>`
                    )
                })
                $('.duallistbox').bootstrapDualListbox('refresh', true)
            }
        }
        $.ajax(list)

    })






    $('#status').click(function() {
        $('#status').val('1');
    })

    $('.duallistbox').bootstrapDualListbox()

    $('#frm-add-dprtmnt').validate({
        rules: {
            group_name: {
                required: true,
            },
            media_type: {
                required: true,
            },
            // remarks: {
            //     required: true
            // },
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

    $(document).on('change', '#type_media', function() {
        id = $(this).val();
        let list = {
            url: base_url + 'admin/media_group/content_list',
            method: "post",
            data: {
                'id': id
            },
            dataType: "json",
            success: function(res) {
                $('#list-services').html('');
                $("#list-services-slctd").html('')
                res.result.map(function(list) {
                    // console.log(list.id);
                    // console.log(list.media_name);
                    $('#list-services').append(
                        // `<option value="${list.id}">${list.media_name}</option>`
                        `<li class="pointer px-1" data-id="${list.id}" style="list-style:none;">${list.media_name}
                                </li>`
                    )
                })
                // $('.duallistbox').bootstrapDualListbox('refresh', true)
            }
        }
        $.ajax(list)

    })

    $(document).on("click", "#list-services li", function() {
        var ths = $(this);
        var id = ths.attr("data-id");
        console.log(id);
        $("#list-services-slctd").append(ths.clone());
        $(this).hide()
        setServiceIdInInput();
    })
    $(document).on("click", "#list-services-slctd li", function() {
        var ths = $(this);
        var id = ths.attr("data-id");
        $("#list-services li").each(function() {
            var ths1 = $(this);
            var sid = ths1.attr("data-id");
            if (id == sid) {
                ths1.show();
            }
        });
        ths.remove();
        setServiceIdInInput();
    })
    $("#list-services-slctd").sortable({
        update: function(event, ui) {
            setServiceIdInInput()
        }
    });

    function setServiceIdInInput() {
        var slctd_lists = [];
        $("#list-services-slctd li").each(function() {
            slctd_lists.push($(this).attr("data-id"));
        });
        console.log(slctd_lists, slctd_lists.join(","))
        $("#slct-srvc-id").val(slctd_lists)
    }
})
</script>