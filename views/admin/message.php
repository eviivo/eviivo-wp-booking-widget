<?php if (!empty($message)) { ?>
		<div id="message" class="updated notice <?php echo $messageType ?> is-dismissible">
			<p><?php echo $message; ?></p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo __('Dismiss this notice.', 'eviivo-booking-widget'); ?></span></button>
		</div>
		<?php
	} 