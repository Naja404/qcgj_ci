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

		$roleRes = $this->RoleModel->getRoleUserList($this->p);
		$this->outData['brandList'] = $this->RoleModel->getBrandList();

		$this->outData['roleList'] = $roleRes['data']['result'];
		$this->outData['ruleList'] = $this->RoleModel->getRuleList();
		$this->outData['roleSelect'] = $this->RoleModel->getRoleList();

		$this->outData['pagination'] = $this->RoleModel->setPagination(site_url('Role/roleList'), $roleRes['data']['total']);

		$this->load->view('Role/rolelist', $this->outData);
	}

	/**
	 * 获取店铺列表
	 *
	 */
	public function getMallList(){
		$list = $this->RoleModel->getMallList(strDecrypt($this->input->post('brandId')));

		$select = '';

		foreach ($list as $k => $v) {
			$select .= '<option value="'.strEncrypt($v->id).'">'.$v->name_zh.$v->address.'</option>';
		}

		$this->ajaxRes = array(
				'status' => 0,
				'html'   => $select,
			);

		jsonReturn($this->ajaxRes);
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
	 * 添加角色
	 */
	public function addRole(){
		if (!$this->input->is_ajax_request()) {
			jsonReturn($this->ajaxRes);
		}

		$verlidationRes = $this->RoleModel->verlidationAddRole($this->lang->line('ADD_ROLE_VALIDATION'), $this->input->post());

		if ($verlidationRes !== true) {
			$this->ajaxRes['msg'] = $verlidationRes;
			jsonReturn($this->ajaxRes);
		}

		$addRoleRes = $this->RoleModel->addRole($this->input->post());
		
		if ($addRoleRes['error']) {
			$this->ajaxRes['msg'] = $this->lang->line('ERR_ADD_FAILURE');
			jsonReturn($this->ajaxRes);
		}

		$this->ajaxRes = array(
					'status' => 0,
			);

		jsonReturn($this->ajaxRes);

	}

	/**
	 * 添加角色用户
	 *
	 */
	public function addRoleUser(){
		if (!$this->input->is_ajax_request()) {
			jsonReturn($this->ajaxRes);
		}

		$verlidationRes = $this->RoleModel->verlidationAddRoleUser($this->lang->line('ADD_ROLE_USER_VALIDATION'), $this->input->post());

		if ($verlidationRes !== true) {
			$this->ajaxRes['msg'] = $verlidationRes;
			jsonReturn($this->ajaxRes);
		}

		$addRoleUserRes = $this->RoleModel->addRoleUser($this->input->post());
		
		if ($addRoleUserRes['error']) {
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

			$updateRes = $this->RoleModel->updateUser($statusArr);

			if ($updateRes['error']) {
				$this->ajaxRes['msg'] = $updateRes['msg'];
			}else{
				$this->ajaxRes = array(
							'status'     => 0, 
							'html'       => $updateRes['html'],
							'userStatus' => $statusArr['status'] == 1 ? 0 : 1,
							'class'      => $updateRes['class'],
							);
			}

			jsonReturn($this->ajaxRes);
		}
	}

}
