<?php
defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 折扣/优惠券管理
 */

class Coupon extends WebBase {
	//  视图输出内容
	public $outData;

	public function __construct(){
		parent::__construct();

		$this->load->model('CouponModel');
		$this->load->model('HongQiaoModel');
		$this->load->library('Snoopy');
		$this->outData['currentModule'] = __CLASS__;
	}

	/**
	 * 添加优惠券
	 *
	 */
	public function addCoupon(){
		if ($this->input->is_ajax_request()) {

			$verlidationRes = $this->_verlidationAddCoupon();

			if ($verlidationRes !== true) {
				$this->ajaxRes['msg'] = $verlidationRes;
				jsonReturn($this->ajaxRes);
			}

			$addCouponRes = $this->CouponModel->addCoupon($this->input->post());

			if ($addCouponRes['error']) {
				$this->ajaxRes['msg'] = $addCouponRes['msg'];
			}else{
				$this->ajaxRes = array(
						'status' => 0,
					);
			}

			jsonReturn($this->ajaxRes);
		}

		$this->outData['pageTitle'] = $this->lang->line('TEXT_TITLE_ADDCOUPON');

		if ($this->CouponModel->isAdminUser($this->userInfo->role_id)) {

			$this->load->view('Coupon/adminAddCoupon', $this->outData);
		}else{
			$shopList                    = $this->CouponModel->getShopList();
			$this->outData['shopList']   = $shopList['data']['list'];
			$this->outData['areaList']   = $shopList['data']['areaList'];
			$this->outData['cityList']   = $shopList['data']['cityList'];
			$this->outData['bjAreaList'] = $shopList['data']['bjAreaList'];
			$this->outData['shAreaList'] = $shopList['data']['shAreaList'];
			$this->outData['gzAreaList'] = $shopList['data']['gzAreaList'];
			$this->load->view('Coupon/addCoupon', $this->outData);
		}
		
	}

	/**
	 * 搜索品牌
	 *
	 */
	public function searchBrand(){
		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$brandName = $this->input->post('brand');
		
		if (empty($brandName)) jsonReturn($this->ajaxRes); 

		$list = $this->HongQiaoModel->searchBrand($brandName);

		$this->ajaxRes = array(
				'status' => 0,
				'list' => $list,
			);

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 优惠券报表
	 *
	 */
	public function analysis(){

		$list = $this->db->get(tname('brand_mall'))->result();

		foreach ($list as $k => $v) {
			echo $v->pic_url;exit;
		}

		// $this->outData['pageTitle'] = $this->lang->line('TEXT_COUPON_TITLE_ANALYSIS');

		// $this->load->view('Coupon/analysis', $this->outData);
	}


}
