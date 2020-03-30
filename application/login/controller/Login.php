<?php

namespace app\login\controller;

use app\common\HttpResponse;
use app\model\Account;
use think\Controller;
use think\facade\Session;

class Login extends Controller
{
    public function regist()
    {
        $rules = [
            'name' => 'require|max:25',
            'password' => 'require|max:16',
            'code' => 'require',
        ];

        $result = $this->validate(input('post.'), $rules, null, true);

        if (true !== $result)
            return HttpResponse::fail($result);

        try
        {
            $accounts = Account::where('code', input('code'))->select();

            if (count($accounts) > 0) {
                return HttpResponse::fail('用户已存在');
            }

            Account::create([
                'code' => input('post.code'),
                'name' => input('post.name'),
                'password' => md5(input('post.password'))
            ]);

        } catch (\Exception $e)
        {
            return HttpResponse::fail($e->getMessage());
        }
        return HttpResponse::success('注册成功');
    }

    public function login()
    {
        $checkResult = $this->validate(input('post.'), [
            'username' => 'require',
            'password' => 'require'
        ], null, true);

        if (true !== $checkResult)
            return HttpResponse::fail($checkResult);

        try
        {
            $accounts = Account::where([
                'code' => input('post.username'),
                'password' => md5(input('post.password'))
            ])->select();

            if (count($accounts) === 0)
                return HttpResponse::fail('用户名密码错误!');

            Session::set('username', $accounts[0]['name']);
            Session::set('code', $accounts[0]['code']);
        }
        catch (\Exception $e)
        {
            return HttpResponse::fail($e->getMessage());
        }

        return HttpResponse::success('登录成功!');
    }
}


