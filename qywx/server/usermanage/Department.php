<?php
	/**
	 * User: Da ming, XDJ
	 */

	namespace qywx\server\usermanage;

	use qywx\Base;
	use qywx\basic\Request;
	use qywx\basic\AccessToken;
	use think\Exception;

	class Department extends Base
	{


	    /*
	    * 操作部门
	    * $action操作方法 add为添加部门 update为更新部门 delete为删除部门 read为获取部门列表
	    */	
		public static function department($content,$action){
			//获取access_token
			$access_token = AccessToken::getAccessToken();	
			switch ($action) {
				// 添加部门
				case 'add':
					$url = config('qywx.baseUrl')."/department/create?access_token=".$access_token;
					$res = json_decode(Request::getUrlContent_POST($url, json_encode($content)), true);
					if ($res['errcode'] !== 0) throw new Exception("添加部门失败");
					$data = $res;
					break;

				// 更新部门
				case 'update':
					$url = config('qywx.baseUrl')."/department/update?access_token=".$access_token;
					$res = json_decode(Request::getUrlContent_POST($url, json_encode($content)), true);
					if ($res['errcode'] !== 0) throw new Exception("更新部门失败");
					$data = $res;
					break;

				// 删除部门
				case 'delete':
					$url = config('qywx.baseUrl')."/department/delete?aaccess_token=".$access_token."&id=".$content['id'];
					$res = json_decode(Request::getUrlContent_GET($url) , true);
					if ($res['errcode'] !== 0) throw new Exception("删除部门失败");
					$data = $res;
					break;

				// 获取部门列表
				case 'read':
					$url = config('qywx.baseUrl')."/department/list?access_token=".$access_token."&id=".$content['id'];
					$res = json_decode(Request::getUrlContent_GET($url) , true);
					if ($res['errcode'] !== 0) throw new Exception("获取部门失败");
					$data = $res;
					break;

				default:
					return "没有此项操作";
					break;
			}
			return $data;
					
		}


	}