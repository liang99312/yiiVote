<?php
namespace common\tools;
class UtilTools {

	public static function cutstr_html($string, $sublen) {

		$string = strip_tags($string);

		$string = preg_replace('/\n/is', '', $string);

		$string = preg_replace('/ |　/is', '', $string);

		$string = preg_replace('/&nbsp;/is', '', $string);

		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);

		if (count($t_string[0]) - 0 > $sublen)
			$string = join('', array_slice($t_string[0], 0, $sublen)) . "…";
		else
			$string = join('', array_slice($t_string[0], 0, $sublen));

		return $string;
	}
        
        public static function getIp(){ 
            $onlineip=''; 
            if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){ 
                $onlineip=getenv('HTTP_CLIENT_IP'); 
            } elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){ 
                $onlineip=getenv('HTTP_X_FORWARDED_FOR'); 
            } elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown')){ 
                $onlineip=getenv('REMOTE_ADDR'); 
            } elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')){ 
                $onlineip=$_SERVER['REMOTE_ADDR']; 
            } 
            return $onlineip; 
        } 

}
