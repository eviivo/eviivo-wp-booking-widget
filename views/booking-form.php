<?php
	/* @var $bookingForm eviivo\Plugin\Model\BookingForm */

	$checkInDate = $bookingForm->getCheckInDate();
	$checkOutDate = $bookingForm->getCheckOutDate();
	$textColor = $bookingForm->getTextColor();
	$backgroundColor = $bookingForm->getBackgroundColor();
	$fontSize = $bookingForm->getFontSize();
	$buttonFontSize = $bookingForm->getButtonFontSize();
	$buttonTextColor = $bookingForm->getButtonTextColor();
	$buttonBackgroundColor = $bookingForm->getButtonBackgroundColor();
	$hasTransparentBackground = $bookingForm->getHasTransparentBackground();

	$textColorStyle = '';
	$backgroundColorStyle = '';
	$fontSizeStyle = '';
	$buttonFontSizeStyle = '';
	$buttonTextColorStyle = '';
	$buttonBackgroundColorStyle = '';

	if ($bookingForm->getTheme() === 'custom') {
		if ($textColor) {
			$textColorStyle = 'color: ' . $textColor . ';';
		}

		if ($hasTransparentBackground) {
			$backgroundColor = 'transparent';
		}

		if ($backgroundColor) {
			$backgroundColorStyle = 'background-color: ' . $backgroundColor . ';';
		}

		if ($buttonTextColor) {
			$buttonTextColorStyle = 'color: ' . $buttonTextColor . ';';
		}

		if ($buttonBackgroundColor) {
			$buttonBackgroundColorStyle = 'background-color: ' . $buttonBackgroundColor . ';';
		}

		if ($fontSize) {
			$fontSizeStyle = 'font-size: ' . $fontSize . 'px;';
		}

		if ($buttonFontSize) {
			$buttonFontSizeStyle = 'font-size: ' . $buttonFontSize . 'px;';
		}
	}

	$roomLabel = $bookingForm->getLabelRoom(false);
	if (strpos($roomLabel, '%d') === false) {
		$roomLabel .= ' %d';
	}
?>
<div 
	id="<?php echo $bookingForm->getUniquId(); ?>" 
	class="eviivo-booking-form<?php echo $bookingForm->getTheme() === 'none' ? '-wrapper' : ' eviivo-booking-form-no-style' ?> eviivo-booking-form-layout-<?php echo $bookingForm->getLayout(); ?> eviivo-booking-form-theme-<?php echo $bookingForm->getTheme(); ?>"
	data-calendar-i18n="<?php echo esc_attr(json_encode($bookingForm->getCalendarI18n())) ?>"
	style="<?php echo $textColorStyle, $backgroundColorStyle, $fontSizeStyle; ?>"
	>
	<form 
		action="http://via.eviivo.com/<?php echo str_replace('_', '-', $bookingForm->getLanguageCode()); ?>/<?php echo $bookingForm->getHotelReference() ?>#mod-results" 
		method="get"
		target="_blank"
		>
		<div class="eviivo-input-row eviivo-input-checkin">
			<label class="eviivo-input-label" for="<?php echo $bookingForm->getUniquId(); ?>-startdate"><?php echo $bookingForm->getLabelCheckIn(false); ?></label>
			<input class="eviivo-input-date" type="text" id="<?php echo $bookingForm->getUniquId(); ?>-startdate" name="startdate" value="<?php echo date('Y-m-d', $checkInDate); ?>" data-date="<?php echo $checkInDate; ?>" />
		</div>

		<div class="eviivo-input-row eviivo-input-checkout">
			<label class="eviivo-input-label" for="<?php echo $bookingForm->getUniquId(); ?>-enddate"><?php echo $bookingForm->getLabelCheckOut(false); ?></label>
			<input 
				type="text" 
				class="eviivo-input-date" 
				name="enddate" 
				id="<?php echo $bookingForm->getUniquId(); ?>-enddate" 
				value="<?php echo date('Y-m-d', $checkOutDate); ?>" 
				data-date="<?php echo $checkOutDate; ?>" 
				data-eviivo-checkout-type="<?php echo $bookingForm->getCheckOutDateType() ?>" 
				data-eviivo-checkout-days="<?php echo $bookingForm->getCheckOutDateRelativeDays() ?>" 
				/>
		</div>

		<div class="eviivo-rooms-selector">
			<div class="eviivo-input-row eviivo-input-rooms" style="<?php echo $textColorStyle, $backgroundColorStyle, $fontSizeStyle; ?>">
				<label class="eviivo-input-label" for="<?php echo $bookingForm->getUniquId(); ?>-rooms"><?php echo $bookingForm->getLabelRooms(false); ?></label>
				<select class="eviivo-room-select eviivo-input-select" id="<?php echo $bookingForm->getUniquId(); ?>-rooms">
					<?php for ($i = 1; $i <= $bookingForm->getRoomCount(); ++$i) { ?><option <?php echo $bookingForm->getDefaultRoomCount() == $i ? 'selected="selected" ' : '' ?>value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?>
				</select>
			</div>

			<div class="eviivo-input-row eviivo-rooms">
				<div class="eviivo-room" style="<?php echo $textColorStyle, $backgroundColorStyle, $fontSizeStyle; ?>">
					<div class="eviivo-room-label" data-label-format="<?php echo $roomLabel; ?>">
						<?php echo sprintf($roomLabel, 1); ?>
					</div>
					<div class="eviivo-adults">
						<label class="eviivo-input-label"  for="<?php echo $bookingForm->getUniquId(); ?>-adult-1"><?php echo $bookingForm->getLabelAdults(false); ?></label>
						<select name="adults1" class="eviivo-room-adults eviivo-input-select" id="<?php echo $bookingForm->getUniquId(); ?>-adult-1">
							<?php for ($i = 0; $i <= $bookingForm->getAdultCount(); ++$i) { ?><option <?php echo $bookingForm->getDefaultAdultCount() == $i ? 'selected="selected" ' : '' ?>value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?>
						</select>
					</div>

					<div class="eviivo-children">
						<label class="eviivo-input-label"  for="<?php echo $bookingForm->getUniquId(); ?>-child-1"><?php echo $bookingForm->getLabelChildren(false); ?></label>
						<select name="children1" class="eviivo-room-children eviivo-input-select" id="<?php echo $bookingForm->getUniquId(); ?>-child-1">
							<?php for ($i = 0; $i <= $bookingForm->getChildCount(); ++$i) { ?><option <?php echo $bookingForm->getDefaultChildCount() == $i ? 'selected="selected" ' : '' ?>value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?>
						</select>
					</div>
				</div>
			</div>
		</div>

		<button type="submit" style="<?php echo $buttonFontSizeStyle, $buttonTextColorStyle, $buttonBackgroundColorStyle; ?>" class="eviivo-button-primary"><?php echo $bookingForm->getLabelShowPrices(false); ?></button>

	</form>
</div>
