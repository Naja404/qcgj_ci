<?php
/**
 * 品牌管理模型
 */

class BrandModel extends CI_Model {
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
	 * 获取品牌列表
	 * @param string $where 查询条件
	 * @param int $p 页数
	 */
	public function getBrandList($where = NULL, $p = 1){

		$limit = "LIMIT ".page($p, 25);
		
		$field = " 	id, 
					name_en, 
					name_zh, 
					logo_url, 
					create_time, 
					update_time, 
					LEFT(description, 50) AS summary, 
					oper ";

		$sql = "SELECT %s FROM ".tname('brand')." %s %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', $where, ''))->first_row();

		$pagination = $this->setPagination(site_url('Brand/listView'), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $where, $limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);

		return $returnRes;
	}

	/**
	 * 删除品牌
	 * @param string $brandId 品牌id
	 */
	public function delBrand($brandId = false){
		
		$where = array('id' => $brandId);

		return $this->db->delete(tname('brand'), $where);
	}

	/**
	 * 获取品牌分类
	 *
	 */
	public function getBrandCategory(){
		$where = array(
				'level' => 1,
			);

		$queryRes = $this->db->select('id, name')->get_where(tname('category'), $where)->result();

		return $queryRes;
	}

	/**
	 * 获取品牌风格
	 *
	 */
	public function getBrandStyle(){

		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.BRAND_STYLE'));

		if (!empty($cacheRes) && count($cacheRes)) return $cacheRes;
		
		$where = array(
				'level' => 1,
			);

		$styleRes = $this->db->select('name, id')->get_where(tname('style'), $where)->result();

		if (count($styleRes)) $this->cache->save(config_item('NORMAL_CACHE.BRAND_STYLE'), $styleRes);

		return $styleRes;
	}

	/**
	 * 获取品牌年龄
	 *
	 */
	public function getBrandAge(){
		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.BRAND_AGE'));

		if (!empty($cacheRes) && count($cacheRes) > 0) return $cacheRes;

		$ageRes = $this->db->select('name, id')->get(tname('age'))->result();

		if (count($ageRes)) $this->cache->save(config_item('NORMAL_CACHE.BRAND_AGE'), $ageRes);

		return $ageRes;
	}

	/**
	 * 获取品牌消费金额
	 *
	 */
	public function getBrandPrice(){
		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.BRAND_PRICE'));

		if (!empty($cacheRes) && count($cacheRes) > 0) return $cacheRes;

		$priceRes = $this->db->select('name, id')->get(tname('price'))->result();

		if (count($priceRes)) $this->cache->save(config_item('NORMAL_CACHE.BRAND_PRICE'), $priceRes);

		return $priceRes;
	}


	/**
	 * 搜索商场/店铺
	 * @param string $mall 商场/店铺名称
	 */
	public function searchMall($mall = false){
		if (empty($mall)) return array();

		$queryRes = $this->db->select('id, name_zh, address, city_name')
							 ->like('name_zh', $mall)
							 ->get(tname('mall'))
							 ->result();
		$returnRes = array();
		
		foreach ($queryRes as $k => $v) {
			$tmp = array(
					'label' => $v->name_zh.'('.$v->city_name.$v->address.')',
					'id'    => $v->id,
				);
			array_push($returnRes, $tmp);
		}

		return $returnRes;
	}

	/**
	 * 添加品牌
	 * @param array $reqData 品牌数据内容
	 */
	public function addBrand($reqData = array()){

		$brand = array(
				'id'          => makeUUID(),
				'name_zh'     => $reqData['nameZh'],
				'name_en'     => $reqData['nameEn'],
				// 'logo_url' => $reqData['logo_url'],
				// 'pic_url'  => $reqData['pic_url'],
				'create_time' => currentTime(),
				'update_time' => currentTime(),
				'description' => $reqData['summary'],
				'tb_age_id'   => isset($reqData['age']) ? $reqData['age'] : '',
				'tb_price_id' => isset($reqData['price']) ? $reqData['price'] : '',
				'oper'        => $this->userInfo->user_id,
			);
		
		$insertRes = $this->db->insert(tname('brand'), $brand);

		if(!$insertRes) return $this->lang->line('ERR_ADD_BRAND_FAILURE');

		// 添加品牌分类
		if (isset($reqData['category']) && count($reqData['category'])) {
			foreach ($reqData['category'] as $k => $v) {
				$category = array(
						'id'             => makeUUID(),
						'create_time'    => currentTime(),
						'update_time'    => currentTime(),
						'tb_brand_id'    => $brand['id'],
						'tb_category_id' => $v,
					);

				$this->db->insert(tname('brand_category'), $category);
			}
		}

		// 添加品牌店铺
		if (isset($reqData['mallId']) && count($reqData['mallId'])) {
			foreach ($reqData['mallId'] as $k => $v) {
				
				$mall = array(
						'tb_brand_id' => $brand['id'],
						'tb_mall_id'  => $v,
						'create_time' => currentTime(),
						'update_time' => currentTime(),
						'address'     => $reqData['floor'][$k],
					);

				$this->db->insert(tname('brand_mall'), $mall);
			}
		}

		return true;
	}

	/**
	 * 添加品牌验证
	 * @param array $validateRule 验证规则
	 * @param arary $reqData 待验证数据
	 */
	public function validateAddBrand($validateRule = array(), $reqData = array()){
		$this->form_validation->set_rules($validateRule);

		if (!$this->form_validation->run()) return validation_errors();

		if (!isset($reqData['category']) || count($reqData['category']) <= 0) return $this->lang->line('ERR_CATEGORY'); 

		if (!isset($reqData['mallId']) || count($reqData['mallId']) <= 0) return $this->lang->line('ERR_MALL'); 

		// 验证品牌名是否存在
		if ($this->existsBrandName($reqData['nameZh'], $reqData['nameEn'])) return $this->lang->line('ERR_EXISTS_BRAND_NAME');

		return true;
	}

	/**
	 * 是否存在品牌
	 * @param string $nameZh 品牌中文名
	 * @param string $nameEn 品牌英文名
	 */
	public function existsBrandName($nameZh = false, $nameEn = false){
		
		$where = "name_zh = '".$nameZh."' ";
		
		if (!empty($nameEn)) $where .= " AND name_en = '".$nameEn."'";

		$sql = "SELECT COUNT(*) AS total FROM ".tname('brand')." WHERE ".$where;

		$queryRes = $this->db->query($sql)->first_row();

		return $queryRes->total > 0 ? true : false;
	}
}
