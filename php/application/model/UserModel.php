<?php
namespace APP\Model;
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
class user {
    /**
     * 获取会员权限
     * @return array|int|mixed
     */
    protected static function _getPower($uid){
        $sql = 'SELECT g.roleID AS adminID,r.name,p.* FROM {prefix}user_group as g,{prefix}user_power as p,{prefix}user_role as r WHERE g.member_id=:uid AND g.roleID=r.id AND g.powerID=p.id ';
        $all = \db::query($sql,[':uid'=>$uid]);
        return \arrayOpera::remove_all($all,'id');
    }
    /**
     * 更新会员资料缓存
     * @param null|string $key
     * @param null|string $value
     * @return bool
     */
    protected static function updateCache( ? string $key , ? string $value){
        $uid = \session::get('uid');
        if (empty($uid)){
            return false;
        }
        $mem = \cacheMem::init();
        $md = md5($uid);
        $res = $mem::get($md);
        if (false === $res){
            $all = source\user\get::info($uid);
            $_REQUEST['mSid'] = base64_encode(\paw::authcode('sid='.$_REQUEST['sid'].'&token='.$_REQUEST['token'].'&time='.time(),'ENCODE',KEY));
            $p   = source\user\get::avatarUrl($all['avatar']);
            $all['avatar']  = SERVER_DOMAIN_NAME.'/'.$p;
            $all['di']      = 1 === $all['di'] ? true : false;
            $all['login']   = true;
            $all['sid']     = $_REQUEST['mSid'];
            $all[$key]      = $value;
            // 保存一个月
            $mem::add($md,$all,time()+3600*24*31);
        }else{
            $all = $res;
            // 删除缓存
            $mem::delete($md);
            $all[$key] = $value;
            $mem::add($md,$all,time()+3600*24*31);
        }
    }

}

