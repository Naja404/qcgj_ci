<?php
defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 用户模块
 */

class User extends WebBase {
	//  视图输出内容
	public $outData;

	public function __construct(){
		parent::__construct();
		$this->load->model('UserModel');
		$this->lang->load('login');
	}

	/**
	 * 用户登录
	 *
	 */
	public function login(){
		if (!$this->input->is_ajax_request()) {
			$this->load->view('User/login');
		}
	}
}
