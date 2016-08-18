/* global eviivoConfig, tinymce */

(function () {


	tinymce.PluginManager.add('eviivo_booking_form', function (editor, url) {
		editor.addButton('eviivo_booking_form', {
			title: 'Eviivo',
			cmd: 'eviivo_booking_form',
			image: url + '/../images/editor-booking-icon.png'
		});

		editor.addCommand('eviivo_booking_form', function () {

			/**
			 * 
			 * @type Number
			 */
			var selectionRange = editor.selection.getRng();
			if (selectionRange) {
				var carretPostion = selectionRange.startOffset;

				var content = selectionRange.startContainer.nodeValue || '';
				var shortcode = eviivoConfig.shortcode || 'eviivo-booking-form';
				var selectedShortCode = '';

				var contentBeforeCarret = content.substr(0, carretPostion);
				var startTagPos = contentBeforeCarret.lastIndexOf('[');
				var endTagPos = contentBeforeCarret.lastIndexOf(']');
				
				if (startTagPos !== -1 && endTagPos < startTagPos) {
					var startTag = content.substr(startTagPos + 1, shortcode.length);
					if (startTag === shortcode) {
						var endTagPos = content.indexOf(']', startTagPos);
						if (endTagPos !== -1) {
							var tag = content.substr(startTagPos, endTagPos - startTagPos + 1);
							if (tag) {
								selectedShortCode = tag;

								selectionRange.setStart(selectionRange.startContainer, startTagPos);
								selectionRange.setEnd(selectionRange.startContainer, endTagPos + 1);
								editor.selection.setRng(selectionRange);
							}
						}
					}
				}
			}

			new window.eviivoConfigWindow({
				'onSuccess': function (shortcode) {
					editor.execCommand('mceInsertContent', false, shortcode);
				},
				/**
				 * 
				 * @param {Element} wrapper
				 * @returns {undefined} 
				 */
				'onDraw': function (wrapper) {
					var submitButton = wrapper.querySelector('.eviivo-btn-primary');
					if (submitButton) {
						if (selectedShortCode !== '') {
							submitButton.innerHTML = eviivoConfig.updateLabel || 'Update';
						} else {
							submitButton.innerHTML = eviivoConfig.insertLabel || 'Insert';
						}
					}
				},
				'shortcode': selectedShortCode
			});
		});
	});
})();