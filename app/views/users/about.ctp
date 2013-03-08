<?php echo $this->element('header'); ?>
<div id="wrapper">
	<!-- start page -->
	<div id="page">

		<?php echo $this->element('sidebaraccounts', array('blank' => 'yep')); ?>
		<!-- start content -->
		
		<?php echo $this->element('content', 
							array('contentTitle' => 'About the <strong>S</strong>ocial <strong>N</strong>etworking <strong>P</strong>ortal', 
								'contentSubtitle' => 'By ' . $html->link('Shannon Quinn', 'http://www.magsolweb.net/') . 
													' and ' . $html->link('Arpit Tandon', 'http://www.andrew.cmu.edu/user/arpitt/'),
								'content' => '<p><strong>As our final 637 project</strong>, we wanted to create a social networking 
								portal, a single website from which a user could manage all their social networking accounts. 
								Ideally, using the OpenSocial API, we could tie numerous networks together until a single
								interface, adding other non-OpenSocial networks (such as Facebook and Twitter) into the fold
								as their APIs could be meshed into the same interface.</p>' . 
								'<p><strong>This is but an intial attempt</strong>, and there is still a significant amount of
								work that could be done to further enhance the application. In particular, the profile
								synchronization proposed in our project outline would be an excellent added bit of functionality.
								This would give users a single dashboard from which to link whichever to they wished administrate
								simultaneously.</p>
								<p>We hope you enjoy using this application as much as we enjoyed building it.</p>')); ?>
		
		<!-- end content -->
		<!-- start sidebars -->
		<div id="sidebar2" class="sidebar">
			<ul>
			</ul>
		</div>
		<!-- end sidebars -->
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end page -->
</div>