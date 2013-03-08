<?php echo $this->element('header'); ?>
<div id="wrapper">
	<!-- start page -->
	<div id="page">

		<?php echo $this->element('sidebaraccounts', array('blank' => 'yep')); ?>
		<!-- start content -->
		
		<?php echo $this->element('content', 
							array('contentTitle' => 'Welcome to the <strong>S</strong>ocial <strong>N</strong>etworking <strong>P</strong>ortal!', 
								'contentSubtitle' => 'version 0.something',
								'content' => '<p>This page allows you to login with the
								username and password you previously registered 
								with the system. Simply type in your login credentials
								for this site in the login box, and you\'re good to go!</p>
								<p>If you don\'t have an account yet, click the link to
								go to the Registration page.</p>')); ?>
		
		<!-- end content -->
		<!-- start sidebars -->
		<div id="sidebar2" class="sidebar">
			<ul>
			<!-- HAVE EACH VIEW IMPORT ITS OWN ELEMENT FOR THIS -->
			<?php echo $this->element('loginform'); ?>
			<?php //echo $this->element('registrationform'); ?>
			<li>
				<div>
					<h2>Want to create an account?</h2>
					<ul>
						<li><?php echo $html->link('Click to go to Registration >>', 
									array('controller' => 'users', 'action' => 'register')); ?></li>
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