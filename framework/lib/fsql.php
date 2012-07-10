<?php
////////////////////////////////////////////
//                                        //
//   © Daniel Fortuyn 2010                //
//   class lib_fsql                   //
//   Last Edit:                           //
//   Ver. 1.01 08/02/10                   //
//   Keuze voor andere error afhandeling  //
//                                        //
////////////////////////////////////////////

class lib_fsql	{
	static public function find($table = '',$query = '',$order = '',$dir = '',$start = '',$num = '',$db = '')	{
		$factory = new lib_fsqlHelp();
		return $factory->find($table,$query,$order,$dir,$start,$num,$db);
	}
	static public function findOne($table,$query,$db = '')	{
		$factory = new lib_fsqlHelp();
		return $factory->findOne($table,$query,$db);
	}
	static public function findById($table,$query,$db = '')	{
		$factory = new lib_fsqlHelp();
		return $factory->findById($table,$query,$db);
	}
	static public function findNum($table,$query,$db = '')	{
		$factory = new lib_fsqlHelp();
		return $factory->findNum($table,$query,$db);
	}
	static public function join($query,$model)	{
		$factory = new lib_fsqlHelp();
		return $factory->join($query,$model);
	}
	static public function findByFk($table,$fk,$fkid,$db = '')	{
		$factory = new lib_fsqlHelp();
		return $factory->findByFk($table,$fk,$fkid,$db);
	}
}
?>