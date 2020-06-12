<?php
	/**
	 * User: Da ming
	 */

	namespace app\admin\model;


	use think\Exception;
	use think\facade\Env;
	use think\facade\Request;

	use think\facade\Config;
	use app\common\model\BaseModel;
	use think\model\concern\SoftDelete;
	use app\admin\model\Tree;
	use app\lib\exception\ParameterException;

	class Base extends BaseModel
	{

		protected $autoWriteTimestamp = true;
		// protected $updateTime = 'update_time';
		// protected $createTime = false;
		// 软删除
		use SoftDelete;
		protected $deleteTime = 'delete_time';
		protected $defaultSoftDelete = 0;

		// 添加
		public static function _add($params, $info_on=false) {
			$modelSelf = new static();
			if(array_key_exists('id', $params)) unset($params['id']);
			$result = $modelSelf->isUpdate(false)
				->allowField(true)
				->save($params);
			if ($result) $result = $info_on? $modelSelf : $modelSelf->id;
			$modelSelf::_hookSql($modelSelf, $params, 'add');
			return $result;
		}
		
        // 删除
        public static function _delete($pk, $info_on=false) {
			$flag = !config('database.soft_delete'); // true 为真实删除
			// $flag = true;
			$modelSelf = new static();
			$mySelf = $modelSelf::get($pk);
			if (!$mySelf) throw new ParameterException([
                'message' => '删除的ID传入有误!',
			]);
			$result = $mySelf::destroy($pk, $flag);
			$result = $info_on? $mySelf : $result;
			$modelSelf::_hookSql($mySelf, [$mySelf->pk=>$pk], 'delete');
            return $result;
		}


		// 修改
		public static function _update($params, $info_on=false, $pk=null) {
			$modelSelf = new static();
			if(array_key_exists('id', $params)) $pk = $params['id'];
			$mySelf = $modelSelf::get($pk);
			if(!$mySelf)  throw new ParameterException([
                'message' => '修改的ID传入有误!',
			]);
			$modelSelf::_hookBeforeDate($mySelf);
			// exit();
			$result = $mySelf->isUpdate(true)
			    ->allowField(true)
				->save($params);
			if ($result) $result = $info_on? $mySelf : $mySelf->id;
			$modelSelf::_hookSql($mySelf, $params, 'update');
			return $result;
		}


		/*
		* 查询
		*/ 
		public static function _search($condition, $params, $fetchSql = false) {
			$modelSelf = new static();
			if(strpos('_', $condition->method) === false){
				$condition->method = '_'.$condition->method;
			}
            $result = $modelSelf ->{$condition->method}($modelSelf, $condition, $params, $fetchSql);
			return $result;
		}


		// paginate查询
        protected static function _paginate($modelSelf, $condition, $params, $fetchSql = false) {
			$result = $modelSelf->with($condition->with)
			    ->withSearch($condition->map, $params)
				->order($condition->order)
				->field($condition->field)
				->fetchSql($fetchSql)
				->paginate($condition->pagenumber, false, ['query' => request()->param()])
				->each(function($item, $key)use($condition){ // 公共处理
					return $item;
				});
			if($result) {
			   $result = $result->toArray();
			}
			$modelSelf::_hookSql($modelSelf, $params, 'paginate', $condition);
			return $result;
		}

		// select查询
        protected static function _select($modelSelf, $condition, $params, $fetchSql = false) {
			$result = $modelSelf->with($condition->with)
			    ->withSearch($condition->map, $params)
				->order($condition->order)
				->field($condition->field)
				->limit($condition->pagenumber)
				->fetchSql($fetchSql)
				->select();
			if($result && $condition){
			   $result = $result->toArray();
			   if(is_array($condition->tree)){
			      if(count($condition->tree) ===6){
					$result = $modelSelf->_tree($condition->tree[0], $result, $condition->tree[1], $condition->tree[2], $condition->tree[3], $condition->tree[4], $condition->tree[5]);
				  }
			   }
			}
			$modelSelf::_hookSql($modelSelf, $params, 'select', $condition);
			return $result;
		}

		// find查询
        protected static function _find($modelSelf, $condition, $params, $fetchSql = false) {
			$result = $modelSelf->with($condition->with)
			    ->withSearch($condition->map, $params)
				->order($condition->order)
				->field($condition->field)
				->fetchSql($fetchSql)
				->find();
			if($result) {
			   $result = $result->toArray();
			}
			$modelSelf::_hookSql($modelSelf, $params, 'find', $condition);
			return $result;
		}


		// 无限集预处理函数
		protected static function _tree($type = 'One', $data, $parentId=0, $lev=0, $pid='pid', $id='id', $myself=true){
			$treeModel = new Tree();
			$tree = [];
			switch ($type) {
				case 'One':
					$tree = $treeModel->_treeSort($data, $parentId, $lev, $pid, $id);	
					if($myself && $parentId!==0) {
						$myselftree = $treeModel->_treeSortMyself($data, $parentId, $lev, $pid, $id);
						$tree = array_merge($myselftree, $tree);
					}
					break;
				default:
					$tree = $treeModel->_treeNode($data,$parentId,$pid, $id);
					if($myself && $parentId!==0) {
			            $sonTree = $tree;
						$tree = $treeModel->_treeNodeMyself($data,$parentId,$pid, $id);
						if(!empty($tree)){
							$tree[$pid] = $sonTree;
						}
					}
					break;
			}
			return  $tree;
		}

		// 记录所执行的sql语句用于数据分析与操作日志
		protected static function _hookSql($modelSelf, $params, $method='add', $condition=false) {
			$data['params'] = $params; // 传入的字段
			$data['table'] = $modelSelf->getTable(); // 获取完整表名
			// 获取sql语句
			if(strpos('add&update&delete', $method) !== false) {
			   $data['sqlstring'] = $modelSelf->getLastSql();
			   $data['table_pk_value'] = $modelSelf->id; // 修改主键值
			}else{
			   $data['sqlstring'] = $modelSelf->with($condition->with)
			        ->withSearch($condition->map, $params)
				    ->order($condition->order)
					->field($condition->field)
					->limit($condition->pagenumber)
					->fetchSql(true)
					->{$method}();
			//    var_dump($data['params']);
			//    var_dump($condition->map);
			//    var_dump($data['sqlstring']);
			   $data['table_pk_value'] = 0; // 修改主键值
			}

			$data['table_field_array'] = $modelSelf->query("SHOW FULL COLUMNS FROM ".$data['table'].""); // 获取数据表所有字段信息
			$data['table_array'] = $modelSelf->query("show table status like '".$data['table']."'"); // 获取数据表信息
			$data['table_self_model'] = $modelSelf; // 操作模型
			Request::hook('afterInfo', function()use($data){
				return $data;
			});
		}

		protected static function _hookBeforeDate($modelSelf){
			$beforeInfo = $modelSelf->getData();
			Request::hook('beforeInfo', function()use($beforeInfo){
				return $beforeInfo;
			});
		}


	}