				<div class="sidebar" id="sidebar">
					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
					</script>

					<ul class="nav nav-list">
						<li>
							<a href="#">
								<i class="icon-dashboard"></i>
								<span class="menu-text"> <?php echo $this->lang->line('TEXT_MENU');?> </span>
							</a>
						</li>
						<?php foreach ($this->sideBar as $v){?>
						<li class="<?php if($this->router->class == $v['module']){?>active open<?php }?>">
							<a href="#" class="dropdown-toggle">
								<i class="icon-list"></i>
								<span class="menu-text"> <?php echo $v['title'];?> </span>

								<b class="arrow icon-angle-down"></b>
							</a>

							<ul class="submenu">
								<?php foreach($v['list'] as $j){?>
								<li class="<?php if(uri_string() == $j['url']){?>active<?php }?>">
									<a href="<?php echo site_url($j['url']);?>">
										<i class="icon-double-angle-right"></i>
										<?php echo $j['title'];?>
									</a>
								</li>
								<?php }?>
							</ul>
						</li>
						<?php } ?>

					</ul><!-- /.nav-list -->

					<div class="sidebar-collapse" id="sidebar-collapse">
						<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
					</div>

					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
					</script>
				</div>
