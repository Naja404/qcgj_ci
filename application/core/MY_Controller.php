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
		
		$p = (int)$this->input->get('p');

		$this->p = $p <= 0 ? 1 : $p;

		$this->load->model('WebBaseModel');

		if (isset($options['loginStatus']) && $options['loginStatus'] === true) {
			$this->loginStatus = true;
			$this->sideBar = $this->setSideBar();
		}
		
	}

	// 设置菜单栏
	public function setSideBar(){
		return $this->WebBaseModel->getSideBar('838ad7c331df1d06b7cf584385d7fcc7');
	}
}
