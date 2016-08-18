<?php

	namespace Eviivo\Plugin\Widgets;

	use Eviivo\Plugin\Model\BookingForm as BookingFormModel;
	use Eviivo\Plugin\Elements\Form\Text;
	use Eviivo\Plugin\Elements\Form\Textarea;
	use Eviivo\Plugin\Elements\HtmlTag;

	/**
	 *  
	 */
	class BookingForm extends Base {

		/**
		 *  
		 */
		public function __construct() {
			$this->setTitle(__('Booking Form', 'eviivo-booking-widget'));
			$this->setDesciption(__('Auto generated booking form from Eviivo', 'eviivo-booking-widget'));

			parent::__construct();
		}

		/**
		 * 
		 * @param array $args
		 * @param array $instance 
		 */
		public function renderWidget($args, $instance) {

			$args['title'] = !empty($instance['title']) ? $instance['title'] : '';

			echo $args['before_widget'];

			echo $args['before_title'];
			if (!empty($args['title'])) {
				echo $args['title'];
			} else {
				echo $args['widget_name'];
			}
			echo $args['after_title'];

			$options = array();

			$bookingForm = new BookingFormModel($options);

			if (!empty($instance['shortcode'])) {
				$bookingForm->hydrateFromShortCode($instance['shortcode']);
			}

			echo '<div class="eviivo-widget">' . $bookingForm->getHtml() . '</div>';

			echo $args['after_widget'];
		}

		/**
		 *
		 * @param array $instance
		 * @return void
		 */
		public function form($instance) {

			$form = new HtmlTag();

			$p1 = new HtmlTag();
			$p1->setTagName('p');
			$title = new Text('title', __('Title', 'eviivo-booking-widget'));
			$title->setIncludeLabel(true);
			$title->addClass('widefat');
			if (!empty($instance['title'])) {
				$title->setValue($instance['title']);
				$this->setTitle($instance['title']);
			}
			$p1->addChild($title);
			$form->addChild($p1);


			$p2 = new HtmlTag();
			$p2->setTagName('p');
			$shortcodeValue = '';
			if (!empty($instance['shortcode'])) {
				$shortcodeValue = $instance['shortcode'];
			}

			if (empty($shortcodeValue)) {
				$shortcodeValue = '[' . BookingFormModel::WP_SHORT_CODE . ']';
			}

			$shortcode = new Textarea('shortcode', __('Shortcode', 'eviivo-booking-widget'));
			$shortcode
				->setIncludeLabel(true)
				->addClass('widefat open-eviivo-booking-lightbox-config')
				->setAttribute('readonly', 'readonly')
				->setInnerHtml($shortcodeValue);
			
			$p2->addChild($shortcode);
			$form->addChild($p2);

			$p3 = new HtmlTag();
			$p3->setTagName('p');
			$explainText = new HtmlTag();
			$explainText->setTagName('a')
				->setInnerHtml(__('Edit', 'eviivo-booking-widget'))
				->setAttribute('href', '#')
				->setAttribute('data-shortcode-selector', 'textarea[name=\'shortcode\']')
				->addClass('open-eviivo-booking-lightbox-config')
			;
			$p3->addChild($explainText);
			$form->addChild($p3);

			echo '<div class="eviivo-booking-form-generator">';
			echo '<div class="eviivo-booking-form-generator-control">';
			echo $form->render();
			echo '</div>';
			echo '</div>';
		}

		/**
		 *
		 * @param array $newInstance
		 * @param array $oldInstance
		 * @return array  
		 */
		public function update($newInstance, $oldInstance) {

			$data = $_POST;

			$newInstance['title'] = stripslashes(!empty($data['title']) ? $data['title'] : '');
			$newInstance['shortcode'] = stripslashes(!empty($data['shortcode']) ? $data['shortcode'] : '[' . BookingFormModel::WP_SHORT_CODE . ']');

			return $newInstance;
		}

	}
	