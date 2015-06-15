<?php
/**
 * 自定义常量 配置内容
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
		// 用户缓存
		'USER_CACHE' => array(
				'LOGIN' => 'User:Login:',
				'MENU'  => 'User:Menu:',
				'RULE'  => 'User:Rule:',
				'SHOPLIST' => 'User:Shop:ShopList:',
				'DEFAULT_EXPIRETIME' => '3600',
		),
		// 分页设置
		'PAGINATION' => array(
				'COUNT' => 15,
			),
		// 文件上传
		'FILE_UPLOAD' => array(
				'upload_path'   => './uploads/',
				'allowed_types' => 'gif|jpg|png',
				'file_name'     => time(),
				'max_size'      => 1024*8,
				'max_width'     => 1024,
				'max_height'    => 768,
				'sub_dir'		=> true,
			),
		// 审核内容
		'COUPON_REVIEWPASS' => array(1, 2, 3), // 1.审核通过后自动上架 2.设置上架时间 3.手动上架
		// 店长Roleid
		'SHOPMANAGER_ROLEID' => 3,
	);


?>
