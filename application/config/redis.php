<?php
/**
 * redis 配置内容
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$config['socket_type'] = 'tcp'; //`tcp` or `unix`
$config['socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
$config['host'] = '115.28.200.133';
$config['password'] = NULL;
$config['port'] = 6379;
$config['timeout'] = 0;


?>
