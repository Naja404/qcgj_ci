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
		$this->lang->load('approve');
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
					'spanDiv' => sprintf(config_item('APPROVE_A_DIV_'.$status['status']), $reqData['commentId'], $status['status']),
				);
		}

		jsonReturn($this->ajaxRes);
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
