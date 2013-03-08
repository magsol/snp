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
							array('contentTitle' => 'View Social Network Link', 
								'contentSubtitle' => 'Network: ' . $network['name'] . ' ; Username: ' . $network['username'],
								'content' => $form->create('Account', array('action' => 'update/')) .
											'<h2>Update Your Status</h2>' .
											'<input name="data[Account][status]" size="50" type="text" value="" id="AccountStatus" />' .
											'<br />' . $form->hidden('uaid', array('value' => $network['id'])) .
											$form->submit('Post Status') . $form->end() . '<br />' .
											'<h2>Your Latest Status</h2>' . $update . '<br /><br />' .
											'<h2>Your Profile</h2>' . $profile .
											'<h2>Your Friends</h2>' . $friends)); ?>
		
		<!-- end content -->
		<!-- start sidebars -->
		<div id="sidebar2" class="sidebar">
			<ul>
			<!-- HAVE EACH VIEW IMPORT ITS OWN ELEMENT FOR THIS -->
				<li>
					<div>
						<ul>
							<li>
								<?php echo $html->link('Delete this link', 
										array('controller' => 'accounts', 'action' => 'delete/' . $network['id'])); ?>
							</li>
						</ul>
					</div>
				</li>
			</ul>
		</div>
		<!-- end sidebars -->
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end page -->
</div>