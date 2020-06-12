<?php
	/**
	 * User: Da ming
	 */

	namespace app\admin\model;
	
	class Tree
	{
       public static function _treeSortMyself($data, $parentId=0, $lev=0, $pid='pid', $id='id'){
		$arr = array();
		foreach ($data as $key=>$value){
			if($value[$id] == $parentId){
				$value["lev"]=$lev-1;
				$arr[]=$value;
			}
		 }
		return $arr;
	   }

		public static function _treeSort($data, $parentId=0, $lev=0, $pid='pid', $id='id') {
			// 此处数据必须是静态数组，不然递归的时候每次都会声明一个新的数组
			static $arr = array();
			foreach ($data as $key=>$value){
				if($value[$pid] == $parentId){
					$value["lev"]=$lev;
					$arr[]=$value;
					self::_treeSort($data,$value[$id],$lev+1,$pid,$id);
				}
			 }
			return $arr;
		}


		public static function _treeNodeMyself($data,$parentId = 0,$pid='pid',$id='id'){
			$arr = array();
			foreach ($data as $key=>$value){
				if($value[$id] == $parentId){
					$arr = $value;
				}
			 }
			return $arr;
		   }

		public static function _treeNode($data,$parentId = 0,$pid='pid',$id='id') {
			// 用于保存整理好的分类节点
			$node = [];
			// 循环所有分类
			foreach ($data as $key => &$value) {
				if( $value ["$pid"] == $parentId )
				{ // 父亲找到儿子
				   $value["$pid"] = self::_treeNode($data,$value ["$id"],$pid,$id); // 顺序错误会报错
				   $node [] = $value;
				}
			}
			return $node;
		} 
		
	}