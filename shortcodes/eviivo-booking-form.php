<?php

	use Eviivo\Plugin\Model\BookingForm;

$bookingForm = new BookingForm();

	if (!empty($attributes)) {
		foreach ($attributes as $name => $value) {
			$methodName = 'set' . ucfirst($name);
			if (method_exists($bookingForm, $methodName)) {
				$bookingForm->$methodName(esc_attr($value));
			}
		}
	}


	echo $bookingForm->getHtml();
	