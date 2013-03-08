<?php echo $this->element('header', array('loggedIn' => 'yup')); ?>
<div id="wrapper">
	<!-- start page -->
	<div id="page">

		<?php 
		$arr = array('accounts' => $accounts);
		if (isset($hasAccounts)) {
			$arr['hasAccounts'] = 'yes';
		}
		echo $this->element('sidebaraccounts', $arr); ?>
		<!-- start content -->
		
		<?php echo $this->element('content', 
							array('contentTitle' => 'You are now authenticated', 
								'contentSubtitle' => 'I\'m sorry, Dave.',
								'content' => '<p>From here, you can use the menu
								on the left to administrate existing linked social
								network accounts, or you can use the menu on the right
								to link new accounts to this application.</p><p>
								You can add multiple accounts from the same network,
								provided such accounts already exist with that network.</p>')); ?>
		
		<!-- end content -->
		<!-- start sidebars -->
		<div id="sidebar2" class="sidebar">
			<ul>
			<!-- HAVE EACH VIEW IMPORT ITS OWN ELEMENT FOR THIS -->
				<li>
					<div>
						<h2>Add a Social Network Account</h2>
					</div>
				</li>
			<?php echo $this->element('addmyspace', array('myspace' => $myspaceid)); ?>
			<?php echo $this->element('addtwitter', array('twitter' => $twitterid)); ?>
			<?php echo $this->element('addlinkedin', array('linkedin' => $linkedinid)); ?>
			</ul>
		</div>
		<!-- end sidebars -->
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end page -->
</div>