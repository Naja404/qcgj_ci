<?php
defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 虹桥模块
 */

class HongQiao extends WebBase {
	// 输出数据
	public $outData;

	public function __construct(){
		parent::__construct();
		$this->load->model('HongQiaoModel');
		$this->load->model('BrandModel');
		$this->lang->load('hongqiao');
	}

	/**
	 * 2万条数据列表
	 *
	 */
	public function mall2w(){

		$where = " ";

		$list = $this->HongQiaoModel->getMall2W($where, $this->p, 'HongQiao/mall2w');

		$this->outData['pageTitle']  = '爬虫数据列表';

		$this->outData['list']       = $list['list'];

		$this->outData['pagination'] = $list['pagination'];

		$this->load->view('HongQiao/mall2w', $this->outData);
	}

	/**
	 * 编辑爬虫数据
	 *
	 */
	public function editMall2w(){
		$id = strDecrypt($this->input->get('id'));

		$detail = $this->HongQiaoModel->getMall2wDetail($id);

		if (count($detail) <= 0) {
			$outData = array(
					'errLang' => $this->lang->line('ERR_AUTH_EDIT_BRAND'),
					'url'     => site_url('HongQiao/mall2w').'?p='.$this->input->get('p'),
				);	
			$this->load->view('Public/error', $outData);
		}

		if ($this->input->is_ajax_request()) return $this->_editMall2wForm($id); 

		$this->outData['pageTitle'] = '编辑爬虫数据';

		$this->outData['detail'] = $detail;

		$this->load->view('HongQiao/editMall2w', $this->outData);		
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
	 * 获取商场列表
	 *
	 */
	public function getMallList(){
		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$mallName = $this->input->post('mall');
		$cityName = $this->input->post('city');
		
		if (empty($mallName)) jsonReturn($this->ajaxRes); 

		$list = $this->HongQiaoModel->getMallList2w($mallName, $mallName, $cityName, 'html');

		$this->ajaxRes = array(
				'status' => 0,
				'list' => $list,
			);

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 编辑爬虫数据表单
	 * @param int $id 
	 */
	public function _editMall2wForm($id = false){

		$reqData = $this->input->post();

		if ($id != $reqData['mallId']) jsonReturn($this->ajaxRes);

		$brandStatus = $this->HongQiaoModel->checkBrand($reqData['brandNameZh_s'], $reqData['brandNameEn_s'], $reqData['brandId_s'], $reqData['brandLogoPath']);

		$update = array(
				'tb_brand_id' => is_string($brandStatus) ? $brandStatus : '',
				'tb_mall_id'  => $reqData['mallId_s'],
				'update_time' => currentTime(),
				'user'        => $this->userInfo->user_id,
			);

		$where = array(
				'id' => $reqData['mallId'],
			);

		$updateRes = $this->db->where($where)->update(tname('new_mall_s'), $update);

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
	 * 标记删除爬虫数据
	 *
	 */
	public function delMall2w(){
		$where = array(
				'id' => $this->input->post('mallId'),
			);

		$this->db->where($where)->update(tname('new_mall_s'), array('status' => '0'));

		jsonReturn(array('status' => 0));
	}

	/**
	 * 商场列表
	 *
	 */
	public function mallList(){
		$list = $this->HongQiaoModel->getBrandShopList($this->p);

		$this->outData['pageTitle']  = '商场列表';

		$this->outData['list']       = $list['list'];

		$this->outData['pagination'] = $list['pagination'];

		$this->load->view('HongQiao/mallList', $this->outData);
	}

	/**
	 * 品牌店铺图片筛选
	 *
	 */
	public function brandShopList(){
		$list = $this->HongQiaoModel->getBrandShopList($this->p);

		$this->outData['pageTitle'] = '品牌店铺列表';

		$this->outData['list']       = $list['list'];

		$this->outData['pagination'] = $list['pagination'];

		$this->load->view('HongQiao/brandShopList', $this->outData);
	}

	/**
	 * 编辑品牌店铺图片
	 *
	 */
	public function editBrandShop(){

		$id = strDecrypt($this->input->get('id'));

		$this->outData['detail'] = $this->HongQiaoModel->getBrandShopDetail($id);

		if ($this->outData['detail'] == false) {
			$outData = array(
					'errLang' => $this->lang->line('ERR_AUTH_EDIT_BRAND'),
					'url'     => site_url('HongQiao/brandShopList'),
				);	
			$this->load->view('Public/error', $outData);
		}

		if ($this->input->is_ajax_request()) return $this->_editBrandShopFrom($id); 

		$this->outData['pageTitle'] = '编辑品牌店铺';

		$this->load->view('HongQiao/editBrandShop', $this->outData);
	}

	/**
	 * 电影院列表
	 *
	 */
	public function cinemaList(){

		$where = $this->_getHongQiaoWhere(array(' level = 5 '));

		$list = $this->HongQiaoModel->getHongQiaoList($where, $this->p, 'HongQiao/cinemaList');

		$this->outData['pageTitle'] = '电影院列表';

		$this->outData['list'] = $list['list'];

		$this->load->view('HongQiao/cinemaList', $this->outData);

	}

	/**
	 * 电影院地址列表
	 *
	 */
	public function cinemaAddressList(){

		$list = $this->HongQiaoModel->getMallList($this->p, 5, 'HongQiao/cinemaAddressList');

		$this->outData['pageTitle'] = '电影院地址列表';

		$this->outData['list'] = $list['list'];

		$this->outData['pagination'] = $list['pagination'];

		$this->load->view('HongQiao/cinemaAddressList', $this->outData);
	}

	/**
	 * 编辑电影院地址
	 *
	 */
	public function editCinemaAddress(){
		$id = strDecrypt($this->input->get('id'));

		if (!$this->HongQiaoModel->checkEditMall($id, 5)) {
			$outData = array(
					'errLang' => $this->lang->line('ERR_AUTH_EDIT_BRAND'),
					'url'     => site_url('HongQiao/cinemaAddressList'),
				);	
			$this->load->view('Public/error', $outData);
		}

		if ($this->input->is_ajax_request()) return $this->_editCinemaAddress($id); 

		$this->outData['pageTitle'] = '编辑电影院地址';

		$this->outData['detail'] = $this->HongQiaoModel->getMallDetail($id, 5);

		// $this->outData['district'] = $this->BrandModel->getDistrictList('391db7b8fdd211e3b2bf00163e000dce');


		$this->load->view('HongQiao/editCinemaAddress', $this->outData);		
	}

	/**
	 * 编辑电影院
	 *
	 */
	public function editCinema(){
		$id = strDecrypt($this->input->get('id'));

		if (!$this->HongQiaoModel->checkEditCinema($id)) {
			$outData = array(
					'errLang' => $this->lang->line('ERR_AUTH_EDIT_BRAND'),
					'url'     => site_url('HongQiao/cinemaList'),
				);	
			$this->load->view('Public/error', $outData);
		}

		if ($this->input->is_ajax_request()) return $this->_editCinema($id); 

		$this->outData['pageTitle'] = '编辑电影院';

		$this->outData['detail'] = $this->HongQiaoModel->getCinemaDetail($id);

		$this->outData['pic'] = $this->outData['detail']->pic;

		$this->load->view('HongQiao/editCinema', $this->outData);

	}

	/**
	 * 景点列表
	 *
	 */
	public function travelList(){

		$where = $this->_getHongQiaoWhere(array(' level = 6 '));

		$list = $this->HongQiaoModel->getHongQiaoList($where, $this->p, 'HongQiao/travelList');

		$this->outData['pageTitle'] = '景点列表';

		$this->outData['list'] = $list['list'];
		$this->outData['pagination'] = $list['pagination'];

		$this->load->view('HongQiao/travelList', $this->outData);
	}

	/**
	 * 景点列表
	 *
	 */
	public function travelAddressList(){

		$list = $this->HongQiaoModel->getMallList($this->p, 6, 'HongQiao/travelAddressList');

		$this->outData['pageTitle'] = '景点地址列表';

		$this->outData['list'] = $list['list'];

		$this->outData['pagination'] = $list['pagination'];

		$this->load->view('HongQiao/travelAddressList', $this->outData);
	}

	/**
	 * 编辑景点地址
	 *
	 */
	public function editTravelAddress(){
		$id = strDecrypt($this->input->get('id'));

		if (!$this->HongQiaoModel->checkEditMall($id, 6)) {
			$outData = array(
					'errLang' => $this->lang->line('ERR_AUTH_EDIT_BRAND'),
					'url'     => site_url('HongQiao/travelAddressList'),
				);	
			$this->load->view('Public/error', $outData);
		}

		if ($this->input->is_ajax_request()) return $this->_editTravelForm($id); 

		$this->outData['pageTitle'] = '编辑景点地址';

		$this->outData['detail'] = $this->HongQiaoModel->getMallDetail($id, 6);

		$this->outData['district'] = $this->BrandModel->getDistrictList('391db7b8fdd211e3b2bf00163e000dce');


		$this->load->view('HongQiao/editTravelAddress', $this->outData);
	}

	/**
	 * 编辑景点
	 *
	 */
	public function editTravel(){
		$id = strDecrypt($this->input->get('id'));

		if (!$this->HongQiaoModel->checkEditTravel($id)) {
			$outData = array(
					'errLang' => $this->lang->line('ERR_AUTH_EDIT_BRAND'),
					'url'     => site_url('HongQiao/restaurantList'),
				);	
			$this->load->view('Public/error', $outData);
		}

		if ($this->input->is_ajax_request()) return $this->_editTravel($id); 

		$this->outData['pageTitle'] = '编辑景点';

		$this->outData['detail'] = $this->HongQiaoModel->getTravelDetail($id);

		$this->outData['pic'] = json_decode($this->outData['detail']->image, true);

		if (!empty($this->outData['detail']->image)) {
			$this->outData['pic'][] = preg_replace('/200_200/', '600_600', $this->outData['detail']->image);
		}

		$this->load->view('HongQiao/editTravel', $this->outData);
	}

	/**
	 * 餐厅列表
	 *
	 */
	public function restaurantList(){

		$where = $this->_getHongQiaoWhere(array(' level = 4 '));

		$list = $this->HongQiaoModel->getHongQiaoList($where, $this->p, 'HongQiao/restaurantList');

		$this->outData['pageTitle'] = '餐厅列表';
		$this->outData['list'] = $list['list'];
		$this->outData['pagination'] = $list['pagination'];

		$this->load->view('HongQiao/restaurantList', $this->outData);
	}

	/**
	 * 餐厅地址列表
	 *
	 */
	public function restaurantAddressList(){
		$where = '';

		$list = $this->HongQiaoModel->getRestaurantAddressList($where, $this->p);

		$this->outData['pageTitle'] = '餐厅地址列表';
		$this->outData['list'] = $list['list'];
		$this->outData['pagination'] = $list['pagination'];

		$this->load->view('HongQiao/restaurantAddressList', $this->outData);
	}

	/**
	 * 删除餐厅地址
	 *
	 */
	public function delMall(){

		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$mallId = $this->input->post('mallId');

		$delRes = $this->HongQiaoModel->delMall($mallId, 4);

		if ($delRes) {
			$this->ajaxRes = array(
					'status' => 0,
				);
		}else{
			$this->ajaxRes['msg'] = '数据删除失败';
		}

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 编辑餐厅
	 *
	 */
	public function editRestaurant(){
		$id = strDecrypt($this->input->get('id'));

		if (!$this->HongQiaoModel->checkEditRestaurant($id)) {
			$outData = array(
					'errLang' => $this->lang->line('ERR_AUTH_EDIT_BRAND'),
					'url'     => site_url('HongQiao/restaurantList'),
				);	
			$this->load->view('Public/error', $outData);
		}

		if ($this->input->is_ajax_request()) return $this->_editRestaurantForm($id); 

		$this->outData['pageTitle'] = '编辑餐厅';

		$this->outData['detail'] = $this->HongQiaoModel->getRestaurantDetail($id);

		$this->outData['pic'] = json_decode($this->outData['detail']->pic, true);

		if (!empty($this->outData['detail']->image)) {
			$this->outData['pic'][] = preg_replace('/_s/', '_b', $this->outData['detail']->image);
		}

		$this->load->view('HongQiao/editRestaurant', $this->outData);
	}

	/**
	 * 编辑餐厅
	 *
	 */
	public function editRestaurantAddress(){
		$id = strDecrypt($this->input->get('id'));

		if (!$this->HongQiaoModel->checkEditMall($id, 4)) {
			$outData = array(
					'errLang' => $this->lang->line('ERR_AUTH_EDIT_BRAND'),
					'url'     => site_url('HongQiao/restaurantAddressList'),
				);	
			$this->load->view('Public/error', $outData);
		}

		if ($this->input->is_ajax_request()) return $this->_editRestaurantAddressForm($id); 

		$this->outData['pageTitle'] = '编辑餐厅地址';

		$this->outData['detail'] = $this->HongQiaoModel->getMallDetail($id, 4);

		$this->outData['category'] = $this->HongQiaoModel->getMallCate(4);

		$this->load->view('HongQiao/editRestaurantAddress', $this->outData);
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
		$pathType = $this->input->get('pathType');

		if ($pathType == 'mall2w') {
			$uploadConf['upload_path'] = './uploadtemp/mall2w/';
			$uploadConf['file_name']     = 'brand_'.md5(currentTime('MICROTIME'));
			$uploadConf['relation_path'] = '/alidata1/apps/uploadtemp_app_admin_v400/mall2w/';
		}else{
			$uploadConf['upload_path']   = './uploadtemp/canting/';
			$uploadConf['file_name']     = 'mall_'.md5(currentTime('MICROTIME'));
		}

		$this->load->library('upload');

		$this->upload->initialize($uploadConf);

		if (!$this->upload->do_upload($filesName)){
			$this->ajaxRes['msg'] = $this->upload->display_errors();
		}else{
			$this->ajaxRes = array(
					'status' => 0,
					'url'    => config_item('shop_image_url').$this->upload->data('relative_path'),
					'path'   => $this->upload->data('relative_path'),
				);
			if ($pathType == 'mall2w') {
				$this->ajaxRes['url'] = config_item('image_url').$this->upload->data('relative_path');
			}
		}

		echo json_encode($this->ajaxRes);exit;

	}

	/**
	 * 获取新虹桥列表查询条件
	 * @param array $where 查询条件
	 */
	public function _getHongQiaoWhere($where = array()){

		return ' WHERE '.implode(' AND ', $where);
	}

	/**
	 * 编辑餐厅表单
	 * @param string $id 餐厅id
	 */
	public function _editRestaurantForm($id = false){

		$reqData = $this->input->post();

		$update = array();

		if (isset($reqData['path']) && !empty($reqData['path'])) {
			$update['path'] = $reqData['path'];
		}

		if (isset($reqData['picPath']) && !empty($reqData['picPath'])) {
			$update['picPath'] = $reqData['picPath'];
		}

		$update['update'] = isset($reqData['hasMake']) && $reqData['hasMake'] ? 2 : 1;
		$update['update_time'] = currentTime();
		$update['user'] = $this->userInfo->user_id;

		$where = array(
				'id' => $id,
			);



		$updateRes = $this->db->where($where)->update(tname('restaurant'), $update);

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
	 * 编辑景点表单
	 * @param string $id 景点id
	 */
	public function _editTravel($id = false){

		$reqData = $this->input->post();

		$update = array();

		if (isset($reqData['path']) && !empty($reqData['path'])) {
			$update['path'] = $reqData['path'];
		}

		if (isset($reqData['picPath']) && !empty($reqData['picPath'])) {
			$update['picPath'] = $reqData['picPath'];
		}

		$update['update'] = isset($reqData['hasMake']) && $reqData['hasMake'] ? 2 : 1;
		$update['update_time'] = currentTime();
		$update['user'] = $this->userInfo->user_id;

		$where = array(
				'id' => $id,
			);

		$updateRes = $this->db->where($where)->update(tname('travel'), $update);

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
	 * 编辑电影院表单
	 * @param string $id 电影院id
	 */
	public function _editCinema($id = false){

		$reqData = $this->input->post();

		$update = array();

		if (isset($reqData['path']) && !empty($reqData['path'])) {
			$update['path'] = $reqData['path'];
		}

		if (isset($reqData['picPath']) && !empty($reqData['picPath'])) {
			$update['picPath'] = $reqData['picPath'];
		}

		$update['update'] = isset($reqData['hasMake']) && $reqData['hasMake'] ? 2 : 1;
		$update['update_time'] = currentTime();
		$update['user'] = $this->userInfo->user_id;

		$where = array(
				'id' => $id,
			);

		$updateRes = $this->db->where($where)->update(tname('cinema'), $update);

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
	 * 编辑餐厅地址
	 * @param string $id 餐厅id
	 */
	public function _editRestaurantAddressForm($id = false){

		$reqData = $this->input->post();
		
		if ($id != $reqData['mallId']) jsonReturn($this->ajaxRes);

		$updateRes = $this->HongQiaoModel->editRestaurantAddress($reqData);

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
	 * 
	 * @param string $brandId 品牌id
	 */
	public function _editBrandShopFrom($brandId = false){
		$reqData = $this->input->post();

		$update = array();

		if (isset($reqData['path']) && !empty($reqData['path'])) {
			$update['path'] = $reqData['path'];
		}

		if (isset($reqData['picPath']) && !empty($reqData['picPath'])) {
			$update['picPath'] = $reqData['picPath'];
		}

		$update['update'] = isset($reqData['hasMake']) && $reqData['hasMake'] ? 2 : 1;
		$update['update_time'] = currentTime();
		$update['user'] = $this->userInfo->user_id;

		$where = array(
				'brand_id' => $brandId,
			);



		$updateRes = $this->db->where($where)->update(tname('brand_rel'), $update);

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
	 * 编辑景点内容
	 * @param string $id 
	 */
	public function _editTravelForm($id = false){
		$reqData = $this->input->post();
		
		if ($id != $reqData['mallId']) jsonReturn($this->ajaxRes);

		$where = array(
				'id' => $reqData['mallId'],
				'level' => 6,
			);

		$update = array(
				'name_zh'        => $reqData['mallName'],
				'address'        => $reqData['address'],
				'tb_district_id' => $reqData['districtId'],
				'longitude'      => $reqData['lng'],
				'latitude'       => $reqData['lat'],
				'tel'            => $reqData['tel'],
				'update_time'    => currentTime(),
			);

		$updateRes = $this->HongQiaoModel->editMallAddress($update, $where);

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
	 * 编辑电影院内容
	 * @param string $id
	 */
	public function _editCinemaAddress($id = false){
		$reqData = $this->input->post();
		
		if ($id != $reqData['mallId']) jsonReturn($this->ajaxRes);

		$where = array(
				'id' => $reqData['mallId'],
				'level' => 5,
			);

		$update = array(
				'name_zh'        => $reqData['mallName'],
				'address'        => $reqData['address'],
				'longitude'      => $reqData['lng'],
				'latitude'       => $reqData['lat'],
				'tel'            => $reqData['tel'],
				'update_time'    => currentTime(),
			);

		$updateRes = $this->HongQiaoModel->editMallAddress($update, $where);

		if ($updateRes) {
			$this->ajaxRes = array(
					'status' => 0,
				);
		}else{
			$this->ajaxRes['msg'] = $this->lang->line('ERR_UPDATE_FAILURE');
		}

		jsonReturn($this->ajaxRes);
	}
}
