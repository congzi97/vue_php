<?php
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
 *  | 时间:  2017/8/20
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 配置文件
 *  ----------------------------------------
 */

return  [
    'verify'=>[
        // 验证码长度
        'length' => 4,
        // 验证码有效期   分钟
        'expired' => 2 ,
        // 验证码图片大小设置
        'size' => ['height' => 40 , 'width' => 130],
        // 默认 default GIF验证码
        'picType' => 'gif',
        // 验证码类型 1.字母+数字 2.字母 3.数字 4.加法 5.减法 (加法、减法char必须是数字int)
        'type' => 1,
    ],
    'email'=>[
        // SMTP 服务器地址
        'smtpHost' => 'smtp.163.com',
        // SMTP服务器用户名
        'userName' => '',
        // 邮箱密码或授权码
        'password' => '',
        // 发送人名称
        'fromName' => 'CZPhp',
        // 发送人的邮箱账号
        'from'  => 'notexpired@163.com',
    ]
];
