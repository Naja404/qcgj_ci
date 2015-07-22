<?php
/**
 * 品牌管理模型
 */

class ApiModel extends CI_Model {

	protected $restaurantLevel;
	protected $cinemaLevel;
	protected $travelLevel;
	protected $distance;

	public function __construct(){

		$this->restaurantLevel = 4;   //餐厅
		$this->cinemaLevel = 5;		  //影院
		$this->travelLevel = 6;		  //景点
		$this->distance = array( '1'=>'0-500',
								 '2'=>'500-1000',
								 '3'=>'1000-2000',
								 '4'=>'2000-5000',
								 '5'=>'5000');
		
	}

	/**
	 * 获取大虹桥首页kv
	 *
	 */
	public function getHomePageMall(){

		$queryRes = $this->db->limit(5)
							->where('tb_district_id', 786)
							->where('level', 1)
							->where('pic_url IS NOT NULL')
							->get(tname('mall'))->result();

		$returnRes = array();

		foreach ($queryRes as $k => $v) {
			$tmp = array(
					'name'   => $v->name_zh,
					'image'  => config_item('image_url').$v->pic_url,
					'mallID' => $v->id,
				);

			array_push($returnRes, $tmp);
		}

		return $returnRes;
	}

	/**
	 * 获取文章列表
	 *
	 */
	public function getArticle(){
		$queryRes = $this->db->select("id AS detailID, title, main_pic_url AS image, IF(status = 1, '1', '1') AS type")
				 ->limit(10)
				 ->get(tname('subject'))->result_array();

		$returnRes = array();

		foreach ($queryRes as $k => $v) {
			$v['image'] = config_item('image_url').$v['image'];

			array_push($returnRes, $v);
		}

		return $returnRes;
	}

	/**
	 * 获取品牌折扣优惠信息
	 * @param string $user_id 用户id
	 */
	public function getBrandCouponByUser($user_id = false){

		if (empty($user_id) || !$user_id) {
			return $this->_getDefaultBrandCoupon();
		}

		// $userBrand = $this->db->get_where()->

	}

	/**
	 * 获取默认品牌折扣信息
	 *
	 */
	public function _getDefaultBrandCoupon(){
		
		$discountSql = "SELECT id,name_zh AS title, CONCAT('".config_item('image_url')."', brand_pic_url) AS image, tb_brand_id AS brandId, discount_desc FROM tb_discount WHERE is_delete = 0 AND LEFT(end_date, 10) >= '".date('Y-m-d')."' ORDER BY status DESC LIMIT 2";

		$returnRes = array();

		$discountRes = $this->db->query($discountSql)->result_array();

		foreach ($discountRes as $k => $v) {
			$tmp = array(
					'type'     => 1,
					'image'    => $v['image'],
					'title'    => $v['discount_desc'],
					'discount' => $v['title'],
				);

			array_push($returnRes, $tmp);
		}

		$couponSql = "SELECT id, name as title, cost_price, CONCAT('".config_item('image_url')."', brand_pic_url) AS image FROM ".tname('coupon')." WHERE is_delete = 0 AND on_sale = 1 AND LEFT(end_date, 10) >= '".date('Y-m-d')."' ORDER BY status, end_date DESC LIMIT 4";

		$couponRes = $this->db->query($couponSql)->result_array();

		foreach ($couponRes as $k => $v) {
			$tmp = array(
					'type'     => 2,
					'image'    => $v['image'],
					'title'    => $v['title'],
					'discount' => $v['cost_price'] > 0 ? '￥'.$v['cost_price'] : '免费',
					'is_free'  => $v['cost_price'] > 0 ? 0 : 1,
				);

			array_push($returnRes, $tmp);
		}

		return $returnRes;
	}

	/**
	 * 获取活动数据
	 *
	 */
	public function getBrandActivity(){
		
		$where = array(
				'tb_district_id' => 786,
				'type'           => 2,
				'status'         => 1,
			);

		$queryRes = $this->db->select('title,main_pic_url AS image, description, link_url AS url')
							->order_by('sort_num', 'ASC')
							->limit(4)
							->get_where(tname('online_activity'), $where)->result_array();	

		$returnRes = array();

		foreach ($queryRes as $k => $v) {
			$v['image'] = config_item('image_url').$v['image'];

			array_push($returnRes, $v);
		}

		return $returnRes;
	}

