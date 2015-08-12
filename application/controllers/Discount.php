<?php
defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 折扣模块
 */

class Discount extends WebBase {

	public $outData;

	public function __construct(){
		
		parent::__construct();

		$this->load->model('DiscountModel');
		$this->load->model('CouponModel');
		$this->lang->load('discount');
	}

	/**
	 * 折扣列表
	 *
	 */
	public function disList(){
		$this->outData['pageTitle'] = $this->lang->line('TITLE_DISCOUNT_LIST');

		$where = $this->_getDisListWhere();

		$disList = $this->DiscountModel->getDiscountList($this->p, $where);

		$this->outData['list'] = $disList['list'];

		$this->outData['page'] = $disList['page'];

		$this->outData['brandCate'] = $this->DiscountModel->getBrandCate();

		$this->outData['disType'] = config_item('DISCOUNT_TYPE');

		$this->load->view('Discount/disList', $this->outData);
	}

	/**
	 * 添加折扣信息
	 *
	 */
	public function addDis(){

		if ($this->input->is_ajax_request()) {
			
			$reqData = $this->input->post();

			$verlidationRes = $this->DiscountModel->verlidationAddDis($reqData);

			if ($verlidationRes !== true) {
				$this->ajaxRes['msg'] = $verlidationRes;
				jsonReturn($this->ajaxRes);
			}

			$addDisRes = $this->DiscountModel->addDis($reqData);

			if ($addDisRes['error']) {
				$this->ajaxRes['msg'] = $addDisRes['msg'];
			}else{
				$this->ajaxRes = array(
						'status' => 0,
					);
			}

			jsonReturn($this->ajaxRes);
		}

		$this->outData['pageTitle'] = $this->lang->line('TITLE_DISCOUNT_ADD');

		$brandId = $this->input->get('brandId');

		if ($this->DiscountModel->isAdminUser($this->userInfo->role_id)) {
			if (!$this->CouponModel->hasBrandById($brandId)) {
				redirect(base_url('Discount/brandSelect'));
				exit();
			}
			$this->outData['brandName']  = $this->DiscountModel->getBrandNameById($brandId, 'ALL');
			$this->outData['brandId'] = $brandId;
		}

		$shopList                    = $this->CouponModel->getShopList($brandId);

		$this->outData['shopList']   = $shopList['data']['list'];
		$this->outData['areaList']   = $shopList['data']['areaList'];
		$this->outData['cityList']   = $shopList['data']['cityList'];
		$this->outData['bjAreaList'] = $shopList['data']['bjAreaList'];
		$this->outData['shAreaList'] = $shopList['data']['shAreaList'];
		$this->outData['gzAreaList'] = $shopList['data']['gzAreaList'];

		$this->outData['discountCate'] = $this->DiscountModel->getBrandCate();

		$this->outData['brandImg'] = $this->DiscountModel->getBrandImg($brandId);

		$this->load->view('Discount/addDis', $this->outData);
	}

	/**
	 * 编辑折扣
	 *
	 */
	public function editDis(){
		if ($this->input->is_ajax_request()) {
			
			$reqData = $this->input->post();

			$verlidationRes = $this->DiscountModel->verlidationAddDis($reqData);

			if ($verlidationRes !== true) {
				$this->ajaxRes['msg'] = $verlidationRes;
				jsonReturn($this->ajaxRes);
			}

			$editDisRes = $this->DiscountModel->editDis($reqData);

			if ($editDisRes['error']) {
				$this->ajaxRes['msg'] = $editDisRes['msg'];
			}else{
				$this->ajaxRes = array(
						'status' => 0,
					);
			}

			jsonReturn($this->ajaxRes);
		}

		$this->outData['pageTitle'] = $this->lang->line('TITLE_DISCOUNT_EDIT');

		$brandId = $this->input->get('brandId');
		$discountId = $this->input->get('discountId');

		if(!$this->checkDiscountAuth($discountId, $brandId)){
			$outData = array(
					'errLang' => $this->lang->line('ERR_AUTH_EDIT_COUPON'),
					'url'     => site_url('Coupon/couponList'),
				);
			$this->load->view('Public/error', $outData);
		}

		$shopList                    = $this->CouponModel->getShopList($brandId);

		$this->outData['shopList']   = $shopList['data']['list'];
		$this->outData['areaList']   = $shopList['data']['areaList'];
		$this->outData['cityList']   = $shopList['data']['cityList'];
		$this->outData['bjAreaList'] = $shopList['data']['bjAreaList'];
		$this->outData['shAreaList'] = $shopList['data']['shAreaList'];
		$this->outData['gzAreaList'] = $shopList['data']['gzAreaList'];

		$this->outData['discountCate'] = $this->DiscountModel->getBrandCate();

		$this->outData['brandImg'] = $this->DiscountModel->getBrandImg($brandId);

		$this->outData['discountDetail'] = $this->DiscountModel->getDiscountDetail($discountId);

		$this->load->view('Discount/editDis', $this->outData);
	}

	/**
	 * 选择一个品牌
	 *
	 */
	public function brandSelect(){
		if (!$this->DiscountModel->isAdminUser($this->userInfo->role_id)) {
			redirect(base_url('Discount/addDis'));
			exit();
		}

		$this->outData['pageTitle'] = $this->lang->line('TITLE_BRAND_SELECT');

		$this->load->view('Discount/brandSelect', $this->outData);

	}

	/**
	 * 删除折扣信息
	 *
	 */
	public function delDis(){
		if (!$this->input->is_ajax_request()) {
			jsonReturn($this->ajaxRes);
		}

		$reqData = $this->input->post();

		$updateRes = $this->DiscountModel->delDiscountById($reqData['discountId'], $reqData['brandId']);

		if (!$updateRes) {
			$this->ajaxRes['msg'] = $this->lang->line('TEXT_DISCOUNT_DELETE_FAIL');
		}else{
			$this->ajaxRes = array('status' => 0);
		}

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 检测折扣编辑权限
	 * @param string $discountId 折扣id
	 * @param string $brandId 品牌id
	 */
	public function checkDiscountAuth($discountId = false, $brandId = false){

		if (!$this->DiscountModel->hasDiscountById($discountId, $brandId)) return false;
		
		if (!$this->DiscountModel->isAdmin){
			if ($brandId != $this->userInfo->brand_id) return false;
		} 

		return true;
	}

	/**
	 * 获取折扣列表 筛选条件
	 *
	 */
	private function _getDisListWhere(){

		$where = array();

		$where[] = " is_delete = 0 ";
		$reqData = $this->input->get();

		if (isset($reqData['title']) && !empty($reqData['title'])) $where[] = " name_zh LIKE '%".addslashes($reqData['title'])."%' ";

		if (isset($reqData['type']) && in_array($reqData['type'], array(1, 2, 3, 4, 6, 7, 8))) $where[] = " type = '".(int)$reqData['type']."' ";

		if (isset($reqData['brand']) && !empty($reqData['brand'])) $where[] = " (brand_name_en LIKE '%".addslashes($reqData['brand'])."%' OR brand_name_zh LIKE '%".addslashes($reqData['brand'])."%') ";

		if (isset($reqData['category']) && !empty($reqData['category'])) $where[] = " tb_category_id = '".addslashes($reqData['category'])."' ";

		if (isset($reqData['expStat']) && in_array($reqData['expStat'], array('normal', 'over'))) $where[] = " LEFT(end_date, 10) >= '".date('Y-m-d')."' ";

		$whereStr = " WHERE ".implode(" AND ", $where);

		return $whereStr;
	}
}
