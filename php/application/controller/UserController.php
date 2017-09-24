<?php
namespace APP\Controller;
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
use source;
class user extends \APP\Model\user {
    /**
     * 获取权限
     * @return array|int|mixed
     */
    public static function getPower(){
        // 判断缓存是否存在
        $uid = \session::get('uid');
        if ( false === source\user\is::uid($uid)){
            return [];
        }
        $mem = \cacheMem::init();
        $name = md5('user_power_'.$uid);
        $res  = $mem::get($name);
        if (false === $res){
            // 获取权限
            $all = parent::_getPower($uid);
            $mem::add($name,$all);
            $res = $all;
        }
        return $res;
    }

    /**
     * 注册
     */
    public static function sign(){
        // 直接调用注册类
        source\user\sign::main();
    }

    /**
     * 登录
     */
    public static function login(){
        // 直接调用登录类
        source\user\login::main();
    }

    /**
     * 找回密码
     */
    public static function forgetPass(){
        $request    = \CZ::$app->request;
        $mod        = $request->get('mod');
        $email      = $request->get('email');
        $verify     = $request->get('verify');
        $password   = $request->get('password');
        $password2  = $request->get('password2');
        if ( '' === $email || '' === $mod){
            message('请输入邮箱');
        }
        // 验证邮箱格式
        if ( false === \check::email($email)){
            message('邮箱不正确');
        }
        // 判断邮箱是否已经注册
        if ( false === source\user\is::email($email)){
            message('邮箱未注册');
        }
        if ( 'getVerify' === $mod){
            if ( 5 < \session::get('forgetPassCodeNumber')){
                message('一天只能发5次验证码');
            }
            // 发送验证码到邮箱
            $newVerify = \get::rand(6,1);
            // 发送验证码
            $res = \email::send($email,'找回密码','找回密码验证码为:'.$newVerify);
            if (false === $res){
                message('请稍候再试...');
            }
            \session::set('forgetPassCode',$newVerify,300);
            \session::set('forgetPassCodeEmail',$email,300);
            $number = null === \session::get('forgetPassCodeNumber') ? 1 : \session::get('forgetPassCodeNumber')+1;
            \session::set('forgetPassCodeNumber',$number,86400);
            message('发送成功，请注意查收',200);
        }
        // 验证验证码
        if ( '' === $verify){
            message('请输入验证码');
        }
        if ( '' === $password || '' === $password2 ){
            message('请输入密码');
        }
        if ( false === \check::password($password)){
            message('密码最少为6位有效字符');
        }
        if ( $password !== $password2){
            message('两次密码不相同');
        }
        if ( 6 != strlen($verify) || !is_numeric($verify)){
            message('请输入6位有效数字');
        }
        if ( null === \session::get('forgetPassCodeNumber')){
            message('数据不完成，请重新获取验证码');
        }
        if ( $verify !== \session::get('forgetPassCode') || $email !== \session::get('forgetPassCodeEmail')){
            message('邮箱或验证码不正确');
        }
        $sql = 'SELECT a.pass,a.myKey,a.uid from {prefix}user as a , {prefix}user_info as b WHERE b.email=:email and b.member_id=a.uid ';
        $all = pdo_sql($sql,[':email'=>$email]);
        if (empty($all)){
            message('邮箱错误...');
        }
        if ( true === \paw::decode($all['pass'],$password,$all['myKey'])){
            message('没有更改密码');
        }
        $md  = \paw::encode($password,$all['myKey']);
        $res = \db::table('user')->where(['uid'=>$all['uid']])->update([
            'pass'=>$md
        ]);
        // 重置成功，注销session
        if (true == $res){
            \session::destroy();
            message('重置密码成功',200);
        }
        message('重置密码失败');
    }


