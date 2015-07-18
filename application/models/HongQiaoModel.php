<?php
/**
 * 新虹桥模型
 */

class HongQiaoModel extends CI_Model {
	
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
	 * 获取爬虫店铺数据
	 * @param string $where 查询条件
	 * 
	 */
	public function getMall2W($where = NULL, $p = 1, $url = false){

		$limit = "LIMIT ".page($p, 25);
		
		$field = " * ";

		$sql = "SELECT %s FROM ".tname('new_mall_s')." WHERE status = 1 %s  ORDER BY update_time ASC %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', $where, ''))->first_row();

		$pagination = $this->setPagination(site_url($url), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $where, $limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);
		
		return $returnRes;
	}

	/**
	 * 获取爬虫数据详情
	 * @param string $id
	 */
	public function getMall2wDetail($id =  false){
		$where = array(
				'id' => $id,
			);

		$queryRes = $this->db->get_where(tname('new_mall_s'), $where)->first_row();

		$searchName = addslashes($queryRes->brandName);

		$queryRes->brandInfo = $this->db->select('id, name_zh, name_en')
											->where(array('name_zh' => $searchName))
											->or_where(array('name_en' => $searchName))
											->get(tname('brand'))
											->first_row();

		$queryRes->mall = $this->getMallList2w($queryRes->mallName, $queryRes->address, $queryRes->cityName, 'html');

		return count($queryRes) ? $queryRes : false;
	}

	/**
	 * 获取商场内容
	 * @param string $mallName
	 * @param string $address
	 * @param string $city
	 */
	public function getMallList2w($mall = false, $address = false, $city = false, $format = 'array'){
		
		$queryRes = $this->db->like('name_zh', $mall)
								->or_like('address', $address)
								->get_where(tname('mall'), array('city_name' => $city))
								->result();

		$returnRes = $queryRes;

		if ($format == 'html') {
			$returnRes = '';
			foreach ($queryRes as $k => $v) {
				$html = '<input type="radio" name="mallId_s[]" id="mall_%s" value="%s"><label for="mall_%s">%s(%s)</label><br>';
				$returnRes .= sprintf($html, $v->id, $v->id, $v->id, $v->name_zh, $v->address);
			}
		}

		return $returnRes ? $returnRes : false;
	}

	/**
	 * 搜索品牌
	 * @param string $brandName
	 */
	public function searchBrand($brandName = false){
		if (!$brandName) return false;

		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.SEARCH_BRAND_LIST').md5($brandName));
		
		if ($cacheRes) return $cacheRes; 

		$list = $this->db->select('id, name_zh, name_en')
						 ->like('name_zh', $brandName)
						 ->or_like('name_en', $brandName)
						 ->order_by('name_en, name_zh ASC')
						 ->limit(20)
						 ->get(tname('brand'))
						 ->result();

		$returnList = array();

		foreach ($list as $k => $v) {
			$tmp = array(
					'label' => $v->name_en.'_'.$v->name_zh,
					'value' => $v->name_zh,
					'id' => $v->id,
				);
			array_push($returnList, $tmp);
		}

		if (count($returnList)) $this->cache->save(config_item('NORMAL_CACHE.SEARCH_BRAND_LIST').md5($brandName), $returnList, 3600); 

		return $returnList;
	}

	/**
	 * 获取店铺列表
	 * @param int $p 页码
	 * @param int $type 
	 */
	public function getMallList($p = 1, $type = 0, $url = ''){

		$limit = "LIMIT ".page($p, 25);
		
		$field = " * ";

		$sql = "SELECT %s FROM ".tname('mall')." WHERE level = ".$type."  ORDER BY update_time ASC %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', ''))->first_row();

		$pagination = $this->setPagination(site_url($url), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);
		
		return $returnRes;

	}

	/**
	 * 品牌店铺列表
	 *
	 */	
	public function getBrandShopList($p = 1){
		$where = " WHERE image != 'null' ";

		$limit = "LIMIT ".page($p, 25);
		
		$field = " * ";

		$sql = "SELECT %s FROM ".tname('brand_rel')." %s %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', $where, ''))->first_row();

		$pagination = $this->setPagination(site_url('HongQiao/brandShopList'), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $where, $limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);

		return $returnRes;

	}

	/**
	 * 获取品牌店铺详情
	 * @param string $brandId 品牌id
	 */
	public function getBrandShopDetail($brandId = false){
		
		$where = array(
				'brand_id' => $brandId,
			);

		$queryRes = $this->db->get_where(tname('brand_rel'), $where)->first_row();

		if (count($queryRes) <= 0) return false;

		$queryRes->pic = json_decode($queryRes->image, true);

		return $queryRes;
	}

	/**
	 * 电影院列表
	 * @param int $p 页码
	 */
	public function getCinemaList($p = 1){
		
		$where = array(
				'area' => '长宁区',
			);

		$queryRes = $this->db->get_where(tname('cinema'), $where)->result();

		$pagination = $this->setPagination(site_url('HongQiao/cinemaList'), count($queryRes), 25);

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);

