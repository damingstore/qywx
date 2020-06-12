<?php
	/**
	 * User: Da ming, XDJ
	 */

	namespace qywx\server\usermanage;

	use qywx\Base;
	use qywx\basic\Request;
	use qywx\basic\AccessToken;
	use think\Exception;

	class User extends Base
	{


	    /*
	    * 远程获取userid或者获取openid（非企业成员）: 根据指定的code
	    */	
		public static function userId($code){
			//获取access_token
			$access_token = AccessToken::getAccessToken();			
			//构建接口地址
			
			$url = config('qywx.baseUrl')."/user/getuserinfo?access_token=".$access_token."&code=".$code;
			// dump($url);die;
			//获取信息 方法在api下的common.php
			$user = json_decode(Request::getUrlContent_GET($url) , true);
			// dump($user);die;
			if($user['errcode'] !== 0)	throw new Exception($user['errmsg']);
			return $user; 

					
		}


	    /*
	    * userid和openid互换
	    */	
		public static function exchangId($id,$type){
			//获取access_token
			$access_token = AccessToken::getAccessToken();	
			switch ($type) {
				case 'openid':
					//构建接口地址
					$url = config('qywx.baseUrl')."/user/convert_to_openid?access_token=".$access_token;
					$res = json_decode(Request::getUrlContent_POST($url,json_encode(["userid" => $id])),true);
					if ($res['errcode'] !== 0) throw new Exception('交换openid失败');
					$data = $res['openid'];
					break;

				case 'userid':
					//构建接口地址
					$url = config('qywx.baseUrl')."/user/convert_to_userid?access_token=".$access_token;
					$res = json_decode(Request::getUrlContent_POST($url, json_encode(["openid" => $id])) , true);
					if ($res['errcode'] !== 0) throw new Exception('交换userid失败');
					$data = $res['userid'];
					break;

				default:
					return "没有这个场景";
					break;
			}
			return $data;	
		}	
			

		/*
		*通讯录成员(单个)/创建/读取/更新/删除
		* $user 相关参数以数组形式传入
		* $action 传"add"为创建 传"read"为读取 传"update"为更新 传"delete"为删除
		*/
		
		public static function user($user,$action){
			$access_token = AccessToken::getAccessToken();
			switch ($action) {
				case 'add':
					$url = config('qywx.baseUrl')."/user/create?access_token=".$access_token;
					$res = json_decode(Request::getUrlContent_POST($url, json_encode($user)) , true);
					if ($res['errcode'] !== 0) throw new Exception('添加通讯录成员失败');
					$date = true;
					break;

				case 'read':
					$url = config('qywx.baseUrl')."/user/get?access_token=".$access_token."&userid=".$user['userid'];
					$res = json_decode(Request::getUrlContent_GET($url) , true);
					if ($res['errcode'] !== 0) throw new Exception('读取通讯录成员信息失败');
					$data = $res;
					break;

				case 'update':
					$url = config('qywx.baseUrl')."/user/create?access_token=".$access_token;
					$res = json_decode(Request::getUrlContent_POST($url, json_encode($user)) , true);
					if ($res['errcode'] !== 0) throw new Exception('更新通讯录成员信息失败');
					$data = true;
					break;

				case 'delete':
					$url = config('qywx.baseUrl')."/user/delete?access_token=".$access_token."&userid=".$user['userid'];
					$res = json_decode(Request::getUrlContent_GET($url) , true);
					if ($res['errcode'] !== 0) throw new Exception('删除通讯录成员失败');
					$data = true;
					break;	

				case 'del_more':
					$url = config('qywx.baseUrl')."/user/batchdelete?access_token=".$access_token;
					$res = json_decode(Request::getUrlContent_POST($url, json_encode($user)), true);
					if ($res['errcode'] !== 0) throw new Exception('批量删除通讯录成员失败');
					$data = true;
					break;	

				default:
					return "没有这项操作";
					break;
			}
			return $data;
		}

	    /*
	    * 获取部门成员
	    */	
		public static function departmentUser($department_id, $fetch_child){
			//获取access_token
			$access_token = AccessToken::getAccessToken();			
			//构建接口地址
			$url = config('qywx.baseUrl')."/user/simplelist?access_token=".$access_token."&department_id=".$department_id."&fetch_child=".$fetch_child;
			//获取信息 方法在api下的common.php
			$users = json_decode(Request::getUrlContent_GET($url) , true);
			if($users['errcode'] !== 0)	throw new Exception('获取部门成员失败');
			return $users;		
		}

	    /*
	    * 获取部门成员详情
	    */	
		public static function departmentUserDetail($department_id, $fetch_child){
			//获取access_token
			$access_token = AccessToken::getAccessToken();			
			//构建接口地址
			$url = config('qywx.baseUrl')."/user/list?access_token=".$access_token."&department_id=".$department_id."&fetch_child=".$fetch_child;
			//获取信息 方法在api下的common.php
			$users = json_decode(Request::getUrlContent_GET($url) , true);
			if($users['errcode'] !== 0)	throw new Exception('获取部门成员失败');
			return $users;		
		}				

	}