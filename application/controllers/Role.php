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

	public function rolelist(){
		$this->outData['pageTitle'] = $this->lang->line('TEXT_TITLE_ROLELIST');
		$this->outData['roleList'] = $this->RoleModel->getRoleList();
		$this->outData['pagination'] = $this->RoleModel->setPagination();
		$this->load->view('Role/rolelist', $this->outData);
	}

	public function index(){

	}
}
