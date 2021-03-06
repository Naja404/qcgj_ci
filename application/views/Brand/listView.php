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
								<a href="<?php echo site_url('Brand/listView');?>"><?php echo $this->lang->line('TITLE_BRAND_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TITLE_BRAND_LIST');?></li>
						</ul>
						<div class="nav-search">
							<a href="<?php echo base_url('Brand/addBrand');?>"><button class="btn btn-xs btn-primary">添加品牌</button></a>
							<?php if($this->input->get('showall') != 'yes'){?>
							<a href="<?php echo base_url('Brand/listView').'?showall=yes';?><?php echo $this->input->get('category') == 'yes' ? '&category=yes' : '' ; ?>"><button class="btn btn-xs btn-primary">显示全部</button></a>
							<?php }else{ ?>
							<a href="<?php echo base_url('Brand/listView');?><?php echo $this->input->get('category') == 'yes' ? '?category=yes' : '' ; ?>"><button class="btn btn-xs btn-primary">显示正常</button></a>
							<?php }?>
						</div>
					</div>

					<div class="page-content">

						<div class="row">
							<div class="col-xs-12">
								<form method="get" action="<?php echo site_url('Brand/listView');?>">

									<?php echo $this->lang->line('TEXT_SHOP_BRANDNAME');?>:<input type="text" name="brand" value="<?php echo $this->input->get('brand');?>"/>

									<button type="submit"><?php echo $this->lang->line('BTN_SEARCH');?></button>
									
									<?php if($this->input->get('category') != 'yes'){?>
									<a  href="<?php echo site_url('Brand/listView').'?category=yes';?>">只看空分类</a>
									<?php }else{ ?>
									<a  href="<?php echo site_url('Brand/listView');?>">查看全部</a>
									<?php } ?>

									<?php if($this->input->get('showall') == 'yes'){?>
									<input type="hidden" name="showall" value="yes">
									<?php } ?>
								</form>
							</div>
							<br>
							<br>
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-12">
										<div class="table-responsive">
											<table id="rolelist-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th><?php echo $this->lang->line('TEXT_LOGO');?></th>
														<th><a href="<?php echo $orderBrand;?>"><?php echo $this->lang->line('TEXT_NAME_EN');?></a><?php if(!empty($getInfo['brandEn'])){?>
															<b class="arrow icon-angle-<?php echo $this->input->get('brandEn') == 'asc' ? 'up' : 'down';?>"></b>
															<?php }?></th>
														<th><?php echo $this->lang->line('TEXT_NAME_ZH');?></th>
														<th><?php echo $this->lang->line('TEXT_PIC_URL');?></th>
														<th style="width:200px;"><?php echo $this->lang->line('TEXT_DESCRIPTION');?></th>
														<th>分类</th>
														<th><?php echo $this->lang->line('TEXT_CREATED_TIME');?></th>
														<th><?php echo $this->lang->line('TEXT_UPDATED_TIME');?></th>
														<th>状态</th>
														<th><?php echo $this->lang->line('TEXT_OPERATION_USER');?></th>
														<th><?php echo $this->lang->line('TEXT_OPERATION');?></th>
													</tr>
												</thead>

												<tbody>
													<?php foreach ($brandList as $v):?>
													<tr>
														<td>
															<img src="<?php echo config_item('image_url').$v->logo_url;?>" width="100px">
														</td>
														<td style="width:100px;"><?php echo $v->name_en;?></td>
														<td style="width:100px;"><?php echo $v->name_zh;?></td>
														<td style="width:100px;">
															<?php $pic = explode(',', $v->pic_url);?>
															<?php foreach($pic as $j => $m):?>
																<?php if ($m) { $m = explode('|', $m);?>
																<a href="<?php echo config_item('image_url').$m[0];?>" target="_blank">图<?php echo $j+1;?></a>
																<?php }?>
															<?php endforeach;?>
														</td>
														<td><?php echo $v->summary;?></td>
														<td style="width:50px;"><?php echo $v->category;?></td>
														<td><?php echo $v->create_time;?></td>
														<td><?php echo $v->update_time;?></td>
														<td id="cidTd_<?php echo $v->id;?>">
															<span class="label label-<?php echo $v->status == 1 ? 'info' : 'danger';?>"><?php echo $v->status == 1 ? '显示' : '隐藏';?></span>
														</td>
														<td style="width:50px;"><?php echo $v->oper;?></td>
														<td style="width:120px;">
															<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
																	<span id="cid_<?php echo $v->id;?>">
																	<?php if($v->status == 0){?>
																	<a onclick="upBrand('<?php echo $v->id;?>', '1');">
																		<i class="icon-ok bigger-120">显示</i>
																	</a>
																	<?php }else{ ?>
																	<a onclick="upBrand('<?php echo $v->id;?>', '0');">
																		<i class="icon-remove bigger-120 green">隐藏</i>
																	</a>
																	<?php } ?>
																	</span>
																	&nbsp;&nbsp;
																<!-- <button class="btn btn-xs btn-info"> -->
																	<a href="<?php echo site_url('Brand/editBrand').'?brandId='.strEncrypt($v->id).'&p='.$this->input->get('p').'&category='.$this->input->get('category');?>"><i class="icon-edit bigger-120"></i>编辑</a>
																<!-- </button> -->

																<a style="color:red;" onclick="delBrand('<?php echo $v->id;?>');">
																	<i class="icon-trash bigger-120">删除</i>
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
							</div><!-- /.col -->
							<?php echo $pagination;?>
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
			function upBrand(brandId, status){
				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Brand/upBrand');?>",
					data:{brandId:brandId, status:status},
					success:function(data){
						if (data.status == '0') {
							$("#cid_"+brandId).html(data.spanDiv);
							$("#cidTd_"+brandId).html(data.tdDiv);
						}else{
							alert(data.msg);
						}
						
					}
				});
			}
			function delBrand(brandId){
				if (!confirm("<?php echo $this->lang->line('TEXT_CONFIRM_DELBRAND');?>")) {
					return false;
				}

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Brand/delBrand');?>",
					data:{brandId:brandId},
					success:function(data){
						window.location.reload();
					}
				});
			}
		</script>

	</body>
</html>
