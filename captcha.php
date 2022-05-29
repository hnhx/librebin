<?php
    function random_string()
    {
        //$chars = array_merge(range("A", "Z"), range("a", "z"), range(0, 9));
        $chars = array_merge(range("A", "N"), range("P", "Z"), range(1, 9));
	$random_str = "";
	

        for($i=0; random_int(3,5)>$i; $i++)
            $random_str .= $chars[random_int(0, count($chars)-1)];

        return $random_str;
    }

    function hex_to_rgb($hex)
    {
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        return array($r, $g, $b);
    }

    function create_captcha($captcha_string,$width,$height,$bg,$fg)
    {
        $im = imagecreate($width, $height);
        
        $bg_rgb = hex_to_rgb($bg);
        $fg_rgb = hex_to_rgb($fg);
    
        $background_color = imagecolorallocate($im, $bg_rgb[0], $bg_rgb[1], $bg_rgb[2]);
        $text_color = imagecolorallocate($im, $fg_rgb[0], $fg_rgb[1], $fg_rgb[2]);
        
        $text_x = 0;
        for($i=0; strlen($captcha_string)>$i; $i++)
        {
            $text_y = random_int(0, $height- ($height*0.60));
            imagestring($im, 5, $text_x, $text_y, $captcha_string[$i], $text_color);
            $text_x += 10 + random_int(0, $width / 10 - 5);
        }
            
        for($i=0; random_int(3, 6)>$i; $i++)
            imageline($im, random_int(0,$width), random_int(0,$height), random_int(0,$width), random_int(0,$width), $text_color);

        imagepng($im);
        imagedestroy($im);
    }
 
  header("Content-Type: image/png");
    
   $captcha_string = random_string();

   $width = 95;
   $height = 30; 
   $bg = "#171717";
   $fg = "#e8eaed";
   create_captcha($captcha_string,$width,$height,$bg,$fg);

    session_start();
    $_SESSION["captcha"] = $captcha_string;
?>
