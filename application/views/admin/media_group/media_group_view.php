<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="card card-default">
            <div class="card-header">
                <h5><?=ucfirst($data['group_name']) . ' (' . $data['media_type'] . ')'?></h5>
                <span class='text-secondary'><?=ucfirst($data['remarks'])?></span>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($data1 as $value) {?>
                    <?php if ($value->media_type_id == 1) {?>
                    <div class="col-md-3 p-3">
                        <div class="card mb-2">
                            <video height="240" controls>
                                <source src="<?php echo $value->media_file ?>" type="video/mp4">
                            </video>
                        </div>
                    </div>
                    <?php } else if ($value->media_type_id == 2) {?>
                    <div class="col-md-3 p-3">
                        <div class="card mb-2">
                            <img src="<?php echo $value->media_file ?>" alt="">
                        </div>
                    </div>
                    <?php } else if ($value->media_type_id == 3) {?>
                    <div class="col-md-3 p-3">
                        <div class="card mb-2">
                            <audio class='w-100' controls>
                                <source src="<?php echo $value->media_file ?>" type="audio/mpeg">
                            </audio>
                        </div>

                    </div>
                    <?php } else {?>
                    <div class=" col-md-3 p-3">
                        <div class="card mb-2">
                            <?php $ifrm = explode('/', $value->media_file);
    $iframesrc = '//www.youtube.com/embed/' . $ifrm[3];?>
                            <iframe src="<?=$iframesrc?>" name="youtube" height="200" scrolling="no"
                                id='youtube-src'></iframe>
                        </div>
                    </div>

                    <?php }?>
                    <?php }?>
                </div>
            </div>
        </div>
    </section>
</div>