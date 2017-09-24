<?php
namespace source\user;
/** *---------------------------------------
 *  | CjForever
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 作者:  CJForever by: notexpired@163.com
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 版权:  © 2017 <CjForever>  Inc
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 时间:  2017/8/27
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
use CZPhp\email;
use source;
class login{
    private static $data = array();
    public static function main(){
        $request = \CZ::$app->request;
        self::$data['email']    = $request->get('email');
        self::$data['password'] = $request->get('password');
        self::check();
        self::checkEmpty();
        self::checkES();
        self::pass();
        // 登录成功操作
        self::success();
    }
    private static function success(){
        \session::set('login',true);
        \session::set('uid',self::$data['uid']);
        $mem = \cacheMem::init();
        $md = md5(self::$data['uid']);
        $res = $mem::get($md);
        if (false === $res){
            $all = source\user\get::info(self::$data['uid']);
            $_REQUEST['mSid'] = base64_encode(\paw::authcode('sid='.$_REQUEST['sid'].'&token='.$_REQUEST['token'].'&time='.time(),'ENCODE',KEY));
            $p   = source\user\get::avatarUrl($all['avatar']);
            $all['avatar']  = SERVER_DOMAIN_NAME.'/'.$p.'?sid='.$_REQUEST['mSid'];
            $all['di']      = 1 === $all['di'] ? true : false;
            $all['login']   = true;
            $all['sid']     = $_REQUEST['mSid'];
            // 保存一个月
            $mem::add($md,$all,time()+3600*24*31);
            \session::set('m_uid',$md);
            message('登录成功',200,['userInfo'=>$all]);
        }else{
            message('登录成功',200,['userInfo'=>$res]);
        }

    }
    private static function pass(){
        try {
            // 两张表一定共存的，所以不需要 右联
            $sql = 'SELECT u.pass,u.myKey,u.uid FROM {prefix}user_info as info ,{prefix}user as u WHERE info.email=:email AND info.member_id=u.uid';
            $res = pdo_sql($sql,[':email'=>self::$data['email']]);
            self::$data['uid'] = $res['uid'];
            if (empty($res)){
                throw new \CZBlogException('会员信息错误');
            }
            // 判断是否锁定
            if ( true === source\user\is::locking($res['uid'])){
                message('系统锁定了你的账户');
            }
            $PRes = \paw::decode($res['pass'],self::$data['password'],$res['myKey']);
            if ( true === $PRes){
                self::record(true);
                return true;
            }
            self::record(false);
            message('账户或密码不正确');
        }catch (\CZBlogException $exception){
            \CZBlogException::core($exception);
            message('登录失败');
        }

    }
    private static function record($success){
        if (true === $success){
            return \db::table('user_login')->insert([
                'email'         =>  self::$data['email'],
                'time'          =>  \get::time(),
                'status'        =>  1,
                'ip'            =>  getIP(),
                'browser'       =>  \get::getBrowser(),
                'equipmentNick' =>  \get::USER_AGENT(),
            ]);
        }
        $conn = \db::begin();
        // 登录失败情况
        $res = $conn::table('user_login')->insert([
                    'email'         =>  self::$data['email'],
                    'time'          =>  \get::time(),
                    'status'        =>  1,
                    'ip'            =>  getIP(),
                    'browser'       =>  \get::getBrowser(),
                    'equipmentNick' =>  \get::USER_AGENT(),
                ]);

        if (empty(self::$data['tmp_locking'])){
            $res2 = $conn::table('user_login_tmp_locking')->insert([
                        'email'         =>  self::$data['email'],
                        'time'          =>  \get::time(),
                        'locking'       =>  1,
                        'number'        =>  1
                    ]);
        }else{
            $number = self::$data['tmp_locking']['number'];
            $nextNumber = $number + 1;
            if ( 5 < $number){
                // 锁定
                $res2 = $conn::table('user_login_tmp_locking')
                    ->where(['email'=>self::$data['email']])
                    ->update(['locking'=>1,'time'=>\get::time(),'number'=>$nextNumber]);
            }else{
                $res2 = $conn::table('user_login_tmp_locking')
                    ->where(['email'=>self::$data['email']])
                    ->update(['time'=>\get::time(),'number'=>$nextNumber]);
            }
        }

        if ( 1 === $res && 1 === $res2){
            $conn::commit();
            return true;
        }
        message('登录失败');
    }

    private static function checkES(){
        if ( true === \session::get('login')){
            message('已经登录');
        }
        if (true === \session::get('loginVerify') ){
            $res = source\common\verify::is($_REQUEST['code']);
            if ( -1 === $res[0] ){
                message($res[1],$res[0],['verify'=>true,'src'=>source\common\verify::getSrc()]);
            }
        }
        // 检查是否临时锁定
        $all = pdo_fetch('user_login_tmp_locking',['email'=>self::$data['email']]);
        self::$data['tmp_locking'] = $all;
        if (!empty($all)){
            // 只有锁定了才判断
            if ( 1 === $all['locking']){
                $time = \get::timeDifference(true,$all['time']);
                // 30 分钟后自动解锁
                if ( 30 < $time[2]){
                    $res = \db::table('user_login_tmp_locking')->where([
                        'email'=>self::$data['email']
                    ])->update([
                        'locking'=>0,
                        'number'=>0
                    ]);
                    return $res;
                }else{
                    message('系统暂时锁定账户,请' .$time[2]. '分钟后再重试');
                }
            }


        }
    }

    private static function checkEmpty(){
        if (false === source\user\is::email(self::$data['email'])){
            message('账户或密码不正确'.self::$data['email']);
        }
    }

    private static function check(){
        if ( '' === self::$data['email']){
            message('请输入账户');
        }
        if ( '' === self::$data['password']){
            message('请输入密码');
        }
        if ( false === \check::email(self::$data['email']) || false === \check::password(self::$data['password'])){
            message('账户或密码不正确');
        }
    }
}
