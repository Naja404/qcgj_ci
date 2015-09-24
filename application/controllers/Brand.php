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
	 * 商场列表
	 *
	 */
	public function mallList(){

		$this->outData['pageTitle'] = '商场列表';
			
		$where = $this->_mallListWhere();

		$list = $this->BrandModel->getMall($where, $this->p, 1, 'Brand/mallList');

		$this->outData['pagination']   = $list['pagination'];
		
		$this->outData['mallList']     = $list['list'];
		
		$this->outData['cityList']     = $this->BrandModel->getCityList();
		
		$this->outData['districtList'] = $this->BrandModel->getDistrictByCity($this->input->get('city'), 'obj');

		$this->load->view('Brand/mallList', $this->outData);
	}

	/**
	 * 编辑商场
	 *
	 */
	public function editMall(){
		
		$id = strDecrypt($this->input->get('id'));
		
		$detail = $this->BrandModel->getMallDetail($id, 1);

		if (count($detail) <= 0) {
			$outData = array(
					'errLang' => $this->lang->line('ERR_AUTH_EDIT_BRAND'),
					'url'     => site_url('Brand/mallList'),
				);	
			$this->load->view('Public/error', $outData);
		}

		if ($this->input->is_ajax_request()) return $this->_editMall($id); 


		$this->outData['pageTitle'] = '编辑商场';

		$this->outData['detail'] = $detail;

		$this->outData['city'] = $this->BrandModel->getCityList();

		$this->outData['district'] = $this->BrandModel->getDistrictList($detail->tb_city_id);

		$this->load->view('Brand/editMall', $this->outData);
	}

	/**
	 * 更新商场
	 *
	 */
	public function upMall(){

		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$reqData = $this->input->post();

		$status = $this->BrandModel->upMallStatus($reqData);

		if ($status['error'] === false) {
			$this->ajaxRes['msg'] = $this->lang->line('ERR_UPDATE_FAILURE');
		}else{
			$this->ajaxRes = array(
					'status'  => 0,
					'tdDiv'   => config_item('HIDE_TD_DIV_'.$reqData['status']),
					'spanDiv' => sprintf(config_item('HIDE_A_DIV_'.$status['status']), 'upMall', $reqData['mallId'], $status['status']),
				);
		}

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 1005品牌列表
	 *
	 */
	public function hadBrandList(){
		$this->outData['pageTitle'] = '1005品牌审核';
		
		$where = " WHERE mark = 1 ";

		$brandName = addslashes($this->input->get('brand'));
		
		if ($brandName) $where .= " AND name_zh LIKE '%".$brandName."%' OR name_en LIKE '%".$brandName."%' ";

		$brandList = $this->BrandModel->getHadBrandList($where, $this->p);

		$this->outData['pagination'] = $brandList['pagination'];

		$this->outData['brandList'] = $brandList['list'];

		$this->load->view('Brand/hadBrandList', $this->outData);
	}

	/**
	 * 品牌列表
	 *
	 */
	public function listView(){

		$this->outData['pageTitle'] = $this->lang->line('TITLE_BRAND_LIST');

		if ($this->input->get('showall') == 'yes') {
			$showAll = true;
			$showStatu = " status IN (1, 0) ";
		}else{
			$showAll = false;
			$showStatu = " status = 1 ";
		}
		
		$where = " WHERE ".$showStatu;

		$brandName = addslashes($this->input->get('brand'));
		
		if ($brandName) $where = " WHERE (name_zh LIKE '%".$brandName."%' OR name_en LIKE '%".$brandName."%') AND ".$showStatu;
		
		$isCate = $this->input->get('category') == 'yes' ? true : false;

		$brandList = $this->BrandModel->getBrandList($where, $this->p, $isCate, $showAll);

		$this->outData['pagination'] = $brandList['pagination'];

		$this->outData['brandList'] = $brandList['list'];

		$this->load->view('Brand/listView', $this->outData);
	}

	/**
	 * 商场店铺列表
	 * 
	 */
	public function shopList(){

		$this->outData['pageTitle'] = $this->lang->line('TITLE_SHOP_MALL_LIST');

		$where = $this->_shopListWhere();

		$shopList = $this->BrandModel->getShopList($where, $this->p);

		$this->outData['pagination'] = $shopList['pagination'];

		$this->outData['shopList'] = $shopList['list'];	

		$this->outData['cityList']     = $this->BrandModel->getCityList();

		$this->outData['districtList'] = $this->BrandModel->getDistrictByCity($this->input->get('city'), 'obj');

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
	 * 更新品牌状态
	 *
	 */
	public function upBrand(){
		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$reqData = $this->input->post();

		$status = $this->BrandModel->upBrandStatus($reqData);

		if ($status['error'] === false) {
			$this->ajaxRes['msg'] = $this->lang->line('ERR_UPDATE_FAILURE');
		}else{
			$this->ajaxRes = array(
					'status'  => 0,
					'tdDiv'   => config_item('HIDE_TD_DIV_'.$reqData['status']),
					'spanDiv' => sprintf(config_item('HIDE_A_DIV_'.$status['status']), 'upBrand', $reqData['brandId'], $status['status']),
				);
		}

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 编辑品牌
	 *
	 */
	public function editBrand(){

		$brandId = strDecrypt($this->input->get('brandId'));

		if (!$this->BrandModel->checkEditBrand($brandId)) {
			$outData = array(
					'errLang' => $this->lang->line('ERR_AUTH_EDIT_BRAND'),
					'url'     => site_url('Brand/listView'),
				);
			$this->load->view('Public/error', $outData);
		}

		if ($this->input->is_ajax_request()) return $this->_editBrandForm($brandId); 

		$this->outData['pageTitle']  = $this->lang->line('TITLE_EDIT_BRAND');
		$this->outData['brandCate']  = $this->BrandModel->getBrandCategory();
		$this->outData['brandStyle'] = $this->BrandModel->getBrandStyle();
		$this->outData['brandAge']   = $this->BrandModel->getBrandAge();
		$this->outData['brandPrice'] = $this->BrandModel->getBrandPrice();
		$this->outData['brand'] = $this->BrandModel->getBrandInfo($brandId);

		$this->load->view('Brand/editBrand', $this->outData);
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
	 * 添加商场
	 *
	 */
	public function addMall(){
		if ($this->input->is_ajax_request()) return $this->_addMallForm(); 

		$this->outData['pageTitle']  = $this->lang->line('TITLE_ADD_MALL');

		$this->outData['city'] = $this->BrandModel->getCityList();

		$this->outData['district'] = $this->BrandModel->getDistrictList('391db7b8fdd211e3b2bf00163e000dce');

		$this->load->view('Brand/addMall', $this->outData);
	}

	/**
	 * 删除商场
	 *
	 */
	public function delMall(){
		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$mallId = $this->input->post('mallId');

		$delRes = $this->BrandModel->delMall($mallId, 1);

		if ($delRes) {
			$this->ajaxRes = array(
					'status' => 0,
				);
		}

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 编辑店铺
	 *
	 */
	public function editShop(){
		
		$shopId = strDecrypt($this->input->get('shopId'));

		if (!$this->BrandModel->checkEditShop($shopId)) {
			$outData = array(
					'errLang' => $this->lang->line('ERR_AUTH_EDIT_SHOP'),
					'url'     => site_url('Brand/shopList'),
				);
			$this->load->view('Public/error', $outData);
		}

		if ($this->input->is_ajax_request()) return $this->_editShopForm($shopId);

		$this->outData['pageTitle']    = $this->lang->line('TITLE_EDIT_SHOP');
		$this->outData['cityList']     = $this->BrandModel->getCityList();
		$this->outData['shop']         = $this->BrandModel->getShopInfo($shopId);
		$this->outData['selectCity']   = $this->BrandModel->getCityNameById($this->outData['shop']->cityId);
		$this->outData['districtList'] = $this->BrandModel->getDistrictList($this->outData['shop']->cityId);

		$this->load->view('Brand/editShop', $this->outData);
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
				'city' => $this->BrandModel->getCityNameById($this->input->post('cityId')),
				// 'mall' => $this->BrandModel->getMallList($this->input->post('cityId'), 'html'),
			);

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 根据城市名获取城市去列表
	 * @param string $city 城市名
	 */
	public function getDistrictByCity(){
		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes); 

		$this->ajaxRes = array(
				'status' => 0,
				'html' => $this->BrandModel->getDistrictByCity($this->input->post('city')),
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
	 * 品牌模糊查询
	 *
	 */
	public function searchBrand(){
		
		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$brandName = $this->input->post('brand');
		
		if (empty($brandName)) jsonReturn($this->ajaxRes); 

		$list = $this->BrandModel->searchBrand($brandName);

		$this->ajaxRes = array(
				'status' => 0,
				'list' => $list,
			);

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 搜索地址
	 * @param string $address 地址
	 */
	public function searchAddress(){

		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$address = $this->input->post('address');
		
		if (empty($address)) jsonReturn($this->ajaxRes); 

		$list = $this->BrandModel->searchAddress($address);

		$this->ajaxRes = array(
				'status' => 0,
				'list' => $list,
			);

		jsonReturn($this->ajaxRes);
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
	 * ajax上传图片
	 *
	 */
	public function uploadPic(){

		if ($this->input->method() != 'post') {
			echo json_encode($this->ajaxRes);exit;
		}

		$uploadConf = config_item('FILE_UPLOAD');
		$filesName = $this->input->get('filesName');
		
		if ($filesName == 'shopImg') {
			$uploadConf['upload_path']   = './uploadtemp/mall/';
			$uploadConf['file_name']     = 'mall_'.md5(currentTime('MICROTIME'));
			$uploadConf['relation_path'] = '/alidata1/apps/uploadtemp_app_admin_v400/mall/';
		}else{
			$uploadConf['upload_path']   = './uploadtemp/brand/';
			$uploadConf['file_name']     = 'brand_'.md5(currentTime('MICROTIME'));
			$uploadConf['relation_path'] = '/alidata1/apps/uploadtemp_app_admin_v400/brand/';
		}

		$this->load->library('upload');

		$this->upload->initialize($uploadConf);

		if (!$this->upload->do_upload($filesName)){
			$this->ajaxRes['msg'] = $this->upload->display_errors();
		}else{
			
			if ($filesName == 'brandLogo') {
				$fullPath = $this->upload->data('full_path');
				$this->_resetlogoPic($fullPath);
			}

			$this->ajaxRes = array(
					'status' => 0,
					'url'    => config_item('image_url').$this->upload->data('relative_path'),
					'path'   => $this->upload->data('relative_path'),
				);
		}

		echo json_encode($this->ajaxRes);exit;

	}

	/**
	 * 添加商场
	 *
	 */
	public function _addMallForm(){
		$reqData = $this->input->post();

		$addRes = $this->BrandModel->addMall($reqData);

		if ($addRes) {
			$this->ajaxRes = array(
					'status' => 0,
				);
		}else{
			$this->ajaxRes['msg'] = $this->lang->line('ERR_ADD_FAILURE');
		}

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
	 * 编辑商场/店铺信息
	 * @param string $shopId 商场/店铺id
	 */
	private function _editShopForm($shopId = false){

		$reqData = $this->input->post();
		$reqData['shopId'] = $shopId;

		$rule = $this->lang->line('ADD_SHOP_VALIDATION');

		$validateRes = $this->BrandModel->validateAddShop($rule, $reqData, 'edit');

		if (is_string($validateRes) && !empty($validateRes)) {
			$this->ajaxRes['msg'] = $validateRes;
			jsonReturn($this->ajaxRes);
		}

		$editRes = $this->BrandModel->editShop($reqData);

		if (is_string($editRes) && !empty($editRes)) {
			$this->ajaxRes['msg'] = $editRes;
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

	/**
	 * 编辑品牌
	 * 
	 */
	private function _editBrandForm($brandId = false){
		
		$reqData = $this->input->post();

		if ($brandId !== $reqData['brandRelation']) jsonReturn($this->ajaxRes);

		$rule = $this->lang->line('ADD_BRAND_VALIDATION');

		$validateRes = $this->BrandModel->validateAddBrand($rule, $reqData, $brandId);

		if (is_string($validateRes) && !empty($validateRes)) {
			$this->ajaxRes['msg'] = $validateRes;
			jsonReturn($this->ajaxRes);
		}

		$editRes = $this->BrandModel->editBrand($reqData);

		if (is_string($editRes) && !empty($editRes)) {
			$this->ajaxRes['msg'] = $editRes;
		}else{
			$this->ajaxRes = array(
					'status' => 0,
				);
		}

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 店铺筛选条件
	 *
	 */
	public function _shopListWhere(){

		$reqData = $this->input->get();

		$where = NULL;

		if (!empty($reqData['city'])) $where .= " AND c.city_name = '".$reqData['city']."' ";

		if (!empty($reqData['district'])) $where .= " AND c.district_name = '".$reqData['district']."' ";

		if (!empty($reqData['brand'])) $where .= " AND (b.name_zh LIKE '%".addslashes($reqData['brand'])."%' OR b.name_en LIKE '%".addslashes($reqData['brand'])."%')";

		if (!empty($reqData['shop'])) $where .= " AND c.name_zh LIKE '%".$reqData['shop']."%' "; 

		if (!empty($reqData['address'])) $where .= " AND c.address LIKE '%".$reqData['address']."%' ";

		return $where;
	}

	/**
	 * 编辑商场表单
	 * @param string $id 
	 */
	public function _editMall($id = false){
		$reqData = $this->input->post();
		
		if ($id != $reqData['mallId']) jsonReturn($this->ajaxRes);

		$where = array(
				'id'    => $reqData['mallId'],
				'level' => 1,
			);

		$update = array(
				'name_zh'        => $reqData['mallName'],
				'address'        => $reqData['address'],
				'tb_city_id'	 => $reqData['cityId'],
				'city_name'		 => $this->BrandModel->getCityNameById($reqData['cityId']),
				'tb_district_id' => $reqData['districtId'],
				'district_name'	 => $this->BrandModel->getDistrictNameById($reqData['districtId']),
				'longitude'      => $reqData['lng'],
				'latitude'       => $reqData['lat'],
				'tel'            => $reqData['tel'],
				'update_time'    => currentTime(),
			);

		// if (!empty($reqData['shopImgPath'])) {
			$update['pic_url'] = $reqData['shopImgPath'];
		// }

		// if (!empty($reqData['shopThumbImgPath'])) {
			$update['thumb_url'] = $reqData['shopThumbImgPath'];
		// }

		$updateRes = $this->BrandModel->editMall($update, $where);

		if ($updateRes) {
			$this->ajaxRes = array(
					'status' => 0,
				);
		}else{
			$this->ajaxRes['msg'] = $this->lang->line('ERR_UPDATE_FAILURE');
		}

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 商场列表查询条件
	 *
	 */
	public function _mallListWhere(){

		$reqData = $this->input->get();

		$where = NULL;

		if (!empty($reqData['city'])) $where .= " AND city_name = '".$reqData['city']."' ";

		if (!empty($reqData['district'])) $where .= " AND district_name = '".$reqData['district']."' ";

		if (!empty($reqData['shop'])) $where .= " AND name_zh LIKE '%".$reqData['shop']."%' "; 

		if (!empty($reqData['address'])) $where .= " AND address LIKE '%".$reqData['address']."%' ";

		return $where;

	}

	/**
	 * 品牌logo图片处理
	 * @param string $fullPath 图片绝对路径地址 
	 */
	private function _resetlogoPic($fullPath = false){
		resizeIMG($fullPath, $fullPath);
		
		$picInfo = getimagesize($fullPath);

		if ($picInfo['mime'] == 'image/jpeg') {
			$sourceImg = imagecreatefromjpeg($fullPath);
		}else{
			$sourceImg = imagecreatefrompng($fullPath);
		}

		$mask = imagecreatefrompng('./uploads/logo_other/mask.png');
		
		imagealphamask($sourceImg, $mask);
		imagepng($sourceImg, $fullPath);
	}
}
