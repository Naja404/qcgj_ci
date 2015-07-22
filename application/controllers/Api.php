<?php

defined('BASEPATH') OR xit('No direct script access allowed');

/**
 * 美食
 */

class Api extends WebBase {

	protected $defaultPage;    		//默认页数
	protected $defaultLimit;   		//默认条数
	protected $defaultSort;    		//默认排序方式
	protected $defaultDistance;    	//默认距离
	protected $appAdminVersion; 	//默认后台版本
	public $reqData;				// request数据

	public function __construct() {

		parent::__construct(array('guest' => true));

		$this->defaultPage = 1;
		$this->defaultLimit = 10;
		$this->defaultSort = 0;
		$this->defaultDistance = 0;
		$this->appAdminVersion = 'app_admin_v400';

		$this->reqData = $this->input->get();

		$this->load->model('ApiModel');
	}

	/**
	 * 大红桥首页内容
	 *
	 */
	public function homePage(){

		$result = array();

		$result['mall'] = $this->ApiModel->getHomePageMall();

		$result['brand'] = $this->ApiModel->getBrandActivity();

		$result['coupon'] = $this->ApiModel->getBrandCouponByUser($reqData['user_id']);

		$result['article'] = $this->ApiModel->getArticle();

		$err = $this->_getErrMsg('0');

		$this->_returnJsonData($err, $result);		//返回json数据		
	}

	/**
	 * 美食列表
	 *
	 */
	public function restaurantList() {

		$pageNum = $this->input->get('pageNum')?$this->input->get('pageNum'):$this->defaultPage;  //页数

		$pageSize = $this->input->get('pageSize')?$this->input->get('pageSize'):$this->defaultLimit;  //每页条数

		$category = $this->input->get('category')?$this->input->get('category'):'';  //分类

		$longitude = $this->input->get('longitude')?$this->input->get('longitude'):0; //当前经度

		$latitude = $this->input->get('latitude')?$this->input->get('latitude'):0;    //当前纬度

		$sortType = $this->input->get('sortType')?$this->input->get('sortType'):$this->defaultSort;    //排序方式

		$distanceType = $this->input->get('distanceType')?$this->input->get('distanceType'):$this->defaultDistance;    //距离类型

		$result = $this->ApiModel->getRestaurantList($pageNum, $pageSize, $category, $longitude, $latitude, $sortType, $distanceType);

		$err = $this->_getErrMsg('0');

		$this->_returnJsonData($err, $result);		//返回json数据		
	}

	/**
	 * 美食详情页
	 *
	 */
	public function restaurantDetail() {

		$id = $this->input->get('id')?$this->input->get('id'):'';  //美食id

		$longitude = $this->input->get('longitude')?$this->input->get('longitude'):0; //当前经度

		$latitude = $this->input->get('latitude')?$this->input->get('latitude'):0;    //当前纬度

		if( empty($id) ) {

			$err = $this->_getErrMsg('1');

			$this->_returnJsonData($err);    //参数缺失
		}

		$result = $this->ApiModel->getRestaurantDetail($id, $longitude, $latitude);  //获取景点详细信息

		$err = $this->_getErrMsg($result['errcode']);

		$this->_returnJsonData($err, $result['data']);	//返回json数据				
	}


	/**
	 * 影院列表
	 *
	 */
	public function cinemaList() {

		$pageNum = $this->input->get('pageNum')?$this->input->get('pageNum'):$this->defaultPage;  //页数

		$pageSize = $this->input->get('pageSize')?$this->input->get('pageSize'):$this->defaultLimit;  //每页条数

		$longitude = $this->input->get('longitude')?$this->input->get('longitude'):0; //当前经度

		$latitude = $this->input->get('latitude')?$this->input->get('latitude'):0;    //当前纬度

		$sortType = $this->input->get('sortType')?$this->input->get('sortType'):$this->defaultSort;    //排序方式

		$distanceType = $this->input->get('distanceType')?$this->input->get('distanceType'):$this->defaultDistance;    //距离类型

		$result = $this->ApiModel->getCinemaList($pageNum, $pageSize, $longitude, $latitude, $sortType, $distanceType);

		$err = $this->_getErrMsg('0');

		$this->_returnJsonData($err, $result);		//返回json数据
	}

