<?php
		
$lang = array(
		'TEXT_COUPON_MANAGER'               => '优惠券管理',
		'TEXT_COUPON_LIST'                  => '优惠券列表',
		'TEXT_COUPON_VERIFY'                => '优惠券审核',
		'TEXT_COUPON_ADDCOUPON'             => '优惠券添加',
		'TEXT_STATELIST'                    => '使用状态',
		'TEXT_COUPON_NAME'                  => '优惠券名',
		'TEXT_COUPON_CODE'                  => '券码',
		'TEXT_COUPON_SHOPCOUNT'             => '参加门店数',
		'TEXT_COUPON_RECEIVECOUNT'          => '领取数',
		'TEXT_COUPON_USECOUNT'              => '使用数',
		'TEXT_COUPON_EXPIRE_DATE'           => '有效时间',
		'TEXT_COUPON_STATUS'                => '状态',
		'TEXT_COUPON_OPERATION'             => '操作',
		'TEXT_COUPON_STATUS_0'              => '上架',
		'TEXT_COUPON_STATUS_1'              => '上架/推荐',
		'TEXT_COUPON_STATUS_2'              => '上架/置顶',
		'TEXT_COUPON_TITLE_LISTVIEW'        => '优惠券列表',
		'TEXT_COUPON_TITLE_ANALYSIS'        => '优惠券报表',
		'TEXT_TITLE_ADDCOUPON'              => '优惠券添加',
		'TEXT_COUPON_FORM_STEP_1'           => '优惠券基本信息',
		'TEXT_COUPON_FORM_STEP_2'           => '选择门店',
		'TEXT_COUPON_FORM_STEP_3'           => '审核结果',
		'TEXT_COUPON_TITLE'                 => '优惠券标题',
		'TEXT_COUPON_TITLE_PLACEHOLDER'     => '最多允许输入40个文字',
		'TEXT_COUPON_TITLE_LENGTH'          => '还可输入%n个文字,',
		'TEXT_COUPON_TITLE_LENGTH_MAX'      => '允许最多输入40个文字.',
		'TEXT_COUPON_TYPE'                  => '优惠券类型',
		'TEXT_COUPON_MONEY'                 => '优惠券金额',
		'TEXT_COUPON_SUM'                   => '优惠券总数',
		'TEXT_COUPON_EVERYONE_SUM'          => '每人领取上限',
		'TEXT_COUPON_EXPIRE'                => '有效期',
		'TEXT_COUPON_RECEIVE'               => '领取期',
		'TEXT_COUPON_USE_TIME'              => '使用时间',
		'TEXT_COUPON_USE_GUIDE'             => '使用说明',
		'TEXT_COUPON_USE_GUIDE_PLACEHOLDER' => '使用回车符分割',
		'TEXT_COUPON_NOTICE'                => '温馨提示',
		'TEXT_COUPON_AUTO_CODE'             => '自动生成',
		'TEXT_COUPON_VERIFICATION'          => '验券说明',
		'TEXT_COUPON_CODE_TYPE'             => '优惠券码类型',
		'TEXT_COUPON_IMAGE'                 => '优惠券展示图',
		'TEXT_COUPON_VOUCHERS'              => '代金券',
		'TEXT_COUPON_DISCOUNT'              => '折扣券',
		'TEXT_COUPON_DELIVERY'              => '提货券',
		'TEXT_COUPON_FREE'                  => '免费',
		'TEXT_COUPON_TOLL'                  => '收费 - 金额',
		'TEXT_SHOP_NAME'                    => '门店名',
		'TEXT_MALL_NAME'                    => '商场名',
		'TEXT_ADDRESS'                      => '地址',
		'TEXT_AREA_NAME'                    => '商圈',
		'TEXT_CITY_NAME'                    => '城市',
		'TEXT_SELECT_CITY_NAME'             => '请选择城市',
		'TEXT_COUPON_CHOOSEN_FILE'          => '选择文件',
		'TEXT_ADDCOUPON_SUCCESS'            => '添加优惠券成功,请耐心等待审核',
		'TEXT_EDITCOUPON_SUCCESS'           => '更新优惠券成功,请耐心等待审核',
		'TEXT_REVIEW_AUTOPASS'              => '提交审核通过后,自动上架',
		'TEXT_REVIEW_MANUALPASS'            => '手动上架',
		'TEXT_REVIEW_PASSTIME'              => '设置上架时间',
		'TEXT_GO_COUPONLIST'                => '优惠券列表',
		'TEXT_CONTINUE_ADDCOUPON'           => '继续添加',
		'TEXT_USED'                         => '使用',
		'TEXT_RECEIVED'                     => '领取',
		'TEXT_STATUS_0'                     => '<span class="label">审核中</span>',
		'TEXT_STATUS_1'                     => '<span class="label label-info" >上架</span>',
		'TEXT_STATUS_2'                     => '<span class="label label-danger" >下架</span>',
		'TEXT_OPERATION_ICON_2'             => 'icon-ok bigger-120',
		'TEXT_OPERATION_ICON_1'             => 'icon-remove bigger-120',
		'TEXT_CONFIRM_DEL_COUPON'           => '是否确认删除该优惠券?',
		'TEXT_TITLE_EDITCOUPON'             => '编辑优惠券',
		
		'ERR_COUPON_MONEY_NUM'          => '请正确填写优惠券金额',
		'ERR_COUPON_EXPIRE_DATE_FORMAT' => '有效期开始日期不能大于结束日期',
		'ERR_COUPON_RECEIVEDATE_FORMAT' => '领取期开始日期不能大于结束日期',
		'ERR_COUPON_ADD_FAILURE'        => '优惠券新建失败, 请刷新后重试',
		'ERR_COUPON_EDIT_FAILURE'       => '优惠券编辑失败, 请刷新后重试',
		'ERR_USETIME'                   => '使用时间:开始时间不能大于结束时间',
		'ERR_REVIEWPASS_DATE'           => '上架时间不能小于当前时间或大于有效期',
		'ERR_AUTH_EDIT_COUPON'          => '您没有权限编辑该优惠券',
		'ERR_COUPON_EVERYONE_LIMIT'     => '每人领取上限不能大于优惠券总数',
		'ERR_AUTH_OPERTION'             => '您无权限进行此操作',
		'ERR_ONSALE_FAIL'				=> '上架失败,请刷新后重试',

		'ADD_COUPON_VALIDATION' => array(
				array(
					'field' => 'couponTitle',
					'label' => 'ERR_COUPON_TITLE',
					'rules' => 'required',
					),
				array(
					'field' => 'couponType',
					'label' => 'ERR_COUPON_TYPE',
					'rules' => 'required',
					),
				array(
					'field' => 'couponMoney',
					'label' => 'ERR_COUPON_MONEY',
					'rules' => 'required',
					),
				// array(
				// 	'field' => 'couponMoneyNum',
				// 	'label' => '请设置金额',
				// 	'rules' => 'required',
				// 	),
				array(
					'field' => 'couponExpireDate',
					'label' => 'ERR_COUPON_EXPIRE_DATE',
					'rules' => 'required',
					),
				array(
					'field' => 'couponReceiveDate',
					'label' => 'ERR_COUPON_RECEIVEDATE',
					'rules' => 'required',
					),
				array(
					'field' => 'couponUseTimeStart',
					'label' => 'ERR_COUPON_USETIME_START',
					'rules' => 'required',
					),
				array(
					'field' => 'couponUseTimeEnd',
					'label' => 'ERR_COUPON_USETIME_END',
					'rules' => 'required',
					),
				array(
					'field' => 'couponUseGuide',
					'label' => 'ERR_COUPON_USEGUIDE',
					'rules' => 'required',
					),
				array(
					'field' => 'couponVerification',
					'label' => 'ERR_COUPON_VERIFICATION',
					'rules' => 'required',
					),
			),
);
