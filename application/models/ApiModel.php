<?php
/**
 * 品牌管理模型
 */

class ApiModel extends CI_Model {

	protected $shoppingLevel;
	protected $restaurantLevel;
	protected $cinemaLevel;
	protected $ktvLevel;
	protected $travelLevel;
	protected $hotelLevel;
	protected $distance;

	public function __construct(){

		$this->shoppingLevel = 1;     //购物
		$this->restaurantLevel = 4;   //美食
		$this->cinemaLevel = 5;		  //影院
		$this->travelLevel = 6;		  //景点
		$this->hotelLevel = 7;		  //酒店
		$this->ktvLevel = 8;		  //KTV
		$this->distance = array( '1'=>'0-500',
								 '2'=>'500-1000',
								 '3'=>'1000-2000',
								 '4'=>'2000-5000',
								 '5'=>'5000',
								 '6'=>'仙霞路',
								 '7'=>'定西路');

		
	}

	/**
	 * 获取大虹桥首页kv
	 * @param string $longitude 经度
	 * @param string $latitude 纬度
	 */
	public function getHomePageMall($longitude = 0, $latitude = 0){

		// $queryRes = $this->db->limit(5)
		// 					->where('tb_district_id', 786)
		// 					->where('level', 1)
		// 					->where('pic_url IS NOT NULL')
		// 					->get(tname('mall'))->result();

		$sql = "SELECT *, `getDistance`('".$longitude."', '".$latitude."', longitude, latitude) as distance FROM `tb_mall` WHERE `tb_district_id` = 786 AND `level` = 1  AND status = 1 AND `pic_url` IS NOT NULL order by distance asc LIMIT 5";

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array();

		foreach ($queryRes as $k => $v) {
			$tmp = array(
					'name'      => $v->name_zh,
					'image'     => config_item('image_url').$v->pic_url,
					'thumb_url' => config_item('image_url').str_replace('.jpg', '_thumb.jpg', $v->pic_url),
					'mallID'    => $v->id,
					'distance'  => $this->_formatDistance($v->distance),
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
		// $queryRes = $this->db->select("id AS detailID, title, main_pic_url AS image, IF(status = 1, '1', '1') AS type")
		// 		 ->limit(10)
		// 		 ->get(tname('subject'))->result_array();

		$sql = "select 
					b.id AS detailID, 
					b.title, 
					b.main_pic_url AS image, 
					IF(b.status = 1, '1', '1') AS type 
					from tb_subject_mall as a
					left join tb_subject as b 
						on b.id = a.tb_subject_id
					where 
						a.tb_district_id = 786 
						and 
						b.status = 1 
					limit 10";

		$queryRes = $this->db->query($sql)->result_array();

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
		
		$discountSql = "SELECT id,name_zh AS title, (SELECT CONCAT('".config_item('image_url')."', logo_url) AS logo_url FROM tb_brand WHERE id = tb_brand_id) AS image, tb_brand_id AS brandId, discount_desc FROM tb_discount WHERE is_delete = 0 AND LEFT(end_date, 10) >= '".date('Y-m-d')."' ORDER BY status DESC LIMIT 2";

		$returnRes = array();

		$discountRes = $this->db->query($discountSql)->result_array();

		foreach ($discountRes as $k => $v) {
			$tmp = array(
					'id'       => $v['id'],
					'type'     => 1,
					'image'    => $v['image'],
					'title'    => $v['discount_desc'],
					'discount' => $v['title'],
				);

			array_push($returnRes, $tmp);
		}

		$couponSql = "SELECT id, name as title, cost_price, (SELECT CONCAT('".config_item('image_url')."', logo_url) AS logo_url FROM tb_brand WHERE id = tb_brand_id) AS image FROM ".tname('coupon')." WHERE is_delete = 0 AND on_sale = 1 AND LEFT(end_date, 10) >= '".date('Y-m-d')."' ORDER BY status, end_date DESC LIMIT 4";

		$couponRes = $this->db->query($couponSql)->result_array();

		foreach ($couponRes as $k => $v) {
			$tmp = array(
					'id'       => $v['id'],
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
	 * 获取购物列表
	 * @param string $p 页数
	 * @param string $limit  每页条数
	 * @param string $category  分类
	 * @param string $longitude 当前经度
	 * @param string $latitude  当前纬度
	 * @param string $sort 排序 0 默认距离最近  1 按距离最近   2 评价最好   3 人气最高  4 人均最低    5 人均最高
	 */
	public function getShoppingList($p, $limit, $category = '', $longitude = '0', $latitude = '0', $sortType = 0, $distanceType = 0, $city = '1') {


		if( $longitude != '0' && $latitude != '0' ) {
			$distanceCond = "getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";
		}else {
			$distanceCond = "'' as distance ";			
		}

		$field = "a.id,
				a.name_en,
				a.name_zh,
				a.pic_url,
				a.thumb_url,
				c.name as category_name,
				a.longitude,
				a.latitude,
				a.avg_rating,
				a.avg_price,
				count(b.tb_obj_id) as viewnum,
				".$distanceCond;
				//getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$where = 'where a.status = 1 and a.level = '.$this->shoppingLevel;  //

		if( $category != '' ) {

			//$where .= " and a.category_name = '".$category."'";
			$where .= " and a.id in ( SELECT tb_mall_id FROM (
								SELECT DISTINCT(tb_mall_id) FROM tb_brand_mall 
								 WHERE tb_brand_id IN ( SELECT tb_brand_id FROM ( SELECT tb_brand_id FROM tb_brand_category d INNER JOIN tb_category c ON d.tb_category_id = c.id WHERE c.name = '".$category."' ) AS bid )
								 ) AS mallid )";
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
		//$sql = "Select %s from ".tname('mall a')." LEFT JOIN ".tname('view b')." ON a.id = b.tb_obj_id %s %s ";
		$sql = "Select %s from ".tname('mall a')." LEFT JOIN ".tname('view b')." ON a.id = b.tb_obj_id LEFT JOIN ".tname('category c')." ON c.id = a.tb_category_id %s %s ";

		$orderby = $this->_sortByType($sortType);   //根据不同类型排序

		$pagelimit = ' group by a.id '.$orderby."LIMIT ".page( $p, $limit );

		$sql = sprintf($sql, $field, $where, $pagelimit);

		//echo $sql;die;

		$result = $this->db->query($sql)->result_array();

		return $result;
	}


	/**
	 * 获取购物详情
	 * @param string $id 购物ID
	 * @param string $longitude 当前经度
	 * @param string $latitude  当前纬度
	 */
	public function getShoppingDetail($id, $longitude = '0', $latitude = '0') {

		if( $longitude != '0' && $latitude != '0' ) {
			$distanceCond = "getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";
		}else {
			$distanceCond = "'' as distance ";			
		}

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
				 b.longitude AS mall_longitude, b.latitude AS mall_latitude, 
				 CONCAT(a.city_name,a.district_name,a.address) AS address,
				 a.tel,
				 a.level,
				 b.name_zh as mall,
				 ".$distanceCond;
				 //getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$sql = "Select ".$field." from ".tname('mall a').
			   " LEFT JOIN ".tname('mall b')." ON a.branch_name = b.name_zh where a.id = '".$id."'";

			   //echo $sql;die;

		$data = $this->db->query($sql)->result_array();

		$result['errcode'] = '0';
		$result['data'] = array();

		if( !empty($data) ) {	

			if( !empty( $data[0]['pic_url'])) {
				$data[0]['pic_url'] = explode(',',$data[0]['pic_url']);
			}

			if( $data[0]['level'] != $this->shoppingLevel ) {
				$result['errcode'] = '2';
			}

			$result['data'] = $data;
		}else {

			$result['errcode'] = '3';
		}

		return $result;
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
	public function getRestaurantList($p, $limit, $category = '', $longitude = '0', $latitude = '0', $sortType = 0, $distanceType = 0) {

		if( $longitude != '0' && $latitude != '0' ) {
			$distanceCond = "getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";
		}else {
			$distanceCond = "'' as distance ";			
		}

		$field = "a.id,
				a.name_en,
				a.name_zh,
				a.pic_url,
				a.thumb_url,
				c.name as category_name,
				a.longitude,
				a.latitude,
				a.avg_rating,
				a.avg_price,
				count(b.tb_obj_id) as viewnum,
				".$distanceCond;
				//getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$where = 'where a.status = 1 and a.level = '.$this->restaurantLevel;  //

		if( $category != '' ) {

			$where .= " and c.name = '".$category."'";
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
		$sql = "Select %s from ".tname('mall a')." LEFT JOIN ".tname('view b')." ON a.id = b.tb_obj_id LEFT JOIN ".tname('category c')." ON c.id = a.tb_category_id %s %s ";

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
	public function getRestaurantDetail($id, $longitude = '0', $latitude = '0') {

		$countData = $this->_getCountByLevel();  //获取其他分类数量

		if( $longitude != '0' && $latitude != '0' ) {
			$distanceCond = "getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";
		}else {
			$distanceCond = "'' as distance ";			
		}

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
				 a.description,
				 b.name_zh as mall,
				 b.longitude AS mall_longitude, b.latitude AS mall_latitude, 
				 CONCAT(a.city_name,a.district_name,a.address) AS address,
				 ".$distanceCond;
				 //getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$sql = "Select ".$field." from ".tname('mall a').
			   " LEFT JOIN ".tname('mall b')." ON a.branch_name = b.name_zh where a.id = '".$id."'";

		$data = $this->db->query($sql)->result_array();

		$result['errcode'] = '0';
		$result['data'] = array();

		if( !empty($data) ) {

			if( $data[0]['level'] != $this->restaurantLevel ) {
				$result['errcode'] = '2';

				return $result;
			}

			if( !empty( $countData ) ) {

				$data[0]['count_level'] = $countData;
			}

			if( isset($data[0]['description']) && $data[0]['description'] != '' ) {

				$description = json_decode($data[0]['description']);

				if( is_array( $description )) {

					$data[0]['description'] = $description;
				}
			}

			if( !empty( $data[0]['pic_url'])) {
				$data[0]['pic_url'] = explode(',',$data[0]['pic_url']);
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
	public function getCinemaList($p, $limit, $longitude = '0', $latitude = '0', $sortType = 0, $distanceType = 0) {

		if( $longitude != '0' && $latitude != '0' ) {
			$distanceCond = "getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";
		}else {
			$distanceCond = "'' as distance ";			
		}

		$field = "a.id,
				a.name_en,
				a.name_zh,
				a.pic_url,
				a.thumb_url,
				a.longitude,
				a.latitude,
				a.avg_rating,
				count(b.tb_obj_id) as viewnum,
				".$distanceCond;
				//getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$where = "where a.status = 1 and a.level in ( '".$this->cinemaLevel."','".$this->ktvLevel."')";  //

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
	 * 获取影院详情
	 * @param string $id 影院ID
	 * @param string $longitude 当前经度
	 * @param string $latitude  当前纬度
	 */
	public function getCinemaDetail($id, $longitude = '0', $latitude = '0') {

		if( $longitude != '0' && $latitude != '0' ) {
			$distanceCond = "getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";
		}else {
			$distanceCond = "'' as distance ";			
		}

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
				 b.longitude AS mall_longitude, b.latitude AS mall_latitude, 
				 CONCAT(a.city_name,a.district_name,a.address) AS address,
				 ".$distanceCond;
				 //getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$sql = "Select ".$field." from ".tname('mall a').
			   " LEFT JOIN ".tname('mall b')." ON a.branch_name = b.name_zh where a.id = '".$id."'";

		$data = $this->db->query($sql)->result_array();

		

		$result['errcode'] = '0';
		$result['data'] = array();

		if( !empty($data) ) {

			if( $data[0]['level'] != $this->cinemaLevel && $data[0]['level'] != $this->ktvLevel ) {
				$result['errcode'] = '2';

				return $result;
			}

			//正在上映的电影
			/*SELECT m.name,m.rating,m.image FROM tb_cinema_movie m 
			INNER JOIN tb_cinema_today t ON t.movie_id = m.movie_id
			WHERE t.cinema_id = '11efd881b27a86d4c569401b1275f1c8'*/
			$movieField = 'm.name, m.rating, m.image';
			$movieSql = "Select ".$movieField." from ".tname('cinema_movie m').
						" LEFT JOIN ".tname('cinema_today t')." ON t.movie_id = m.movie_id where t.cinema_id = '".$id."'";

			$movieData = $this->db->query($movieSql)->result_array();
			//echo '<pre>';print_r($movieData);die;

			$data[0]['movie_list'] = $movieData;

			if( !empty( $data[0]['pic_url'])) {
				$data[0]['pic_url'] = explode(',',$data[0]['pic_url']);
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
	public function getTravelList($p, $limit, $category = '' ,$longitude = '0', $latitude = '0', $sortType = 0, $distanceType = 0) {

		if( $longitude != '0' && $latitude != '0' ) {
			$distanceCond = "getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";
		}else {
			$distanceCond = "'' as distance ";			
		}

		$field = "a.id,
				a.name_en,
				a.name_zh,
				a.pic_url,
				a.thumb_url,
				c.name as category_name,
				a.longitude,
				a.latitude,
				a.avg_rating,
				count(b.tb_obj_id) as viewnum,
				".$distanceCond;
				//getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$where = 'where a.status = 1 and a.level = '.$this->travelLevel;

		if( $category != '' ) {

			$where .= " and c.name = '".$category."'";
		}

		if( $distanceType > 0 ) {

			$where .= $this->_getDistanceCondition($distanceType, $longitude, $latitude);
		}

		//$sql = "SELECT %s FROM ".tname('mall')." %s %s ";
		$sql = "Select %s from ".tname('mall a')." LEFT JOIN ".tname('view b')." ON a.id = b.tb_obj_id LEFT JOIN ".tname('category c')." ON c.id = a.tb_category_id %s %s ";

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
	public function getTravelDetail($id, $longitude = '0', $latitude = '0') {

		if( $longitude != '0' && $latitude != '0' ) {
			$distanceCond = "getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";
		}else {
			$distanceCond = "'' as distance ";			
		}

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
				 b.longitude AS mall_longitude, b.latitude AS mall_latitude, 
				 CONCAT(a.city_name,a.district_name,a.address) AS address,
				 ".$distanceCond;
				 //getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$sql = "Select ".$field." from ".tname('mall a').
			   " LEFT JOIN ".tname('mall b')." ON a.branch_name = b.name_zh where a.id = '".$id."'";

		$data = $this->db->query($sql)->result_array();

		$result['errcode'] = '0';
		$result['data'] = array();

		if( !empty($data) ) {

			if( $data[0]['level'] != $this->travelLevel ) {
				$result['errcode'] = '2';

				return $result;
			}

			if( !empty( $data[0]['pic_url'])) {
				$data[0]['pic_url'] = explode(',',$data[0]['pic_url']);
			}

			$result['data'] = $data;
		}else {

			$result['errcode'] = '3';
		}

		return $result;
	}


	/**
	 * 获取酒店列表
	 * @param string $p 页数
	 * @param string $limit  每页条数
	 * @param string $longitude  当前经度
	 * @param string $latitude  当前纬度
	 * @param string $sort 排序 0 默认按距离最近  1 按距离最近   2 评价最好   3 人气最高  4 人均最低    5 人均最高
	 */
	public function getHotelList($p, $limit ,$longitude = '0', $latitude = '0', $sortType = 0, $distanceType = 0) {

		if( $longitude != '0' && $latitude != '0' ) {
			$distanceCond = "getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";
		}else {
			$distanceCond = "'' as distance ";			
		}

		$field = "a.id,
				a.name_en,
				a.name_zh,
				a.pic_url,
				a.thumb_url,
				a.category_name,
				a.longitude,
				a.latitude,
				a.avg_rating,
				count(b.tb_obj_id) as viewnum,
				".$distanceCond;
				//getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$where = 'where a.status = 1 and a.level = '.$this->hotelLevel;

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
	 * 获取酒店详情
	 * @param string $id 酒店ID
	 * @param string $longitude 当前经度
	 * @param string $latitude  当前纬度
	 */
	public function getHotelDetail($id, $longitude = '0', $latitude = '0') {

		if( $longitude != '0' && $latitude != '0' ) {
			$distanceCond = "getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";
		}else {
			$distanceCond = "'' as distance ";			
		}

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
				 b.longitude AS mall_longitude, b.latitude AS mall_latitude, 
				 CONCAT(a.city_name,a.district_name,a.address) AS address,
				 ".$distanceCond;
				 //getDistance('".$latitude."','".$longitude."',a.latitude,a.longitude) as distance ";

		$sql = "Select ".$field." from ".tname('mall a').
			   " LEFT JOIN ".tname('mall b')." ON a.branch_name = b.name_zh where a.id = '".$id."'";

		$data = $this->db->query($sql)->result_array();

		$result['errcode'] = '0';
		$result['data'] = array();

		if( !empty($data) ) {

			if( $data[0]['level'] != $this->hotelLevel ) {
				$result['errcode'] = '2';

				return $result;
			}

			if( !empty( $data[0]['pic_url'])) {
				$data[0]['pic_url'] = explode(',',$data[0]['pic_url']);
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

		$orderby = 'Order by distance asc ';

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
				//$orderby = 'Order by a.avg_price asc ';
				$orderby = "ORDER BY CASE WHEN IFNULL(avg_price,'')='' THEN 0 ELSE 1 END DESC, avg_price ASC ";
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
	private function _getDistanceCondition($distanceType, $longitude = '0', $latitude = '0') {

		$condition = '';

		if( isset($this->distance[$distanceType]) ) {

			$distance = explode('-',$this->distance[$distanceType]);

			if( count($distance) > 1 ) {

				$condition = ' and getDistance('.$latitude.','.$longitude.',a.latitude,a.longitude) >= '.$distance[0].' and getDistance('.$latitude.','.$longitude.',a.latitude,a.longitude) < '.$distance[1];
			}else {

				if( $distance[0] == '5000' ) {
					$condition = ' and getDistance('.$latitude.','.$longitude.',a.latitude,a.longitude) > '.$distance[0];
				}else {
					$condition = " and a.address != '' and a.address like '".$distance[0]."%'";
				}
			}
		}
		//print_r($condition);die;

		return $condition;
	}

	/**
	 * 格式化距离
	 * @param int $distance 距离/米
	 */
	private function _formatDistance($distance = 0){

		if ($distance <= 0) return '';

		if ($distance >= 1000) return round($distance / 1000, 2).'km';

		return $distance.'m';
	}


	/**
	 * 统计各类型的数量
	 *
	 */
	private function _getCountByLevel() {

		$data = array();

		$sql = "SELECT level,COUNT(*) as count FROM ".tname('mall')." where level != '2' GROUP BY level";

		$result = $this->db->query($sql)->result_array();
		//echo '<pre>';print_r($result);die;

		$entertainment = 0;
		foreach( $result as $key=>$item ) {
			if( $key == '2' || $key == '5' ) {
				$entertainment += intVal($item['count']);
			}else {
				array_push($data, intVal($item['count']));
			}
		}
		/*
		 * 0 - 购物  1 - 美食  2 - 景点  3 - 酒店  4 - 娱乐
		 */
		$data[] = $entertainment;

		//echo '<pre>';print_r($data);die;
		return $data;
	}
}