		return $returnRes;
	}

	/**
	 * 景点列表
	 * @param int $p 页码
	 */
	public function getTravelList($where = NULL, $p = 1){

		$limit = "LIMIT ".page($p, 25);
		
		$field = " * ";

		$sql = "SELECT %s FROM ".tname('travel')." %s %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', $where, ''))->first_row();

		$pagination = $this->setPagination(site_url('HongQiao/travelList'), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $where, $limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);
		
		return $returnRes;
	}

	/**
	 * 餐厅列表
	 * @param int $p 页码
	 */
	public function getRestaurantList($where = NULL, $p = 1){

		$limit = "LIMIT ".page($p, 25);
		
		$field = " * ";

		$sql = "SELECT %s FROM ".tname('restaurant')." %s %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', $where, ''))->first_row();

		$pagination = $this->setPagination(site_url('HongQiao/restaurantList'), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $where, $limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);
		
		return $returnRes;
	}

	/**
	 * 餐厅地址列表
	 *
	 */
	public function getRestaurantAddressList($where = NULL, $p = 1){
		$limit = "LIMIT ".page($p, 25);
		
		$field = " * ";

		$sql = "SELECT %s FROM ".tname('mall')." WHERE level = 4  %s ORDER BY update_time ASC %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', $where, ''))->first_row();

		$pagination = $this->setPagination(site_url('HongQiao/restaurantAddressList'), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $where, $limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);
		
		return $returnRes;
	}

	/**
	 * 获取景点详细内容
	 * @param string $id
	 */
	public function getTravelDetail($id = false){
		$where = array(
				'id' => $id,
			);

		$queryRes = $this->db->get_where(tname('travel'), $where)->first_row();

		return $queryRes;
	}

	/**
	 * 获取餐厅详细信息
	 * @param string $id 餐厅id
	 */
	public function getRestaurantDetail($id = false){
		
		$where = array(
				'id' => $id,
			);

		$queryRes = $this->db->get_where(tname('restaurant'), $where)->first_row();

		return $queryRes;
	}

	/**
	 * 获取店铺详细内容
	 * @param string $id 店铺id
	 * @param int $type 1=商场 2=街边店 4=餐厅 5=影院 6=景点
	 */
	public function getMallDetail($id = false, $type = 1){

		$where = array(
				'id'   => $id,
				'level' => $type,
			);

		$queryRes = $this->db->get_where(tname('mall'), $where)->first_row();

		return $queryRes;
	}

	/**
	 * 获取电影院详情
	 * @param string $id 电影院id
	 */
	public function getCinemaDetail($id = false){
		$where = array(
				'id' => $id,
			);

		$queryRes = $this->db->get_where(tname('cinema'), $where)->first_row();

		$path = json_decode($queryRes->text, true);

		foreach ($path['img'] as $j => $m) {
			$arr = explode('/', $m);
			$num = count($arr) - 1;
			$queryRes->pic[] = $arr[$num];
		}

		return $queryRes;
	}

	/**
	 * 检测餐厅id权限
	 * @param string $id 餐厅id
	 */
	public function checkEditRestaurant($id = false){
		$where = array(
				'id' => $id,
			);

		$queryRes = $this->db->get_where(tname('restaurant'), $where)->first_row();

		return count($queryRes) > 0 ? true : false;	
	}

	/**
	 * 检测店铺权限
	 * @param string $id 店铺id
	 * @param int $type 1=商场 2=街边店 4=餐厅 5=影院 6=景点
	 */
	public function checkEditMall($id = false, $type){
		$where = array(
				'id'    => $id,
				'level' => $type,
			);

		$queryRes = $this->db->get_where(tname('mall'), $where)->first_row();

		return count($queryRes) > 0 ? true : false;		
	}

	/**
	 * 检测景点id权限
	 * @param string $id 景点id
	 */
	public function checkEditTravel($id = false){
		$where = array(
				'id' => $id,
			);

		$queryRes = $this->db->get_where(tname('travel'), $where)->first_row();

		return count($queryRes) > 0 ? true : false;	
	}

	/**
	 * 检测电影院id权限
	 * @param string $id 电影院id
	 */
	public function checkEditCinema($id = false){
		$where = array(
				'id' => $id,
			);

		$queryRes = $this->db->get_where(tname('cinema'), $where)->first_row();

		return count($queryRes) > 0 ? true : false;	
	}

	/**
	 * 获取分类
	 * @param int $type 分类类型
	 */
	public function getMallCate($type = 1){

		$where = array(
				'type' => $type,
			);

		$queryRes = $this->db->select('id, name')->get_where(tname('category'), $where)->result();

		return $queryRes;
	}

	/**
	 * 更新餐厅地址内容
	 * @param array $reqData 更新数组
	 */
	public function editRestaurantAddress($reqData = array()){
		
		$update = array(
				'name_zh'     => $reqData['mallName'],
				'branch_name' => $reqData['branchName'],
				'floor'       => $reqData['floor'],
				'address'     => $reqData['address'],
				'longitude'   => $reqData['lng'],
				'latitude'    => $reqData['lat'],
				'tel'         => $reqData['tel'],
				'update_time' => currentTime(),
			);

		$where = array(
				'id' => $reqData['mallId'],
			);

		$updateRes = $this->db->where($where)->update(tname('mall'), $update);

		return $updateRes ? true : false;
	}

	/**
	 * 编辑地址
	 * @param array $update 更新内容
	 * @param array $where 更新条件
	 */
	public function editMallAddress($update = array(), $where = array()){

		$updateRes = $this->db->where($where)->update(tname('mall'), $update);

		return $updateRes ? true : false;
	}

	/**
	 * 删除mall 数据
	 * @param string $mallId
	 * @param int $type 
	 */
	public function delMall($mallId = false, $type = 0){
		$where = array(
				'id'    => $mallId,
				'level' => $type,
			);

		$delRes = $this->db->where($where)->delete(tname('mall'));

		return $delRes ? true : false;
	}

}
