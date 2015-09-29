<?php $this->load->view('Public/header');?>

		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/ace-skins.min.css" />

		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/jquery-ui-1.10.3.full.min.css" />


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
						<div class="nav-search">
							<a href="<?php echo base_url('Discount/addDis');?>"><button class="btn btn-xs btn-primary">添加折扣</button></a>
						</div>
					</div>

					<div class="page-content">

						<div class="row">
							<div class="col-xs-12">
								<form method="get" action="<?php echo site_url('Discount/disList');?>">

									折扣标题:<input type="text" name="title" value="<?php echo $this->input->get('title');?>">
									&nbsp;
									&nbsp;
									折扣类型:
									<select name="type">
										<option value="">全部</option>
										<?php foreach ($disType as $k => $v) {?>
										<option value="<?php echo $v['key'];?>" <?php echo $v['key'] == $this->input->get('type') ? 'selected' : '' ;?>><?php echo $v['value'];?></option>
										<?php }?>
									</select>
									&nbsp;
									&nbsp;
									品牌:<input type="text" name="brand" value="<?php echo $this->input->get('brand');?>">
									&nbsp;
									&nbsp;
									品牌分类:
									<select name="category">
										<option value="">全部</option>
										<?php foreach ($brandCate as $k => $v) {?>
										<option value="<?php echo $v['id'];?>" <?php echo $v['id'] == $this->input->get('category') ? 'selected' : '' ;?> ><?php echo $v['name'];?></option>
										<?php }?>
									</select>
									&nbsp;
									&nbsp;
									折扣状态:
									<select name="expStat">
										<option value="">全部</option>
										<option value="normal" <?php echo 'normal' == $this->input->get('expStat') ? 'selected' : '' ;?>>未过期</option>
										<option value="over" <?php echo 'over' == $this->input->get('expStat') ? 'selected' : '' ;?>>已过期</option>
									</select>
									<?php if(!empty($getInfo['brandEn'])){?>
									<input type="hidden" name="brandEn" value="<?php echo @$this->input->get('brandEn') == 'asc' ? 'asc' : 'desc';?>">
									<?php }?>
									<button type="submit"><?php echo $this->lang->line('BTN_SEARCH');?></button>
								</form>
								<br>
								<div class="row">
									<div class="col-xs-12">
										<div class="table-responsive">
											<table id="rolelist-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>宣传图</th>
														<th>标题</th>
														<th width="50px;">数据来源</th>
														<th width="30px;">类型</th>
														<th width="85px;">有效期开始</th>
														<th width="85px;">有效期结束</th>	
														<th width="85px;"><a href="<?php echo $orderBrand;?>">品牌英文</a><?php if(!empty($getInfo['brandEn'])){?>
															<b class="arrow icon-angle-<?php echo $this->input->get('brandEn') == 'asc' ? 'up' : 'down';?>"></b>
															<?php }?></th>
														<th width="85px;">品牌中文</th>
														<th width="70px;">所属分类</th>
														<th>描述</th>
														<th>更新时间</th>
														<th>操作人</th>
														<th width="130px;"><?php echo $this->lang->line('TEXT_OPERATION');?></th>
													</tr>
												</thead>

												<tbody>
													<?php foreach ($list as $k => $v):?>
													<tr>
														<td>
															<img src="<?php echo config_item('image_url').$v['brand_pic_url'];?>" width="100px;">	
														</td>
														<td><?php echo $v['name_zh'];?></td>
														<td><?php echo isset($v['data_source']) ? $v['data_source'] : '管理员';?></td>
														<td width="50px;"><?php echo $this->lang->line('TEXT_DISCOUNT_TYPE_'.$v['type']);?></td>
														<td><?php echo $v['begin_date'];?></td>
														<td><?php echo $v['end_date'];?></td>
														<td><?php echo @$v['brand_name_en'];?></td>
														<td><?php echo @$v['brand_name_zh'];?></td>
														<td><?php echo $v['category_name'];?></td>
														<td><?php echo $v['discount_desc'];?></td>
														<td><?php echo $v['update_time'];?></td>
														<td><?php echo @$v['oper'];?></td>
														<td>
															<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
																<a href="<?php echo base_url('Discount/editDis').'?discountId='.$v['id'].'&brandId='.$v['tb_brand_id'];?>">
																	<i class="icon-edit bigger-120">编辑</i>
																</a>
																&nbsp;
																&nbsp;
																<a style="color:red;" onclick="delDiscount('<?php echo $v['id'];?>', '<?php echo $v['tb_brand_id'];?>');">
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

		<script src="<?php echo config_item('html_url');?>js/ace-elements.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace.min.js"></script>

		<script type="text/javascript">

			function delDiscount(discountId, brandId){
				if (!confirm("<?php echo $this->lang->line('TEXT_CONFIRM_DEL_DISCOUNT');?>")) {
					return false;
				}

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Discount/delDis');?>",
					data:{discountId:discountId, brandId:brandId},
					success:function(data){
						if (data.status == '0') {
							window.location.reload();
						}else{
							alert(data.msg);
						}
						
					}
				});
			}
		</script>

	</body>
</html>
