<?php
namespace app\common;

class Account {
    public $name;

//    public function __construct($name)
//    {
//        $this->name = $name;
//    }

    public function set($name)
    {
        $this->name = $name;
    }

    public function get()
    {
        return $this->name;
    }

}