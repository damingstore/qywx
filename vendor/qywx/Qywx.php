<?php
	/*
	* User: Da ming, XDJ
	*/

	namespace app\lib\qywx;

	use app\lib\qywx\basic\AccessToken;
	use app\lib\qywx\basic\DomainIp;
	use app\lib\qywx\server\login\Login; 
	use app\lib\qywx\server\usermanage\User;
	use app\lib\qywx\server\usermanage\Department;		
	use app\lib\qywx\server\message\Message;	

	class Qywx
	{

		/*
		* (已测试)
		* 获取重定向地址
		*/
		public static function getOauth2(){
			$redirect_uri = Login::oauth2();
			return $redirect_uri;
		}
		

		/*
		* (已测试)
		* 获取重定向地址
		*/
		public static function getQrConnectUrl(){
			$qr_redirect_uri = Login::qrConnectUrl();
			return $qr_redirect_uri;
		}


		/*
		* (已测试)
		* 获取access_token
		*/
		public static function getAccessToken(){
			$access_token = AccessToken::getAccessToken();
			return $access_token;
		}



		/*
		* (已测试)
		* 获取企业微信API域名IP段
		*/
		public static function getApiDomainIp(){
			$domain_ip = DomainIp::getDomainIp();
			return $domain_ip;
		}


		/*
		* 获取用户的userid 
		* 必须传code
		*/
		public static function getUserId($code){
			$user = User::userId($code);
			return $user;
		}

		/*
		* (已测试)
		* userid和openid互换
		* $type 换取类型 传userid为openid转userid 传openid为userid转openid
		*/
		public static function getExchangId($id,$type){
			$type_id = User::exchangId($id,$type);
			return $type_id;
		}


		/* 
		* 通讯录成员/创建/读取/更新/删除
		* $user 相关参数以数组形式传入
		* $action 传"add"为创建 传"read"为读取 传"update"为更新 传"delete"为删除(单个) 传"del_more"为删除(多个)
		* add: 必须传userid, name
		* read: 必须传userid (已测试)
		* update: 必须传userid
		* delete: 必须传userid
		* del_more: 必须传成员userid列表(二维数组形式)["useridlist"=>["zhangsan", "lisi"]]
		*/
		public static function operateUser($user = [],$action){
			$userinfo = User::user($user,$action);
			return $userinfo;
		}	

		/* 
		* 获取部门成员 (已测试)
		* $department_id 必须传 部门id
		* $fetch_child 可以不传,传1-递归获取
		*/
		public static function getDepartmentUser($department_id,$fetch_child){
			$userinfo = User::departmentUser($department_id,$fetch_child);
			return $userinfo;
		}	

		/* 
		* 获取部门成员详情 (已测试)
		* $department_id 必须传 部门id
		* $fetch_child 可以不传,传1-递归获取
		*/
		public static function getDepartmentUserDetail($department_id,$fetch_child){
			$userinfo = User::departmentUserDetail($department_id,$fetch_child);
			return $userinfo;
		}	


		/* 
		* 操作部门
		* $content 以数组形式传入参数
		* $action 传"add"为创建 传"read"为获取 传"update"为更新 传"delete"为删除
		* 
		* add: 必须传
		* (name	是	部门名称。同一个层级的部门名称不能重复。长度限制为1~32个字符，字符不能包括\:?”<>｜
		* parentid	是	父部门id，32位整型
		*
		* read: 必传id，当获取所有的部门是id的值为空["id"=>""] (已测试)
		* 
		* update: 必须传
		* id	是	部门id
		* 
		* delete: 必须传
		* id	是	部门id。（注：不能删除根部门；不能删除含有子部门、成员的部门）
		* 
		*/
		public static function operateDepartment($content,$action){
			$department = Department::department($content,$action);
			return $department;
		}


		/* 
	    * (已测试)
	    * 企业消息推送
	    * $touser发送信息的成员 以字符串形式传入 userid之间用 | 隔开 
	    * 
	    * $content 传入数组:
	    * text文本时 文本消息以content为键传入
	    * textcard:卡片消息时  键为title表示标题，键为content表示内容 ，键为url表示跳转地址 （三者必须）
	    * news:图文消息时 以articles为键传入,多个文章时是添加数组即可
	    * 例如:$content = [
		*		"articles" =>[
		*		   [
		*		    "title" => "中秋节礼品领取",
		*		    "description" => "今年中秋节公司有豪礼相送,点我、点我、点我！",
		*		    "url" => "http://www.baidu.com",
		*		    "picurl" => "http://res.mail.qq.com/node/ww/wwopenmng/images/independent/doc/test_pic_msg1.png"
		*		   ]		           
		*		 ]
		*	   ];
	    * 
	    * $msg_type消息的类型(必须传):
	    * text:文本 
	    * textcard:卡片消息 
	    * image:图片消息 
	    * voice:语音消息 
	    * video:视频消息 
	    * file:文件消息 
	    * news:图文消息
		*/
		public static function sendMessage($touser, $content, $msg_type,$toparty="",$totag="",$toall=0){
			$message = Message::sendMessage($touser, $content, $msg_type,$toparty="",$totag="",$toall=0);
			return $message;
		}

	}