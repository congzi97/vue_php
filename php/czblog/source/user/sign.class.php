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
 *  | 时间:  2017/8/26
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */

class sign {

    private static $data = [];
    public static function main(){
        $request = \CZ::$app->request;
        self::$data['email']        = $request->get('email');
        self::$data['password']     = $request->get('password');
        self::$data['password2']    = $request->get('password2');
        if ( true === \session::get('login')){
            message('已经登录');
        }
        $mod  = $_REQUEST['mod'];
        $emailCode = $_REQUEST['emailCode'];
        // 发送验证码
        if ( 'getCode' === $mod){
            self::sendCode();
        }elseif ( 'sign' === $mod){
            self::check($emailCode);
            self::sql();
        }else{
            message('Access TOKEN');
        }
    }
    private static function sendCode(){
        if ( '' === self::$data['email']){
            message('请输入邮箱');
        }
        if (false === \check::email(self::$data['email'])){
            message('邮箱不正确');
        }
        // 是否已经存在
        if ( 0 < pdo_row('user_info',['email'=>self::$data['email']])){
            message('邮箱已经注册');
        }
        $all = \db::table('user_sign_email_code')
            ->where(['email'=>self::$data['email']])
            ->getOne();
        // 发送验证码
        $config = config('user');
        $emailConfig = $config['sign']['getCode'];
        $code = \get::rand(6,1);
        $title = $emailConfig['title'];
        $content = str_replace('{$code}',$code,$emailConfig['content']);
        $res = \email::send(self::$data['email'],$title,$content);
        if (empty($all)){
            $res2 = \db::table('user_sign_email_code')->insert([
                'email'=>self::$data['email'],
                'code'=>$code,
                'time'=>date('Y-m-d H:i:s',time())
            ]);
        }else{
            $res2 = \db::table('user_sign_email_code')
                ->where([
                    'email'=>self::$data['email']
                ])
                ->update([
                'code'=>$code,
                'time'=>date('Y-m-d H:i:s',time())
            ]);
        }
        message('获取验证码成功',200);
    }
    private static function check($emailCode){
        if ( '' === $emailCode){
            message('请输入验证码');
        }
        if ( '' === self::$data['email']){
            message('请输入邮箱');
        }
        if ( '' === self::$data['password'] || '' === self::$data['password2']){
            message('请输入密码');
        }
        if ( self::$data['password'] !== self::$data['password2']){
            message('两次密码不相同');
        }
        if (false === \check::email(self::$data['email'])){
            message('邮箱不正确');
        }
        if (false === \check::password(self::$data['password'])){
            message('密码最少为8位字母和数字(不能包含特殊字符)');
        }
        // 是否已经存在
        if ( 0 < pdo_row('user_info',['email'=>self::$data['email']])){
            message('邮箱已经注册');
        }
        // 判断是否已经输入验证码
        $all = \db::table('user_sign_email_code')
            ->where(['email'=>self::$data['email']])
            ->getOne();
        if (empty($all)){
            message('请先获取验证码');
        }
        $timeArr = \get::timeDifference(true,$all['time']);
        if ( 5 < $timeArr[2] ){
            message('验证码过期/无效，请重新获取');
        }
        if ( $emailCode !== $all['code']){
            message('验证码错误');
        }
        return true;
    }
    private static function sql( $manual = false){
        $conn = \db::begin();
        $key  = md5(\get::rand(16).'/'.time());
        $pass = \paw::encode(self::$data['password'],$key);
        $uid  = $conn::table('user')->insert([
            'pass'      =>  $pass,
            'myKey'     =>  $key,
            'signTime'  =>  \get::time(),
        ],true);
        if ( 0 >= $uid){
            $conn::rollback();
            message('注册失败');
        }
        $res2  = $conn::table('user_info')->insert([
            'member_id'     =>  $uid,
            'email'         =>  self::$data['email'],
            'bi'            =>  0,
            'experience'    =>  0,
            'jifen'         =>  0,
            'nick'          =>  \get::rand(6).'_'.$uid,
            'sex'           =>  '男',
            'birthday'      =>  '1900-01-01',
            'age'           =>  1,
            'city'          =>  '广东深圳',
            'autograph'     =>  '这家伙很懒，什么也没有留下来...',
            'di'            =>  0,
            'avatar'        =>  'avatar.jpg',
        ]);
        if ( true === $manual){
            $res3 = true;
        }else{
            $res3 = \db::table('user_sign_email_code')
                ->where(['email'=>self::$data['email']])
                ->del();
        }
        if ( 1 === $res2 && 1 === $res3 ){
            $conn::commit();
            message('注册成功',200);
        }
        $conn::rollback();
        message('注册失败');
    }

    public static function ManualAdd($email , $password){
        self::$data['password'] = $password;
        self::$data['email'] = $email;
        self::sql(true);
    }

}
