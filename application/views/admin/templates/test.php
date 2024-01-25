<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <!-- For Messages -->
        <?php $this->load->view('admin/includes/_messages.php')?>
        <div class="card">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?=trans('templates')?></h3>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('admin/templates/add');?>" class="btn btn-success"><i class="fa fa-plus"></i>
                        <?=trans('add_new_template')?></a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <div class="row">
                    <?php $content = str_replace(
    ['{department}', '{name}', '{qualification}', '{affiliation}', '{morning_time}', '{evening_time}', '{image}'],
    ['Gastroenterologist & Hepatologist', 'Atul Kumar', 'MBBS, MS, MD', 'AIRS, AHP, MCI', '9:00-1:00', '4:00-6:00', base_url('/uploads/doctors_image/doctors_image1651668213.jpg')], $content
)?>
                    <div class="col-12 col-lg-4 col-sm-6 col-md-4 ">
                        <div class='border d-flex align-items-stretch flex-column' style="
    height: 300px;overflow:hidden;">
                            <?php echo $content; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>