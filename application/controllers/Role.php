<?php
defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 角色管理
 */

class Role extends WebBase {
	//  视图输出内容
	public $outData;

	public function __construct(){
		parent::__construct();
		
		$this->load->model('RoleModel');
		$this->outData['currentModule'] = __CLASS__;
	}

	/**
	 * 角色列表
	 */
	public function roleList(){
		
		$this->outData['pageTitle'] = $this->lang->line('TEXT_TITLE_ROLELIST');

		$roleRes = $this->RoleModel->getRoleList($this->p);

		$this->outData['roleList'] = $roleRes['data']['result'];
		$this->outData['ruleList'] = $this->RoleModel->getRuleList();

		$this->outData['pagination'] = $this->RoleModel->setPagination(site_url('Role/roleList'), $roleRes['data']['total']);

		$this->load->view('Role/rolelist', $this->outData);
	}

	/**
	 * 添加权限规则
	 */
	public function addRule(){
		if (!$this->input->is_ajax_request()) {
			jsonReturn($this->ajaxRes);
		}

		$verlidationRes = $this->RoleModel->verlidationAddRule($this->lang->line('ADD_RULE_VALIDATION'));

		if ($verlidationRes !== true) {
			$this->ajaxRes['msg'] = $verlidationRes;
			jsonReturn($this->ajaxRes);
		}

		if (!in_array($this->input->post('type'), array(1, 2))) {
			$this->ajaxRes['msg'] = $this->lang->line('ERR_ROLE_TYPE');
			jsonReturn($this->ajaxRes);
		}

		$addRuleRes = $this->RoleModel->addRule($this->input->post());

		if ($addRuleRes['error']) {
			$this->ajaxRes['msg'] = $this->lang->line('ERR_ADD_FAILURE');
			jsonReturn($this->ajaxRes);
		}

		$this->ajaxRes = array(
					'status' => 0,
			);

		jsonReturn($this->ajaxRes);
	}

	/**
	 * 更新用户内容
	 *
	 */
	public function updateUser(){
		if (!$this->input->is_ajax_request()) {
			jsonReturn($this->ajaxRes);
		}

		if ($this->input->post('type') == 'userStatus') {
			$statusArr = array(
					'user_id' => $this->input->post('user_id'),
					'status'  => $this->input->post('status'),
				);
			$this->RoleModel->updateUser($statusArr);
		}
	}

}
