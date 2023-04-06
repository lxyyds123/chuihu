<?php

namespace App\Models;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;

class LX_History extends Model
{
    protected $table = "history";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    /**
     * 创建某人最近
     */
    public static function lx_recent($user_id, $content_id)
    {
        try{
            $data = self::where('user_id',$user_id)
                ->where('content_id',$content_id)
                ->count();
            if($data == 0){
                $data1 = self::create([
                    'user_id' => $user_id,
                    'content_id' => $content_id,
                    'status1' => 0,
                    'status2' => 0,
                ]);
                return $data1;
            }else {
                $data = self::where('user_id', $user_id)
                    ->where('content_id', $content_id)
                    ->update([
                        'user_id' => $user_id,
                        'content_id' => $content_id
                    ]);
                return $data;
            }
        }catch (\Exception $e) {
            logError('操作失败', [$e->getMessage()]);
            return false;
        }

    }


    /**
     * 返回点赞收藏状态码
     */
    public static function lx_status($user_id, $content_id)
    {
        try {
            $data = self::select('status1', 'status2')
                ->where('user_id', $user_id)
                ->where('content_id', $content_id)
                ->get();

            return $data;
        } catch (\Exception $e) {
            logError('操作失败', [$e->getMessage()]);
            return false;

        }
    }


    /**
     * 点赞
     */
    public static function lx_dianzan($user_id, $content_id, int $status2)
    {
        try{
            if($status2 == 0){
                $data1 = self::where('user_id',$user_id)
                    ->where('content_id',$content_id)
                    ->update([
                        'status1' => 1
                    ]);
            }else{
                $data1 = self::where('user_id',$user_id)
                    ->where('content_id',$content_id)
                    ->update([
                        'status1' => 0
                    ]);
            }
            return $data1;
        }catch (\Exception $e) {
            logError('操作失败', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 收藏
     */
    public static function lx_shoucang($user_id, $content_id, int $status2)
    {
        try{
            if($status2 == 0){
                $data1 = self::where('user_id',$user_id)
                    ->where('content_id',$content_id)
                    ->update([
                        'status2' => 1
                    ]);
            }else{
                $data1 = self::where('user_id',$user_id)
                    ->where('content_id',$content_id)
                    ->update([
                        'status2' => 0
                    ]);
            }
            return $data1;
        }catch (\Exception $e) {
            logError('操作失败', [$e->getMessage()]);
            return false;
        }
    }

    public static function lx_cx($account)
    {
        try{
            $data = self::join('users','users.account','history.user_id')
                ->join('content','content.id','history.content_id')
                ->select('content.id as content_id','users.nick','history.user_id','content.title','content.content','content.image','status')
                ->where('history.user_id',$account)
                ->get();
            return $data;
        }catch (\Exception $e) {
            logError('操作失败', [$e->getMessage()]);
            return false;
        }

    }

}
