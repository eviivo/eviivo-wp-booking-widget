<?php

	use eviivo\Plugin\Util;
	use eviivo\Plugin\Helpers\Php\View;

/* @var $adminPage eviivo\Plugin\Admin\Pages\Main */
	/* @var $form eviivo\Plugin\Admin\Pages\Forms\BookingFormConfig */
	/* @var $bookingForm \eviivo\Plugin\Model\BookingForm */
?>
<div class="wrap" id="poststuff">
	<h1><?php echo __('eviivo Booking Form Configurator', 'eviivo-booking-widget') ?></h1>

	<?php echo Util::renderMessage($adminPage->getMessage(), $adminPage->getMessageType()); ?>

	<div class="eviivo-booking-form-generator eviivo-clearfix">


		<div class="eviivo-booking-form-generator-control">
			<?php echo $form->render(); ?>
		</div>

		<div class="eviivo-booking-form-generator-preview">

			<div class="meta-box-sortables">
				<div class="postbox">
					<h2>
						<span><?php echo __('Preview', 'eviivo-booking-widget') ?></span>
					</h2>

					<ul class="eviivo-tabs">
						<li>
							<a class="current" href="#eviivo-booking-form-generator-preview"><?php echo __('Preview', 'eviivo-booking-widget') ?></a>
						</li>
						<li>
							<a href="#eviivo-booking-form-generator-code"><?php echo __('Code', 'eviivo-booking-widget') ?></a>
						</li>
					</ul>

					<div class="eviivo-tabs-wrapper">
						<div class="eviivo-tab current" id="eviivo-booking-form-generator-preview">
							<?php
								$view = new View(array(
									'bookingForm' => $bookingForm
								));
								echo $view->render(Util::getViewPath('admin/booking-form-preview'));
							?>
						</div>

						<div class="eviivo-tab" id="eviivo-booking-form-generator-code">
							<?php if ($bookingForm->isValid()) { ?>
									<pre><code><?php echo htmlentities($bookingForm->getHtml()); ?></code></pre>
								<?php } else { ?>
									<div class="">
										<?php echo $bookingForm->getErrorMessage(); ?>
									</div>
								<?php } ?>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
