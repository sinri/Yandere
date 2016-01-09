<?php
require_once(__DIR__."/../toolkit/init.php");

/**
* 
*/
class YamaiModel
{
	
	function __construct()
	{
		# code...
	}

	public static function getYamaiId($yamai_name,$yamai_type="",$yamai_system=""){
		global $db;

		QLog::log("YamaiModel::getYamaiId($yamai_name,$yamai_type,$yamai_system)");

		$yamai_name_quoted=$db->quote($yamai_name);
		$yamai_type_quoted=$db->quote($yamai_type);
		$yamai_system_quoted=$db->quote($yamai_system);

		$sql="SELECT yamai_id FROM yamai 
		WHERE yamai_name = {$yamai_name_quoted}
		AND yamai_type = {$yamai_type_quoted}
		AND yamai_system = {$yamai_system_quoted}
		";
		$yamai_id=$db->getOne($sql);

		QLog::log("getYamaiId::Search: [".$sql."] => $yamai_id");

		if(empty($yamai_id)){
			$yamai_id=YamaiModel::createYamaiItem($yamai_name,$yamai_type,$yamai_system);
		}

		return $yamai_id;
	}

	public static function createYamaiItem($yamai_name,$yamai_type="",$yamai_system=""){
		global $db;

		$yamai_name_quoted=$db->quote($yamai_name);
		$yamai_type_quoted=$db->quote($yamai_type);
		$yamai_system_quoted=$db->quote($yamai_system);

		$sql="INSERT INTO yamai (yamai_id,yamai_name,yamai_type,yamai_system)
		VALUES (NULL,{$yamai_name_quoted},{$yamai_type_quoted},{$yamai_system_quoted})";

		$yamai_id=$db->insert($sql);

		QLog::log("getYamaiId::Insert: [".$sql."] => $yamai_id");

		return $yamai_id;
	}

	public static function listYamai($yamai_name="",$yamai_type="",$yamai_system="",$limit=0,$offset=0){
		global $db;

		$yamai_name_quoted=$db->quote($yamai_name);
		$yamai_type_quoted=$db->quote($yamai_type);
		$yamai_system_quoted=$db->quote($yamai_system);

		$sql="SELECT y.*,group_concat('',k.keyword) keyword_list FROM yamai y
			LEFT JOIN yamai_keyword yk ON y.yamai_id=yk.yamai_id
			LEFT JOIN keyword k ON k.keyword_id=yk.keyword_id
			WHERE 1 ";
		if(!empty($yamai_name)){
			$sql.=" AND y.yamai_name LIKE {$yamai_name_quoted} ";
		}
		if(!empty($yamai_type)){
			$sql.=" AND y.yamai_type LIKE {$yamai_type_quoted} ";
		}
		if(!empty($yamai_system)){
			$sql.=" AND y.yamai_system LIKE {$yamai_system_quoted} ";
		}
		$sql.=" group by y.yamai_id ";
		if(!empty($limit)){
			$sql.=" LIMIT ".intval($limit)." ";
		}
		if(!empty($offset)){
			$sql.=" OFFSET ".intval($offset)." ";
		}

		$list=$db->getAll($sql);
		return $list;
	}

	public static function queryYamaiWithTypeAndPrefix($which,$prefix='',$limit=10){
		global $db;

		if(!in_array($which, array('yamai_type','yamai_system','yamai_name'))){
			return false;
		}

		$keyword_quoted=$db->quote($prefix."%");

		$sql="SELECT distinct `{$which}` as itemName FROM yamai 
			WHERE `{$which}` like {$keyword_quoted} 
			LIMIT ".intval($limit);
		$list=$db->getAll($sql);
		QLog::log("YamaiModel::queryYamaiWithTypeAndPrefix($which,$prefix,$limit)->sql: ".$sql." -> list: ".json_encode($list));
		return $list;
	}
}

