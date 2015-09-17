<?php
/**
 * 自定义常量 配置内容
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
		// 普通缓存
		'NORMAL_CACHE' => array(
				'BRAND_LIST'          => 'Normal:Brand:List',
				'MALLLIST_BY_BRAND'   => 'Normal:Mall:Brand:',
				'CITY_LIST'           => 'Normal:cityList',
				'AREA_LIST'           => 'Normal:areaList:',
				'SEARCH_BRAND_LIST'   => 'Normal:Search:brand:',
				'SEARCH_ADDRESS_LIST' => 'Normal:Search:address:',
				'BRAND_STYLE'         => 'Normal:Brand:Style',
				'BRAND_AGE'           => 'Normal:Brand:Age',
				'BRAND_PRICE'         => 'Normal:Brand:Price',
				'BRAND_DISTRICT'      => 'Normal:Brand:District:',
				'CITY_NAME'			  => 'Normal:Brand:cityName',
				'BRAND_SHOP'		  => 'Normal:BrandShop:',
				'PROVINCE_LIST'		  => 'Normal:ProvinceList',
			),
		// 用户缓存
		'USER_CACHE' => array(
				'LOGIN'              => 'User:Login:',
				'MENU'               => 'User:Menu:',
				'RULE'               => 'User:Rule:',
				'SHOPLIST'           => 'User:Shop:ShopList:',
				'COUPON_ID_LIST'     => 'User:Coupon:CouponId:',
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
				// 'max_width'     => 1024,
				// 'max_height'    => 768,
				'sub_dir'		=> true,
			),
		// 审核内容
		'COUPON_REVIEWPASS' => array(1, 2, 3), // 1.审核通过后自动上架 2.设置上架时间 3.手动上架
		// 店长Roleid
		'SHOPMANAGER_ROLEID' => 3,

		// 管理员RoleId
		'AdminConf' => array(
				'RoleId' => array(1),
			),

		//折扣类型（1满减，2满赠，3折扣，4满量减，6买就赠，7新品，8特价）
		'DISCOUNT_TYPE' => array(
						array(
							'key'   => 1,
							'value' => '满减',
							),
						array(
							'key'   => 2,
							'value' => '满赠',
							),
						array(
							'key'   => 3,
							'value' => '折扣',
							),
						array(
							'key'   => 4,
							'value' => '满量减',
							),
						array(
							'key'   => 6,
							'value' => '满减',
							),
						array(
							'key'   => 7,
							'value' => '新品',
							),
						array(
							'key'   => 8,
							'value' => '特价',
							),
			),

		// 优惠券 状态列表
		'COUPON_STATUS' => array(
				array(
					'id' => '0',
					'name' => '审核中',
					),
				array(
					'id' => '1',
					'name' => '上架',
					),
				array(
					'id' => '2',
					'name' => '下架',
					),	
			),
		// 优惠券 类型列表 
		'COUPON_TYPE' => array(
				'1'   => '代金券',
				'2'   => '普通券单张',
				'4'   => '展示券',
				'5'   => '链接券',
				'6'   => '普通券多张',
				'999' => '临时活动',
			),
		// 审核目标
		'APPROVE_COMMENT_TYPE' => array(
				'1' => '商场',
				'2' => '店铺',
			),
		// 审核状态
		'APPROVE_STATUS' => array(
				'0' => '待审核',
				'1' => '已通过',
				'2' => '不通过',
			),

		// 评论审核状态
		'APPROVE_TD_DIV_1' => '<span class="label label-info">已通过</span>',
		'APPROVE_TD_DIV_2' => '<span class="label label-danger">不通过</span>',
		'APPROVE_A_DIV_2' => '<a onclick="%s(\'%s\', \'%s\');"><i class="icon-remove bigger-120 green">不通过</i></a>',
		'APPROVE_A_DIV_1' => '<a onclick="%s(\'%s\', \'%s\');"><i class="icon-remove bigger-120">通过</i></a>',

		// 评论审核状态
		'HIDE_TD_DIV_1' => '<span class="label label-info">显示</span>',
		'HIDE_TD_DIV_0' => '<span class="label label-danger">隐藏</span>',
		'HIDE_A_DIV_0' => '<a onclick="%s(\'%s\', \'%s\');"><i class="icon-remove bigger-120 green">隐藏</i></a>',
		'HIDE_A_DIV_1' => '<a onclick="%s(\'%s\', \'%s\');"><i class="icon-remove bigger-120">显示</i></a>',

 	);


?>
