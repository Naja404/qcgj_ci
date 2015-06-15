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
		$this->lang->load('shop');
		$this->outData['currentModule'] = __CLASS__;
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
		$this->outData['cityList'] = $this->lang->line('SELECT_CITY_LIST');
		
		$this->_shopListPage();
		$this->load->view('Shop/shopList', $this->outData);
	}

	/**
	 * 门店查询条件
	 *
	 */
	public function shopListWhere(){
		$cityID = (int)$this->input->get('city', true);
		$shopName = $this->input->get('shop', true);
		$address = $this->input->get('address', true);

		if (!in_array($this->cityList[$cityID], $this->cityList)) {
			$cityName = $this->cityList[0];
		}else{
			$cityName = $this->cityList[$cityID];
		}

		$where = " WHERE c.city_name = '".$cityName."' ";

		if (!empty($shopName)) {
			$where .= " AND c.name_zh LIKE '%".$shopName."%' ";
		}

		if (!empty($address)) {
			$where .= " AND (c.address LIKE '%".$address."%' OR c.trade_area_name LIKE '%".$address."%')";
		}

		return $where;

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
