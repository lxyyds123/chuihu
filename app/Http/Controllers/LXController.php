<?php

namespace App\Http\Controllers;


use App\Http\Requests\LX_remarkRequest;
use App\Http\Requests\LX_sousuoRequest;
use App\Models\LX_Content;
use App\Models\LX_History;
use App\Models\LX_Remark;
use Illuminate\Http\Request;

class LXController extends Controller
{
    /**
     * 返回点赞收藏状态码
     */
    public function LX_status(Request $request){
        $user_id = auth('api')->user()->account;
        $content_id=$request['content_id'];
        $data = LX_History::lx_status($user_id,$content_id);
        return $data ?
            json_success("查询成功", $data, 200) :
            json_fail("查询失败", null, 100);

    }



    /**
     * 创建某人最近
     */
    public function LX_recent(Request $request){
        $user_id= auth('api')->user()->account;
        $content_id=$request['content_id'];
        $data= LX_History::lx_recent($user_id,$content_id);
        return $data ?
            json_success("添加成功", $data, 200) :
            json_fail("数据库已有数据", null, 100);

    }

    /**
     * 发评论
     */
    public function LX_remark(LX_remarkRequest $request){
        $user_id= auth('api')->user()->account;
        $content_id=$request['content_id'];
        $comment = $request['comment'];
        $data = LX_Remark::lx_remark($user_id,$content_id,$comment);
        return $data ?
            json_success("提交成功", $data, 200) :
            json_fail("提交失败", null, 100);
    }

    /**
     * 删除评论
     */
    public function LX_delete_remark(Request $request){
        $user_id= auth('api')->user()->account;
        $id = $request['id'];
        $data = LX_Remark::lx_delete_remark($user_id,$id);
        return $data ?
            json_success("删除成功", $data, 200) :
            json_fail("删除失败", null, 100);
    }

    /**
     * 展示评论
     */
    public function LX_show_remark(Request $request){
        $content_id = $request['content_id'];
        $fromDate = $request['fromDate'];
        $toDate = $request['toDate'];
        $data = LX_Remark::lx_show_remark($content_id,$fromDate,$toDate);
        return $data ?
            json_success("展示成功", $data, 200) :
            json_fail("展示失败", null, 100);
    }

    /**
     * 详情页
     */
    public function LX_detail(Request $request){
        $content_id = $request['content_id'];
        $data = LX_Content::lx_detail($content_id);
        return $data ?
            json_success("展示成功", $data, 200) :
            json_fail("展示失败", null, 100);
    }

    /**
     * 点赞
     */
    public function LX_dianzan(Request $request){
        $user_id= auth('api')->user()->account;
        $content_id = $request['content_id'];
        $status1 = self::LX_status($request);
        $aa = $status1->original;
        $bb = json_encode($aa);
        $arr = json_decode($bb);
        $cc = $arr->data;
        foreach ($cc as $k) {
            $status = $k->status1;
        }
        $status1 = $status;
        $status2 = intval($status1);
        $data = LX_History::lx_dianzan($user_id,$content_id,$status2);
        return $data ?
            json_success("点赞成功", $data, 200) :
            json_fail("点赞失败", null, 100);
    }

    /**
     * 收藏
     */
    public function LX_shoucang(Request $request){
        $user_id= auth('api')->user()->account;
        $content_id = $request['content_id'];
        $status1 = self::LX_status($request);
        $aa = $status1->original;
        $bb = json_encode($aa);
        $arr = json_decode($bb);
        $cc = $arr->data;
        foreach ($cc as $k) {
            $status = $k->status2;
        }
        $status1 = $status;
        $status2 = intval($status1);
        $data = LX_History::lx_shoucang($user_id,$content_id,$status2);
        return $data ?
            json_success("收藏成功", $data, 200) :
            json_fail("收藏失败", null, 100);
    }


    /**
     * 分类
     */
    public function LX_fenlei(Request $request){
        $status = $request['status'];
        $data = LX_Content::lx_fenlei($status);
        return $data ?
            json_success("查询成功", $data, 200) :
            json_fail("查询失败", null, 100);
    }

    /**
     * 搜索
     */
    public function LX_sousuo(LX_sousuoRequest $request){
        $title = $request['title'];
        $data = LX_Content::lx_sousuo($title);
        return $data ?
            json_success("搜索成功", $data, 200) :
            json_fail("搜索失败", null, 100);
    }

    /**
     * 首页推荐
     */
    public function LX_shouye(){
        $data = LX_Content::lx_shouye();
        return $data ?
            json_success("查询成功", $data, 200) :
            json_fail("查询失败", null, 100);
    }
}
