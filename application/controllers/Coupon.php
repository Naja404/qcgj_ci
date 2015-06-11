<?php
defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 优惠券管理
 */

class Coupon extends WebBase {
	//  视图输出内容
	public $outData;

	public function __construct(){
		parent::__construct();
		
		$this->load->model('CouponModel');
		$this->outData['currentModule'] = __CLASS__;
	}

	/**
	 * 优惠券列表
	 *
	 */
	public function couponList(){

		if ($this->input->method() == 'post') {
			echo '<pre>';
			print_r(currentTime('MICROTIME'));exit;
			$uploadConf = config_item('FILE_UPLOAD');
			// TODO 新建优惠券 加ajax文件上传
			$this->load->library('upload');
			$this->upload->initialize($uploadConf);
			if (!$this->upload->do_upload('image')) {
				echo $this->upload->display_errors();exit;
			}else{
				echo '<pre>';
				print_r($this->upload->data());exit;
			}
			
		}

		$this->load->view('Coupon/couponList');
	}

	/**
	 * 新建优惠券
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
		$shopList = $this->CouponModel->getShopList();
		
		$this->outData['shopList'] = $shopList['data']['list'];
		$this->outData['areaList'] = $shopList['data']['areaList'];
		$this->outData['bjAreaList'] = $shopList['data']['bjAreaList'];
		$this->outData['shAreaList'] = $shopList['data']['shAreaList'];
		$this->outData['gzAreaList'] = $shopList['data']['gzAreaList'];

		$this->load->view('Coupon/addCoupon', $this->outData);
	}

	/**
	 * ajax上传优惠券图片
	 *
	 */
	public function uploadCouponPic(){



		if ($this->input->method() == 'post') {
		$uploadConf = config_item('FILE_UPLOAD');

		$uploadConf['upload_path'] = './uploads/Coupon/';
	
		$this->load->library('upload');
		
		$this->upload->initialize($uploadConf);

		if (!$this->upload->do_upload('image')){
			$this->ajaxRes['msg'] = $this->upload->display_errors();
		}else{
			$this->ajaxRes = array(
					'status' => 0,
					'url'    => config_item('base_url').$this->upload->data('full_path'),
				);
		} 
		echo '<pre>';
		print_r($this->upload->data());exit;
		jsonReturn($this->ajaxRes);

		}
			$this->load->view('Coupon/couponList');

	}

	/**
	 * 验证优惠券添加
	 */
	private function _verlidationAddCoupon(){

		$this->form_validation->set_rules($this->lang->line('ADD_COUPON_VALIDATION'));

		if (!$this->form_validation->run()) {
			return validation_errors();
		}

		// 设置优惠券金额
		if ((int)$this->input->post('couponMoney') === 2) {
			if ((float)$this->input->post('couponMoneyNum') <= 0) {
				return $this->lang->line('ERR_COUPON_MONEY_NUM');
			}
		}

		// 验证有效期、领取期
		$couponDate = array(
				explode(' - ', $this->input->post('couponExpireDate')),
				explode(' - ', $this->input->post('couponReceiveDate')),
			);

		foreach ($couponDate as $k => $v) {
			if (strtotime($v[1]) <= strtotime($v[0])) {
				$langLine = $k == 0 ? 'ERR_COUPON_EXPIRE_DATE_FORMAT' : 'ERR_COUPON_RECEIVEDATE_FORMAT';
				return $this->lang->line($langLine);
			}
		}

		// 验证使用时间
		if (strtotime($this->input->post('couponUseTimeStart')) >= strtotime($this->input->post('couponUseTimeEnd'))) {
			return $this->lang->line('ERR_USETIME');
		}

		return true;
	}
}
