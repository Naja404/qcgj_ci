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
								<a >品牌管理</a>
							</li>
							<li class="active">商场列表</li>
						</ul>
						<div class="nav-search">
							<a href="<?php echo base_url('Brand/addMall');?>"><button class="btn btn-xs btn-primary">添加商场</button></a>
						</div>
					</div>

					<div class="page-content">

						<div class="row">
							<div class="col-xs-12">
								<form method="get" action="<?php echo site_url('Brand/mallList');?>">
									<?php echo $this->lang->line('TEXT_SELECT_CITY');?>:
									<select name="city" id="citySelect">
											<option value="">城市</option>
										<?php foreach($cityList as $city){?>
											<option value="<?php echo $city->name;?>" <?php echo $city->name == $this->input->get('city') ? 'selected' : '' ;?>><?php echo $city->name;?></option>
										<?php }?>
									</select>

									<select name="district" id="districtSelect">
											<option value="">区域</option>
										<?php foreach($districtList as $v){?>
											<option value="<?php echo $v->name;?>" <?php echo $v->name == $this->input->get('district') ? 'selected' : '' ;?>><?php echo $v->name;?></option>
										<?php }?>
									</select>

									<?php echo $this->lang->line('TEXT_SHOP_NAME');?>:<input type="text" name="shop" value="<?php echo $this->input->get('shop');?>"/>

									<?php echo $this->lang->line('TEXT_SHOP_ADDRESS');?>:<input type="text" name="address" value="<?php echo $this->input->get('address');?>"/>
									<button type="submit"><?php echo $this->lang->line('BTN_SEARCH');?></button>
									<br>
									<br>
								</form>
							</div>
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-12">
										<div class="table-responsive">
											<table id="rolelist-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>商场图片</th>
														<th>商场名</th>
														<th>城市</th>
														<th>区域</th>
														<th>地址</th>
														<th>状态</th>
														<th><?php echo $this->lang->line('TEXT_OPERATION');?></th>
													</tr>
												</thead>

												<tbody>
													<?php foreach ($mallList as $v):?>
													<tr >
														<td><img src="<?php echo config_item('image_url').$v->thumb_url;?>" width="100px;"></td>
														<td id="<?php echo $v->id;?>">
																<?php echo $v->name_zh;?>
														</td>
														<td>
															<?php echo $v->city_name;?>
														</td>
														<td>
															<?php echo $v->district_name;?>
														</td>
														<td>
															<?php echo $v->address;?>
														</td>
														<td id="cidTd_<?php echo $v->id;?>">
															<span class="label label-<?php echo $v->status == 1 ? 'info' : 'danger';?>"><?php echo $v->status == 1 ? '显示' : '隐藏';?></span>
														</td>
														<td>
															<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

																<!-- <button class="btn btn-xs btn-info"> -->
																	<span id="cid_<?php echo $v->id;?>">
																	<?php if($v->status == 0){?>
																	<a onclick="upMall('<?php echo $v->id;?>', '1');">
																		<i class="icon-ok bigger-120">显示</i>
																	</a>
																	<?php }else{ ?>
																	<a onclick="upMall('<?php echo $v->id;?>', '0');">
																		<i class="icon-remove bigger-120 green">隐藏</i>
																	</a>
																	<?php } ?>
																	</span>
																	&nbsp;&nbsp;
																	<a href="<?php echo site_url('Brand/editMall').'?id='.strEncrypt($v->id).'&p='.$this->input->get('p').'&mark='.$v->id;?>"><i class="icon-edit bigger-120">编辑</i></a>

																<!-- </button> -->
<!-- 																<button class="btn btn-xs btn-danger" onclick="delMall('<?php echo $v->id;?>');">
																	<i class="icon-trash bigger-120"></i>
																</button> -->

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
			$(document).ready(function() {
				$(window.location.hash).css('background-color', '#f2849f');
			});

			jQuery(function($) {
				$('#citySelect').on('change', function(){
					$.ajax({
						type:"POST",
						url:"<?php echo site_url('Brand/getDistrictByCity');?>",
						data:{city:this.value},
						success:function(data){
							$('#districtSelect').html(data.html);
						}
					});
				});
			});
			
			function delMallbak(mallId){
				if (!confirm("<?php echo $this->lang->line('TEXT_CONFIRM_DELBRAND');?>")) {
					return false;
				}

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('HongQiao/delMall');?>",
					data:{mallId:mallId},
					success:function(data){
						window.location.reload();
					}
				});
			}

			function upMall(mallId, status){
				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Brand/upMall');?>",
					data:{mallId:mallId, status:status},
					success:function(data){
						if (data.status == '0') {
							$("#cid_"+mallId).html(data.spanDiv);
							$("#cidTd_"+mallId).html(data.tdDiv);
						}else{
							alert(data.msg);
						}
						
					}
				});
			}
		</script>

	</body>
</html>
