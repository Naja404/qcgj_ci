<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class CI_Model {
	// 返回数据 
	public $returnRes;
	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		log_message('info', 'Model Class Initialized');
		
		$this->returnRes = array(
							'error' => true, // true=有错误, false=正确
							'msg'   => false,
							'data'  => array()
							);
	}

	// --------------------------------------------------------------------

	/**
	 * __get magic
	 *
	 * Allows models to access CI's loaded classes using the same
	 * syntax as controllers.
	 *
	 * @param	string	$key
	 */
	public function __get($key)
	{
		// Debugging note:
		//	If you're here because you're getting an error message
		//	saying 'Undefined Property: system/core/Model.php', it's
		//	most likely a typo in your model code.
		return get_instance()->$key;
	}

	/**
	 * 设置分页
	 * @param string $url 
	 * @param int $total 总数
	 * @param int $perPage 每页条数
	 */
	public function setPagination($url = false, $total = 1, $perPage = 15){
		$pageConfig = array(
				'base_url'   => $url,
				'total_rows' => $total,
				'per_page'   => $perPage,
			);

		$this->pagination->initialize($pageConfig);

		return $this->pagination->create_links();
	}

	/**
	 * model 返回内容
	 * @param string $msg 返回信息内容
	 * @param array $data 数据内容
	 * @param bool $error 返回状态
	 */
	public function returnRes($msg = false, $data = array(), $error = true){

		$this->returnRes = array(
					'error' => $error,
					'msg'   => $msg,
					'data'  => $data,
			);

		return $this->returnRes;
	}

	/**
	 * 获取品牌信息
	 * @param string $brandId 品牌id
	 */
	public function getBrandInfoById($brandId = false){
		
		if (!$brandId) {
			return false;
		}

		$sql = "SELECT 	 a.id,
						 a.name_en,
						 a.name_zh,
						 a.logo_url,
						 b.tb_category_id,
						 c.name AS category_name
				 FROM ".tname('brand')." AS a 
				LEFT JOIN ".tname('brand_category')." AS b ON b.tb_brand_id = a.id
				LEFT JOIN ".tname('category')." AS c ON c.id = b.tb_category_id
				WHERE a.id = '".$brandId."'";

		$brandInfo = $this->db->query($sql)->first_row();

		return $brandInfo;
	}

	/**
	 * 获取mall楼层
	 * @param string $mallId 商户id
	 */
	public function getMallFloorById($mallId = false, $brandId = false){
		if (!$mallId || !$brandId) {
			return false;
		}

		$where = array(
				'tb_brand_id' => $brandId,
				'tb_mall_id'  => $mallId,
			);

		$floor = $this->db->select('address')->get_where(tname('brand_mall'), $where)->first_row();

		return isset($floor->address) ? $floor->address : false;
	}

}
