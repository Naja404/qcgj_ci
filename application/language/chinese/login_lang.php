<?php

$lang = array(
		// BTN
		'BTN_LOGIN'            => '登录',

		// INPUT
		'INPUT_USERNAME'       => '用户名',
		'INPUT_PASSWORD'       => '密码',
		
		// TEXT
		'TEXT_REMEMBER_ME'     => '记住我',
		'TEXT_FORGOT_PWD'      => '忘记密码?',
		'TEXT_REGISTER'        => '注册',
		'TEXT_TITLE_USERLOGIN' => '用户登录',
		
		// ERROR
		'ERR_LOGIN_PASSWD'     => '用户名或密码错误',
		'ERR_LOGIN_STATUS_0'   => '您的帐号已停用',

		// 登录表单验证配置信息
		'LOGIN_VALIDATION' => array(
					array(
						'field' => 'username',
						'label' => 'ERR_LOGIN_USERNAME_EMPTY',
						'rules' => 'required',
						),
					array(
						'field' => 'passwd',
						'label' => 'ERR_LOGIN_PASSWD_EMPTY',
						'rules' => 'required',
						),
				),
	);
