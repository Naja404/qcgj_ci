<?php
defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 用户模块
 */

class Index extends WebBase {
	// 输出数据
	public $outData;

	public function __construct(){
		parent::__construct();
		$this->load->model('IndexModel');
		$this->lang->load('index');
	}

	/**
	 * 首页dashbord
	 *
	 */
	public function index(){
		$this->outData['pageTitle'] = $this->lang->line('TEXT_INDEX_TITLE');
		$this->load->view('Index/index', $this->outData);
	}
}
