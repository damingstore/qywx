<?php
	/**
	 * User: Da ming, XDJ
	 */

	namespace qywx\basic;

	use qywx\Base;

	class Request extends Base
	{
	 	//远程获取: Get方式
		public static function getUrlContent_GET($url){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			//执行并获取接口传的内容
			$output = curl_exec($ch);
			curl_close($ch);
			return $output;
		}

		//远程获取: POST方式
		public static function getUrlContent_POST($url, $data_json){
			$ch     = curl_init();
	        $header = ["Accept-Charset: utf-8"];
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//执行并获取接口传的内容
			$output = curl_exec($ch);
			curl_close($ch);
			return $output;
		}

	}