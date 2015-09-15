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
								<a href="<?php echo site_url('Approve/comment');?>"><?php echo $this->lang->line('TITLE_APPROVE_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TITLE_YMT_LIST');?></li>
						</ul>
					</div>

					<div class="page-content">

						<div class="row">
							<div class="col-xs-12">
								<form method="get" action="<?php echo site_url('Approve/ymtPic');?>">

									评论状态:
									<select name="status">
										<option value="all">全部</option>
										<?php foreach ($ymtStatus as $k => $v) {?>
										<option value="<?php echo $k;?>" <?php echo (string)$k === $this->input->get('status') ? 'selected' : '' ;?>><?php echo $v;?></option>
										<?php }?>
									</select>
									&nbsp;
									&nbsp;
									省份:
									<select name="province">
										<option value="">全部</option>
										<?php foreach ($provinceList as $k => $v) {?>
										<option value="<?php echo $v->id;?>" <?php echo (string)$v->id === $this->input->get('province') ? 'selected' : '' ;?>><?php echo $v->name;?></option>
										<?php }?>
									</select>
									&nbsp;
									&nbsp;
									姓名:<input type="text" name="name" value="<?php echo $this->input->get('name');?>">
									<br><br>
									编&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号:<input type="text" name="no" value="<?php echo $this->input->get('no');?>" style="width:200px;">
									辣妈宣言:<input type="text" name="slogan" value="<?php echo $this->input->get('slogan');?>" style="width:200px;">
									<br><br>
									创建时间:<input type="text" name="createTime" id="createTime" value="<?php echo $this->input->get('createTime');?>" style="width:200px;">
									<br><br>
									更新时间:<input type="text" name="updateTime" id="updateTime" value="<?php echo $this->input->get('updateTime');?>" style="width:200px;">
									<button type="submit"><?php echo $this->lang->line('BTN_SEARCH');?></button>
									&nbsp;
									&nbsp;
									<a href="<?php echo site_url('Approve/ymtPic');?>">清空</a>
								</form>
								<br>
								<div class="row">
									<div class="col-xs-12">
										<div class="table-responsive">
											<table id="rolelist-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th width="50px;">姓名</th>
														<th width="80px;">照片</th>
														<th width="50px;">省份</th>
														<th width="100px;">编号</th>
														<th width="150px;">辣妈宣言</th>
														<th width="80px;">审核状态</th>	
														<th width="100px;">创建时间</th>
														<th width="100px;">更新时间</th>
														<th width="150px;"><?php echo $this->lang->line('TEXT_OPERATION');?></th>
													</tr>
												</thead>

												<tbody>
													<?php foreach ($list as $k => $v):?>
													<tr>
														<td><?php echo $v->name;?></td>
														<td>
															<img src="<?php echo config_item('image_url').$v->picUrl;?>" width="200px">
														</td>
														<td><?php echo $v->provinceName;?>
														</td>
														<td><?php echo $v->no;?>
														</td>
														<td><?php echo $v->slogan;?>
														</td>
														<td id="cidTd_<?php echo $v->ymtId;?>">
															<span class="label label-<?php echo (int)$v->status == 1 ? 'info' : 'danger';?>"><?php echo $ymtStatus[$v->status];?></span>
														</td>
														<td><?php echo $v->create_time;?></td>
														<td><?php echo $v->update_time;?></td>
														<td>
															<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
																<span id="cid_<?php echo $v->ymtId;?>">
																<?php if($v->status != 1){?>
																	<a onclick="upYmtPic('<?php echo $v->ymtId;?>', '1');">
																		<i class="icon-ok bigger-120">通过</i>
																	</a>
																<?php }else{ ?>
																<a onclick="upYmtPic('<?php echo $v->ymtId;?>', '2');">
																	<i class="icon-remove bigger-120 green">不通过</i>
																</a>
																<?php }?>
																</span>
																&nbsp;
																&nbsp;
																<a href="<?php echo site_url('Approve/editYmt').'?yid='.$v->ymtId;?>" target="_blank">
																	<i class="icon-edit bigger-120"></i>编辑
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
				$('input[name=createTime]').daterangepicker().prev().on(ace.click_event, function(){
					$(this).next().focus();
				});
				$('input[name=updateTime]').daterangepicker().prev().on(ace.click_event, function(){
					$(this).next().focus();
				});
			})

			function upYmtPic(ymtId, status){

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Approve/upYmtPic');?>",
					data:{ymtId:ymtId, status:status},
					success:function(data){
						if (data.status == '0') {
							$("#cid_"+ymtId).html(data.spanDiv);
							$("#cidTd_"+ymtId).html(data.tdDiv);
						}else{
							alert(data.msg);
						}
						
					}
				});
			}

		</script>

	</body>
</html>