    private static $data = [];
    public static function update(){
        $request                = \CZ::$app->request;
        self::$data['key']      = $request->get('mod');
        self::$data['value']    = $request->get('value');
        if ( empty(self::$data['key']) ){
            message('数据非法');
        }
        if (empty(self::$data['value'])){
            message('未更改内容');
        }
        if ('pass' === self::$data['key']){
            // 修改密码
            self::$data['newPass']    = $request->get('value2');
            self::$data['newPass2']   = $request->get('value3');
            self::updatePass();
        }
        // 获取配置
        $config = \get::config('user');
        self::$data['config'] = $config['modifyInfo'];
        // 上传头像
        if ( 'avatar' === self::$data['key']){
            $obj = $_FILES['avatar'];
            if ( 0 < $obj['error']){
                message(\get::uploadError($_FILES['avatar']['error']));
            }
            $arr = explode(',',self::$data['config']['avatar']['type']);
            $type = \get::fileHZ($obj['name']);
            if (!in_array($type,$arr)){
                message('不允许上传'.$type.'格式图片');
            }
            // 新的文件名
            $newFileName = md5(\get::rand(6)).'.'.$type;
            // 会员头像目录
            $dir = date('Ymd').'/'.\session::get('uid');
            $path = USER_AVATAR_DIR.$dir.'/';
            if (!is_dir($path)){
                mkdir($path,0777,true);
            }
            if (!@move_uploaded_file($obj['tmp_name'],$path.$newFileName) ){
                message('上传失败');
            }
            // 压缩图片
            $new_new_name =   md5(\get::rand(6)).'.'.$type;
            $res = \image::HDCompression($path.$newFileName,$path.$new_new_name,0.5);
            if (false === $res){
                message('上传失败');
            }
            // 删除HD头像
            @unlink($path.$newFileName);
            // 更新数据库
            self::$data['value'] = $dir.'/'.$new_new_name;
        }
        else{
            switch (self::$data['key']){
                case 'nick':
                    if ( false ===
                        \check::intBetween(self::$data['config']['nick'],\get::strLength(self::$data['value'])) ){
                        message('昵称长度范围在'.self::$data['config']['nick'].'字节');
                    }
                    break;
                case 'sex':
                    $tmpArr = explode('|',self::$data['config']['sex']);
                    if (!in_array(self::$data['value'],$tmpArr)){
                        message('性别只能设置为:'.self::$data['config']['sex']);
                    }
                    break;
                case 'age':
                    if ( false ===
                        \check::intBetween(self::$data['config']['age'],\get::strLength(self::$data['value'])) ){
                        message('年龄需要在'.self::$data['config']['nick'].'岁');
                    }
                    break;
                case 'autograph':
                    if ( false ===
                        \check::intBetween(self::$data['config']['qm'],\get::strLength(self::$data['value'])) ){
                        message('签名长度范围在'.self::$data['config']['nick'].'字节');
                    }
                    break;
            }
        }
        try {
            $all = \db::table('user_info')
                ->where(['member_id'=>\session::get('uid')])
                ->getOne();
            if ($all[self::$data['key']] === self::$data['value']){
                message('没有更改');
            }
            if ('avatar' === self::$data['key']){
                // 删除旧的头像
                if (!@unlink(USER_AVATAR_DIR.$all['avatar'])){
                    @unlink($dir.'/'.$newFileName);
                    message('上传失败');
                }
            }
            $res = \db::table('user_info')
                ->where(['member_id'=>\session::get('uid')])
                ->update([
                    self::$data['key']  =>  self::$data['value']
                ]);
            if (!$res){
                throw new \CZBlogException('更新失败');
            }
            if ('avatar' === self::$data['key']){
                $_REQUEST['mSid'] = base64_encode(\paw::authcode('sid='.$_REQUEST['sid'].'&token='.$_REQUEST['token'].'&time='.time(),'ENCODE',KEY));
                $p   = source\user\get::avatarUrl(self::$data['value']);
                $value  = SERVER_DOMAIN_NAME.'/'.$p.'?sid='.$_REQUEST['mSid'];
                parent::updateCache(self::$data['key'],self::$data['value']);
                message('更改成功',200,[
                    'key'   =>  self::$data['key'],
                    'value' =>  $value
                ]);
            }else{
                parent::updateCache(self::$data['key'],self::$data['value']);
                message('更改成功',200,[
                    'key'   =>  self::$data['key'],
                    'value' =>  self::$data['value']
                ]);
            }
        }catch (\CZBlogException $exception){
            message('更改失败');
        }


    }

    /**
     * 更改密码
     * @throws \CZBlogException
     */
    public static function updatePass(){
        // 修改密码
        if ( '' === self::$data['newPass'] || '' === self::$data['newPass2']){
            message('请输入新的密码');
        }
        if ( self::$data['newPass'] !== self::$data['newPass2']){
            message('两次密码不相同');
        }
        if ( false === \check::password(self::$data['newPass'])){
            message('密码格式错误');
        }
        // 判断密码是否正确
        $uid = \session::get('uid');
        $sql = 'SELECT u.pass,u.myKey,u.uid FROM {prefix}user as u WHERE u.uid=:uid  ';
        $res = pdo_sql($sql,[':uid'=>$uid]);
        if (empty($res)){
            throw new \CZBlogException('会员信息错误');
        }
        $PRes = \paw::decode($res['pass'],self::$data['value'],$res['myKey']);
        if ( false === $PRes){
            message('密码错误');
        }
        $md  = \paw::encode(self::$data['newPass'],$res['myKey']);
        $res = \db::table('user')->where(['uid'=>$uid])->update([
            'pass'=>$md
        ]);

        message('更改密码成功');
    }

}
