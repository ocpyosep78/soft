<?php
    if (! function_exists('WatermarkImage'))
    {
        function WatermarkImage($sourceImage, $suffix=null, $watermarkText=null, $imageHeight, $imageWidth, $fontSize = null)
        {
            $CI =& get_instance();
            $imagesInfo = pathinfo($sourceImage);
            $imageFiles = $imagesInfo['dirname']."/".$imagesInfo['filename'].".".$imagesInfo['extension'];
            $imageSize  = getimagesize($imagesInfo['dirname']."/".$imagesInfo['filename'].".".$imagesInfo['extension']); 
            $widthImage = $imageSize[0];
            $heightImage = $imageSize[1];
            $newImageFiles =  $imagesInfo['dirname']."/".$imagesInfo['filename'].$suffix.".".$imagesInfo['extension'];
            
            $imageWidth = ($imageWidth != null )?$imageWidth:$widthImage;
            $imageHeight = ($imageHeight != null)?$imageHeight:$heightImage;
            if(file_exists($imageFiles))
            {
                $config['source_image']	    = ($suffix != null)?$newImageFiles:$imageFiles;
                $config['wm_text']          = ($watermarkText != null)?$watermarkText:"olshop";
                $config['wm_type']          = 'text';
                $config['wm_font_path']     =  './system/fonts/texb.ttf';
                $config['wm_font_size']	    = ($fontSize != null)? $fontSize : 10;
                $config['wm_font_color']    = '000000';
                $config['wm_vrt_alignment'] = 'bottom';
                $config['wm_hor_alignment'] = 'right';
                $config['wm_hor_offset']    = (0.25 * $imageWidth)*-1;
                $config['wm_vrt_offset']    = (0.25 * $imageHeight)*-1;
                $CI->load->library('image_lib');
                $CI->image_lib->initialize($config);
                $CI->image_lib->watermark();
                $CI->image_lib->clear();
            }
        }
    }
    
?>