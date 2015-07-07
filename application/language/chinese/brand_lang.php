<?php

$lang = array(
	
	'TITLE_BRAND_MANAGER'         => '品牌管理',
	'TITLE_BRAND_LIST'            => '品牌列表',
	'TITLE_SHOP_LIST'			  => '门店列表',
	'TITLE_ADD_BRAND'             => '添加品牌',
	'TITLE_ADD_SHOP'              => '添加店铺',
	
	'TEXT_NAME_EN'                => '品牌英文名',
	'TEXT_NAME_ZH'                => '品牌中文名',
	'TEXT_CREATED_TIME'           => '创建时间',
	'TEXT_UPDATED_TIME'           => '更新时间',
	'TEXT_OPERATION_USER'         => '操作人',
	'TEXT_OPERATION'              => '操作',
	'TEXT_DESCRIPTION'            => '描述',
	'TEXT_CATEGORY'               => '分类',
	'TEXT_LOGO'                   => 'LOGO图片',
	'TEXT_LOGO_SHOW'              => '品牌宣传图',
	'TEXT_CONFIRM_DELBRAND'       => '是否确认删除该品牌?',
	'TEXT_CONFIRM_DELSHOP'		  => '是否确认删除商场/门店',
	'TEXT_SHOPMALL'               => '商场/店铺',
	'TEXT_STYLE'                  => '风格',
	'TEXT_AGE'                    => '年龄',
	'TEXT_PRICE'                  => '价格区间',
	'TEXT_SHOP_NAMEZH'            => '门店中文名',
	'TEXT_SHOP_NAMEEN'            => '门店英文名',
	'TEXT_SHOP_NAME_PY'           => '门店名拼音',
	'TEXT_SHOP_NAME_SHORT'        => '门店名缩写',
	'TEXT_SHOP_IMG'               => '门店图片',
	'TEXT_SHOP_TYPE'              => '门店类型',
	'TEXT_SHOP_TYPE_MALL'         => '商场',
	'TEXT_SHOP_TYPE_STREET'       => '街边店',
	'TEXT_SHOP_DISTRICT'          => '所属区',
	'TEXT_SHOP_OPENTIME'          => '开始营业时间',
	'TEXT_SHOP_CLOSETIME'         => '结束营业时间',
	'TEXT_SHOP_TEL'               => '商场/店铺电话',
	'TEXT_SHOP_LNG'               => '经度',
	'TEXT_SHOP_LAT'               => '纬度',
	'TEXT_SHOP_ADDRESS'           => '商场/门店地址',
	'TEXT_SHOP_MALL'              => '所属商场',
	
	'PLACEHOLDER_NAME_ZH'         => '请输入品牌中文名',
	'PLACEHOLDER_NAME_EN'         => '请输入品牌英文名',
	'PLACEHOLDER_SEARCH_MALL'     => '商场/店铺名称',
	'PLACEHOLDER_FLOOR'           => '请输入楼层',
	'PLACEHOLDER_SHOP_NAMEZH'     => '请输入门店中文名',
	'PLACEHOLDER_SHOP_NAMEEN'     => '请输入门店英文名',
	'PLACEHOLDER_SHOP_NAME_PY'    => '请输入门店名拼音',
	'PLACEHOLDER_SHOP_NAME_SHORT' => '请输入门店名缩写',
	'PLACEHOLDER_SHOP_ADDRESS'    => '如:上海市延安西路1号',
	'PLACEHOLDER_SHOP_TEL'        => '如:021-65631234*123',
	
	'ERR_NAME_ZH'                 => '品牌中文名不能为空',
	'ERR_CHECKBOX_MIN'            => '至少选择一项',
	'ERR_CATEGORY'                => '请选择品牌分类',
	'ERR_MALL'                    => '请选择商场/店铺',
	'ERR_EXISTS_BRAND_NAME'       => '品牌名已存在',
	'ERR_NO_ADDRESS_LNGLAT'       => '查询不到该地址经纬度,请重新输入关键字',
	'ERR_EXISTS_SHOP_NAME'        => '门店中文名或英文名已存在',
	
	'ERR_SHOP_NAMEZH'             => '请正确填写门店中文名',
	'ERR_SHOP_LNG'                => '请正确填写门店经度',
	'ERR_SHOP_LAT'                => '请正确填写门店纬度',
	'ERR_SHOP_ADDRESS'            => '请正确填写门店地址',


	'ADD_BRAND_VALIDATION' => array(
				array(
					'field' => 'nameZh',
					'label' => 'ERR_NAME_ZH',
					'rules' => 'required',
					),
		),

	'ADD_SHOP_VALIDATION' => array(
				array(
					'field' => 'shopNameZH',
					'label' => 'ERR_SHOP_NAMEZH',
					'rules' => 'required',
					),
				array(
					'field' => 'shopCity',
					'label' => 'ERR_SHOP_CITY',
					'rules' => 'required',
					),
				array(
					'field' => 'shopAddress',
					'label' => 'ERR_SHOP_ADDRESS',
					'rules' => 'required',
					),
				array(
					'field' => 'shopDistrict',
					'label' => 'ERR_SHOP_DISTRICT',
					'rules' => 'required',
					),
				array(
					'field' => 'shopLng',
					'label' => 'ERR_SHOP_LNG',
					'rules' => 'required',
					),
				array(
					'field' => 'shopLat',
					'label' => 'ERR_SHOP_LAT',
					'rules' => 'required',
					),
		),

);
