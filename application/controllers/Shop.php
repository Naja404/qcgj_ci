<?php
defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 门店管理
 */

class Shop extends WebBase {
	//  视图输出内容
	public $outData;

	// 城市列表
	public $cityList = array('上海', '北京', '上海', '广州');

	public function __construct(){
		parent::__construct();
		
		$this->load->model('ShopModel');
		$this->load->model('BrandModel');
		$this->lang->load('shop');
		$this->outData['currentModule'] = __CLASS__;
	}

	/**
	 * 添加门店
	 *
	 */
	public function addShop(){
		$this->outData['pageTitle'] = $this->lang->line('TEXT_ADD_SHOP');
		$this->outData['cityList'] = $this->ShopModel->getCityList();

		$this->outData['areaList'] = $this->BrandModel->getDistrictList($this->outData['cityList'][0]->cityId);
		
		$this->outData['mallList'] = $this->ShopModel->getMallList($this->outData['cityList'][0]->cityId, $this->outData['areaList'][0]->id);

		$this->load->view('Shop/addShop', $this->outData);
	}

	/**
	 * 删除门店
	 *
	 */
	public function delShop(){
		if (!$this->input->is_ajax_request()) {
			jsonReturn($this->ajaxRes);
		}

		$shopId = $this->input->post('shopId');
		
		$this->ShopModel->delShop($shopId);

		jsonReturn(array('status' => 0));

	}

	/**
	 * 编辑门店
	 *
	 */
	public function editShop(){
		$this->outData['pageTitle'] = '编辑门店';
		
		$shopId = $this->input->get('id');

		$this->outData['detail'] = $this->ShopModel->getShopDetail($shopId);

		$this->outData['cityList'] = $this->ShopModel->getCityList();

		$this->outData['areaList'] = $this->BrandModel->getDistrictList($this->outData['detail']->cityId);
		
		$this->outData['mallList'] = $this->ShopModel->getMallList($this->outData['detail']->cityId, $this->outData['detail']->districtId);

		$this->load->view('Shop/editShop', $this->outData);
	}

	/**
	 * 门店列表
	 */
	public function shopList(){

		$where = $this->shopListWhere();

		$shopList = $this->ShopModel->getShopList($where, '', $this->p);

		$this->outData['shopList'] = $shopList['data']['list'];
		$this->outData['shopListTotal'] = $shopList['data']['total'];
		$this->outData['shopListTotalLang'] = sprintf($this->lang->line('TEXT_SHOPLIST_TOTAL'), $shopList['data']['total']);
		$this->outData['pageTitle'] = $this->lang->line('TEXT_TITLE_SHOPLIST');
		$this->outData['cityList'] = $this->ShopModel->getCityById();

		$this->_shopListPage();

		$this->load->view('Shop/shopList', $this->outData);
	}

	/**
	 * 门店查询条件
	 *
	 */
	public function shopListWhere(){

		$reqData = $this->input->get();

		$where = array();

		$where[] = " b.status = 1 AND b.level IN  (1, 2) ";

		if (isset($reqData['city']) && !empty($reqData['city'])) $where[] = " b.tb_city_id = '".addslashes($reqData['city'])."' ";

		if (isset($reqData['brand']) && !empty($reqData['brand'])) $where[] = " (c.name_zh LIKE '%".addslashes($reqData['brand'])."%' OR c.name_en LIKE '%".addslashes($reqData['brand'])."%') ";

		if (isset($reqData['shop']) && !empty($reqData['shop'])) $where[] = " b.name_zh LIKE '%".addslashes($reqData['shop'])."%' ";

		if (isset($reqData['address']) && !empty($reqData['address'])) $where[] = " b.address LIKE '%".addslashes($reqData['address'])."%' ";

		$whereStr = " WHERE ".implode(" AND ", $where);

		return $whereStr;

	}

	/**
	 * 店长列表
	 *
	 */
	public function managerList(){
		
		$managerList = $this->ShopModel->getManagerList($this->p);
		$this->outData['pageTitle'] = $this->lang->line('TEXT_TITLE_MANAGERLIST');
		$this->outData['managerList'] = $managerList['data']['list'];
		$this->outData['managerListPage'] = $managerList['data']['page'];

		$this->load->view('Shop/managerList', $this->outData);
	}

	/**
	 * 添加店长
	 *
	 */
	public function addShopManager(){

		if ($this->input->method() === 'post') {
			$verlidationRes = $this->_verlidationAddShopManager();
			
			if ($verlidationRes !== true) {
				$this->ajaxRes['msg'] = $verlidationRes;
				jsonReturn($this->ajaxRes);
			}

			$addManagerRes = $this->ShopModel->addShopManager($this->input->post());

			if ($addManagerRes['error']) {
				$this->ajaxRes['msg'] = $addManagerRes['msg'];
			}else{
				$this->ajaxRes = array(
						'status' => 0,
					);
			}

			jsonReturn($this->ajaxRes);

		}

		$this->outData['pageTitle'] = $this->lang->line('TEXT_TITLE_ADD_SHOPMANAGER');
		$this->outData['shopList'] = $this->ShopModel->getShopListWithForm();

		$this->load->view('Shop/addShopManager', $this->outData);
	}

	/**
	 * 获取区域列表
	 * @param string $cityId
	 */
	public function getAreaList(){
		
	}

	/**
	 * 添加店长表单验证
	 *
	 */
	private function _verlidationAddShopManager(){
		$this->form_validation->set_rules($this->lang->line('ADD_SHOPMANAGER_VALIDATION'));

		if (!$this->form_validation->run()) {
			return validation_errors();
		}

		if ($this->ShopModel->existsManagerName($this->input->post('managerName'))) return $this->lang->line('ERR_MANAGERNAME_EXISTS');


		if ($this->input->post('passwd') != $this->input->post('confirmPasswd')) {
			return $this->lang->line('ERR_CONFIRM_PASSWD_NOTSAME');
		}
		
		$shopList = $this->ShopModel->getShopListWithForm();
		$shopListArr = array();

		foreach ($shopList as $k => $v) {
			$shopListArr[] = $v->mallID;
		}

		if (!in_array($this->input->post('mallID'), $shopListArr)) {
			return $this->lang->line('ERR_MALLID');
		}

		return true;
	}

	/**
	 * 门店列表分页
	 */
	private function _shopListPage(){
		$shopListPageConf = array(
				'base_url'         => site_url('Shop/shopList'),
				'total_rows'       => $this->outData['shopListTotal'],
				'per_page'         => 25, 
			);

		$this->pagination->initialize($shopListPageConf);

		$this->outData['pagination'] = $this->pagination->create_links();
	}

}