	/**
	 * 获取美食列表
	 * @param string $p 页数
	 * @param string $limit  每页条数
	 * @param string $category  分类
	 * @param string $longitude 当前经度
	 * @param string $latitude  当前纬度
	 * @param string $sort 排序 0 默认按创建时间  1 按距离最近   2 评价最好   3 人气最高  4 人均最低    5 人均最高
	 */
	public function getRestaurantList($p, $limit, $category = '', $longitude = '', $latitude = '', $sortType = 0, $distanceType = 0) {

		$field = "a.id,
				a.name_en,
				a.name_zh,
				a.pic_url,
				a.category_name,
				a.longitude,
				a.latitude,
				a.avg_rating,
				a.avg_price,
				count(b.tb_obj_id) as viewnum,
				getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$where = 'where a.level = '.$this->restaurantLevel;  //

		if( $category != '' ) {

			$where .= " and a.category_name = '".$category."'";
		}

		if( $distanceType > 0 ) {

			$where .= $this->_getDistanceCondition($distanceType, $longitude, $latitude);
		}
		/*
		SELECT a.id, a.name_en, a.name_zh, a.pic_url, a.category_name, a.longitude, a.latitude, a.avg_rating, a.avg_price, 
		COUNT(b.tb_obj_id) AS viewnum, getDistance('','',a.latitude,a.longitude) AS distance FROM tb_mall a 
		LEFT JOIN tb_view b ON a.id = b.tb_obj_id WHERE LEVEL = 1 GROUP BY a.id	ORDER BY viewnum DESC
		*/
		//$sql = "SELECT %s FROM ".tname('mall a')." %s %s ";
		$sql = "Select %s from ".tname('mall a')." LEFT JOIN ".tname('view b')." ON a.id = b.tb_obj_id %s %s ";

		$orderby = $this->_sortByType($sortType);   //根据不同类型排序

		$pagelimit = ' group by a.id '.$orderby."LIMIT ".page( $p, $limit );

		$sql = sprintf($sql, $field, $where, $pagelimit);

		//echo $sql;die;

		$result = $this->db->query($sql)->result_array();

		return $result;
	}

	/**
	 * 获取美食详情
	 * @param string $id 美食ID
	 * @param string $longitude 当前经度
	 * @param string $latitude  当前纬度
	 */
	public function getRestaurantDetail($id, $longitude = '', $latitude = '') {

		$field = "a.id,
				 a.name_en,
				 a.name_zh, 
				 a.pic_url, 
				 a.thumb_url,
				 a.category_name, 
				 a.longitude, 
				 a.latitude,
				 a.open_time,
				 a.close_time,
				 a.avg_rating, 
				 a.avg_price,
				 a.tel,
				 a.level,
				 b.name_zh as mall,
				 getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$sql = "Select ".$field." from ".tname('mall a').
			   " LEFT JOIN ".tname('mall b')." ON a.branch_name = b.name_zh where a.id = '".$id."'";

		$data = $this->db->query($sql)->result_array();

		$result['errcode'] = '0';
		$result['data'] = array();

		if( !empty($data) ) {	

			if( !empty( $data[0]['pic_url'])) {
				$data[0]['pic_url'] = explode(',',$data[0]['pic_url']);
			}

			if( $data[0]['level'] != $this->restaurantLevel ) {
				$result['errcode'] = '2';
			}

			$result['data'] = $data;
		}else {

			$result['errcode'] = '3';
		}

		return $result;
	}


	/**
	 * 获取影院列表
	 * @param string $p 页数
	 * @param string $limit  每页条数
	 * @param string $category  分类
	 * @param string $longitude  当前经度
	 * @param string $latitude  当前纬度
	 * @param string $sort 排序 0 默认按创建时间  1 按距离最近   2 评价最好   3 人气最高  4 人均最低    5 人均最高
	 */
	public function getCinemaList($p, $limit, $longitude = 0, $latitude = 0, $sortType = 0, $distanceType = 0) {

		$field = "a.id,
				a.name_en,
				a.name_zh,
				a.pic_url,
				a.longitude,
				a.latitude,
				a.avg_rating,
				count(b.tb_obj_id) as viewnum,
				getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$where = 'where a.level = '.$this->cinemaLevel;  //

		if( $distanceType > 0 ) {

			$where .= $this->_getDistanceCondition($distanceType, $longitude, $latitude);
		}

		//$sql = "SELECT %s FROM ".tname('mall')." %s %s ";
		$sql = "Select %s from ".tname('mall a')." LEFT JOIN ".tname('view b')." ON a.id = b.tb_obj_id %s %s ";

		$orderby = $this->_sortByType($sortType);   //根据不同类型排序

		$pagelimit = ' group by a.id '.$orderby."LIMIT ".page( $p, $limit );

		$sql = sprintf($sql, $field, $where, $pagelimit);

		$result = $this->db->query($sql)->result_array();

		return $result;
	}

	/**
	 * 获取影院详情
	 * @param string $id 影院ID
	 * @param string $longitude 当前经度
	 * @param string $latitude  当前纬度
	 */
	public function getCinemaDetail($id, $longitude = '', $latitude = '') {

		$field = "a.id,
				 a.name_en,
				 a.name_zh, 
				 a.pic_url, 
				 a.thumb_url,
				 a.longitude,
				 a.latitude,
				 a.open_time,
				 a.close_time,
				 a.avg_rating,
				 a.tel,
				 a.level,
				 a.affiliated_facilities,
				 b.name_zh as mall,
				 getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$sql = "Select ".$field." from ".tname('mall a').
			   " LEFT JOIN ".tname('mall b')." ON a.branch_name = b.name_zh where a.id = '".$id."'";

		$data = $this->db->query($sql)->result_array();

		$result['errcode'] = '0';
		$result['data'] = array();

		if( !empty($data) ) {	

			if( !empty( $data[0]['pic_url'])) {
				$data[0]['pic_url'] = explode(',',$data[0]['pic_url']);
			}

			if( $data[0]['level'] != $this->cinemaLevel ) {
				$result['errcode'] = '2';
			}

			$result['data'] = $data;
		}else {

			$result['errcode'] = '3';
		}

		return $result;
	}


