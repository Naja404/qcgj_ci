<?php
/**
 * 用户模型
 */

class UserModel extends CI_Model {
	
	// 返回数据
	public $returnRes;

	public function __construct(){
		$this->returnRes = array(
							'error' => true, // true=有错误, false=正确
							'msg'   => false, 
							'data'  => array()
						);
	}

	/**
	 * 用户登录
	 * @param array $loginData 登录信息
	 */
	public function login($loginData = array()){
		$login = array(
				'name'   => $loginData['username'],
				'passwd' => md5($loginData['passwd']),
			);
		
		$queryRes = $this->db->get_where(tname('qcgj_role_user'), $login)->first_row();

		if (!isset($queryRes->user_id)) {
			return $this->_return($this->lang->line('ERR_LOGIN_PASSWD'));
		}

		if ((int)$queryRes->status !== 1) {
			return $this->_return($this->lang->line('ERR_LOGIN_STATUS_'.$queryRes->status));
		}

		$this->returnRes = array(
				'error' => false,
				'data'  => $queryRes,
			);

		return $this->returnRes;

	}

	/**
	 * 设置用户登录信息
	 * @param string $sessionSSID 会话id
	 * @param int $expireTime 过期时间
	 */
	public function setSessionInfo($userId = false, $sessionSSID = false, $expireTime = 28800){

		if (!$userId || !$sessionSSID) {
			return false;
		}

		$update = array(
				'session_id' => $sessionSSID,
				'expireTime' => time() + $expireTime,
			);

		$queryRes = $this->db->where(array('user_id' => $userId))
							 ->update(tname('qcgj_role_user'), $update);

		return $queryRes ? true : false;
	}

	/**
	 * 验证登录表单
	 * @param array $verlidationConf 验证配置信息
	 */
	public function verlidationLogin($verlidationConf = array()){
		$this->form_validation->set_rules($verlidationConf);

		if (!$this->form_validation->run()) {
			return validation_errors();
		}

		return true;
	}

	/**
	 * model 返回内容
	 * @param string $msg 返回信息内容
	 * @param array $data 数据内容
	 * @param bool $error 返回状态
	 */
	private function _return($msg = false, $data = array(), $error = true){

		$this->returnRes = array(
					'error' => $error,
					'msg'   => $msg,
					'data'  => $data,
			);

		return $this->returnRes;
	}

}
