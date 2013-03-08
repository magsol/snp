<?php echo $this->element('header'); ?>
<div id="wrapper">
	<!-- start page -->
	<div id="page">

		<?php echo $this->element('sidebaraccounts', array('blank' => 'yep')); ?>
		<!-- start content -->
		
		<?php echo $this->element('content', 
							array('contentTitle' => 'Welcome to the <strong>S</strong>ocial <strong>N</strong>etworking <strong>P</strong>ortal!', 
								'contentSubtitle' => 'version 0.something',
								'content' => '<p>From this page, you can register a new account with the 
								system. Make sure you:
								<ul>
									<li>select a unique username (at least 6 characters)</li>
									<li>type the same password twice (at least 6 characters)</li>
								</ul>
								After successfully registering, you\'ll be redirected to your
								home page, where you can begin linking social networking accounts!
								</p>')); ?>
		
		<!-- end content -->
		<!-- start sidebars -->
		<div id="sidebar2" class="sidebar">
			<ul>
			<!-- HAVE EACH VIEW IMPORT ITS OWN ELEMENT FOR THIS -->
			<?php //echo $this->element('loginform'); ?>
			<?php echo $this->element('registrationform'); ?>
			<li>
				<div>
					<h2>Already have an account?</h2>
					<ul>
						<li><?php echo $html->link('Click to go to Login >>', 
									array('controller' => 'users', 'action' => 'login')); ?></li>
					</ul>
				</div>
			</li>
			<!-- $this->element('addTwitter.ctp') -->
			<!-- $this->element('addMyspace.ctp') -->
			<!-- ... -->
			</ul>
		</div>
		<!-- end sidebars -->
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end page -->
</div>