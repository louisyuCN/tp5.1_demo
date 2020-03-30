<?php


namespace app\login\controller;


use app\common\HttpResponse;
use think\Response;

class Test
{
    public function test()
    {
        return HttpResponse::success('test');
    }

}