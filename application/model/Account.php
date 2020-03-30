<?php

namespace app\model;

use think\Model;

class Account extends Model
{
    protected $table = 'account';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'create_time';
    protected $updateTime = 'modify_time';
}

//abstract class Test extends Account
//{
//    abstract public function test1();
//    public function test()
//    {
//        return $this -> table;
//    }
//}
//
//class TestEntity extends Test
//{
//    public function test1()
//    {
//
//    }
//
//}
//
//$obj = new TestEntity;
//echo $obj->test();