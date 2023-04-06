<?php
namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Users extends Authenticatable implements JWTSubject
{
    use Notifiable ;

    protected $table = 'users';
    protected $remeberTokenName = NULL;
    protected $guarded = [];




    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
        return ['role' => 'admin'];
    }


    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey();
    }
    /**
     * 创建用户
     *
     * @param array $array
     * @return |null
     * @throws \Exception
     */
    public static function createUser($request)
    {
        try {
            $student_id = self::create(['account'=>$request['account'],
                'password'=>$request['password'],
                'nick'=>$request['account']])->id;
            return $student_id ?
                $student_id :
                false;
        } catch (\Exception $e) {
            logError('添加用户失败!', [$e->getMessage()]);
            die($e->getMessage());
            return false;
        }
    }

    /**
     * 查询该工号是否已经注册
     * 返回该工号注册过的个数
     * @param $request
     * @return false
     */
    public static function checknumber($request)
    {
        $student_job_number = $request['account'];
        try{
            $count =self::select('account')
                ->where('account',$student_job_number)
                ->count();
            //echo "该账号存在个数：".$count;
            //echo "\n";
            return $count;
        }catch (\Exception $e) {
            logError("账号查询失败！", [$e->getMessage()]);
            return false;
        }
    }
    //修改个人资料哦
    public static function wyh_fix($account, $nick, $introduce, $gender, $home,$url)
    {
        try {
            $student_id = self::where('account',$account)
                ->update(['image'=>$url,
                    'nick'=>$nick,
                    'introduce'=>$introduce,
                   'gender'=> $gender,
                    'home'=>$home]);
            return $student_id ?
                $student_id :
                false;
        } catch (\Exception $e) {
            logError('编辑资料失败!', [$e->getMessage()]);
            die($e->getMessage());
            return false;
        }
    }
    //展示个人资料
    public static function wyh_show($account)
    {
        try{
            $count =self::select('account','nick','image','gender','introduce')
            ->where('account',$account)
                ->get();
            return $count;
        }catch (\Exception $e) {
            logError("账号查询失败！", [$e->getMessage()]);
            return false;
        }
    }
}
