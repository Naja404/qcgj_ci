<?php
defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 用户模块
 */

class User extends WebBase {
	//  视图输出内容 默认登录密码 202cb962ac59075b964b07152d234b70 = 123
	public $outData;

	public function __construct(){
		
		$options = array('guest' => true);
		parent::__construct($options);

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
						'href' => site_url('Index'),
					);
			}

			jsonReturn($this->ajaxRes);
		}

		$this->input->clearCookie();
		$this->outData['pageTitle'] = $this->lang->line('TEXT_TITLE_USERLOGIN');
		$this->load->view('User/login', $this->outData);

	}

	/**
	 * 注销
	 *
	 */
	public function logout(){
		
		$userID = strDecrypt($this->input->cookie('sessionUser'));

		$this->input->clearCookie();

		$this->cache->delete(config_item('USER_CACHE.LOGIN').$userID);
		$this->cache->delete(config_item('USER_CACHE.MENU').$userID);
		$this->cache->delete(config_item('USER_CACHE.RULE').$userID);

		redirect(site_url('User/login'));
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
		
		$sessionSSID = makeUUID();

		$expireTime = 3600*24;
		if (isset($loginData->remeberLogin) && $loginData->remeberLogin === true) {
			$expireTime = 3600*24*7;
		}
		
		$returnRes = $this->UserModel->setSessionInfo($loginData->user_id, $sessionSSID, $expireTime);

		if (!$returnRes) return false;

		$loginData->sessionSSID = $sessionSSID;
		$this->input->set_cookie('sessionSSID', strEncrypt($sessionSSID), $expireTime);
		$this->input->set_cookie('sessionUser', strEncrypt($loginData->user_id), $expireTime);

		$this->cache->save(config_item('USER_CACHE.LOGIN').$loginData->user_id, $loginData, $expireTime);

		return true;

	}
}
