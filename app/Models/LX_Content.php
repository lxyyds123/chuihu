<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LX_Content extends Model
{
    protected $table = "content";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];


    /**
     * 详情页
     */
    public static function lx_detail($content_id)
    {
        try{
            $data = self::join('users','users.account','content.user_id')
                ->select('users.account','users.nick','users.image as 头像','users.introduce',
                    'content.title','content.content','content.image as 图片','content.created_at','content.updated_at')
                ->where('content.id',$content_id)
                ->get();
            return $data;
        }catch (\Exception $e) {
            logError('操作失败', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 分类
     */
    public static function lx_fenlei($status)
    {
        try{
            $data = self::join('users','users.account','content.user_id')
                ->select('user_id','users.nick','users.image as 头像','content.id as content.id','content.title',
                    'content.content','content.image as 封面','content.created_at','content.updated_at')
                ->where('status',$status)
                ->orderby('content.updated_at','desc')
                ->get();
            return $data;
        }catch (\Exception $e) {
            logError('操作失败', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 搜索
     */
    public static function lx_sousuo($title)
    {
        try{
            $data = self::join('users','users.account','content.user_id')
                ->select('user_id','users.nick','users.image as 头像','content.id as content.id','content.title',
                    'content.content','content.image as 封面','content.created_at','content.updated_at')
                ->orwhere('title','like','%'.$title.'%')
                ->orderby('content.updated_at','desc')
                ->get();
            return $data;
        }catch (\Exception $e) {
            logError('操作失败', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 首页推荐
     */
    public static function lx_shouye()
    {
        try{
            $data = self::join('users','users.account','content.user_id')
                ->select('user_id','users.nick','users.image as 头像','content.id as content.id','content.title',
                    'content.content','content.image as 封面','content.created_at','content.updated_at')
                ->inRandomOrder()
                ->take(10)
                ->get();
            return $data;
        }catch (\Exception $e) {
            logError('操作失败', [$e->getMessage()]);
            return false;
        }
    }


}
