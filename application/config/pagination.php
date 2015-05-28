<?php
/**
 * 分页配置内容
 */

 $config = array(
			'uri_segment'          => 3, 
			'num_links'            => 2,
			'use_page_numbers'     => true,
			'page_query_string'    => true,
			'query_string_segment' => 'p',
			'full_tag_open'        => '<div class="col-sm-12" style="text-align:right;"><ul class="pagination" >',
			'full_tag_close'       => '</ul></div>',
			'first_link'           => '<<',
			'first_tag_open'       => '<li>',
			'first_tag_close'      => '</li>',
			'last_link'            => '>>',
			'last_tag_open'        => '<li>',
			'last_tag_close'       => '</li>',
			'next_link'            => '',
			'next_tag_open'        => '',
			'next_tag_close'       => '',
			'prev_link'            => '',
			'prev_tag_open'        => '',
			'prev_tag_close'       => '',
			'cur_tag_open'         => '<li class="active"><a>',
			'cur_tag_close'        => '</a></li>',
			'num_tag_open'         => '<li>',
			'num_tag_close'        => '</li>',
 	);
?>
