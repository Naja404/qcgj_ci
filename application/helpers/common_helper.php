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
			$microtime = explode(' ', microtime());

			$currentTime = $microtime[1]+$microtime[0];
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
function strEncrypt($str,$key = 'qcgj') {
    $ret = '';
    $str = base64_encode ($str);
    for ($i=0; $i<=strlen($str)-1; $i++){
		$d_str = substr($str, $i, 1);
		$int   = ord($d_str);
		$int   = $int^$key;
		$hex   = strtoupper(dechex($int));
		$ret  .= $hex;
    }
    return $ret;
}

/**
 * 系统解密方法
 * @param string $str 要解密的字符串 （必须是strEncrypt方法加密的字符串）
 * @param string $key  加密密钥
 * @return string
 */
function strDecrypt($str,$key = 'qcgj') {
    $ret = '';
    for ($i=0; $i <= strlen($str)-1; 0){
		$hex = substr($str, $i, 2);
		$dec = hexdec($hex);
		$dec = $dec^$key;
		$ret.= chr($dec);
		$i   = $i+2;
    }
    return base64_decode($ret);
}
/**
 * 生成遮罩logo图片
 * @param string $picture 原始图片路径
 * @param string $mask 遮罩图片路劲
 */
function imagealphamask( &$picture, $mask ) {
    // Get sizes and set up new picture
    $xSize = imagesx( $picture );
    $ySize = imagesy( $picture );
    $newPicture = imagecreatetruecolor( $xSize, $ySize );
    imagesavealpha( $newPicture, true );
    imagefill( $newPicture, 0, 0, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) );

    // Resize mask if necessary
    if( $xSize != imagesx( $mask ) || $ySize != imagesy( $mask ) ) {
        $tempPic = imagecreatetruecolor( $xSize, $ySize );
        imagecopyresampled( $tempPic, $mask, 0, 0, 0, 0, $xSize, $ySize, imagesx( $mask ), imagesy( $mask ) );
        imagedestroy( $mask );
        $mask = $tempPic;
    }

    // Perform pixel-based alpha map application
    for( $x = 0; $x < $xSize; $x++ ) {
        for( $y = 0; $y < $ySize; $y++ ) {

            $alpha = imagecolorsforindex( $mask, imagecolorat( $mask, $x, $y ) );
            $alpha = 127 - floor( $alpha[ 'red' ] / 2 );
            if ($alpha == 0) {
                continue;
            }
            $color = imagecolorsforindex( $picture, imagecolorat( $picture, $x, $y ) );

            imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, $color[ 'red' ], $color[ 'green' ], $color[ 'blue' ], $alpha ) );

        }
    }


    // Copy back to original picture
    imagedestroy( $picture );
    $picture = $newPicture;
}

/**
 * 缩放尺寸
 * @param string $src_img 图片路径
 * @param string $save_src 存储路径
 */
function resizeIMG($src_img = false, $save_src = false, $dst_h = 200, $dst_w = 200){
    list($src_w,$src_h) = $imageInfo = getimagesize($src_img);  // 获取原图尺寸

    $dst_scale = $dst_h/$dst_w; //目标图像长宽比
    $src_scale = $src_h/$src_w; // 原图长宽比

    if ($src_scale>=$dst_scale){  // 过高
        $w = intval($src_w);
        $h = intval($dst_scale*$w);

        $x = 0;
        $y = ($src_h - $h)/3;
    } else { // 过宽
        $h = intval($src_h);
        $w = intval($h/$dst_scale);

        $x = ($src_w - $w)/2;
        $y = 0;
    }

    if ($imageInfo['mime'] == 'image/jpeg') {
        $source=imagecreatefromjpeg($src_img); 
    }else{
        $source = imagecreatefrompng($src_img);
    }

    // 剪裁

    $croped=imagecreatetruecolor($w, $h);
    imagecopy($croped, $source, 0, 0, $x, $y, $src_w, $src_h);

    // 缩放
    $scale = $dst_w / $w;
    $target = imagecreatetruecolor($dst_w, $dst_h);
    $final_w = intval($w * $scale);
    $final_h = intval($h * $scale);
    imagecopyresampled($target, $croped, 0, 0, 0, 0, $final_w,$final_h, $w, $h);

    // 保存
    if ($imageInfo['mime'] == 'image/jpeg') {
        imagejpeg($target, $save_src); 
    }else{
        imagepng($target, $save_src);
    }
    imagedestroy($target);
}

/**
 * limit 计算
 * @param int $page 当前页
 * @param int $count 页面条数
 */
function page($page = 1, $count = 25){
	$page = $page <= 0 ? 1 : $page;
	return ($page - 1)*$count.','.$count;
}

/**
 * 获取文件夹目录
 * @param string $dir 文件夹路径
 * @return mixed
 */
function get_dir($dir, $page = 1, $pagesize = 10) {

	$dirArray = array();

	if (false != ($handle = opendir ( $dir ))) {
		$dirNum = 0;
		while ( false !== ($file = readdir ( $handle )) ) {

			//去掉"“.”、“..”以及带“.xxx”后缀的文件
			if ($file != "." && $file != "..") {
				if (opendir($dir.'/'.$file) || is_dir($file)) {
					$dirArray[filemtime($dir.'/'.$file)+$dirNum] = array(
									'path'   => $file,
									'create' => filectime($dir.'/'.$file),
									'modify' => filemtime($dir.'/'.$file),
									'type'   => 'dir',
						);
					$dirNum++;
				}
			}
		}
		//关闭句柄
		closedir($handle);
	}

	krsort($dirArray, SORT_NUMERIC);

	foreach ($dirArray as $k => $v) {
		$tmpDirArr[] = $v;
	}


	for ($i = $pagesize * ($page - 1); $i < $pagesize * $page; $i++) {
		if ($tmpDirArr[$i]) {
			$result['data'][] = $tmpDirArr[$i];
		}else{
			break;
		}
	}

	$result['count'] = count($tmpDirArr);

	return $result;
}
?>
