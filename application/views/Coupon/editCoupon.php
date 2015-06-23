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
								<a href="<?php echo site_url();?>"><?php echo $this->lang->line('TEXT_HOME_PAGE');?></a>
							</li>

							<li>
								<a href="{:U('Coupon/rolelist')}"><?php echo $this->lang->line('TEXT_COUPON_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TEXT_COUPON_ADDCOUPON');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="row">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-12">

								<div class="row-fluid">
									<div class="span12">
										<div class="widget-box">
											<div class="widget-header widget-header-blue widget-header-flat">
												<h4 class="lighter"><?php echo $this->lang->line('TEXT_COUPON_ADDCOUPON');?></h4>
											</div>

											<div class="widget-body">
												<div class="widget-main">
													<hr />
													<div class="step-content row-fluid position-relative" id="step-container">
														<div class="step-pane active" id="step1">

															<form class="form-horizontal" id="editCouponform" method="post" enctype="multipart/form-data" >
																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponTitle"><?php echo $this->lang->line('TEXT_COUPON_TITLE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<input type="text" name="couponTitle" id="couponTitle" class="col-xs-12 col-sm-6" maxlength="40" placeholder="<?php echo $this->lang->line('TEXT_COUPON_TITLE_PLACEHOLDER');?>" value="<?php echo $couponData->name;?>"/>
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right"><?php echo $this->lang->line('TEXT_COUPON_TYPE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="row">
																		<div class="col-xs-3">
																			<label class="blue">
																				<input name="couponType" value="1" type="radio" class="ace" <?php echo $couponData->coupon_type === '1' ? 'checked' : '';?> />
																				<span class="lbl"><?php echo $this->lang->line('TEXT_COUPON_VOUCHERS');?></span>
																			</label>
																		</div>

																		<div class="col-xs-3">
																			<label class="blue">
																				<input name="couponType" value="102" type="radio" class="ace" <?php echo $couponData->coupon_type === '102' ? 'checked' : '';?>/>
																				<span class="lbl"><?php echo $this->lang->line('TEXT_COUPON_DISCOUNT');?></span>
																			</label>
																		</div>

																		<div class="col-xs-3">
																			<label class="blue">
																				<input name="couponType" value="103" type="radio" class="ace" <?php echo $couponData->coupon_type === '103' ? 'checked' : '';?> />
																				<span class="lbl"><?php echo $this->lang->line('TEXT_COUPON_DELIVERY');?></span>
																			</label>
																		</div>

																		</div>
																	</div>
																</div>

																<div class="space-2"></div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right"><?php echo $this->lang->line('TEXT_COUPON_MONEY');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="row">
																		<div class="col-xs-3">
																			<label class="blue">
																				<input name="couponMoney" value="1" type="radio" class="ace" <?php echo $couponData->cost_price > 0 ? '' : 'checked';?> />
																				<span class="lbl"><?php echo $this->lang->line('TEXT_COUPON_FREE');?></span>
																			</label>
																		</div>

																		<div class="col-xs-3">
																			<label class="blue">
																				<input name="couponMoney" value="2" type="radio" class="ace" <?php echo $couponData->cost_price > 0 ? 'checked' : '';?> />
																				<span class="lbl"><?php echo $this->lang->line('TEXT_COUPON_TOLL');?></span>
																				<div class="form-inline">
																				<input type="text" name="couponMoneyNum" class="input-small" placeholder="如:50.00" value="<?php echo $couponData->cost_price > 0 ? $couponData->cost_price : '';?>"/>
																				</div>
																			</label>

																		</div>
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponSum"><?php echo $this->lang->line('TEXT_COUPON_SUM');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<input type="text" class="input-mini" id="couponSum" name="couponSum" />
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponEveryoneSum"><?php echo $this->lang->line('TEXT_COUPON_EVERYONE_SUM');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<input type="text" class="input-mini" id="couponEveryoneSum" name="couponEveryoneSum"/>
																		</div>
																	</div>
																</div>

																<div class="hr hr-dotted"></div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponExpireDate"><?php echo $this->lang->line('TEXT_COUPON_EXPIRE');?>:</label>
																	<div class="col-xs-12 col-sm-9">
																		<div class="input-group">
																			<span class="input-group-addon">
																				<i class="icon-calendar bigger-110"></i>
																			</span>

																			<input type="text" name="couponExpireDate" id="couponExpireDate" class="col-xs-6 col-sm-4" value="<?php echo $couponData->couponExpireDate;?>"/>
																		</div>
																	</div>
																</div>

																<div class="space-2"></div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponReceiveDate"><?php echo $this->lang->line('TEXT_COUPON_RECEIVE');?>:</label>
																	<div class="col-xs-12 col-sm-9">
																		<div class="input-group">
																			<span class="input-group-addon">
																				<i class="icon-calendar bigger-110"></i>
																			</span>

																			<input type="text" name="couponReceiveDate" id="couponReceiveDate" class="col-xs-6 col-sm-4" value="<?php echo $couponData->couponReceiveDate;?>"/>
																		</div>
																	</div>
																</div>

																<div class="space-2"></div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponUseTime"><?php echo $this->lang->line('TEXT_COUPON_USE_TIME');?>:</label>

																	<div class="col-xs-12 col-sm-8">
																		<div class="row">
																			<div class="col-xs-6">
																			<div class="input-group bootstrap-timepicker">
																				<span class="input-group-addon">
																					<i class="icon-time bigger-110"></i>
																				</span>
																				<input id="couponUseTimeStart" type="text" name="couponUseTimeStart" class="col-xs-6 col-sm-4" value="10:00:00"/>
																			</div>
																			</div>

																			<div class="col-xs-6">
																				<div class="input-group bootstrap-timepicker">
																					<span class="input-group-addon">
																						<i class="icon-time bigger-110"></i>
																					</span>
																					<input id="couponUseTimeEnd" type="text" name="couponUseTimeEnd" class="col-xs-6 col-sm-4" value="20:00:00"/>
																				</div>
																			</div>

																		</div>
																	</div>
																</div>

																<div class="hr hr-dotted"></div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponUseGuide"><?php echo $this->lang->line('TEXT_COUPON_USE_GUIDE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<textarea class="input-xlarge" name="couponUseGuide" id="couponUseGuide" placeholder="<?php echo $this->lang->line('TEXT_COUPON_USE_GUIDE_PLACEHOLDER');?>"><?php echo $couponData->coupon_desc;?></textarea>
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponNotice"><?php echo $this->lang->line('TEXT_COUPON_NOTICE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<textarea class="input-xlarge" name="couponNotice" id="couponNotice" placeholder="<?php echo $this->lang->line('TEXT_COUPON_USE_GUIDE_PLACEHOLDER');?>"><?php echo $couponData->recommend_desc;?></textarea>
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponVerification"><?php echo $this->lang->line('TEXT_COUPON_VERIFICATION');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<textarea class="input-xlarge" name="couponVerification" id="couponVerification" placeholder="<?php echo $this->lang->line('TEXT_COUPON_USE_GUIDE_PLACEHOLDER');?>"></textarea>
																		</div>
																	</div>
																</div>

																<div class="hr hr-dotted"></div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right"><?php echo $this->lang->line('TEXT_COUPON_CODE_TYPE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div>
																			<label>
																				<input name="couponAutoCode" value="1" type="checkbox" class="ace" id="couponAutoCode" <?php echo $couponData->gene_type == '1' ? 'checked' : '';?>/>
																				<span class="lbl" for="couponAutoCode"><?php echo $this->lang->line('TEXT_COUPON_AUTO_CODE');?></span>
																			</label>
																		</div>
																	</div>
																</div>
																<!-- 图片上传 -->
																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right"><?php echo $this->lang->line('TEXT_COUPON_IMAGE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																				<?php if (isset($couponData->brand_pic_url) && !empty($couponData->brand_pic_url)) {?>
																				<span id="fileImageSpan">
																					<img src="<?php echo site_url().''.$couponData->brand_pic_url;?>" style="width: 200px;"><a onclick="removeFileImg();">重置</a>
																				</span>
																				<?php } ?>
																		<div>
																			<label>
																				<input type="file" name="image" class="ace" id="fileImage" />
																				<input type="hidden" name="couponPic" value="<?php echo isset($couponData->brand_pic_url) && !empty($couponData->brand_pic_url) ? $couponData->brand_pic_url : '';?>"/>
																			</label>
																		</div>
																	</div>
																</div>
																
																<div class="hr hr-dotted"></div>
																<div class="form-group">
																	<select name="cityName" id="citySelect">
																		<option value=""><?php echo $this->lang->line('TEXT_CITY');?></option>
																		<?php foreach ($cityList as $k => $v) {?>
																		<option value="<?php echo $v['cityId'];?>"><?php echo $v['name'];?></option>
																		<?php }?>
																	</select>
																	<select name="areaName" id="areaSelect">
																		<option value=""><?php echo $this->lang->line('TEXT_AREA');?></option>
																		<?php foreach ($areaList as $k) {?>
																		<option value="<?php echo $k;?>"><?php echo $k;?></option>
																		<?php }?>
																	</select>
																</div>
																<div class="form-group">
																	<div class="row-fluid">
																		<div class="col-xs-12">
																			<div class="table-responsive">
																				<table class="table table-striped table-bordered table-hover">
																					<thead>
																						<tr>
																							<th class="center">
																								<label>
																									<input type="checkbox" class="ace" id="cityListCheck"/>
																									<span class="lbl"></span>
																								</label>
																							</th>
																							<th><?php echo $this->lang->line('TEXT_CITY_NAME');?></th>
																							<th><?php echo $this->lang->line('TEXT_AREA_NAME');?></th>
																							<th><?php echo $this->lang->line('TEXT_MALL_NAME');?></th>
																							<th><?php echo $this->lang->line('TEXT_ADDRESS');?></th>
																						</tr>
																					</thead>

																					<tbody id="shopListHTML">
																						<?php foreach ($shopList as $k => $v):?>
																						<tr>
																							<td class="center">
																								<label>
																									<input type="checkbox" class="ace" name="mallID[]" value="<?php echo $v['mallID']?>" <?php echo isset($v['checked']) && $v['checked'] ? 'checked' : '';?>/>
																									<span class="lbl"></span>
																								</label>
																							</td>
																							<td><?php echo $v['cityName'];?></td>
																							<td><?php echo $v['areaName'];?></td>
																							<td>
																								<a href="#"><?php echo $v['mallName'];?></a>
																							</td>
																							<td>
																								<a href="#"><?php echo $v['address'];?></a>
																							</td>
																						</tr>
																						<?php endforeach;?>
																					</tbody>
																				</table>
																			</div><!-- /.table-responsive -->

																		</div><!-- /span -->
																	</div>
																</div>
																<div class="space-8"></div>
																
																<div class="hr hr-dotted"></div>

																<div class="form-group">

																	<div class="col-xs-12 col-sm-9">
																		<div>
																			<label class="blue">
																				<input name="reviewPass" value="1" type="radio" class="ace" checked/>
																				<span class="lbl"> <?php echo $this->lang->line('TEXT_REVIEW_AUTOPASS');?></span>
																			</label>
																		</div>

																		<div>
																			<label class="blue">
																				<input name="reviewPass" value="2" type="radio" class="ace" <?php echo $couponData->on_sale_time ? 'checked' : '';?>/>
																				<span class="lbl"> <?php echo $this->lang->line('TEXT_REVIEW_PASSTIME');?></span>
																			</label>
																			<div class="input-group col-xs-12 col-sm-3" style="float:right;">
																				<input class="form-control date-picker" type="text" name="reviewPassDate" data-date-format="yyyy-mm-dd" value="<?php echo $couponData->on_sale_time ? date('Y-m-d', strtotime($couponData->on_sale_time)) : '';?>"/>
																				<span class="input-group-addon">
																					<i class="icon-calendar bigger-110"></i>
																				</span>
																			</div>

																		</div>
																		<div>
																			<label class="blue">
																				<input name="reviewPass" value="3" type="radio" class="ace" />
																				<span class="lbl"> <?php echo $this->lang->line('TEXT_REVIEW_MANUALPASS');?></span>
																			</label>
																		</div>
																	</div>
																</div>

															</form>
														</div>

													</div>

													<hr />
													<div class="row-fluid wizard-actions">
														<button class="btn btn-success" onclick="subEditCouponForm();">
															<?php echo $this->lang->line('BTN_UPDATE');?>
															<i class="icon-arrow-right icon-on-right"></i>
														</button>
													</div>
												</div><!-- /widget-main -->
											</div><!-- /widget-body -->
										</div>
									</div>
								</div>

									</div><!-- /span -->
								</div>
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

		<!-- page specific plugin scripts -->
		<script src="<?php echo config_item('html_url');?>js/fuelux/fuelux.spinner.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/date-time/bootstrap-datepicker.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/date-time/bootstrap-timepicker.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/date-time/moment.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/date-time/daterangepicker.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/jquery.inputlimiter.1.3.1.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/fuelux/fuelux.wizard.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/jquery.validate.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/additional-methods.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/bootbox.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/jquery.maskedinput.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/select2.min.js"></script>
		
		<!-- ace scripts -->

		<script src="<?php echo config_item('html_url');?>js/ace-elements.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace.min.js"></script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript" src="<?php echo config_item('html_url');?>js/jquery.ajaxfileupload.js"></script>
		<script type="text/javascript">

		$(document).ready(function() {
			var interval;

			function applyAjaxFileUpload(element) {
				$(element).AjaxFileUpload({
					action: "<?php echo site_url('Coupon/uploadCouponPic');?>",
					onChange: function(filename) {
						// Create a span element to notify the user of an upload in progress
						var $span = $("<span />")
							.attr("class", $(this).attr("id"))
							.text("Uploading")
							.insertAfter($(this));

						$(this).remove();

						interval = window.setInterval(function() {
							var text = $span.text();
							if (text.length < 13) {
								$span.text(text + ".");
							} else {
								$span.text("Uploading");
							}
						}, 200);
					},
					onSubmit: function(filename) {
						return true;
					},
					onComplete: function(filename, response) {
						window.clearInterval(interval);
						console.log(response);
						var $span = $("span." + $(this).attr("id")).text(filename + " "),
							$fileInput = $("<input />")
								.attr({
									type: "file",
									name: $(this).attr("name"),
									id: $(this).attr("id")
								});

						if (response.status) {
							$span.replaceWith($fileInput);

							applyAjaxFileUpload($fileInput);

							alert(response.msg);

							return;
						}else{
							$('#fileImageSpan').html('');
							$("<img />").attr("src", response.url).css("width", 200).appendTo($span);
							$("<a />").attr("href", "#").text("<?php echo $this->lang->line('BTN_RESET');?>").bind("click", function(e) {
									$span.replaceWith($fileInput);
									applyAjaxFileUpload($fileInput);
									$("input[name=couponPic]").val('');
								}).appendTo($span);
							$("input[name=couponPic]").val(response.path);
						}
					}
				});
			}

			applyAjaxFileUpload("#fileImage");
		});

			jQuery(function($) {
				var areaSelect = '<?php echo json_encode($shopList);?>';
					areaSelectValue = this.value;

				$('#citySelect').on('change', function(){
					var areaHTML_1 = '<?php echo $bjAreaList;?>';
						areaHTML_2 = '<?php echo $shAreaList?>';
						areaHTML_3 = '<?php echo $gzAreaList;?>';
						areaHTML = '';

					switch(this.value){
						case ('1'):
						areaHTML = areaHTML_1;
						break;
						case ('2'):
						areaHTML = areaHTML_2;
						break;
						case ('3'):
						areaHTML = areaHTML_3;
						break;
						default:
						areaHTML = "<option><?php echo $this->lang->line('TEXT_SELECT_CITY_NAME');?></option>";
						shopListHTML = '';
						$.each($.parseJSON(areaSelect), function(k, v){
							shopListHTML += '<tr><td class="center"><label><input type="checkbox" class="ace" name="mallID[]" value="'+v.mallID+'"><span class="lbl"></span></label><\/td>';
							shopListHTML += '<td>'+v.cityName+'<\/td>';
							shopListHTML += '<td>'+v.areaName+'</td>';
							shopListHTML += '<td><a href="#">'+v.mallName+'</a></td>';
							shopListHTML += '<td><a href="#">'+v.address+'</a></td>';
							shopListHTML += '<\/tr>';
						});

						$('#shopListHTML').html(shopListHTML);
						break;
					}

					$('#areaSelect').html(areaHTML);
				});

				$('#areaSelect').on('change', function(){
					var areaSelectValue = this.value;
						shopListHTML = '';
					$('#cityListCheck').attr("checked",false);
					$.each($.parseJSON(areaSelect), function(k, v){
						if (v.areaName == areaSelectValue) {
							shopListHTML += '<tr><td class="center"><label><input type="checkbox" class="ace" name="mallID[]" value="'+v.mallID+'"><span class="lbl"></span></label><\/td>';
							shopListHTML += '<td>'+v.cityName+'<\/td>';
							shopListHTML += '<td>'+v.areaName+'</td>';
							shopListHTML += '<td><a href="#">'+v.mallName+'</a></td>';
							shopListHTML += '<td><a href="#">'+v.address+'</a></td>';
							shopListHTML += '<\/tr>';
						}
					});

					$('#shopListHTML').html(shopListHTML);
				});

				// 数字选择
				$('#couponSum').ace_spinner({value:<?php echo $couponData->total_count > 0 ? $couponData->total_count : 0 ;?>,min:0,max:9999,step:10, btn_up_class:'btn-info' , btn_down_class:'btn-info'})
				.on('change', function(){
					//alert(this.value)
				});

				$('#couponEveryoneSum').ace_spinner({value:<?php echo $couponData->limit_count_per_person > 0 ? $couponData->limit_count_per_person : 0 ;?>,min:0,max:9999,step:1, btn_up_class:'btn-info' , btn_down_class:'btn-info'})
				.on('change', function(){
					//alert(this.value)
				});

				// 日期选择
				$('input[name=couponExpireDate]').daterangepicker().prev().on(ace.click_event, function(){
					$(this).next().focus();
				});
				$('input[name=couponReceiveDate]').daterangepicker().prev().on(ace.click_event, function(){
					$(this).next().focus();
				});

				$('.date-picker').datepicker({autoclose:true}).next().on(ace.click_event, function(){
					$(this).prev().focus();
				});

				
				$('#couponUseTimeStart').timepicker({
					minuteStep: 1,
					showSeconds: true,
					showMeridian: false
				}).next().on(ace.click_event, function(){
					$(this).prev().focus();
				});

				$('#couponUseTimeEnd').timepicker({
					minuteStep: 1,
					showSeconds: true,
					showMeridian: false
				}).next().on(ace.click_event, function(){
					$(this).prev().focus();
				});

				// 字数限制
				$('#couponTitle').inputlimiter({
					remText: '<?php echo $this->lang->line("TEXT_COUPON_TITLE_LENGTH")?>',
					limitText: '<?php echo $this->lang->line("TEXT_COUPON_TITLE_LENGTH_MAX")?>'
				});

				$('table th input:checkbox').on('click' , function(){
					var that = this;
					$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function(){
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});

				});

				$('[data-rel=tooltip]').tooltip();
			
				$(".select2").css('width','200px').select2({allowClear:true})
				.on('change', function(){
					$(this).closest('form').validate().element($(this));
				}); 
			
				//documentation : http://docs.jquery.com/Plugins/Validation/validate
			
				$('#editCouponform').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						couponTitle: {
							required:true,
							maxlength:40,
						},
						couponType: {
							required:true
						},
						couponMoney: {
							required:true
						},
						// couponEveryoneSum: {
						// 	required:true
						// },
						// couponSum: {
						// 	required:true
						// },
						couponExpireDate: {
							required:true
						},
						couponReceiveDate: {
							required:true
						}
					},
			
					messages: {
						couponTitle: {
							required:"<?php echo $this->lang->line('ERR_COUPON_TITLE');?>",
							maxlength:"<?php echo $this->lang->line('ERR_COUPON_TITLE_LENGTH');?>"
						},
						couponType: {
							required:"<?php echo $this->lang->line('ERR_COUPON_TYPE');?>"
						},
						couponMoney: {
							required:"<?php echo $this->lang->line('ERR_COUPON_MONEY');?>"
						},
						// couponEveryoneSum: {
						// 	required:"<?php echo $this->lang->line('ERR_COUPON_EXPIRE_DATE');?>"
						// },
						// couponSum: {
						// 	required:"<?php echo $this->lang->line('ERR_COUPON_RECEIVEDATE');?>"
						// },
						couponExpireDate: {
							required:"<?php echo $this->lang->line('ERR_COUPON_EXPIRE_DATE');?>"
						},
						couponReceiveDate: {
							required:"<?php echo $this->lang->line('ERR_COUPON_RECEIVEDATE');?>"
						}
					},
			
					invalidHandler: function (event, validator) { //display error alert on form submit   
						$('.alert-danger', $('.login-form')).show();
					},
			
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {
						$(e).closest('.form-group').removeClass('has-error').addClass('has-info');
						$(e).remove();
					},
			
					errorPlacement: function (error, element) {
						if(element.is(':checkbox') || element.is(':radio')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},
			
					submitHandler: function (form) {
					},
					invalidHandler: function (form) {
					}
				});

				$('#modal-wizard .modal-header').ace_wizard();
				$('#modal-wizard .wizard-actions .btn[data-dismiss=modal]').removeAttr('disabled');
			})
	
			function removeFileImg(){
				$('#fileImageSpan').html('');
			}

			function subEditCouponForm(){
				if(!$('#editCouponform').valid()){
					return false;
				}

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Coupon/editCoupon').'?couponId='.$this->input->get('couponId');?>",
					data:$('#editCouponform').serialize(),
					success:function(data){
						if (data.status == 0) {

							bootbox.dialog({
								message: "<?php echo $this->lang->line('TEXT_EDITCOUPON_SUCCESS');?>", 
								buttons: {
									"goList" : {
										"label" : "<?php echo $this->lang->line('TEXT_GO_COUPONLIST');?>",
										"className" : "btn-sm btn-primary",
										callback: function(){
											window.location.href = "<?php echo site_url('Coupon/couponList');?>";
										}
									},
									"continue" : {
										"label" : "<?php echo $this->lang->line('TEXT_CONTINUE_ADDCOUPON');?>",
										"className" : "btn-sm btn-primary",
										callback: function(){
											window.location.href = "<?php echo site_url('Coupon/addCoupon');?>";
										}
									}
								}
							});
							return true;
						}else{
							alert(data.msg);
							return false;
						}
					}
				});
			}

		</script>
	</body>
</html>

