<?php echo $this->element('header', array('loggedIn' => 'yup')); ?>
<div id="wrapper">
	<!-- start page -->
	<div id="page">
	
		<?php 
		if (isset($error)) { 
			echo $error;
		}
		?>

		<?php echo $this->element('sidebaraccounts', array('blank' => 'yep')); ?>
		
		<?php echo $this->element('content', 
							array('contentTitle' => $params['contentTitle'], 
								'contentSubtitle' => 'Network: ' . $params['networkName'] . ' ; Username: ' . $params['username'],
								'content' => '<p>You are linking your ' . $params['networkName'] .
								' to your SNP account. Please enter the following information.</p>' .
								$this->element($accountType, array('id' => $params['id'])))); ?>
		
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