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
	);


?>
