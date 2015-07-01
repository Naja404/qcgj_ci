<?php

$lang = array(
	'TEXT_ROLE_ADD'                      => '角色添加',
	'TEXT_ROLE_RULE_ADD'                 => '权限添加',
	'TEXT_ROLE_RULE_USER_ADD'			 => '用户添加',
	'TEXT_ROLE_NAME'                     => '角色名',
	'TEXT_ROLE_RULE'                     => '权限',
	'TEXT_RULE_MODULE'                   => '模块',
	'TEXT_RULE_MODULE_TITLE'             => '主菜单标题',
	'TEXT_RULE_ACTION'                   => '操作',
	'TEXT_RULE_ACTION_TITLE'             => '操作标题',
	'TEXT_RULE_ACTION_URL'               => '操作URL',
	'TEXT_RULE_SORT'                     => '排序',
	'TEXT_RULE_TYPE'                     => '类型',
	'TEXT_RULE_TYPE_1'                   => 'URL',
	'TEXT_RULE_TYPE_2'                   => 'Ajax',
	'TEXT_RULE_TYPE_NOTE'                => 'URL:作为菜单显示,Ajax:不显示为菜单',
	'TEXT_ROLE_MANAGER'                  => '角色管理',
	'TEXT_ROLE_LIST'                     => '角色列表',
	'TEXT_TITLE_ROLELIST'                => '角色列表',
	'TEXT_ROLE_USERNAME'                 => '用户名',
	'TEXT_ROLE_CONFIRM_PASSWD'			 => '确认密码',
	'TEXT_ROLE_PASSWD'					 => '密码',
	'TEXT_PLASE_SELECT_MALL'			 => '请选择店铺',
	'TEXT_BRAND'						 => '品牌',
	'TEXT_SHOP'							 => '店铺',
	
	'TEXT_PLACEHOLDER_RULE_MODULE'       => '如:Coupon',
	'TEXT_PLACEHOLDER_RULE_MODULE_TITLE' => '如:优惠券管理',
	'TEXT_PLACEHOLDER_RULE_ACTION'       => '如:Web/Coupon/listview',
	'TEXT_PLACEHOLDER_RULE_ACTION_TITLE' => '如:优惠券列表',
	'TEXT_PLACEHOLDER_RULE_ACTION_URL'   => '如:Web/Coupon/listview',
	'TEXT_PLACEHOLDER_RULE_SORT'         => '数字越大排序越高',
	'TEXT_UPDATE_USER_STATUS_0'			 => '<span class="label label-success">停用</span>',
	'TEXT_UPDATE_USER_STATUS_1'			 => '<span class="label label-error">正常</span>',
	'TEXT_UPDATE_USERSTATUS_CLASS_0'	 => 'icon-ok bigger-120',
	'TEXT_UPDATE_USERSTATUS_CLASS_1'	 => 'icon-remove bigger-120',

	'ERR_ADD_ROLENAME_EXISTS'            => '角色名已存在',
	'ERR_ADDROLE_USERNAME_EXISTS'		 => '用户名已存在',
	'ERR_ROLE_NO_BRAND'					 => '品牌错误,请刷新后重试',
	'ERR_ROLE_NO_MALL'					 => '该品牌下店铺不存在,请刷新后重试',

	'ADD_RULE_VALIDATION' => array(
				array(
					'field' => 'module',
					'label' => 'ERR_ROLE_MODULE',
					'rules' => 'required',
					),
				array(
					'field' => 'module_title',
					'label' => 'ERR_ROLE_MODULETITLE',
					'rules' => 'required',
					),
				array(
					'field' => 'action_title',
					'label' => 'ERR_ROLE_ACTIONTITLE',
					'rules' => 'required',
					),
				array(
					'field' => 'action_url',
					'label' => 'ERR_ROLE_ACTIONURL',
					'rules' => 'required',
					),
				array(
					'field' => 'sort',
					'label' => 'ERR_ROLE_SORT',
					'rules' => 'required|integer',
					),
				array(
					'field'     => 'type',
					'label'     => 'ERR_ROLE_TYPE',
					'rules'     => 'required',
					),
		),

	'ADD_ROLE_VALIDATION' => array(
				array(
					'field' => 'role_name',
					'label' => 'ERR_ROLE_NAME',
					'rules' => 'required',
					),
		),

	'ADD_ROLE_USER_VALIDATION' => array(
				array(
					'field' => 'role_username',
					'label' => 'ERR_ROLE_USERNAME',
					'rules' => 'required',
					),
				array(
					'field' => 'passwd',
					'label' => 'ERR_ROLE_PASSWD',
					'rules' => 'required',
					),
				array(
					'field' => 'confirm_passwd',
					'label' => 'ERR_ROLE_CONFIRM_PASSWD',
					'rules' => 'required|matches[passwd]',
					),
		),
);
