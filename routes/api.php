<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();

});
Route::post('registed', 'UserController@registered');//注册账号
Route::post('login', 'UserController@login');//登录账号
Route::post('new_password', 'UserController@new_password');//修改密码
Route::post('fix_pel', 'UserController@wyh_fix');//编辑个人资料
Route::post('show_pel', 'UserController@wyh_show');//展示个人资料
Route::post('show_re', 'UserController@wyh_recent');//展示最近
Route::post('show_fabu', 'UserController@wyh_showfabu');//展示发布的东西
Route::post('show_dian', 'UserController@wyh_dian');//我的点赞
Route::post('show_sou', 'UserController@wyh_shou');//我的收藏
Route::post('delete', 'UserController@wyh_delete');//删除内容
Route::post('fabu', 'UserController@wyh_fabu');//发布内容

Route::post('recent', 'LXController@LX_recent');//创建某人最近
Route::post('status', 'LXController@LX_status');//返回点赞收藏状态码
Route::post('remark', 'LXController@LX_remark');//发评论
Route::post('show_remark', 'LXController@LX_show_remark');//展示评论
Route::post('delete_remark', 'LXController@LX_delete_remark');//删除评论
Route::post('detail', 'LXController@LX_detail');//详情页
Route::post('dianzan', 'LXController@LX_dianzan');//点赞
Route::post('shoucang', 'LXController@LX_shoucang');//收藏

Route::post('fenlei', 'LXController@LX_fenlei');//分类
Route::post('sousuo', 'LXController@LX_sousuo');//搜索
Route::get('shouye', 'LXController@LX_shouye');//搜索


Route::post('xc', 'UserController@LX_cx');
