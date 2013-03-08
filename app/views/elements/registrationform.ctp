				<li>
					<?php echo $form->create('User', array('action' => 'register')); ?>
					<div>
						<h2>Want to create an account?</h2>
						<p>
							Username<br />
							<?php echo $form->input('username', array('label' => false, 'div' => false)); ?>
						</p>
						<p>
							Password<br />
							<?php echo $form->input('password', array('label' => false, 'div' => false)); ?>
						</p>
						<p>
							Password (again)<br />
							<?php echo $form->input('passwd', array('label' => false, 'div' => false)); ?>
						</p>
						<p>
							<?php echo $form->submit('Register!'); ?>
						</p>
					</div>
					<?php echo $form->end(); ?>
				</li>
