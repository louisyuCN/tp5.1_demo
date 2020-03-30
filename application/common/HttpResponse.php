<?php


namespace app\common;


class HttpResponse
{
   public static function success($data)
   {
       return json([
           'code' => 0,
           'list' => $data,
       ]);
   }

   public static function fail($msg)
   {
       return json([
            'code' => -1,
            'msg' => $msg
       ]);
   }
}