<?php
	class RedditTester{
		private const URL='https://cable.ayra.ch/reddit/auto.php';

		static function checkUser($name,$key,&$errno=NULL,&$errstr=NULL){
			$errno=0;
			$errstr=NULL;
			//Check if curl module is loaded
			if(!in_array('curl',get_loaded_extensions())){
				$errno=-1;
				$errstr='curl_init function does not exists. Please load the curl module into PHP';
				return FALSE;
			}
			
			$query=http_build_query(array(
				'un'=>$name,
				'token'=>$key,
				'api'=>'1'
			));
			$crlf="\r\n";
			$cafile=__DIR__ . '/CA.pem';
			$ch=curl_init(RedditTester::URL);
			if(!$ch){
				die('CURL INIT FAILED');
			}
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
			curl_setopt($ch,CURLOPT_POST,TRUE);
			if(is_file($cafile)){
				curl_setopt($ch,CURLOPT_CAINFO,__DIR__ . '/CA.pem');
			}
			curl_setopt($ch,CURLOPT_POSTFIELDS,$query);
			curl_setopt($ch,CURLOPT_USERAGENT,
				'RedditShadowbanTesterPHP/1.0 +https://github.com/AyrA/PENDING');
			$result=curl_exec($ch);
			$code=curl_getinfo($ch,CURLINFO_RESPONSE_CODE);
			if($result===FALSE){
				$errno=curl_errno($ch);
				$errstr=curl_error($ch);
			}
			elseif($code!==200){
				$errno=$code;
				if(strlen($result)>0){
					$errstr=$result;
				}
				else{
					switch($code){
						case 400:
							$errstr='Invalid token. Probably expired';
							break;
						case 404:
							$errstr='Supplied token does not exist';
							break;
						default:
							$errstr='Unknown API error';
							break;
					}
				}
				$result=FALSE;
			}
			curl_close($ch);
			return $result;
		}
	}
	$result=RedditTester::checkUser('test','12345678-90AB-CDEF-0000-A6F3D97A08FF',$errno,$errstr);
	if($errno===0){
		print_r(json_decode($result));
	}
	else{
		echo "Error $errno: $errstr";
	}
?>