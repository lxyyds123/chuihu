<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wyhhistory extends Model
{
    protected $table = 'history';
    protected $remeberTokenName = NULL;
    protected $guarded = [];

    public static function check($account)
    {
        try{
            $count =self::select('content_id')->where('user_id',$account)
                ->orderby('updated_at', 'desc')
                ->get();
            return $count;
        }catch (\Exception $e) {
            logError("账号查询失败！", [$e->getMessage()]);
            return false;
        }
    }

    public static function dian($account)
    {
        try{

            $count =self::where(['user_id'=>$account,
                'status1'=>1])
                ->orderby('updated_at', 'desc')
                ->get('content_id');
            return $count;
        }catch (\Exception $e) {
            logError("账号查询失败！", [$e->getMessage()]);
            return false;
        }
    }

    public static function shou($account)
    {
        try{

            $count =self::where(['user_id'=>$account,
                'status2'=>1])
                ->orderby('updated_at', 'desc')
                ->get('content_id');
            return $count;
        }catch (\Exception $e) {
            logError("账号查询失败！", [$e->getMessage()]);
            return false;
        }
    }
}
