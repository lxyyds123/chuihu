<?php

namespace App\Http\Controllers;


use App\Models\LX_Content;
use App\Models\LX_History;
use App\Models\Users;
use App\Models\Wyhcontent;
use App\Models\Wyhhistory;
use App\services\OSS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use mysql_xdevapi\XSession;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Tymon\JWTAuth\Http\Middleware\RefreshToken;

class UserController extends Controller
{
    /**
     * 注册
     * @param Request $registeredRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function registered(Request $registeredRequest)
    {
        $count = Users::checknumber($registeredRequest);   //检测账号密码是否存在
        if($count == 0)
        {
            $student_id = Users::createUser(self::userHandle($registeredRequest));

            return  $student_id ?
                json_success('注册成功!',$student_id,200  ) :
                json_fail('注册失败!',null,100  ) ;
        }
        else{
            return
                json_success('注册失败!该工号已经注册过了！',null,100  ) ;
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $credentials = self::credentials($request);   //从前端获取账号密码
        $token = auth('api')->attempt($credentials);   //获取token
        return $token?
            json_success('登录成功!',$token,  200):
            json_fail('登录失败!账号或密码错误',null, 100 ) ;
        //       json_success('登录成功!',$this->respondWithToken($token,$user),  200);
    }
    /**
     * 修改密码时从新加密
     */
    protected function userHandle111($password)   //对密码进行哈希256加密
    {
        $red = bcrypt($password);
        return $red;
    }


    public function new_password(Request $request)
    {
        $account= auth('api')->user()->account;
        $password1=DB::table('users')->where('account', '=', $account)->value('password');
        $password=$request['password'];
        if (!Hash::check($password,$password1))
        {
            return json_fail('原密码错误',null,100);
        }
        else
        {
            $newpassword=$request['newpassword'];
            $password3 = self::userHandle111($newpassword);
            $red = DB::table('users')->where('account', '=', $account)->update([
                'password' => $password3
            ]);
            return $red ?
                json_success('修改成功!', $red, 200) :
                json_fail('修改失败!', null, 100);
        }


    }

