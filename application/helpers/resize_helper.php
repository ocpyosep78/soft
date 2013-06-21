<?php
    if (! function_exists('ResizeImage'))
    {
        function ResizeImage($sourceImage, $suffix=null, $imageHeight, $imageWidth, $aspectRatio = null)
        {
            $CI =& get_instance();
            $imagesInfo = pathinfo($sourceImage);
            $imageFiles = $imagesInfo['dirname']."/".$imagesInfo['filename'].".".$imagesInfo['extension'];
            $newImageFiles =  $imagesInfo['dirname']."/".$imagesInfo['filename'].$suffix.".".$imagesInfo['extension'];
            if(file_exists($imageFiles))
            {
                $config['image_library']    = 'gd2';
                $config['source_image']     = $imageFiles;
                $config['maintain_ratio']   = ($aspectRatio != null) ? $aspectRatio: true;
                $config['new_image']        = ($suffix != null)?$newImageFiles:$imageFiles;
                $config['width']            = $imageWidth;
                $config['height']           = $imageHeight;
                $CI->load->library('image_lib');
                $CI->image_lib->initialize($config); 
                $CI->image_lib->resize();
                $CI->image_lib->clear();
            }
        }
    }
    
    if (! function_exists('GetResource')) {
        function GetResource($Source) {
            $Buffer = '';
            $Handle = fopen($Source, "rb+");
            if ($Handle) {
                while (!feof($Handle)) {
                    $Buffer .= fgets($Handle, 16384);
                }
                fclose($Handle);
            }
            return $Buffer;
        }
    }
    
    if (! function_exists('ImageResize')) {
        function ImageResize($ImageSource, $ImageOutput, $MinWidth, $MinHeight, $IsCrop = 0) {
            $info = @getimagesize($ImageSource);
            if (!empty($info)) {
                $Image = imagecreatefromstring(GetResource($ImageSource));
                $ImageWidth = imagesx($Image);
                
                $ImageHeight = imagesy($Image);
                
                // Enlarge for Small Image
                if ($ImageWidth < $MinWidth || $ImageHeight < $MinHeight) {
                    $FactorWidth = $FactorHeight = 0;
                    if ($ImageWidth < $MinWidth) {
                        $FactorWidth = $MinWidth / $ImageWidth;
                    }
                    if ($ImageHeight < $MinHeight) {
                        $FactorHeight = $MinHeight / $ImageHeight;
                    }
                    
                    $FactorMultiply = ($FactorWidth > $FactorHeight) ? $FactorWidth : $FactorHeight;
                    $ResultWidth = intval($FactorMultiply * $ImageWidth);
                    $ResultHeight = intval($FactorMultiply * $ImageHeight);
                    
                    // Resize for Large Image
                    } else {
                    $FactorWidth = $ImageWidth / $MinWidth;
                    
                    $FactorHeight = $ImageHeight / $MinHeight;
                    
                    $FactorMultiply = ($FactorWidth < $FactorHeight) ? $FactorWidth : $FactorHeight;
                    $ResultWidth = intval($ImageWidth / $FactorMultiply);
                    $ResultHeight = intval($ImageHeight / $FactorMultiply);
                }
                
                $Result = imagecreatetruecolor($ResultWidth, $ResultHeight);
                imagecopyresampled($Result, $Image, 0, 0, 0, 0, $ResultWidth, $ResultHeight, $ImageWidth, $ImageHeight);
                imagejpeg($Result, $ImageOutput);
                imagedestroy($Image);
                imagedestroy($Result);
                
                if ($IsCrop == 1) {
                    ImageCrop($ImageOutput, $ImageOutput, $MinWidth, $MinHeight);
                }
            }
        }
    }
    
    if (! function_exists('ImageCrop')) {
        function ImageCrop($source, $output, $out_x, $out_y) {
            $info = @getimagesize($source);
            if (!empty($info)){
                $img = imagecreatefromstring(GetResource($source));
                $img_x = imagesx($img);
                $img_y = imagesy($img);
                $img_top = 0;
                $img_left = 0;
                
                if ($img_x <= $out_x && $img_y <= $out_y){
                    copy($source, $output);
                    return;
                }
                
                $diff = round($img_y/2) - round($out_y/2);
                $img_top = 0;
                $img_y = $out_y;
                
                
                
                $out = imagecreatetruecolor($out_x, $out_y);
                imagecopyresampled($out, $img, 0, 0, $img_left, $img_top, $out_x, $out_y, $img_x, $img_y);
                imagejpeg($out, $output);
                imagedestroy($img);
                imagedestroy($out);
            }
        }
    }
    
?>