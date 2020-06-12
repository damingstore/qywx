<?php
	/**
	 * User: Da ming
	 */

	namespace app\admin\model;
	use think\Exception;
	use think\Request;
	use think\facade\Cache;
	use app\lib\exception\TokenException;
	use app\lib\exception\ParameterException;
	use app\admin\model\Token;
	use app\admin\validate\TokenValidate;
	use Firebase\JWT\JWT;

	class Usertoken
	{
        private $type = 'mysql';

		public function getToken(Request $request)
		{
			$data = $request->param();
			$key = "huang";  //这里是自定义的一个随机字串，应该写在config文件中的，解密时也会用，相当    于加密中常用的 盐  salt
			$token = [
				"iss"=>"",  //签发者 可以为空
				"aud"=>"", //面象的用户，可以为空
				"iat" => time(), //签发时间
				"nbf" => time()+100, //在什么时候jwt开始生效  （这里表示生成100秒后才生效）(如果token没生效就解密会报Cannot handle token prior to 2019-11-11T07:03:06+0000")
				"exp" => time()+7200, //token 过期时间
				"uid" => $data['id'] //记录的userid的信息，这里是自已添加上去的，如果有其它信息，可以再添加数组的键值对
			];
	
			$jwt = JWT::encode($token,$key,"HS256"); //根据参数生成了 token
	
			return json([
				 "token"=>$jwt
			]);
	
		}
	
	
	public function index(Request $request)
	{
			$token = $request->header('Authorization');
			//解密
			$key = "huang";           
			$info = JWT::decode($token,$key,["HS256"]); //解密jwt
			return json($info); 
	}
}