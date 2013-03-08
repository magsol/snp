		<div id="sidebar1" class="sidebar">
			<ul>
				<li>
					<?php if (isset($blank)) { ?>
					<ul>
						
					</ul>
					<?php } else if (isset($hasAccounts)) { ?>
					<h2>Your Existing Accounts</h2>
					<ul>
						<?php foreach ($accounts as $account) { ?>
						<li><?php echo $html->link($account['Account']['accountname'] . ' (' . $account['Account']['account_username'] . ')', 
													array('controller' => 'accounts',
															'action' => 'view/' . $account['Account']['uaid'])); ?></li>
						<?php } ?>
					</ul>
					<?php } else { ?>
					<h2>No Accounts...yet</h2>
					<ul>
						<li>&nbsp;</li>
					</ul>
					<?php } ?>
				</li>
			</ul>
		</div>