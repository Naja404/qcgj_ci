<?php
/**
 * 公用方法
 */

/**
 * 缓存,默认Redis缓存
 * @param  mixed $name    makeUUID缓存名
 * @param  mixed $value   缓存数据
 * @param  mixed $options 缓存参数
 * @return mixed
 */
function cache($name,$value = '',$options = array()){
	static $cache   =   '';
	if(empty($cache)){
		//默认memcache
		$type       =   isset($options['type'])?$options['type']:'Redis';
		$cache 	    =   Think\Cache::getInstance($type);
	}
	//获取缓存
	if($value === ''){
		return $cache->get($name);
	}elseif(is_null($value)){
		//删除缓存
		return $cache->rm($name);
	}else{
		//缓存数据
		if(is_array($options))
			$expire = $options['expire'] ? $options['expire'] : '';
		else
			$expire = is_numeric($options)	?	$options	  : '';

		return $cache->set($name,$value,$expire);
	}
}

/**
 * 设置缓存队列
 * @param string $path 队列存储路径
 * @param array $value 队列数组内容
 * @param array $options 队列参数
 * @return mixed
 */
function cacheList($path = false, $value = array(), $options = array()){
	static $cache   =   '';
	if(empty($cache)){
		//默认Redis
		$type       =   isset($options['type']) ? $options['type'] : 'Redis';
		$cache 	    =   Think\Cache::getInstance($type);
	}

	if (empty($path) || !$path) {
		return false;
	}

	if (is_array($options) && count($options) > 0) {
		if ($options['function'] = 'size') {
			return $cache->size($path);
		}
	}

	if ($value === array() && $path) {
		$results = $cache->pop($path);
		return json_decode($results, true);
	}

	return $cache->push($path, json_encode($value));
}

/**
 * 去除所有空格
 * @param string $str 字符串
 */
function trimAll($str = false){

	$search = array(" ","　","\t","\n","\r");

	$replace = array("","","","","");

	return str_replace($search, $replace, $str);
}

/**
 * 存储文本文件
 * @param text $content 文本内容
 */
function makeFetchFile($content = false, $path = null){

	Think\Log::write($content, '', '', $path, array('write_type' => 'html'));
}

/**
 * 表名前缀
 * @param string $table 表名
 */
function tname($table = false){
	return config_item('db_prefix').$table;
}

/**
 * 创建uuid
 *
 */
function makeUUID(){

	mt_srand((double)microtime()*10000);
	$charid = strtolower(md5(uniqid(rand(), true)));
	$uuid = substr($charid, 0, 8)
		    .substr($charid, 8, 4)
		    .substr($charid,12, 4)
		    .substr($charid,16, 4)
		    .substr($charid,20,12);

	return $uuid;
}
/**
 * 检测手机号码
 * @param int $mobile 手机号
 * @return bool
 */
function checkMobileFormat($mobile = 0){
	if (!is_numeric($mobile)) {
		return false;
	}

	$preg = '/^1[34587]\d{9}$/';

	if (preg_match($preg, $mobile)) {
		return true;
	}

	return false;
}

/**
 * 记录错误日志
 * @param array $arr mixed
 * @param string $path 日志存储路径
 */
function errLog($arr = array(), $path = false){

	$level = $arr['level'] ? $arr['level'] : 'WARN';
	$path  = $path ? $path : C('LOG_PATH').date('y_m_d').'_custom.log';

	Think\Log::write(var_export($arr, true), $level, '', $path);
}

/**
 * json输出
 * @param $data 输出数据内容
 * @return json
 */
function jsonReturn($data = array()){
	header('Content-Type:application/json; charset=utf-8');
	exit(json_encode($data));
}

/**
 * 当前时间
 * @param string $format 时间格式 timestamp＝时间戳, datetime=时间, microtime=毫秒
 * @param timestamp $timestamp 时间戳
 */
function currentTime($format = '', $timestamp = 0){
	
	$format = strtoupper($format);

	switch ($format) {
		case 'TIMESTAMP':
			$currentTime = time();
			break;
		case 'MICROTIME':
			$currentTime = microtime();
			break;
		default:
			$timestamp = $timestamp > 0 ? $timestamp : time();
			$currentTime = date('Y-m-d H:i:s', $timestamp);
			break;
	}

	return $currentTime;
}

/**
 * 获取session_id
 *
 */
function getSessionId(){
	session_start();
	return session_id();
}

/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 (单位:秒)
 * @return string
 */
function strEncrypt($data, $key = '@#qcgj*', $expire = 0) {
	$key  = md5($key);
	$data = base64_encode($data);
	$x    = 0;
	$len  = strlen($data);
	$l    = strlen($key);
	$char =  '';
	for ($i = 0; $i < $len; $i++) {
		if ($x == $l) $x=0;
		$char  .= substr($key, $x, 1);
		$x++;
	}
	$str = sprintf('%010d', $expire ? $expire + time() : 0);
	for ($i = 0; $i < $len; $i++) {
		$str .= chr(ord(substr($data,$i,1)) + (ord(substr($char,$i,1)))%256);
	}
	return str_replace('=', '', base64_encode($str));
}

/**
 * 系统解密方法
 * @param string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param string $key  加密密钥
 * @return string
 */
function strDecrypt($data, $key = '@#qcgj*'){
	$key    = md5($key);
	$x      = 0;
	$data   = base64_decode($data);
	$expire = substr($data, 0, 10);
	$data   = substr($data, 10);
	if($expire > 0 && $expire < time()) {
		return '';
	}
	$len  = strlen($data);
	$l    = strlen($key);
	$char = $str = '';
	for ($i = 0; $i < $len; $i++) {
		if ($x == $l) $x = 0;
		$char  .= substr($key, $x, 1);
		$x++;
	}
	for ($i = 0; $i < $len; $i++) {
		if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
			$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
		}else{
			$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
		}
	}
	return base64_decode($str);
}
?>