    //封装token的返回方式
    protected function respondWithToken($token, $msg)
    {
        // $data = Auth::user();
        return json_success( $msg, array(
            'token' => $token,
            //设置权限  'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ),200);
    }
    protected function credentials($request)   //从前端获取账号密码
    {
        return ['account' => $request['account'], 'password' => $request['password']];
    }

    protected function userHandle($request)   //对密码进行哈希256加密
    {
        $registeredInfo = $request->except('password_confirmation');
        $registeredInfo['password'] = bcrypt($registeredInfo['password']);
        $registeredInfo['account'] = $registeredInfo['account'];
        return $registeredInfo;
    }
    //编辑个人资料
    public function wyh_fix(Request $request)
    {
        $file = $request->file('image');//读取file文件
        $tmppath = $file->getRealPath();//获取文件的真实路径
        $fileName = rand(1000,9999).$file->getFilename().time().date('ymd').'.'.$file->getClientOriginalExtension();
        //拼接文件名
        $pathName = date('Y-m/d').'/'.$fileName;
        OSS::publicUpload('wyhzs',$pathName,$tmppath,['ContentType'=>'inline']);
        //获取文件URl
        $url  =OSS::getPublicObjectURL('wyhzs',$pathName);
        $account= auth('api')->user()->account;
        $nick=$request['nick'];
        $introduce=$request['introduce'];
        $gender=$request['gender'];
        $home=$request['home'];
        $date=Users::wyh_fix($account,$nick,$introduce,$gender,$home,$url);
        return $date?
            json_success("编辑成功!",$date,200):
            json_fail("编辑失败!",null,100);

    }
    //展示个人资料
    public function wyh_show(Request $request)
    {
        $account= auth('api')->user()->account;
        $date=Users::wyh_show($account);
        return $date?
            json_success("编辑成功!",$date,200):
            json_fail("编辑失败!",null,100);
    }
    //展示最近
    public function wyh_recent(Request $request)
    {
        $account= auth('api')->user()->account;
        $content_id=Wyhhistory::check($account);
        $re=[];
        foreach ($content_id as $k=>$value)
        {
          $content=$value['content_id'];
          $datil=DB::table('content')->where('id',$content)->get(['id','user_id','title','content','image','status']);
          $nick=DB::table('users')->where('account',$account)->value('nick');
          $datil[0]->nick=$nick;
          array_push($re,$datil[0]);
        }
        return $re?
            json_success("查找成功!",$re,200):
            json_fail("查找失败!",null,100);
    }

    //展示最近
    public function LX_cx(Request $request){
        $account= auth('api')->user()->account;
        $data = LX_History::lx_cx($account);

        return $data?
            json_success("查找成功!",$data,200):
            json_fail("查找失败!",null,100);
    }

    //展示自己发布的东西
    public function wyh_showfabu(Request $request)
    {
        $account= auth('api')->user()->account;
        $status=$request['status'];
        $date=Wyhcontent::wyh_showfabu($account,$status);
        $nick=DB::table('users')->where('account',$account)->value('nick');
        foreach ($date as $value)
        {
            $value['nick']=$nick;

        }
        return $date?
            json_success("查找成功!",$date,200):
            json_fail("查找失败!",null,100);
    }
    //我的点赞
    public function wyh_dian(Request $request)
    {
        $account= auth('api')->user()->account;
        $content_id=Wyhhistory::dian($account);
        $re=[];
        foreach ($content_id as $k=>$value)
        {
            $content=$value['content_id'];
            $datil=DB::table('content')->where('id',$content)->get(['id','user_id','title','content','image','status']);
            $nick=DB::table('users')->where('account',$account)->value('nick');
            $datil[0]->nick=$nick;
            array_push($re,$datil[0]);
        }
        return $re?
            json_success("查找成功!",$re,200):
            json_fail("查找失败!",null,100);

    }
    //我的收藏
    public function wyh_shou(Request $request)
    {
        $account= auth('api')->user()->account;
        $content_id=Wyhhistory::shou($account);
        $re=[];
        foreach ($content_id as $k=>$value)
        {
            $content=$value['content_id'];
            $datil=DB::table('content')->where('id',$content)->get(['id','user_id','title','content','image','status']);
            $nick=DB::table('users')->where('account',$account)->value('nick');
            $datil[0]->nick=$nick;
            array_push($re,$datil[0]);
        }
        return $re?
            json_success("查找成功!",$re,200):
            json_fail("查找失败!",null,100);
    }
    //删除内容
    public function wyh_delete(Request $request)
    {
        $account= auth('api')->user()->account;
        $content_id=$request['content_id'];
        $date=DB::table('content')->where('id',$content_id)->delete();
        DB::table('history')->where('content_id',$content_id)->delete();
        DB::table('remark')->where('content_id',$content_id)->delete();
        return $date?
            json_success("删除成功!",$date,200):
            json_fail("删除失败!",null,100);
    }
    //发布内容
    public function wyh_fabu(Request $request)
    {
        $account= auth('api')->user()->account;
        $title=$request['title'];
        $cont=Wyhcontent::checkk($title);
        $url=null;
        if ($cont==0)
        {
            if ($request['image']!=false)
            {
                $file = $request->file('image');//读取file文件
                $tmppath = $file->getRealPath();//获取文件的真实路径
                $fileName = rand(1000,9999).$file->getFilename().time().date('ymd').'.'.$file->getClientOriginalExtension();
                //拼接文件名
                $pathName = date('Y-m/d').'/'.$fileName;
                OSS::publicUpload('wyhzs',$pathName,$tmppath,['ContentType'=>'inline']);
                //获取文件URl
                $url  =OSS::getPublicObjectURL('wyhzs',$pathName);
            }
            $content=$request['content'];
            $status=$request['status'];
            $da=Wyhcontent::creattt($account,$title,$content,$url,$status);
            return $da?
                json_success("发布成功!",$da,200):
                json_fail("发布失败!",null,100);
        }
        else
        {
            return json_fail('该内容标题发布过',null,100);
        }
    }



}
