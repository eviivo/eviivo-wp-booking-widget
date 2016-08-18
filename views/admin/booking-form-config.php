<?php

	use Eviivo\Plugin\Model\BookingForm;
	use Eviivo\Plugin\Helpers\Php\View;
	use Eviivo\Plugin\Util;

/* @var $bookingForm Eviivo\Plugin\Model\BookingForm */
	/* @var $form Eviivo\Plugin\Admin\Pages\Forms\BookingFormConfig */

	$uniqueId = $bookingForm->getId();
?>
<?php echo $bookingForm->getIncludeFormTag() ? $form->startTag() : ''; ?>
<div class="meta-box-sortables">
	<div class="postbox">
		<h2 class="eviivo-form-title">
			<span><?php echo __('Configuration', 'eviivo-booking-widget') ?></span>
		</h2>

		<?php if ($form->hasElement('inheritFromGeneral')) { ?>
				<div class="eviivo-tabs-wrapper">		
					<div class="eviivo-admin-input-row">
						<label for=""><?php echo __('Title', 'eviivo-booking-widget') ?></label>
						<?php echo $form->getElement('title')->render(); ?>
					</div>

					<div class="eviivo-admin-input-row">
						<label for=""><?php echo __('Inherit from global options', 'eviivo-booking-widget') ?></label>
						<?php echo $form->getElement('inheritFromGeneral')->render(); ?>
					</div>
				</div>
			<?php } ?>


		<?php if (!$form->hasElement('inheritFromGeneral') || $form->getElement('inheritFromGeneral')->getValue() == 'no') { ?>
				<ul class="eviivo-tabs">
					<li>
						<a class="current" href="#eviivo-booking-form-generator-setup-<?php echo $uniqueId; ?>"><?php echo __('Setup', 'eviivo-booking-widget') ?></a>
					</li>
					<li>
						<a href="#eviivo-booking-form-generator-appearance-<?php echo $uniqueId; ?>"><?php echo __('Appearance', 'eviivo-booking-widget') ?></a>
					</li>
					<li>
						<a href="#eviivo-booking-form-generator-labels-<?php echo $uniqueId; ?>"><?php echo __('Labels', 'eviivo-booking-widget') ?></a>
					</li>
					<li>
						<a href="#eviivo-booking-form-generator-advanced-<?php echo $uniqueId; ?>"><?php echo __('Advanced', 'eviivo-booking-widget') ?></a>
					</li>
					<?php if (!empty($includePreviewTab)) { ?>
						<li>
							<a href="#eviivo-booking-form-generator-inlinepreview-<?php echo $uniqueId; ?>"><?php echo __('Preview', 'eviivo-booking-widget') ?></a>
						</li>
					<?php } ?>
				</ul>
				<div class="eviivo-tabs-wrapper">
					<div class="eviivo-tab current" id="eviivo-booking-form-generator-setup-<?php echo $uniqueId; ?>">

						<div class="eviivo-admin-input-row">
							<label for=""><?php echo $form->getElement('hotelReference')->getLabel(); ?></label>
							<?php echo $form->getElement('hotelReference')->render(); ?>

							<div class="eviivo-tooltip-wrapper">
								<button type="button" class="eviivo-tooltip-trigger"></button>
								<div class="eviivo-tooltip-content">
									<h3><?php echo $form->getElement('hotelReference')->getLabel(); ?></h3>
									<p><?php echo __('This can be found inside your eviivo suite by selecting the button that looks like a picture of a person on the top right of the screen next to the help button.', 'eviivo-booking-widget'); ?></p>
								</div>
							</div>
						</div>

						<div class="eviivo-admin-input-row">
							<label for=""><?php echo $form->getElement('languageCode')->getLabel(); ?></label>
							<?php echo $form->getElement('languageCode')->render(); ?>
						</div>

						<div class="eviivo-admin-input-row">
							<label for=""><?php echo $form->getElement('layout')->getLabel(); ?></label>
							<?php echo $form->getElement('layout')->render(); ?>
						</div>
					</div>

					<div class="eviivo-tab eviivo-booking-form-generator-labels" id="eviivo-booking-form-generator-labels-<?php echo $uniqueId; ?>">
						<?php foreach (BookingForm::getLabels() as $name => $label) { ?>
							<div class="eviivo-admin-input-row">
								<label for=""><?php echo $label; ?></label>
								<?php echo $form->getElement($name)->render(); ?>
							</div>
						<?php } ?>

						<hr />
						<div class="eviivo-admin-input-row">
							<small>* <?php echo __('Leaving the labels empty will tell the plugin to get the default labels from the .PO dictionary.', 'eviivo-booking-widget'); ?> </small>
						</div>
					</div>

					<div class="eviivo-tab" id="eviivo-booking-form-generator-appearance-<?php echo $uniqueId; ?>">

						<div class="eviivo-admin-input-row">
							<label for=""><?php echo $form->getElement('theme')->getLabel(); ?></label>
							<?php echo $form->getElement('theme')->render(); ?>
							<div>
								<hr />
								<small><?php echo __('"None" layout - you will have to manage the CSS style from your theme files. You can view the HTML structure of the Form in the Configuration -> Code tab.', 'eviivo-booking-widget'); ?> </small>
							</div>
						</div>

						<div class="eviivo-custom-style" style="display: <?php echo $bookingForm->getTheme() === 'custom' ? 'block' : 'none'; ?>">

							<div class="eviivo-admin-input-label">
								<?php echo __('Colors', 'eviivo-booking-widget') ?>
							</div>

							<div class="eviivo-custom-colors">
								<div class="eviivo-admin-input-row">
									<label for=""><?php echo $form->getElement('textColor')->getLabel(); ?></label>
									<?php echo $form->getElement('textColor')->render(); ?>
								</div>

								<div class="eviivo-admin-input-row">
									<label for=""><?php echo $form->getElement('hasTransparentBackground')->getLabel(); ?></label>
									<?php echo $form->getElement('hasTransparentBackground')->render(); ?>
								</div>
								<div class="eviivo-admin-input-row eviivo-background-color" style="display: <?php echo $bookingForm->getHasTransparentBackground() ? 'none' : 'block'; ?>;">
									<label for=""><?php echo $form->getElement('backgroundColor')->getLabel(); ?></label>
									<?php echo $form->getElement('backgroundColor')->render(); ?>
								</div>

								<div class="eviivo-admin-input-row">
									<label for=""><?php echo $form->getElement('buttonTextColor')->getLabel(); ?></label>
									<?php echo $form->getElement('buttonTextColor')->render(); ?>
								</div>

								<div class="eviivo-admin-input-row">
									<label for=""><?php echo $form->getElement('buttonBackgroundColor')->getLabel(); ?></label>
									<?php echo $form->getElement('buttonBackgroundColor')->render(); ?>
								</div>

							</div>

							<div class="eviivo-admin-input-label">
								<?php echo __('Font size', 'eviivo-booking-widget') ?>
							</div>

							<div class="eviivo-admin-input-row">
								<label for=""><?php echo $form->getElement('fontSize')->getLabel(); ?></label>
								<?php echo $form->getElement('fontSize')->render(); ?>
							</div>

							<div class="eviivo-admin-input-row">
								<label for=""><?php echo $form->getElement('buttonFontSize')->getLabel(); ?></label>
								<?php echo $form->getElement('buttonFontSize')->render(); ?>
							</div>
						</div>
					</div>

					<div class="eviivo-tab" id="eviivo-booking-form-generator-advanced-<?php echo $uniqueId; ?>">

						<?php
						$datesConfig = array(
							array(
								'label' => __('Check In Date', 'eviivo-booking-widget'),
								'filePrefix' => 'checkIn'
							),
							array(
								'label' => __('Check Out Date', 'eviivo-booking-widget'),
								'filePrefix' => 'checkOut'
							)
						);
						foreach ($datesConfig as $dateConfig) {
							?>

							<div class="eviivo-data-config-row">
								<div class="eviivo-admin-input-label">
									<?php echo $dateConfig['label'] ?>
								</div>
								<div class="eviivo-admin-input-row">
									<label for=""><?php echo $form->getElement($dateConfig['filePrefix'] . 'DateType')->getLabel(); ?></label>
									<?php echo $form->getElement($dateConfig['filePrefix'] . 'DateType')->addClass('type-select')->render(); ?>
									<div class="eviivo-tooltip-wrapper">
										<button type="button" class="eviivo-tooltip-trigger"></button>
										<div class="eviivo-tooltip-content">
											<h3><?php echo $form->getElement($dateConfig['filePrefix'] . 'DateType')->getLabel(); ?></h3>
											<p><?php echo __('This allows you to set how you want people to be able to select their check in/out dates, you can select one of two options 1) relative to today or 2) relative to day of the week. #1 allows you to set the earliest possible booking date to say tomorrow or X days in the future, so you do not need to worry about same day bookings if that is a problem for you. #2 allows you to set the default date to be a day of the week, so it is always the next Saturday for example, if you only accept check ins on specific days this can be useful.', 'eviivo-booking-widget'); ?></p>
										</div>
									</div>
								</div>

								<div class="eviivo-admin-input-row">
									<label for=""><?php echo $form->getElement($dateConfig['filePrefix'] . 'DateRelativeDays')->getLabel(); ?></label>
									<?php echo $form->getElement($dateConfig['filePrefix'] . 'DateRelativeDays')->addClass('days-select')->render(); ?>
									<div class="eviivo-tooltip-wrapper">
										<button type="button" class="eviivo-tooltip-trigger"></button>
										<div class="eviivo-tooltip-content">
											<h3><?php echo $form->getElement($dateConfig['filePrefix'] . 'DateRelativeDays')->getLabel(); ?></h3>
											<p><?php echo __('This is where you select the number of days in the future to show as the earliest booking date or the day of the week to show as the next available booking date', 'eviivo-booking-widget'); ?></p>
										</div>
									</div>
								</div>

								<div class="eviivo-admin-input-row">
									<label for=""><?php echo $form->getElement('absolute' . ucfirst($dateConfig['filePrefix']) . 'Date')->getLabel(); ?></label>
									<?php echo $form->getElement('absolute' . ucfirst($dateConfig['filePrefix']) . 'Date')->addClass('date-select')->render(); ?>
								</div>

								<div class="eviivo-admin-input-row">
									<label for=""><?php echo $form->getElement($dateConfig['filePrefix'] . 'DateRelativeDay')->getLabel(); ?></label>
									<?php echo $form->getElement($dateConfig['filePrefix'] . 'DateRelativeDay')->addClass('day-select')->render(); ?>
								</div>
							</div>
						<?php } ?>

						<div class="eviivo-admin-input-label">
							<?php echo __('Rooms numbers', 'eviivo-booking-widget') ?>
						</div>

						<div class="eviivo-admin-input-row">
							<label for=""><?php echo $form->getElement('roomCount')->getLabel() ?></label>
							<?php echo $form->getElement('roomCount')->render(); ?>
						</div>

						<div class="eviivo-admin-input-row">
							<label for=""><?php echo $form->getElement('defaultRoomCount')->getLabel() ?></label>
							<?php echo $form->getElement('defaultRoomCount')->render(); ?>
							<div class="eviivo-tooltip-wrapper">
								<button type="button" class="eviivo-tooltip-trigger"></button>
								<div class="eviivo-tooltip-content">
									<h3><?php echo $form->getElement('defaultRoomCount')->getLabel(); ?></h3>
									<p><?php echo __('This allows you to select how many rooms the guest will be booking by default, this should almost always be one if you are a regular hotel or B&amp;B.', 'eviivo-booking-widget'); ?></p>
								</div>
							</div>
						</div>

						<div class="eviivo-admin-input-row">
							<label for=""><?php echo $form->getElement('adultCount')->getLabel() ?></label>
							<?php echo $form->getElement('adultCount')->render(); ?>
							<div class="eviivo-tooltip-wrapper">
								<button type="button" class="eviivo-tooltip-trigger"></button>
								<div class="eviivo-tooltip-content">
									<h3><?php echo $form->getElement('adultCount')->getLabel(); ?></h3>
									<p><?php echo __('This is where you set the maximum number of adults any of your rooms can accommodate. For example, if your biggest room can sleep 5 adults but your average rooms sleep 2 this should be 5. ', 'eviivo-booking-widget'); ?></p>
								</div>
							</div>
						</div>

						<div class="eviivo-admin-input-row">
							<label for=""><?php echo $form->getElement('defaultAdultCount')->getLabel() ?></label>
							<?php echo $form->getElement('defaultAdultCount')->render(); ?>
							<div class="eviivo-tooltip-wrapper">
								<button type="button" class="eviivo-tooltip-trigger"></button>
								<div class="eviivo-tooltip-content">
									<h3><?php echo $form->getElement('defaultAdultCount')->getLabel(); ?></h3>
									<p><?php echo __('This is the default and should reflect your typical booking, generally 1 or 2 adults', 'eviivo-booking-widget'); ?></p>
								</div>
							</div>
						</div>

						<div class="eviivo-admin-input-row">
							<label for=""><?php echo $form->getElement('childCount')->getLabel() ?></label>
							<?php echo $form->getElement('childCount')->render(); ?>
							<div class="eviivo-tooltip-wrapper">
								<button type="button" class="eviivo-tooltip-trigger"></button>
								<div class="eviivo-tooltip-content">
									<h3><?php echo $form->getElement('childCount')->getLabel(); ?></h3>
									<p><?php echo __('This is where you set the maximum number of children any of your rooms can accommodate. For example, if your biggest room can sleep 5 children but your average rooms sleep 2 this should be 5. - Remember, due to legal requirements the max number of children should always be one less than the max number of adults, as chuldren cannot stay unnacompanied ', 'eviivo-booking-widget'); ?></p>
								</div>
							</div>
						</div>

						<div class="eviivo-admin-input-row">
							<label for=""><?php echo $form->getElement('defaultChildCount')->getLabel() ?></label>
							<?php echo $form->getElement('defaultChildCount')->render(); ?>
							<div class="eviivo-tooltip-wrapper">
								<button type="button" class="eviivo-tooltip-trigger"></button>
								<div class="eviivo-tooltip-content">
									<h3><?php echo $form->getElement('defaultChildCount')->getLabel(); ?></h3>
									<p><?php echo __('This is the default and should reflect your typical booking, generally 0 for children', 'eviivo-booking-widget'); ?></p>
								</div>
							</div>
						</div>

						<div class="eviivo-admin-input-label">
							<?php echo __('Front end resources', 'eviivo-booking-widget') ?>
						</div>

						<div class="eviivo-admin-input-row">
							<label for=""><?php echo $form->getElement('excludeJavascript')->getLabel() ?></label>
							<?php echo $form->getElement('excludeJavascript')->render(); ?>
						</div>

						<div class="eviivo-admin-input-row">
							<label for=""><?php echo $form->getElement('excludeCss')->getLabel() ?></label>
							<?php echo $form->getElement('excludeCss')->render(); ?>
						</div>
					</div>

					<?php if (!empty($includePreviewTab)) { ?>
						<div class="eviivo-tab" id="eviivo-booking-form-generator-inlinepreview-<?php echo $uniqueId; ?>">
							<?php
							$view = new View(array(
								'bookingForm' => $bookingForm
							));
							echo $view->render(Util::getViewPath('admin/booking-form-preview'));
							?>
						</div>
					<?php } ?>
				</div>

				<div class="eviivo-tabs-wrapper">
					<div class="eviivo-input-actions">
						<?php echo $form->getElement('submit')->render(); ?>
					</div>
				</div>
			<?php } ?>
	</div>
</div>
<?php
	echo $bookingForm->getIncludeFormTag() ? $form->endTag() : '';
	