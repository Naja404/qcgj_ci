<?php
defined('BASEPATH') OR xit('No direct script access allowed');

class Role extends WebBase {
	//  视图输出内容
	public $outData;

	public function __construct(){
		parent::__construct();
		
		$this->load->model('RoleModel');
		$this->data['currentModule'] = __CLASS__;
	}

	public function rolelist(){
		$this->data['roleList'] = $this->RoleModel->getRoleList();
		$this->data['pagination'] = $this->RoleModel->setPagination();
		$this->load->view('Role/rolelist', $this->data);
	}

	public function index(){

	}
}
