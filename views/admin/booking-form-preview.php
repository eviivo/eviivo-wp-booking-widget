<?php

	use eviivo\Plugin\Util;

/* @var $bookingForm \eviivo\Plugin\Model\BookingForm */
?>

<div class="eviivo-booking-form-preview"
	 data-eviivo-booking-form-preview-url="<?php echo Util::getAjaxLink('PreviewBookingForm'); ?>"
	 data-eviivo-loading-message="<?php echo __('Loading', 'eviivo-booking-widget') ?>"
	 >
		 <?php echo $bookingForm->isValid() ? $bookingForm->getHtml() : $bookingForm->getErrorMessage(); ?>
</div>
