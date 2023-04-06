<?php

namespace App\Models;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;

class LX_Remark extends Model
{
    protected $table = "remark";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];


    /**
     * 发评论
     */
    public static function lx_remark($user_id, $content_id, $comment)
    {
        try{
            $data = self::create([
                'user_id' => $user_id,
                'content_id' => $content_id,
                'comment' => $comment
            ]);
            return $data;
        }catch (\Exception $e) {
            logError('操作失败', [$e->getMessage()]);
            return false;
        }

    }

    /**
     *删除评论
     */
    public static function lx_delete_remark($user_id, $id)
    {
        try{
            $data = self::where('user_id',$user_id)
                ->where('id',$id)
                ->delete();
            return $data;
        }catch (\Exception $e) {
            logError('操作失败', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 展示评论
     */
    public static function lx_show_remark($content_id,$fromDate,$toDate)
    {
        try{
            $data = self::join('users','users.account','remark.user_id')
                ->select('users.account','users.nick','users.image','remark.id','users.introduce',
                    'remark.comment','remark.created_at','remark.updated_at')
                ->where('content_id',$content_id)
                ->whereBetween('remark.created_at',[$fromDate,$toDate])
                ->orderby('remark.created_at','desc')
                ->get();
            return $data;
        }catch (\Exception $e) {
            logError('操作失败', [$e->getMessage()]);
            return false;
        }
    }




}
