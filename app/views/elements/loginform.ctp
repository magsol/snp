				<li>
					<?php echo $form->create('User', array('action' => 'login')); ?>
					<div>
						<h2>Already have an account?</h2>
						<p>
							Enter your username<br />
							<?php echo $form->input('username', array('label' => false, 'div' => false)); ?>
						</p>
						<p>
							And your password<br />
							<?php echo $form->input('password', array('label' => false, 'div' => false)); ?>
						</p>
						<p>
						<?php $session->flash('auth'); ?>
						</p>
						<p>
							<?php echo $form->submit('Login!'); ?>
						</p>
					</div>
					<?php echo $form->end(); ?>
				</li>