	/**
	 * 获取景点列表
	 * @param string $p 页数
	 * @param string $limit  每页条数
	 * @param string $category  分类
	 * @param string $longitude  当前经度
	 * @param string $latitude  当前纬度
	 * @param string $sort 排序 0 默认按创建时间  1 按距离最近   2 评价最好   3 人气最高  4 人均最低    5 人均最高
	 */
	public function getTravelList($p, $limit, $category = '' ,$longitude = '', $latitude = '', $sortType = 0, $distanceType = 0) {

		$field = "a.id,
				a.name_en,
				a.name_zh,
				a.pic_url,
				a.category_name,
				a.longitude,
				a.latitude,
				a.avg_rating,
				count(b.tb_obj_id) as viewnum,
				getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$where = 'where a.level = '.$this->travelLevel;

		if( $category != '' ) {

			$where .= " and a.category_name = '".$category."'";
		}

		if( $distanceType > 0 ) {

			$where .= $this->_getDistanceCondition($distanceType, $longitude, $latitude);
		}

		//$sql = "SELECT %s FROM ".tname('mall')." %s %s ";
		$sql = "Select %s from ".tname('mall a')." LEFT JOIN ".tname('view b')." ON a.id = b.tb_obj_id %s %s ";

		$orderby = $this->_sortByType($sortType);   //根据不同类型排序

		$pagelimit = ' group by a.id '.$orderby."LIMIT ".page( $p, $limit );

		$sql = sprintf($sql, $field, $where, $pagelimit);
		//echo $sql;die;

		$result = $this->db->query($sql)->result_array();

		return $result;
	}

	/**
	 * 获取景点详情
	 * @param string $id 景点ID
	 * @param string $longitude 当前经度
	 * @param string $latitude  当前纬度
	 */
	public function getTravelDetail($id, $longitude = '', $latitude = '') {

		$field = "a.id,
				 a.name_en,
				 a.name_zh, 
				 a.pic_url, 
				 a.thumb_url,
				 a.category_name,
				 a.longitude,
				 a.latitude,
				 a.open_time,
				 a.close_time,
				 a.avg_rating,
				 a.tel,
				 a.level,
				 a.description,
				 b.name_zh as mall,
				 getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$sql = "Select ".$field." from ".tname('mall a').
			   " LEFT JOIN ".tname('mall b')." ON a.branch_name = b.name_zh where a.id = '".$id."'";

		$data = $this->db->query($sql)->result_array();

		$result['errcode'] = '0';
		$result['data'] = array();

		if( !empty($data) ) {

			if( !empty( $data[0]['pic_url'])) {
				$data[0]['pic_url'] = explode(',',$data[0]['pic_url']);
			}

			if( $data[0]['level'] != $this->travelLevel ) {
				$result['errcode'] = '2';
			}

			$result['data'] = $data;
		}else {

			$result['errcode'] = '3';
		}

		return $result;
	}

	/**
	 * 根据排序方式返回数据
	 * @param string $sort 排序 0默认按距离最近   1 按距离最近   2 评价最好   3 人气最高  4 人均最低    5 人均最高
	 *
	 */
	private function _sortByType($sortType) {

		$orderby = 'Order by distance desc ';

		switch($sortType) {
			case '1':
				$orderby = 'Order by distance asc ';
				break;
			case '2':
				$orderby = 'Order by a.avg_rating desc ';
				break;
			case '3':
				$orderby = 'Order by viewnum desc ';
				break;
			case '4':
				$orderby = 'Order by a.avg_price asc ';
				break;
			case '5':
				$orderby = 'Order by a.avg_price desc ';
				break;
		}

		return $orderby;
	}


	/**
	 * 根据距离条件筛选
	 * @param int $distanceType 距离类型
	 * @param string $longitude 当前经度
	 * @param string $latitude  当前纬度
	 *
	 */
	private function _getDistanceCondition($distanceType, $longitude = '', $latitude = '') {

		$condition = '';

		if( isset($this->distance[$distanceType]) ) {

			$distance = explode('-',$this->distance[$distanceType]);

			if( count($distance) > 1 ) {

				$condition = ' and getDistance('.$latitude.','.$longitude.',a.latitude,a.longitude) >= '.$distance[0].' and getDistance('.$latitude.','.$longitude.',a.latitude,a.longitude) < '.$distance[1];
			}else {

				$condition = ' and getDistance('.$latitude.','.$longitude.',a.latitude,a.longitude) > '.$distance[0];
			}
		}

		return $condition;
	}

}
