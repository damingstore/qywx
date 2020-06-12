<?php
	/**
	 * User: Da ming XDJ
	 */

	namespace app\admin\model;


	use think\Exception;
	use app\admin\model\Qywx as QywxModel;	

	class User extends Base
	{

//		protected $hidden = [ 'create_time','update_time','delete_time'];


		/*
		* 建立关联(家庭关系)
		*/	
		public function home(){
			return $this->hasMany("home","uid","id");
		}

		/*
		* 建立关联(教育情况)
		*/	
		public function education(){
			return $this->hasMany("education","uid","id");
		}

		/*
		* 建立关联(工作经历)
		*/	
		public function job(){
			return $this->hasMany("job","uid","id");
		}

		/*
		* 建立关联(在职情况)
		*/	
		public function position(){
			return $this->hasOne("position","uid","id");
		}	

		/*
		* 建立关联(在职历史)
		*/	
		public function positionHistory(){
			return $this->hasMany("position_history","uid","id");
		}

		/*
		* 建立关联(企业微信信息)
		*/	
		public function qywx(){
			return $this->hasOne("qywx","uid","id");
		}

		/*
		* 获取成员列表信息
		*/	
		public function getUsers($params){
			$res = self::with(['home','job','education','position','qywx'])
					->paginate($params['limit'], false, ['query' => request()->param()]);
			return $res;
		}
		


		/*
		* 根据openid查user
		*/	
		public function getUserByOpenId($params){
			$uid = QywxModel::where('openid',$params['openid'])->value('uid');
			$res = self::with(['home','job','education','position','qywx'])->where('id',$uid)->find();
			return $res;
		}	

		/*
		* 根据id查user
		*/	
		public function getUserByUId($params){
			$res = self::with(['home','job','education','position','qywx'])->where('id',$params['id'])->find()->toArray();
			return $res;
		}	


	}