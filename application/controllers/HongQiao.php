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
		$this->lang->load('hongqiao');
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

		$list = $this->HongQiaoModel->getCinemaList($this->p);

		$this->outData['pageTitle'] = '电影院列表';

		$this->outData['list'] = $list['list'];

		$this->load->view('HongQiao/cinemaList', $this->outData);

	}

	/**
	 * 电影院地址列表
	 *
	 */
	public function cinemaAddressList(){

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

		$where = " WHERE name != '' ";

		$list = $this->HongQiaoModel->getTravelList($where, $this->p);
		$this->outData['pageTitle'] = '景点列表';
		$this->outData['list'] = $list['list'];
		$this->outData['pagination'] = $list['pagination'];

		$this->load->view('HongQiao/travelList', $this->outData);
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

		$where = ' WHERE mark = 1 ';

		$list = $this->HongQiaoModel->getRestaurantList($where, $this->p);

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
		
		$uploadConf['upload_path']   = './uploadtemp/canting/';
		$uploadConf['file_name']     = 'mall_'.md5(currentTime('MICROTIME'));

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
		}

		echo json_encode($this->ajaxRes);exit;

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
}
