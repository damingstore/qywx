<?php
	/**
	 * User: Da ming
	 */

	namespace app\admin\model;

	use think\Exception;
	// use think\model\concern\SoftDelete;
	use app\common\model\Mediadesc;
	use app\lib\exception\ParameterException;

	class Department extends Base
	{
		
		// protected $table = 'sj_tasklogs'; 
		protected $pk = 'id';
		// protected $connection = 'db_config';
		protected $autoWriteTimestamp = true;
		// protected $updateTime = 'update_time';
		// protected $createTime = false;
		// 软删除
		// use SoftDelete;
		protected $deleteTime = 'delete_time';
		protected $defaultSoftDelete = 0;
		// 自动完成
		// protected $auto = ['name', 'ip'];
		protected $insert = ['status' => 1];  
		protected $update = [];
		// 隐藏字段
		protected $hidden = ['type', 'uid', 'delete_time', 'create_time'];
	
		// 搜索器
		public function searchNameAttr($query, $value, $data){
			$query->where('name', 'like', '%'.$value.'%');
		}

		public function searchNameEnAttr($query, $value, $data){
			$query->where('name_en', 'like', '%'.$value.'%');
		}

		public function searchIdAttr($query, $value, $data){
			$query->where('id', $value);
		}

		public function searchNeqIdAttr($query, $value, $data){
			$query->where('id', 'neq', $value);
		}

		public function searchUpdateTimeAtrr($query, $value, $data){
			$query->whereBetweenTime('update_time', $value[0], $value[1]);
		}


	}