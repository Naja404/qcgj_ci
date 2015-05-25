<?php
/**
 * 分页配置内容
 */

 $config = array(
			'uri_segment'       => 3, 
			'num_links'         => 2,
			'use_page_numbers'  => true,
			'page_query_string' => true,
			'full_tag_open'     => '<li>',
			'full_tag_close'    => '</li>',
			'first_link'        => 'first',
			'first_tag_open'    => '<li class="disabled"><a href="#"><i class="icon-double-angle-left">',
			'first_tag_close'   => '</i></a></li>',
			'last_link'         => '',
			'last_tag_open'     => '<li class=""><a href="#"><i class="icon-double-angle-right">',
			'last_tag_close'    => '</i></a></li>',
			'next_link'         => '&gt;',
			'next_tag_open'     => '<div>',
			'next_tag_close'    => '</div>',
			'prev_link'         => '&lt;',
			'prev_tag_open'     => '<div>',
			'prev_tag_close'    => '</div>',
			'cur_tag_open'      => '<b>',
			'cur_tag_close'     => '</b>',
			'num_tag_open'      => '<div>',
			'num_tag_close'     => '</div>',
 	);
?>
