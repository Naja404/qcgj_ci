<?php

class MY_Controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}
}

class WebBase extends MY_Controller {
	// ajax返回值
	public $ajaxRes;

	// 菜单数组
	public $sideBar;

	// 分页参数
	public $p;

	// 登录状态
	public $loginStatus = false;

	// 用户信息
	public $userInfo;

	/**
	 * 初始化
	 * @param array $options 配置参数内容
	 */
	public function __construct($options = array()){
		parent::__construct();

		$this->ajaxRes = array(
					'status' => 1,
					'msg'    => $this->lang->line('ERR_PARAM'),
			);

		$this->load->model('WebBaseModel');

		$this->setBaseInfo($options);
		
	}

	/**
	 * 基础设置
	 * @param array $options 配置参数内容
	 */
	public function setBaseInfo($options = array()){

		if (!isset($options['guest']) || !$options['guest']) {

			$authInfo = array(
					'sessionSSID' => strDecrypt($this->input->cookie('sessionSSID')),
					'sessionUser' => strDecrypt($this->input->cookie('sessionUser')),
				);

			$authRes = $this->WebBaseModel->checkAuth($authInfo);

			if ($authRes['error']) {
				
				if ($authRes['data']['url']) {
					$outData = array(
							'errLang' => $authRes['msg'],
							'url'     => $authRes['data']['url'],
						);
					$this->load->view('Public/error', $outData);
				}

				redirect(site_url('User/login'));
			}

			$this->userInfo = $authRes['data'];
			$this->userID = $this->userInfo->user_id;
			$this->loginStatus = true;
		}

		$p = (int)$this->input->get('p');

		$this->p = $p <= 0 ? 1 : $p;

		if (isset($this->loginStatus) && $this->loginStatus === true) {
			$this->sideBar = $this->setSideBar();
		}
	}

	// 设置菜单栏
	public function setSideBar(){
		return $this->WebBaseModel->getSideBar($this->userID);
	}
}
