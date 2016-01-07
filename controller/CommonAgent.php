<?php
require_once(__DIR__."/../toolkit/init.php");
require_once(__DIR__."/../model/KeywordModel.php");
require_once(__DIR__."/../model/YamaiModel.php");
require_once(__DIR__."/../model/KYScoreModel.php");

/**
keyword_id ~ keyword
yamai_id ~ yamai_name yamai_type yamai_system
yamai_id keyword_id ~ relation_score
 */

$act=getRequest('act');

if($act=='update'){
	$yamai_name=getRequest('yamai_name','');
	$yamai_type=getRequest('yamai_type',null);
	$yamai_system=getRequest('yamai_system',null);
	$keyword=getRequest('keyword','');

	$score = getRequest('score',3);

	QLog::log("COMMON UPDATE: [{$yamai_name}][$yamai_type][$yamai_system]+[{$keyword}]={$score}");

	$yamai_id=YamaiModel::getYamaiId($yamai_name,$yamai_type,$yamai_system);
	$keyword_id=KeywordModel::getKeywordId($keyword);
	$afx=KYStoreModel::updateKYScore($keyword_id,$yamai_id,$score);

	QLog::log("DB RESULT: Y[{$yamai_id}]+K[{$keyword_id}]=".($afx===false?'FALSE':$afx));

	echo json_encode(array('updated'=>($afx===false?'0':'1')));
	exit();
}
elseif($act=="list_yamai"){
	$yamai_name=getRequest('yamai_name','');
	$yamai_type=getRequest('yamai_type',null);
	$yamai_system=getRequest('yamai_system',null);

	$limit=0;
	$offset=0;

	$list_of_yamai=YamaiModel::listYamai($yamai_name,$yamai_type,$yamai_system,$limit,$offset);
	echo json_encode(array('list'=>($list_of_yamai===false?array():$list_of_yamai)));
	exit();
}
