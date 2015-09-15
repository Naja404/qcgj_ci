<?php $this->load->view('Public/header');?>

		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/ace-skins.min.css" />
		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/datepicker.css" />
		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/daterangepicker.css" />


		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="<?php echo config_item('html_url');?>css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->

		<script src="<?php echo config_item('html_url');?>js/ace-extra.min.js"></script>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="<?php echo config_item('html_url');?>js/html5shiv.js"></script>
		<script src="<?php echo config_item('html_url');?>js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>

		<?php $this->load->view('Public/navbar');?>

		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div class="main-container-inner">
				<a class="menu-toggler" id="menu-toggler" href="#">
					<span class="menu-text"></span>
				</a>

				<?php $this->load->view('Public/sidebar');?>

				<div class="main-content">
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="icon-home home-icon"></i>
								<a href="<?php echo config_item('base_url');?>"><?php echo $this->lang->line('TEXT_HOME_PAGE');?></a>
							</li>

							<li>
								<a href="<?php echo site_url('Discount/disList');?>"><?php echo $this->lang->line('TITLE_DISCOUNT_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TITLE_DISCOUNT_LIST');?></li>
						</ul>
					</div>

					<div class="page-content">

						<div class="row">
							<div class="col-xs-12">
								<form method="get" action="<?php echo site_url('Approve/comment');?>">

									评论目标:
									<select name="type">
										<option value="">全部</option>
										<?php foreach($commentType as $k => $v){?>
										<option value="<?php echo $k;?>" <?php echo $k == $this->input->get('type') ? 'selected' : '' ;?>><?php echo $v;?></option>
										<?php }?>
									</select>
									&nbsp;
									&nbsp;
									评论状态:
									<select name="status">
										<option value="all">全部</option>
										<?php foreach ($commentStatus as $k => $v) {?>
										<option value="<?php echo $k;?>" <?php echo (string)$k === $this->input->get('status') ? 'selected' : '' ;?>><?php echo $v;?></option>
										<?php }?>
									</select>
									&nbsp;
									&nbsp;
									评论星级:
									<select name="grade">
										<option value="">全部</option>
										<?php for ($i=1; $i <= 5; $i++) { ?>
										<option value="<?php echo $i;?>" <?php echo (string)$i === $this->input->get('grade') ? 'selected' : '' ;?>><?php echo $i;?></option>
										<?php }?>
									</select>
									&nbsp;
									&nbsp;
									用户手机:<input type="text" name="mobile" value="<?php echo $this->input->get('mobile');?>">
									<br><br>
									商户名称:<input type="text" name="shop" value="<?php echo $this->input->get('shop');?>" style="width:200px;">
									评论内容:<input type="text" name="content" value="<?php echo $this->input->get('content');?>" style="width:200px;">
									<br><br>
									评论时间:<input type="text" name="pubTime" id="pubTime" value="<?php echo $this->input->get('pubTime');?>" style="width:200px;">
									<br><br>
									审核时间:<input type="text" name="approveTime" id="approveTime" value="<?php echo $this->input->get('approveTime');?>" style="width:200px;">
									<button type="submit"><?php echo $this->lang->line('BTN_SEARCH');?></button>
									&nbsp;
									&nbsp;
									<a href="<?php echo site_url('Approve/comment');?>">清空</a>
								</form>
								<br>
								<div class="row">
									<div class="col-xs-12">
										<div class="table-responsive">
											<table id="rolelist-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th width="50px;">评论目标</th>
														<th width="80px;">状态</th>
														<th width="50px;">用户手机</th>
														<th width="100px;">商户名称</th>
														<th width="50px;">评论星级</th>
														<th>评论内容</th>	
														<th width="50px;">评论图片</th>
														<th width="100px;">评论时间</th>
														<th width="100px;">审核时间</th>
														<th width="80px;">审核人</th>
														<th width="150px;"><?php echo $this->lang->line('TEXT_OPERATION');?></th>
													</tr>
												</thead>

												<tbody>
													<?php foreach ($list as $k => $v):?>
													<tr>
														<td><?php echo $commentType[$v->type];?></td>
														<td id="cidTd_<?php echo $v->commentId;?>"><span class="label label-<?php echo (int)$v->status == 1 ? 'info' : 'danger';?>"><?php echo $commentStatus[$v->status];?></span></td>
														<td><?php echo $v->mobile;?></td>
														<td><?php echo $v->shopName;?></td>
														<td><?php echo $v->grade;?></td>
														<td><?php echo $v->comment;?></td>
														<td><?php if(!empty($v->picUrl)){?>
															<a onclick="preComment('<?php echo base_url("Approve/preComment")."?commentId=".$v->commentId;?>')" >查看图片</a>
															<?php }?>
														</td>
														<td><?php echo $v->pubTime;?></td>
														<td><?php echo $v->approveTime;?></td>
														<td><?php echo $v->operName;?></td>
														<td>
															<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
																<span id="cid_<?php echo $v->commentId;?>">
																<?php if($v->status != 1){?>
																	<a onclick="upComment('<?php echo $v->commentId;?>', '1');">
																		<i class="icon-ok bigger-120">通过</i>
																	</a>
																<?php }else{ ?>
																<a onclick="upComment('<?php echo $v->commentId;?>', '0');">
																	<i class="icon-remove bigger-120 green">不通过</i>
																</a>
																<?php }?>
																</span>
																&nbsp;
																&nbsp;
																<a style="color:red;" onclick="">
																	<i class="icon-edit bigger-120">删除</i>
																</a>
															</div>

														</td>
													</tr>
													<?php endforeach;?>
												</tbody>
											</table>
										</div><!-- /.table-responsive -->

									</div><!-- /span -->
								</div>
								<?php echo $page;?>
							</div><!-- /.col -->
						</div><!-- /.row -->

					</div><!-- /.page-content -->
				</div><!-- /.main-content -->
			</div><!-- /.main-container-inner -->

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="icon-double-angle-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->

		<script src="<?php echo config_item('html_url');?>js/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo config_item('html_url');?>js/jquery-ui-1.10.3.full.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
		<script src="<?php echo config_item('html_url');?>js/jquery-1.10.2.min.js"></script>
		<![endif]-->

		<!--[if !IE]> -->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo config_item('html_url');?>js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
		<script type="text/javascript">
		 window.jQuery || document.write("<script src='<?php echo config_item('html_url');?>js/jquery-1.10.2.min.js'>"+"<"+"/script>");
		</script>
		<![endif]-->

		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='<?php echo config_item('html_url');?>js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo config_item('html_url');?>js/bootstrap.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/typeahead-bs2.min.js"></script>

		<!-- ace scripts -->
		<script src="<?php echo config_item('html_url');?>js/date-time/moment.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/date-time/daterangepicker.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace-elements.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace.min.js"></script>

		<script type="text/javascript">

			jQuery(function($) {
						// 日期选择
				$('input[name=pubTime]').daterangepicker().prev().on(ace.click_event, function(){
					$(this).next().focus();
				});
				$('input[name=approveTime]').daterangepicker().prev().on(ace.click_event, function(){
					$(this).next().focus();
				});
			})

			function upComment(commentId, status){

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Approve/upComment');?>",
					data:{commentId:commentId, status:status},
					success:function(data){
						if (data.status == '0') {
							$("#cid_"+commentId).html(data.spanDiv);
							$("#cidTd_"+commentId).html(data.tdDiv);
						}else{
							alert(data.msg);
						}
						
					}
				});
			}

			function preComment(url){
					window.open(url, '', 'toolbar=no,height=800,width=800');
			}
		</script>

	</body>
</html>