	/**
	 * 影院详情
	 * @param int $id 影院id
	 *
	 */
	public function cinemaDetail() {

		$id = $this->input->get('id')?$this->input->get('id'):'';  //影院id

		$longitude = $this->input->get('longitude')?$this->input->get('longitude'):0; //当前经度

		$latitude = $this->input->get('latitude')?$this->input->get('latitude'):0;    //当前纬度

		if( empty($id) ) {

			$err = $this->_getErrMsg('1');

			$this->_returnJsonData($err);    //参数缺失
		}

		$result = $this->ApiModel->getCinemaDetail($id, $longitude, $latitude);  //获取景点详细信息

		$err = $this->_getErrMsg($result['errcode']);

		$this->_returnJsonData($err, $result['data']);	//返回json数据				
	}


	/**
	 * 景点列表
	 *
	 */
	public function travelList() {

		$pageNum = $this->input->get('pageNum')?$this->input->get('pageNum'):$this->defaultPage;  //页数

		$pageSize = $this->input->get('pageSize')?$this->input->get('pageSize'):$this->defaultLimit;  //每页条数

		$category = $this->input->get('category')?$this->input->get('category'):'';  //分类

		$longitude = $this->input->get('longitude')?$this->input->get('longitude'):0; //当前经度

		$latitude = $this->input->get('latitude')?$this->input->get('latitude'):0;    //当前纬度

		$sortType = $this->input->get('sortType')?$this->input->get('sortType'):$this->defaultSort;    //排序方式

		$distanceType = $this->input->get('distanceType')?$this->input->get('distanceType'):$this->defaultDistance;    //距离类型

		$result = $this->ApiModel->getTravelList($pageNum, $pageSize, $category, $longitude, $latitude, $sortType, $distanceType);

		$err = $this->_getErrMsg('0');

		$this->_returnJsonData($err, $result);		//返回json数据
	}


	/**
	 * 景点详情
	 * @param int $id 景点id
	 *
	 */
	public function travelDetail() {

		$id = $this->input->get('id')?$this->input->get('id'):'';  //景点id

		$longitude = $this->input->get('longitude')?$this->input->get('longitude'):0; //当前经度

		$latitude = $this->input->get('latitude')?$this->input->get('latitude'):0;    //当前纬度

		if( empty($id) ) {

			$err = $this->_getErrMsg('1');

			$this->_returnJsonData($err);    //参数缺失
		}

		$result = $this->ApiModel->getTravelDetail($id, $longitude, $latitude);  //获取景点详细信息

		$err = $this->_getErrMsg($result['errcode']);

		$this->_returnJsonData($err, $result['data']);	//返回json数据	
	}

	/**
	 * 返回错误信息
	 * @param int $errcode 错误code
	 *
	 */
	private function _getErrMsg( $errcode ) {

		$err['errcode'] = $errcode;
		$err['errmsg'] = '';

		switch( $errcode ) {
			case '0':
				$errmsg = '';
				break;
			case '1':
				$errmsg = $this->lang->line('ERR_PARAM');  //参数缺失
				break;
			case '2':
				$errmsg = $this->lang->line('ERR_PARAM_TYPE');  //类型错误
				break;
			case '3':
				$errmsg = 'No record';			//没记录
				break;
		}

		$err['errmsg'] = $errmsg;

		return $err;
	}


	/**
	 * 返回json数据
	 * @param string $errcode 错误code
	 * @param string $errmsg  错误消息
	 * @param array $data 数据
	 *
	 */
	private function _returnJsonData( $err = array(), $data = array() ) {

		 $return = array(
				'errcode' => $err['errcode'],
				'errmsg'   => $err['errmsg'], 
				'datas'  => $data
			);

		 jsonReturn($return);
	}
}
