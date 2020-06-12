<?php
	/**
	 * User: Da ming, XDJ
	 */

	namespace qywx\basic;

	use qywx\Base;
	use qywx\basic\Request;
	use think\facade\Cache;
	use think\Exception;

	class AccessToken extends Base
	{
		//获取access_token
		public static function getAccessToken(){
			//拼接参数
			$url = config('qywx.baseUrl')."/gettoken?"."corpid=".config('qywx.corpId')."&corpsecret=".config('qywx.corpSecret');
			//判断是缓存是否存在
			$have_token = Cache::get('access_token');
			//不存在就从新获取并存缓存
			if (!$have_token) {
				//获取access_token
				$res = Request::getUrlContent_GET($url);
				$data = json_decode($res);
				if ($data->errcode === 0) {
					//存缓存
					cache('access_token', $data->access_token, 7190);
					$access_token = $data->access_token;					
				}else{
					throw new Exception('获取access_token失败');
				}
			}else{
				$access_token = $have_token;
			}
			return $access_token;
		} 
	}