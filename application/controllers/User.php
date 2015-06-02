<?php
defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 用户模块
 */

class User extends WebBase {
	//  视图输出内容 默认登录密码 202cb962ac59075b964b07152d234b70 = 123
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

		if ($this->input->is_ajax_request()) {

			$verlidationRes = $this->UserModel->verlidationLogin($this->lang->line('LOGIN_VALIDATION'));
			
			if ($verlidationRes !== true) {
				$this->ajaxRes['msg'] = $verlidationRes;
				jsonReturn($this->ajaxRes);
			}

			$loginRes = $this->UserModel->login($this->input->post());

			if ($loginRes['error']) {
				$this->ajaxRes['msg'] = $loginRes['msg'];
			}else{
				
				if ($this->input->post('remeberLogin')) {
					$loginRes['data']->remeberLogin = true;
				}

				$returnRes = $this->_setLogin($loginRes['data']);

				if (!$returnRes) jsonReturn($this->ajaxRes);

				$this->ajaxRes = array(
						'status' => 0,
					);
			}
			// TODO 登录成功后跳转
			jsonReturn($this->ajaxRes);
		}

		$this->outData['pageTitle'] = $this->lang->line('TEXT_TITLE_USERLOGIN');
		$this->load->view('User/login', $this->outData);

	}

	/**
	 * 设置登录信息内容
	 * @param array $loginData 登录信息
	 */
	private function _setLogin($loginData = array()){

		if (!isset($loginData->user_id) || $loginData->status != 1) {
			unset($_COOKIE);
			return false;
		}
		
		$sessionId = getSessionId();

		$expireTime = 3600*24;
		if (isset($loginData->remeberLogin) && $loginData->remeberLogin === true) {
			$expireTime = 3600*24*7;
		}
		
		$returnRes = $this->UserModel->setSessionInfo($loginData->user_id, $sessionId, $expireTime);

		if (!$returnRes) return false;

		$this->input->set_cookie('sessionId', $sessionId, $expireTime);

		return true;

	}
}
