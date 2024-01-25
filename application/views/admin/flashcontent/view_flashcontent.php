<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flash Content</title>

    <!-- jQuery -->
    <script src="<?=base_url()?>assets/plugins/jquery/jquery.min.js"></script>
</head>

<body>
    <?php
// prd("img");
$prperty = '';
foreach ($css as $k => $v) {
    $prperty .= $k . ":" . $v . ";";
}
?>
    <div style='display:flex;align-items:center;position:fixed;<?=strtolower($position) == 'top' ? 'top:0' : 'bottom:0'?>;left:0;right:0;<?=$prperty?>'
        id='flash-div'>
        <?php if ($img): ?>
        <img src="<?=$img?>" id='logo-img' />
        <?php endif?>
        <marquee class="w-100 d-flex align-items-center" direction="left" id='preview-flash'>
            <?=$content?>
        </marquee>
    </div>
</body>
<script>
var div_h = $('#flash-div').height();
$('#logo-img').css('max-height', div_h);

var font_size = $('#flash-div').css('font-size');
if (div_h <= font_size) {
    $('#flash-div').css('height', font_size + div_h);
}
</script>

</html>