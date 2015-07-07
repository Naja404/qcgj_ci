<?php
defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 品牌管理
 */

class Brand extends WebBase {
	//  视图输出内容
	public $outData;

	public function __construct(){
		parent::__construct();
		
		$this->load->model('BrandModel');
		$this->lang->load('brand');
		$this->outData['currentModule'] = __CLASS__;
	}

	/**
	 * 品牌列表
	 *
	 */
	public function listView(){
		
		$this->outData['pageTitle'] = $this->lang->line('TITLE_BRAND_LIST');
		
		$where = NULL;
		$brandName = $this->input->get('brand');
		
		if ($brandName) $where = " name_zh LIKE '%".$brandName."%' OR name_en LIKE '%".$brandName."%' ";

		$brandList = $this->BrandModel->getBrandList($where, $this->p);

		$this->outData['pagination'] = $brandList['pagination'];

		$this->outData['brandList'] = $brandList['list'];

		$this->load->view('Brand/listView', $this->outData);
	}

	/**
	 * 商场店铺列表
	 * 
	 */
	public function shopList(){
		$this->outData['pageTitle'] = $this->lang->line('TITLE_SHOP_LIST');
		
		$where = NULL;
		$shopName = $this->input->get('shop');
		
		if ($shopName) $where = " name_zh LIKE '%".$shopName."%' OR name_en LIKE '%".$shopName."%' ";

		$shopList = $this->BrandModel->getShopList($where, $this->p);

		$this->outData['pagination'] = $shopList['pagination'];

		$this->outData['shopList'] = $shopList['list'];

		$this->load->view('Brand/shopList', $this->outData);
	}

	/**
	 * 添加品牌
	 *
	 */
	public function addBrand(){

		if ($this->input->is_ajax_request()) return $this->_addBrandForm(); 

		$this->outData['pageTitle']  = $this->lang->line('TITLE_ADD_BRAND');
		$this->outData['brandCate']  = $this->BrandModel->getBrandCategory();
		$this->outData['brandStyle'] = $this->BrandModel->getBrandStyle();
		$this->outData['brandAge']   = $this->BrandModel->getBrandAge();
		$this->outData['brandPrice'] = $this->BrandModel->getBrandPrice();

		$this->load->view('Brand/addBrand', $this->outData);
	}

	/**
	 * 添加店铺
	 *
	 */ 
	public function addShop(){

		if ($this->input->is_ajax_request()) return $this->_addShopForm(); 

		$this->outData['pageTitle']    = $this->lang->line('TITLE_ADD_SHOP');
		$this->outData['cityList']     = $this->BrandModel->getCityList();
		$this->outData['districtList'] = $this->BrandModel->getDistrictList($this->outData['cityList'][0]->id);
		// $this->outData['mallList']     = $this->BrandModel->getMallList($this->outData['cityList'][0]->id, 'html');

		$this->load->view('Brand/addShop', $this->outData);
	}

	/**
	 * 获取城市区域列表
	 * @param string $_request['cityId'] 城市id
	 */
	public function getDistrictList(){
		
		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes); 

		$this->ajaxRes = array(
				'status' => 0,
				'list' => $this->BrandModel->getDistrictList($this->input->post('cityId')),
				// 'mall' => $this->BrandModel->getMallList($this->input->post('cityId'), 'html'),
			);

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 删除品牌
	 *
	 */
	public function delBrand(){
		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$brandId = $this->input->post('brandId');
		
		$this->BrandModel->delBrand($brandId);

		jsonReturn(array('status' => 0));
	}

	/**
	 * 删除商场/门店
	 *
	 */
	public function delShop(){
		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$shopId = $this->input->post('shopId');
		
		$this->BrandModel->delShop($shopId);

		jsonReturn(array('status' => 0));
	}

	/**
	 * 搜索商场/店铺
	 * @param string $mall 商场/店铺名称
	 */
	public function searchMall(){
		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$mall = $this->input->post('mall');

		$queryRes =  $this->BrandModel->searchMall($mall);

		$this->ajaxRes = array(
				'status' => 0,
				'list' => $queryRes,
			);

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 添加店铺
	 *
	 */
	private function _addShopForm(){
		$reqData = $this->input->post();

		$rule = $this->lang->line('ADD_SHOP_VALIDATION');

		$validateRes = $this->BrandModel->validateAddShop($rule, $reqData);

		if (is_string($validateRes) && !empty($validateRes)) {
			$this->ajaxRes['msg'] = $validateRes;
			jsonReturn($this->ajaxRes);
		}

		$addRes = $this->BrandModel->addShop($reqData);

		if (is_string($addRes) && !empty($addRes)) {
			$this->ajaxRes['msg'] = $addRes;
		}else{
			$this->ajaxRes = array(
					'status' => 0,
				);
		}

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 添加品牌
	 *
	 */
	private function _addBrandForm(){

		$reqData = $this->input->post();

		$rule = $this->lang->line('ADD_BRAND_VALIDATION');

		$validateRes = $this->BrandModel->validateAddBrand($rule, $reqData);

		if (is_string($validateRes) && !empty($validateRes)) {
			$this->ajaxRes['msg'] = $validateRes;
			jsonReturn($this->ajaxRes);
		}

		$addRes = $this->BrandModel->addBrand($reqData);

		if (is_string($addRes) && !empty($addRes)) {
			$this->ajaxRes['msg'] = $addRes;
		}else{
			$this->ajaxRes = array(
					'status' => 0,
				);
		}

		jsonReturn($this->ajaxRes);
	}
}
