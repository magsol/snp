<!-- start header -->
<div id="header">
	<div id="logo">
		<p><?php echo $html->image('photo1.png'); ?></p>
		<h1><a href="#">SNP</a></h1>
	</div>
	<div id="menu">
		<ul id="main">
			<li><?php echo $html->link('Home', (isset($loggedIn) ? array('controller' => 'accounts', 'action' => 'index') : array('controller' => 'users', 'action' => 'login'))); ?></li>
			<?php if (isset($loggedIn)) { ?>
			<li><?php echo $html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?></li>
			<?php } else { ?>
			<li><?php echo $html->link('About', array('controller' => 'users', 'action' => 'about')); ?></li>
			<?php } ?>
		</ul>
	</div>
</div>
<!-- end header -->