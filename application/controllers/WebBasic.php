<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WebBasic extends CI_Controller {

	// ajax返回值
	public $ajaxRes;

	// 基类初始化
	public function __construct(){
		$this->ajaxRes = array(
					'status' => 1,
					'msg'    => 'ERR_PARAM',
			);
	}

	public function setSideBar(){
		return true;
	}
}
