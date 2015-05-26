<?php
defined('BASEPATH') OR xit('No direct script access allowed');

class Coupon extends WebBase {
	//  视图输出内容
	public $outData;

	public function __construct(){
		parent::__construct();
		
		$this->load->model('RoleModel');
		$this->outData['currentModule'] = __CLASS__;
	}

	/**
	 * 新建优惠券
	 *
	 */
	public function addCoupon(){

		if ($this->input->is_ajax_request()) {

			$test = $this->form_validation->set_rules($this->lang->line('ADD_COUPON_VALIDATION'));

			if (!$this->form_validation->run()) {
				print_r(validation_errors());exit('===');
			}

			jsonReturn($this->ajaxRes);
		}

		$this->outData['pageTitle'] = $this->lang->line('TEXT_TITLE_ADDCOUPON');
		$this->load->view('Coupon/addCoupon', $this->outData);
	}

	public function rolelist(){
		$this->outData['pageTitle'] = $this->lang->line('TEXT_TITLE_ROLELIST');
		$this->outData['roleList'] = $this->RoleModel->getRoleList();
		$this->outData['pagination'] = $this->RoleModel->setPagination();
		$this->load->view('Role/rolelist', $this->outData);
	}

	public function index(){

	}
}
