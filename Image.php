<?php
	/**
	 * User: Da ming XDJ
	 */

	namespace app\admin\model;


	use think\Exception;

	class Image extends Base
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
		protected $hidden = ['source', 'uid', 'delete_time', 'create_time'];

        // 读取器
        public function getUrlAttr($value, $data) {
             return $this->prefixImgUrl($value, $data);
		}


	}