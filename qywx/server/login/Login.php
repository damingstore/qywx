<?php
	/**
	 * User: Da ming, XDJ
	 */

	namespace qywx\server\login;

	use qywx\Base;
	use qywx\basic\Request;
	use qywx\basic\AccessToken;
	use think\Exception;

	class Login extends Base
	{

		/*
		* 构造网页授权oauth2链接
		*/
		public static function oauth2(){
			$wx_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".config('qywx.corpId')."&redirect_uri=".urlencode(config('qywx.redirect_uri'))."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
			return $wx_url;
		}

		/*
		* 构造独立窗口登录二维码链接
		*/
		public static function qrConnectUrl(){
			$qr_connect_url ="https://open.work.weixin.qq.com/wwopen/sso/qrConnect?appid=".config('qywx.corpId')."&agentid=".config('qywx.agentId')."&redirect_uri=".urlencode(config('qywx.qr_redirect_uri'))."&state=123";
			return $qr_connect_url;
		}	


	}