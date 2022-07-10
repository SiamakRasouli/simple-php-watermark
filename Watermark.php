<?php

class Watermark {
    public $save_folder = NULL;

    public function __construct($save_folder = NULL)
    {
        $this->save_folder = $save_folder;
    }

    function set_watermark($source_file, $watermark_file, $position = "top-left"){

        $photo = $this->create_image($source_file);
        $watermark = $this->create_image($watermark_file);

        $final_image = $this->create_watermark($source_file, $photo, $watermark, $position);
        
        unlink($source_file);
        imagedestroy($photo);
        imagedestroy($watermark);

        return $final_image;
    }

    function create_image($image){
        $type = strtolower(pathinfo($image,PATHINFO_EXTENSION));
        if($type == 'jpg') {
            return imagecreatefromjpeg($image);
        } elseif($type == 'png') {
            return imagecreatefrompng($image);
        }
    }

    function create_watermark($source_file, $photo, $watermark, $position){
        
        $this->do_watermark($photo, $watermark, $position);
        return imagejpeg($photo, 'new_'.$source_file,90);
    }

    function do_watermark($photo, $watermark, $position){
        if($position == "top-left") {
            $dst_x = 5;
            $dst_y = 5;
        } elseif($position == "top-right") {
            $dst_x = imagesx($photo) - (imagesx($watermark) + 5);
            $dst_y = 5;
        } elseif($position == "bottom-left") {
            $dst_x = 5;
            $dst_y = imagesy($photo) - (imagesy($watermark) + 5);
        } elseif($position == "bottom-right") {
            $dst_x = imagesx($photo) - (imagesx($watermark) + 5);
            $dst_y = imagesy($photo) - (imagesy($watermark) + 5);
        } elseif($position == "center") {
            $dst_x = (imagesx($photo) / 2) - (imagesx($watermark) / 2);
            $dst_y = (imagesy($photo) / 2) - (imagesy($watermark) / 2);
        }

        return imagecopy($photo, $watermark, $dst_x, $dst_y, 0, 0, imagesx($watermark), imagesy($watermark));
    }


}