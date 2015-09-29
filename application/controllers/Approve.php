<?php
defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 审核模块
 */

class Approve extends WebBase {

	public $outData;

	public function __construct(){
		
		parent::__construct();

		$this->load->model('ApproveModel');
		$this->load->model('DiscountModel');
		$this->lang->load('approve');
	}

	/**
	 * 折扣审核
	 *
	 */
	public function discount(){
		$this->outData['pageTitle'] = $this->lang->line('TITLE_DISCOUNT_LIST');

		$where = $this->_getDiscountWhere();

		$discountList = $this->ApproveModel->getDiscountList($this->p, $where);

		$this->outData['list'] = $discountList['list'];

		$this->outData['page'] = $discountList['page'];

		$this->outData['brandCate'] = $this->DiscountModel->getBrandCate();

		$this->outData['disType'] = config_item('DISCOUNT_TYPE');

		$this->outData['discountStatus'] = config_item('APPROVE_STATUS');

		$this->load->view('Approve/discountList', $this->outData);
	}

	/**
	 * 更新折扣审核状态
	 *
	 */
	public function upDiscount(){

		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$reqData = $this->input->post();

		$status = $this->ApproveModel->upDiscountStatus($reqData);

		if ($status['error'] === false) {
			$this->ajaxRes['msg'] = $this->lang->line('ERR_DISCOUNT_UPDATE_FAIL');
		}else{
			$this->ajaxRes = array(
					'status'  => 0,
					'tdDiv'   => config_item('APPROVE_TD_DIV_'.$reqData['status']),
					'spanDiv' => sprintf(config_item('APPROVE_A_DIV_'.$status['status']), 'upDiscount', $reqData['discountId'], $status['status']),
				);
		}

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 删除折扣
	 *
	 */
	public function delDiscount(){
		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$reqData = $this->input->post();

		$status = $this->ApproveModel->delDiscount($reqData);

		if ($status['error'] === false) {
			$this->ajaxRes['msg'] = $this->lang->line('ERR_DISCOUNT_DEL_FAIL');
		}else{
			$this->ajaxRes = array(
					'status'  => 0,
				);
		}

		jsonReturn($this->ajaxRes);	
	}

	/**
	 * 评论审核列表
	 *
	 */
	public function comment(){

		$this->outData['pageTitle'] = $this->lang->line('TITLE_COMMENT_LIST');

		$where = $this->_getCommentWhere();

		$commentList = $this->ApproveModel->getCommentList($this->p, $where);

		$this->outData['list'] = $commentList['list'];

		$this->outData['page'] = $commentList['page'];

		$this->outData['commentType'] = config_item('APPROVE_COMMENT_TYPE');

		$this->outData['commentStatus'] = config_item('APPROVE_STATUS');

		$this->load->view('Approve/commentList', $this->outData);
	}

	/**
	 * 预览评论审核图片
	 *
	 */
	public function preComment(){
		$commentId = $this->input->get('commentId');

		$image = $this->ApproveModel->getCommentImage($commentId);

		foreach ($image as $k => $v) {
			echo '<img src="'.config_item('image_url').$v.'" width="500px"><br><br>';
		}

		exit();
	}

	/**
	 * 编辑评论审核状态
	 *
	 */
	public function upComment(){

		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$reqData = $this->input->post();

		$status = $this->ApproveModel->upCommentStatus($reqData);

		if ($status['error'] === false) {
			$this->ajaxRes['msg'] = $this->lang->line('ERR_COMMENT_UPDATE_FAIL');
		}else{
			$this->ajaxRes = array(
					'status'  => 0,
					'tdDiv'   => config_item('APPROVE_TD_DIV_'.$reqData['status']),
					'spanDiv' => sprintf(config_item('APPROVE_A_DIV_'.$status['status']), 'upComment', $reqData['commentId'], $status['status']),
				);
		}

		jsonReturn($this->ajaxRes);
	}
 
 	/**
 	 * 删除评论
 	 *
 	 */
 	public function delComment(){
		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$reqData = $this->input->post();

		$status = $this->ApproveModel->delComment($reqData['commentId']);

		if ($status === false) {
			$this->ajaxRes['msg'] = $this->lang->line('ERR_COMMENT_DEL_FAIL');
		}else{
			$this->ajaxRes = array(
					'status'  => 0,
				);
		}

		jsonReturn($this->ajaxRes);
 	}

	/**
	 * 审核洋码头图片
	 *
	 */
	public function ymtPic(){
		$this->outData['pageTitle'] = $this->lang->line('TITLE_YMT_LIST');

		$where = $this->_getYmtWhere();

		$ymtList = $this->ApproveModel->getYmtList($this->p, $where);

		$this->outData['list'] = $ymtList['list'];

		$this->outData['page'] = $ymtList['page'];

		$this->outData['ymtStatus'] = array(
				'0' => '待审核',
				'1' => '已通过',
				'2' => '不通过',
			);
		
		$this->outData['provinceList'] = $this->ApproveModel->getProvinceList();

		$this->load->view('Approve/ymtList', $this->outData);
	}

	/**
	 * 更新洋码头图片状态
	 *
	 */
	public function upYmtPic(){
		if (!$this->input->is_ajax_request()) jsonReturn($this->ajaxRes);

		$reqData = $this->input->post();

		$status = $this->ApproveModel->upYmtPicStatus($reqData);

		if ($status['error'] === false) {
			$this->ajaxRes['msg'] = $this->lang->line('ERR_YMT_UPDATE_FAIL');
		}else{
			$this->ajaxRes = array(
					'status'  => 0,
					'tdDiv'   => config_item('APPROVE_TD_DIV_'.$reqData['status']),
					'spanDiv' => sprintf(config_item('APPROVE_A_DIV_'.$status['status']), 'upYmtPic', $reqData['ymtId'], $status['status']),
				);
		}

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 编辑洋码头图片
	 *
	 */
	public function editYmt(){
		
		$ymtId = $this->input->get('yid');

		if ($this->input->is_ajax_request()) {
			
			$reqData = $this->input->post();

			if (isset($reqData['origPicPath']) && !empty($reqData['origPicPath'])) {
				
				$update = array(
						'orig_pic_url' => $reqData['origPicPath'],
						'update_time'  => currentTime(),
					);

				$this->ApproveModel->editYmt($update, $ymtId);

				$this->ajaxRes = array(
						'status' => 0,
					);
			}

			jsonReturn($this->ajaxRes);
		}

		$this->outData['pageTitle'] = $this->lang->line('TITLE_EDIT_YMT');

		$this->outData['detail'] = $this->ApproveModel->getYmtDetail($ymtId);

		$this->load->view('Approve/editYmt', $this->outData);
	}

	/**
	 * 上传图片
	 *
	 */
	public function uploadPic(){
		if ($this->input->method() != 'post') {
			echo json_encode($this->ajaxRes);exit;
		}

		$uploadConf = config_item('FILE_UPLOAD');
		$filesName = $this->input->get('filesName');
		
		if ($filesName == 'origPic') {
			$uploadConf['upload_path']   = './uploadtemp/ymatou/original/';
			$uploadConf['file_name']     = md5(currentTime('MICROTIME'));
			$uploadConf['relation_path'] = '/alidata1/apps/uploadtemp_app_admin_v400/ymatou/original/';
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
	 * 获取评论审核查询条件内容
	 *
	 */
	private function _getCommentWhere(){
		
		$reqData = $this->input->get();
		$where = array();

		if (isset($reqData['type']) && !empty($reqData['type'])) $where[] = " a.type = '".(int)$reqData['type']."' ";

		if (isset($reqData['status']) && in_array($reqData['status'], array('0', '1', '2'))) $where[] = " a.status = '".(int)$reqData['status']."' ";

		if (isset($reqData['grade']) && !empty($reqData['grade'])) $where[] = " a.grade = '".(int)$reqData['grade']."' ";

		if (isset($reqData['mobile']) && !empty($reqData['mobile'])) $where[] = " b.mobile LIKE '%".(int)$reqData['mobile']."%' ";

		if (isset($reqData['shop']) && !empty($reqData['shop'])) $where[] = " d.name_zh LIKE '%".addslashes($reqData['shop'])."%' ";

		if (isset($reqData['content']) && !empty($reqData['content'])) $where[] = " a.content LIKE '%".addslashes($reqData['content'])."%' ";

		if (isset($reqData['pubTime']) && !empty($reqData['pubTime'])){
			
			$pubTime = $this->_formatDate($reqData['pubTime']);

			$where[] = " LEFT(a.create_time, 10) BETWEEN '".$pubTime[0]."' AND '".$pubTime[1]."' ";
		} 

		if (isset($reqData['approveTime']) && !empty($reqData['approveTime'])){
			$approveTime = $this->_formatDate($reqData['approveTime']);

			$where[] = " LEFT(a.approve_time, 10) BETWEEN '".$approveTime[0]."' AND '".$approveTime[1]."' ";
		} 

		$whereStr = NULL;

		if (count($where) > 0) $whereStr = " WHERE ".implode(" AND ", $where);

		return $whereStr;

	}

	/**
	 * 获取洋码头查询条件
	 *
	 */
	private function _getYmtWhere(){
		$reqData = $this->input->get();
		$where = array();

		if (isset($reqData['status']) && in_array($reqData['status'], array('0', '1', '2'))) $where[] = " a.status = '".(int)$reqData['status']."' ";

		if (isset($reqData['province']) && !empty($reqData['province'])) $where[] = " a.tb_province_id = '".(int)$reqData['province']."' ";
		
		if (isset($reqData['name']) && !empty($reqData['name'])) $where[] = " a.name = '".addslashes($reqData['name'])."' ";

		if (isset($reqData['no']) && !empty($reqData['no'])) $where[] = " a.no = '".addslashes($reqData['no'])."' ";

		if (isset($reqData['slogan']) && !empty($reqData['slogan'])) $where[] = " a.slogan = '".addslashes($reqData['slogan'])."' ";

		if (isset($reqData['createTime']) && !empty($reqData['createTime'])){
			
			$createTime = $this->_formatDate($reqData['createTime']);

			$where[] = " LEFT(a.create_time, 10) BETWEEN '".$createTime[0]."' AND '".$createTime[1]."' ";
		} 

		if (isset($reqData['updateTime']) && !empty($reqData['updateTime'])){
			$updateTime = $this->_formatDate($reqData['updateTime']);

			$where[] = " LEFT(a.update_time, 10) BETWEEN '".$updateTime[0]."' AND '".$updateTime[1]."' ";
		} 

		$whereStr = NULL;

		if (count($where) > 0) $whereStr = " WHERE ".implode(" AND ", $where);

		return $whereStr;

	}

	/**
	 * 获取折扣审核查询条件
	 *
	 */
	private function _getDiscountWhere(){
		$where = array();

		$where[] = " audit = 0 AND is_delete = 0 ";
		$reqData = $this->input->get();

		if (isset($reqData['title']) && !empty($reqData['title'])) $where[] = " name_zh LIKE '%".addslashes($reqData['title'])."%' ";

		if (isset($reqData['type']) && in_array($reqData['type'], array(1, 2, 3, 4, 6, 7, 8))) $where[] = " type = '".(int)$reqData['type']."' ";

		if (isset($reqData['brand']) && !empty($reqData['brand'])) $where[] = " (brand_name_en LIKE '%".addslashes($reqData['brand'])."%' OR brand_name_zh LIKE '%".addslashes($reqData['brand'])."%') ";

		if (isset($reqData['category']) && !empty($reqData['category'])) $where[] = " tb_category_id = '".addslashes($reqData['category'])."' ";

		if (isset($reqData['expStat']) && in_array($reqData['expStat'], array('normal', 'over'))) $where[] = " LEFT(end_date, 10) >= '".date('Y-m-d')."' ";

		$whereStr = " WHERE ".implode(" AND ", $where);

		return $whereStr;
	}

	/**
	 * 格式化日期
	 * @param string $DateTime 2015/09/14 - 2015/09/14
	 */
	private function _formatDate($DateTime = false){

		$date = explode('-', $DateTime);

		$returnDate = array();

		foreach ($date as $k => $v) {
			array_push($returnDate, date('Y-m-d', strtotime($v)));
		}

		return $returnDate;
	}

}
