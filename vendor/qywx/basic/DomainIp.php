<?php
	/**
	 * User: Da ming, XDJ
	 */

	namespace qywx\basic;

	use qywx\Base;
	use qywx\basic\AccessToken;
	use qywx\basic\Request;
	use think\Exception;

	class DomainIp extends Base
	{
		//获取企业微信API域名IP段
		public static function getDomainIp(){
			//获取access_token
			$access_token = AccessToken::getAccessToken();
			//拼接参数
			$url = config('qywx.baseUrl')."/get_api_domain_ip?"."access_token=".$access_token;
			//获取access_token
			$data = json_decode(Request::getUrlContent_GET($url));
			if ($data->errcode === 0) {
				$ip_list = $data->ip_list;			
			}else{
				throw new Exception('企业微信API域名IP段失败');
			}
			return $ip_list;
		} 
	}