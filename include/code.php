<?php

header("Content-type: image/jpeg");
session_start();
 		$bg=imagecreatetruecolor(66,23);
 		$bg_color=imagecolorallocate($bg, 122, 111, 111) ;
 		$text_color=imagecolorallocate($bg,255, 255,255) ;
 		imagefilledrectangle($bg, 0, 0, 73,23,$bg_color);
 		$str='1';
 		$len=strlen($str);
 		$newstr='';
 		for ($i=0; $i <2 ; $i++) { 
 			$tmp=$str[mt_rand(0,$len-1)];
 			$newstr.=$tmp;
 			imagettftext($bg, 12, 0, 18+22*$i, 17, $text_color,'a.ttf',$tmp);

 		}
 			$_SESSION['code']=$newstr;
		imagepng($bg);
 ?>