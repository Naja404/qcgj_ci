<?php
/**
 * 门店管理语言包
 */
$lang = array(
	'TEXT_SHOP_MANAGER'          => '门店管理',
	'TEXT_SHOP_LIST'             => '门店列表',
	'TEXT_TITLE_SHOPLIST'        => '门店列表',
	'TEXT_TITLE_MANAGERLIST'     => '店长列表',
	'TEXT_TITLE_ADD_SHOPMANAGER' => '添加店长',
	'TEXT_MANGER_NAME'           => '店长名',
	'TEXT_ENTER_MANAGER_NAME'    => '请输入邮箱地址',
	'TEXT_PASSWORD'              => '登陆密码',
	'TEXT_ENTER_PASSWORD'        => '请输入密码',
	'TEXT_CONFIRM_PASSWORD'      => '确认密码',
	'TEXT_ENTER_PASSWORD_AGAIN'  => '请再次输入密码',
	'TEXT_MANAGER_NAME'          => '店长',
	'TEXT_BRAND_NAME'            => '品牌名',
	'TEXT_CATEGORY_NAME'         => '分类',
	'TEXT_MALL_NAME'             => '商场名',
	'TEXT_ADDRESS'               => '地址',
	'TEXT_AREA_NAME'             => '商圈',
	'TEXT_CITY_NAME'             => '城市',
	'TEXT_FLOOR'                 => '楼层',
	'TEXT_SHOPLIST_TOTAL'        => '共 %s 条',
	'TEXT_ADDMANAGER_SUCCESS'    => '添加店长成功',
	'TEXT_GO_MANAGERLIST'        =>'店长列表',
	'TEXT_CONTINUE_ADDMANAGER'   => '继续添加',


	'ERR_ENTER_MANAGER_NAME'     => '请输入正确的店长名',
	'ERR_MANAGER_NAME_LENGTH'    => '店长名长度为2-6个字符',
	'ERR_ENTER_PASSWD'           => '请输入正确的登陆密码',
	'ERR_PASSWD_LENGTH'          => '登陆密码长度不能低于6个字符',
	'ERR_ENTER_CONFIRM_PASSWD'   => '请输入正确的确认密码',
	'ERR_CONFIRM_PASSWD_LENGTH'  => '确认密码长度不能低于6个字符',
	'ERR_CONFIRM_PASSWD_NOTSAME' => '确认密码与登陆密码不一致',
	'ERR_MANAGER_NAME_TRIM' 	 => '店长名不能包含空格及特殊符号',
	'ERR_MANAGER_NAME_FORMAT'    => '请输入正确格式的店长名',
	'ERR_MALLID'				 => '请正确选择地址',
	'ERR_MANAGERNAME_EXISTS'	 => '店长名已存在',
	'ERR_ADD_MANAGER_FAIL'	     => '添加店长失败,请刷新后重试',

	'ADD_SHOPMANAGER_VALIDATION' => array(
				array(
					'field' => 'managerName',
					'label' => 'ERR_MANAGER_NAME_FORMAT',
					'rules' => 'required|valid_email',
					),
				array(
					'field' => 'passwd',
					'label' => 'ERR_ENTER_PASSWD',
					'rules' => 'required',
					),
				array(
					'field' => 'confirmPasswd',
					'label' => 'ERR_ENTER_CONFIRM_PASSWD',
					'rules' => 'required',
					),
				array(
					'field' => 'mallID',
					'label' => 'ERR_MALLID',
					'rules' => 'required',
					),
		),
);
