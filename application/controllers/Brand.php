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
	 * 添加品牌
	 *
	 */
	public function addBrand(){
		$this->outData['pageTitle'] = $this->lang->line('TITLE_ADD_BRAND');
		$this->load->view('Brand/addBrand', $this->outData);
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

}
