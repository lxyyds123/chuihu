<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wyhcontent extends Model
{
    protected $table = 'content';
    protected $remeberTokenName = NULL;
    protected $guarded = [];

    public static function wyh_showfabu($account, $status)
    {
        try{
            $count =self::where(['user_id'=>$account,
                'status'=>$status])
                ->orderby('updated_at', 'desc')
                ->get(['id','title','content','image','status']);
            return $count;
        }catch (\Exception $e) {
            logError("账号查询失败！", [$e->getMessage()]);
            return false;
        }
    }

    public static function checkk($title)
    {
        try{
            $count =self::where('title',$title)
                ->count();
            return $count;
        }catch (\Exception $e) {
            logError("发布失败！", [$e->getMessage()]);
            return false;
        }
    }

    public static function creattt($account, $title, $content, $url, $status)
    {

        try{

            $count =self::create(['user_id'=>$account,'title'=>$title,'content'=>$content,'image'=>$url,'status'=>$status]);
            return $count ?
                $count :
                false;
        }catch (\Exception $e) {
            logError("发布失败！", [$e->getMessage()]);
            return false;
        }
    }


}
