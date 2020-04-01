<?php
namespace app\index\controller;
use think\facade\Cache;

class Index
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) 2018新年快乐</h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function setCache()
    {
//        $cache = Cache::init();
//        $handler = $cache->handler();
//        $result = $handler->get('yll');
//        dump($result);
//        Cache::store('redis')->set('name', 'yll', ['NX', 'EX'=>20]);
//        $handler = Cache::store('redis')->handler();
//        $lock = $handler->set('name', 'yll', ['NX', 'EX'=>20]);
//        if ($lock)
//                return 'add lock success';
//        else
//            return 'has locked';
        //Cache::store('redis')->set('map', [ 'name' => 1, 'age' => 27], 30000);
        //return;
        $client = Cache::store('redis');
        $handler = $client->handler();
        $lock = $handler->set('lock', '111', array('nx', 'ex' => 5));

        if ($lock) {
            echo '获得锁处理事情';
        } else {
            echo '未获得锁下一次执行';
        }
    }

    public function getCache()
    {
        $redisClient = Cache::store('redis');
        $result = $redisClient->get('yll', false);
        return json($result);
    }

    public function test()
    {
        return 11;
    }
}

//$name = [ 'a' => 1 ];
//$kkk = $name;
//var_dump(boolval('a'));
//debug_zval_dump($name);
//echo doubleval('123helloworld');
//var_dump(empty(''));

//$name = 111;
//$key = 222;
//print_r(get_defined_vars());
//$resource = opendir('c://test');
//echo get_resource_type($resource);
//echo gettype('111');
//echo intval('1.11');
//$arr = [];
//echo is_array($arr);
//$arr = [];
//var_dump(is_callable($arr));
//echo is_iterable('aaa');
//echo is_null(null);
//echo is_numeric('111');
//echo is_numeric(111);
//$name = '222';
$arr = ['name'=>2, 'nam2'=>[ 'aaa' => 1 ]];
//echo isset($arr['name1']);

//$serializedStr =  serialize($arr);
//var_dump(unserialize($serializedStr));
$name = '111aaa';
//settype($name, 'integer');
//echo $name;
//echo strval($name);
//unset($name);
//$result = preg_filter(['/\d+/', '/[a-z]{1,}/', '/[@|%]/'], ['数字', '字母', '特殊字符'], [ 1, 2, 4, 'adc', '@', '%' ]);
//var_dump($result);

//$result = preg_replace('/\d+/', 'hello', '1 shanghai 2 beijing 3');
//echo $result;
//echo preg_last_error();