<?php

$lang = array(
		'TITLE_DISCOUNT_LIST'              => '折扣列表',
		'TITLE_DISCOUNT_MANAGER'           => '折扣管理',
		'TITLE_DISCOUNT_ADD'               => '添加折扣',
		'TITLE_BRAND_SELECT'               => '品牌选择',
		'TITLE_DISCOUNT_EDIT'			   => '编辑折扣',
		
		'TEXT_CONFIRM_DEL_DISCOUNT'        => '是否确认删除该条折扣?',
		'TEXT_DISCOUNT_DELETE_FAIL'        => '折扣删除失败,请刷新重试',
		'TEXT_DISCOUNT_TITLE'              => '折扣标题',
		'TEXT_DISCOUNT_CATEGORY'           => '所属分类',
		'TEXT_DISCOUNT_TYPE'               => '折扣类型',
		'TEXT_DISCOUNT_DATE'               => '有效期',
		'TEXT_DISCOUNT_DESCRIPTION'        => '折扣描述',
		'TEXT_DISCOUNT_IMAGE'              => '折扣图片',
		'TEXT_DISCOUNT_TYPE_1'			   => '满减',
		'TEXT_DISCOUNT_TYPE_2'			   => '满赠',
		'TEXT_DISCOUNT_TYPE_3'			   => '折扣',
		'TEXT_DISCOUNT_TYPE_4'			   => '满量减',
		'TEXT_DISCOUNT_TYPE_6'			   => '买就赠',
		'TEXT_DISCOUNT_TYPE_7'			   => '新品',
		'TEXT_DISCOUNT_TYPE_8'			   => '特价',
		
		'PLACEHOLDER_DISCOUNT_TITLE'       => '请填写折扣标题',
		'PLACEHOLDER_DISCOUNT_DATE'        => '请选择有效期',
		'PLACEHOLDER_DISCOUNT_DESCRIPTION' => '请填写折扣描述',
		
		'EMPTY_DISCOUNT_TITLE'             => '请正确填写折扣标题',
		'EMPTY_DISCOUNT_DATE'              => '请正确选择折扣有效期',
		'EMPTY_DISCOUNT_DESCRIPTION'       => '请正确填写折扣描述',
		'ERR_DISCOUNT_ADD_FAILURE'		   => '折扣添加失败,请刷新后重试',
		'ERR_DISCOUNT_UPDATE_FAILURE'	   => '折扣更新失败,请刷新后重试',
		'TEXT_DISCOUNT_ADD_SUCCESS'		   => '折扣添加成功',
		'TEXT_CONTINUE_DISCOUNT_ADD'	   => '继续添加折扣',

		'ADD_DISCOUNT_VALIDATION' => array(
				array(
					'field' => 'discountTitle',
					'label' => 'ERR_DISCOUNT_TITLE',
					'rules' => 'required',
					),
				array(
					'field' => 'discountDate',
					'label' => 'ERR_DISCOUNT_DATE',
					'rules' => 'required',
					),
				array(
					'field' => 'discountDescription',
					'label' => 'ERR_DISCOUNT_DESCRIPTION',
					'rules' => 'required',
					),
				array(
					'field' => 'discountImg',
					'label' => 'ERR_DISCOUNT_IMAGE',
					'rules' => 'required',
					),
			),


	);
