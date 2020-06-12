<?php
	/**
	 * User: Da ming, XDJ
	 */

	namespace qywx\server\message;

	use qywx\Base;
	use qywx\basic\Request;
	use qywx\basic\AccessToken;
	use think\Exception;

	class Message extends Base
	{

	    /*
	    * 企业消息推送
	    * $touser发送信息的成员 以数组形式传入 
	    * $content 
	    * $msg_type消息的类型  text:文本 textcard:卡片消息 image:图片消息 voice:语音消息 
	    * video:视频消息 file:文件消息 news:图文消息
	    * 
	    */	
		public static function sendMessage($touser, $content, $msg_type="",$toparty="",$totag="",$toall=0){
			// $touser = json_encode($touser);
			dump($msg_type);
			//配置发送内容 
			switch($msg_type){
				case 'text':
				  $data_json = '{"touser":"'.$touser.'", "toparty":"'.$toparty.'","totag":"'.$totag.'","toall":"'.$toall.'","msgtype":"'.$msg_type.'","agentid":'.config('qywx.agentId').',"text":{"content": "'.$content['content'].'"}}';
				  // return $data_json;
				  break;
				case 'textcard':
				  $data_json = '{"touser": "'.$touser.'", "toparty": "'.$toparty.'", "totag": "'.$totag.'", "toall": "'.$toall.'", "msgtype": "'.$msg_type.'", "agentid": '.config('qywx.agentId').', "textcard": {"title": "'.$content['title'].'", "description": "'.$content['content'].'", "url": "'.$content['url'].'", "btntxt": "更多"}}';
				  break;
				case 'image':
				  $data_json = '{"touser": "'.$touser.'", "toparty": "'.$toparty.'", "totag": "'.$totag.'", "toall": "'.$toall.'", "msgtype": "'.$msg_type.'", "agentid": '.config('qywx.agentId').', "image": {"media_id": "'.$content['content'].'"}}';
				  break;
				case 'voice':
				  $data_json = '{"touser": "'.$touser.'", "toparty": "'.$toparty.'", "totag": "'.$totag.'", "toall": "'.$toall.'", "msgtype": "'.$msg_type.'", "agentid": '.config('qywx.agentId').', "voice": {"media_id": "'.$content['content'].'"}}';
				  break;
				case 'video':
				  $data_json = '{"touser": "'.$touser.'", "toparty": "'.$toparty.'", "totag": "'.$totag.'", "toall": "'.$toall.'", "msgtype": "'.$msg_type.'", "agentid": '.config('qywx.agentId').', "video": {'.$content['content'].'}}';
				  break;
				case 'file':
				  $data_json = '{"touser": "'.$touser.'", "toparty": "'.$toparty.'", "totag": "'.$totag.'", "toall": "'.$toall.'", "msgtype": "'.$msg_type.'", "agentid": '.config('qywx.agentId').', "file": {"media_id": "'.$content['content'].'"}}';
				  break;
				case 'news':
				  $data_json = '{"touser": "'.$touser.'", "toparty": "'.$toparty.'", "totag": "'.$totag.'", "toall": "'.$toall.'", "msgtype": "'.$msg_type.'", "agentid": '.config('qywx.agentId').', "news": '.json_encode($content).'}';
				  break;
				default:
				  $data_json = '{"touser": "'.$touser.'", "toparty": "'.$toparty.'", "totag": "'.$totag.'", "toall": "'.$toall.'", "msgtype": "'.$msg_type.'", "agentid": '.config('qywx.agentId').', "text": {"content": "'.$content['content'].'"}}';
			}
			$access_token = AccessToken::getAccessToken();
			// dump($access_token);die;
			//发送消息
			$url    = config('qywx.baseUrl')."/message/send?access_token=".$access_token;
			$message = json_decode(Request::getUrlContent_POST($url,$data_json),true);
			dump($message);die;
			if($message['errcode'] > 0) throw new Exception("发送消息失败");
			return $message;
		}

	}