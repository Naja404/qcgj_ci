<?php
defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 门店管理
 */

class Shop extends WebBase {
	//  视图输出内容
	public $outData;

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
		// TODO 数据总数和数据数组
		$shopList = $this->ShopModel->getShopList();
		echo '<pre>';
		print_r($shopList);exit;
		$this->outData['shopList'] = $shopList['list'];
		$this->outData['pageTitle'] = $this->lang->line('TEXT_TITLE_SHOPLIST');
		$this->outData['cityList'] = $this->lang->line('SELECT_CITY_LIST');
		$this->_shopListPage();
		$this->load->view('Shop/shoplist', $this->outData);
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
