<?php
class S3upload
{
    public function __construct()
    {
    }

    public function upload($image, $fileName, $folderName)
    {
        require_once 'S3.php';
        $awsAccessKey = 'AKIAX5XNO5URFIR4TEF5';
        $awsSecretKey = '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';
        $s3 = new S3($awsAccessKey, $awsSecretKey);
        $bucket = 'iddms';
        $info = pathinfo($image);
        $extension = $info['extension'];
        $name = $info['basename'];
        $tmp = $image;
        // echo $tmp; die;
        $file_path = $name;

        //$CI =& get_instance();

        ///$CI->load->library('s3',$awsAccessKey, $awsSecretKey);

        //$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);

        $info = pathinfo($name);
        $ext = $info['extension'];
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg", "PNG", "JPG", "JPEG", "GIF", "BMP", "html", "HTML", 'mp4', 'mp3');
        if (strlen($name) > 0) {
            if (in_array($ext, $valid_formats)) {
                /*if($size<(1024*1024)) { */
                $actual_image_name = $folderName . '/' . $fileName;
                if ($s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ)) {
                    //return $s3file='http://'.$bucket.'.s3.amazonaws.com/'.$actual_image_name;
                    return true;
                } else {
                    //return 'Issue in saving in Bucket!';
                    return false;
                }

                /*} else{
            return 'Size Issue! - Less than 1024*1024';
            }*/
            }
        }
    }

}