<?php echo $this->element('header', array('loggedIn' => 'yup')); ?>
<div id="wrapper">
	<!-- start page -->
	<div id="page">

		<?php echo $this->element('sidebaraccounts', array('blank' => 'yep')); ?>
		
		<?php echo $this->element('content', 
							array('contentTitle' => 'Delete Social Network Link', 
								'contentSubtitle' => 'Network: ' . $network['name'] . ' ; Username: ' . $network['username'],
								'content' => '<p><strong>Warning!</strong></p>
								<p>Are you sure you want to delete this link?</p>
								<p>' . 
								$form->create('Accounts', array('action' => 'delete/' . $network['id'])) .
								$form->submit('Delete!') . 
								'<input type="hidden" name="data[Account][id]" value="' . $network['id'] . '" />' .
								$form->end()
								. '</p>')); ?>
		
		<!-- end content -->
		<!-- start sidebars -->
		<div id="sidebar2" class="sidebar">
			<ul>
			<!-- HAVE EACH VIEW IMPORT ITS OWN ELEMENT FOR THIS -->
				<li>
					&nbsp;
				</li>
			</ul>
		</div>
		<!-- end sidebars -->
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end page -->
</div>