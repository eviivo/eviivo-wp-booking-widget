/* global eviivoConfig */

document.addEventListener('DOMContentLoaded', function () {

	//Tab support
	(function () {

		var openTab = function (tabId) {
			var tabWrappers = document.querySelectorAll('#' + tabId);
			for (var tabIndex = 0, c = tabWrappers.length; tabIndex < c; ++tabIndex) {
				var tabWrapper = tabWrappers[tabIndex];
				if (tabWrapper) {
					var childrenTabs = tabWrapper.parentNode.querySelectorAll('.eviivo-tab');
					for (var i = 0, c = childrenTabs.length; i < c; ++i) {
						childrenTabs[i].classList.remove('current');
					}

					var activeTabLinks = document.querySelectorAll('a[href$="#' + tabId + '"]');

					for (var i = 0, c = activeTabLinks.length; i < c; ++i) {
						var activeTabLink = activeTabLinks[i];
						var childrenTabs = activeTabLink.parentNode.parentNode.querySelectorAll('a');

						for (var j = 0, cj = childrenTabs.length; j < cj; ++j) {
							childrenTabs[j].classList.remove('current');
						}

						activeTabLink.classList.add('current');
					}

					tabWrapper.classList.add('current');
				}
			}
		};

		var hasTooltipOpened = false;
		document.body.addEventListener('click', function (event) {
			var target = event.target;
			var nodeName = target.nodeName.toLowerCase();
			if (nodeName === 'a') {
				var hash = target.href.split('#').pop();

				if (hash) {
					var tabWrapper = document.getElementById(hash);
					if (tabWrapper) {
						openTab(hash);
						event.preventDefault();
						return false;
					}
				}
			}

			if (nodeName === 'button' && target.classList.contains('eviivo-tooltip-trigger')) {
				var parent = target.parentNode;
				var content = parent.querySelector('.eviivo-tooltip-content');
				if (content) {
					if (content.classList.contains('open')) {
						content.classList.remove('open');
					} else {
						if (hasTooltipOpened) {
							hasTooltipOpened.classList.remove('open');
						}
						content.classList.add('open');
						hasTooltipOpened = content;
						redrawBody();
					}
				}
			} else {
				if (hasTooltipOpened) {
					var tooltipWrapper = getClosestParent(target, 'eviivo-tooltip-wrapper');
					if (!tooltipWrapper) {
						hasTooltipOpened.classList.remove('open');
						hasTooltipOpened = false;
					}
				}
			}

			if (nodeName === 'a' || nodeName === 'textarea') {
				if (target.classList.contains('open-eviivo-booking-lightbox-config')) {

					var shortCode;

					switch (nodeName) {
						case 'a':
							var shortCodeSelctor = target.getAttribute('data-shortcode-selector');
							if (shortCodeSelctor) {
								shortCode = target.parentNode.parentNode.querySelector(shortCodeSelctor);
							}
							break;
						case 'textarea':
							shortCode = target;
							break;
					}


					if (shortCode) {
						new window.eviivoConfigWindow({
							'shortcode': shortCode.value,
							'onSuccess': function (shortcode) {
								shortCode.value = shortcode;

								var widgetContent = getClosestParent(target, 'widget-inside');
								if (widgetContent) {
									var saveWidgetButton = widgetContent.querySelector('.widget-control-save');
									if (saveWidgetButton) {
										saveWidgetButton.click();
									}
								}
							},
							'onDraw': function (form) {
								var actions = form.querySelector('.eviivo-input-actions');
								if (actions) {
									var button = actions.querySelector('button[type="submit"]');
									if (button) {
										button.innerHTML = eviivoConfig.updateLabel || 'Update';
									}
								}

								var widgetContent = getClosestParent(target, 'widget-inside');
								var formTitle = form.querySelector('.eviivo-form-title');
								if (widgetContent && formTitle) {
									var title = widgetContent.querySelector('input[name="title"]');
									if (title) {
										var titleValue = (title.value || (eviivoConfig.defaultWidgetName || 'Booking Form'));
										formTitle.innerHTML = (eviivoConfig.widgetEditTitle || 'Widget edit %s').replace('%s', titleValue);
									}
								}
							}
						});
					}

					event.preventDefault();
				}
			}
		});
		
		var redrawBody = function() {
			if(hasTooltipOpened !== false) {
				
				hasTooltipOpened.style.maxWidth = hasTooltipOpened.parentNode.parentNode.offsetWidth + 'px';
				console.log(hasTooltipOpened.parentNode.parentNode.offsetWidth);
			}
		};
		redrawBody();
		
		window.addEventListener('resize', redrawBody);
		
		if (window.location.hash) {
			var activeTabLink = document.querySelector('a[href$="' + window.location.hash + '"]');
			if (activeTabLink) {
				openTab(window.location.hash.substr(1));
			}
		}
	})();

	/**
	 * 
	 * @param {Element} node
	 * @param {String} className
	 * @returns {Element} 
	 */
	var getClosestParent = function (node, className) {
		var hasParent = false;
		while (node && node.classList && !node.classList.contains(className)) {
			node = node.parentNode;
		}

		if (node && node.classList && node.classList.contains(className)) {
			hasParent = true;
		}

		if (hasParent) {
			return node;
		} else {
			return null;
		}
	};

	/**
	 * 
	 * @param {Element} wrapper
	 * @returns {undefined} 
	 */
	var registerPreviewWrapper = function (wrapper) {

		var reloadScript = wrapper.getAttribute('data-eviivo-booking-form-preview-url');
		//var loadingLabel = wrapper.getAttribute('data-eviivo-loading-message') || 'Loading...';
		var genericErrorMessage = wrapper.getAttribute('data-eviivo-error-message') || 'Error generating the preview. Please try again later';

		if (!reloadScript) {
			return;
		}
		var loadingTimeout;

		var reload = function (data) {
			clearTimeout(loadingTimeout);

			loadingTimeout = setTimeout(function () {
				var request = new XMLHttpRequest();
				request.open('POST', reloadScript, true);
				request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

				//wrapper.innerHTML = loadingLabel;
				wrapper.classList.add('loading');

				request.onload = function () {
					try {
						if (request.status >= 200 && request.status < 400) {
							var response = JSON.parse(request.responseText);
							wrapper.innerHTML = response.form;

							var bookingForms = wrapper.querySelectorAll('.eviivo-booking-form');
							for (var i = 0, c = bookingForms.length; i < c; ++i) {
								window.registerBookingForm(bookingForms[i]);
							}
						} else {
							throw new Error();
						}
					} catch (exception) {
						wrapper.innerHTML = genericErrorMessage;
					}

					wrapper.classList.remove('loading');
				};

				request.onerror = function () {
					wrapper.innerHTML = genericErrorMessage;
					wrapper.classList.remove('loading');
				};

				request.send(jsonToQueryString(data));
			}, 350);
		};
		wrapper._reload = reload;
	};

	/**
	 * 
	 * @param {Object} data
	 * @returns {String} 
	 */
	var jsonToQueryString = function (data) {

		return Object.keys(data).map(function (k) {
			return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]);
		}).join('&');
	};

	/**
	 * 
	 * @param {Element} wrapper
	 * @returns {undefined} 
	 */
	var registerDatePickers = function (wrapper) {
		var datePickers = wrapper.querySelectorAll('.eviivo-datepicker');
		for (var i = 0, c = datePickers.length; i < c; ++i) {
			new Pikaday({
				'field': datePickers[i],
				'format': 'YYYY-MM-DD'
					//'i18n': i18n
			});
		}
	};

	/**
	 * 
	 * @param {Element} input
	 * @returns {String} 
	 */
	var getInputValue = function (input) {
		var value = '';
		switch (input.nodeName.toLowerCase()) {
			case 'select':
				value = input.options[input.selectedIndex].value;
				break;
			default:
				if (input.type === 'checkbox') {
					value = input.checked ? input.value : 0;
				} else {
					value = input.value;
				}
				break;
		}

		return value;
	};

	/**
	 * 
	 * @param {Element} input
	 * @param {String} value
	 * @returns {undefined} 
	 */
	var setInputValue = function (input, value) {
		switch (input.nodeName.toLowerCase()) {
			case 'select':
				for (var i = 0, c = input.options.length; i < c; ++i) {
					var option = input.options[i];
					if (option.value == value) {
						input.selectedIndex = i;
						break;
					}
				}
				break;
			default:
				input.value = value;
				break;
		}
	};

	/**
	 * 
	 * @param {Element} wrapper
	 * @returns {undefined} 
	 */
	var registerBookingFormConfig = function (wrapper) {
		var registerDateConfig = function (wrapper) {
			/** @type Element */
			var type = wrapper.querySelector('.type-select');

			var days = wrapper.querySelector('.days-select').parentNode;
			var date = wrapper.querySelector('.date-select').parentNode;
			var day = wrapper.querySelector('.day-select').parentNode;

			var redraw = function () {
				days.style.display = 'none';
				date.style.display = 'none';
				day.style.display = 'none';
				switch (getInputValue(type)) {
					case 'relative':
						days.style.display = 'block';
						break;
					case 'relative_to_checkin_date':
						days.style.display = 'block';
					break;
					case 'absolute':
						date.style.display = 'block';
						break;
					case 'relative_to_day_of_week':
						day.style.display = 'block';
						break;
				}
			};

			type.addEventListener('change', redraw);
			redraw();
		};

		/**
		 * 
		 * @type Element
		 */
		var theme = wrapper.querySelector('select[name$="[theme]"]');
		var customStyles = wrapper.querySelector('.eviivo-custom-style');
		if (theme && customStyles) {
			var checkTheme = function () {
				if (getInputValue(theme) === 'custom') {
					customStyles.style.display = 'block';
				} else {
					customStyles.style.display = 'none';
				}
			};

			theme.addEventListener('change', function () {
				checkTheme();
			});
			checkTheme();
		}

		/**
		 * 
		 * @type Element
		 */
		var hasTransparentBackground = wrapper.querySelector('input[name$="[hasTransparentBackground]"]');
		var backgroundColor = wrapper.querySelector('.eviivo-background-color');
		if (hasTransparentBackground && backgroundColor) {
			var checkHasTransparentBackground = function () {
				if (getInputValue(hasTransparentBackground)) {
					backgroundColor.style.display = 'none';
				} else {
					backgroundColor.style.display = 'block';
				}
			};
			hasTransparentBackground.addEventListener('change', function () {
				checkHasTransparentBackground();
			});
			checkHasTransparentBackground();
		}


		var dateConfigRows = wrapper.querySelectorAll('.eviivo-data-config-row');
		for (var i = 0, c = dateConfigRows.length; i < c; ++i) {
			registerDateConfig(dateConfigRows[i]);
		}
	};

	/**
	 * 
	 * @param {Element} wrapper
	 * @param {Element} form
	 * @returns {undefined} 
	 */
	var registerPreviewForm = function (wrapper, form) {
		var inputs = [];
		var checkInputs = [];

		var registerInput = function (name) {
			var inputName = form.id + '[' + name + ']';
			/** @type Element */
			var input = form.querySelector('[name="' + inputName + '"]');

			if (input) {
				input.addEventListener('change', function () {
					eviivoConfig.bookingForm[name] = getInputValue(input);
					reloadPreviewPanels();
				});

				inputs.push({
					'input': input,
					'name': name,
					'initalValue': getInputValue(input)
				});
			}
		};

		var reloadPreviewPanels = function () {
			var data = {};
			for (var i = 0, c = inputs.length; i < c; ++i) {
				var input = inputs[i];
				data[input.name] = getInputValue(input.input);
			}

			for (var i = 0, c = previewWrappers.length; i < c; ++i) {
				previewWrappers[i]._reload(data);
			}
		};

		var previewWrappers = wrapper.querySelectorAll('.eviivo-booking-form-preview');
		for (var i = 0, c = previewWrappers.length; i < c; ++i) {
			registerPreviewWrapper(previewWrappers[i]);
		}

		var allInputs = form.querySelectorAll('input, select, textarea');
		for (var i = 0, c = allInputs.length; i < c; ++i) {
			var name = allInputs[i].name;
			if (name) {
				var start = name.indexOf('[');
				var end = name.lastIndexOf(']');
				if (start !== -1 && end !== -1) {
					checkInputs.push(name.substr(start + 1, end - start - 1));
				}
			}
		}

		for (var i = 0, c = checkInputs.length; i < c; ++i) {
			registerInput(checkInputs[i]);
		}
	};

	//eviivo preview functionality
	(function () {

		registerBookingFormConfig(document);
		registerDatePickers(document);

		var bookingFormConfig = document.querySelector('form.eviivo-booking-form-config');
		if (bookingFormConfig) {
			registerPreviewForm(document, bookingFormConfig);

			var tabs = bookingFormConfig.querySelectorAll('.eviivo-tabs a');

			var selectTab = function (hash) {
				/**
				 * 
				 * @type String
				 */
				var action = bookingFormConfig.action;
				var hashStart = action.indexOf('#');
				if (hashStart !== -1) {
					action = action.substr(0, hashStart);
				}

				bookingFormConfig.action = action + '#' + hash;
			}

			/**
			 * 
			 * @param {Element} tab
			 * @returns {undefined} 
			 */
			var registerBookingFormConfigTab = function (tab) {
				tab.addEventListener('click', function () {
					var hash = this.href.split('#').pop();
					if (hash) {
						selectTab(hash);
					}
				});
			};
			for (var i = 0, c = tabs.length; i < c; ++i) {
				registerBookingFormConfigTab(tabs[i]);
			}

			var windowHash = window.location.hash;
			if (windowHash && document.getElementById(windowHash.substr(1))) {
				selectTab(windowHash.substr(1));
			}
		}
	})();

	window.eviivoConfigWindow = function (options) {

		var loading = false;
		var instance = this;
		var initalConfig = {};
		var wrapper = document.createElement('div');
		var form;
		wrapper.className = 'eviivo-lightbox';
		wrapper._eviivoConfigWindow = instance;

		var init = function () {
			document.body.appendChild(wrapper);
			instance.show();
		};

		/**
		 * 
		 * @returns {Object} 
		 */
		var getConfigFromForm = function () {
			var config = {};

			if (form) {
				var allInputs = form.querySelectorAll('input, select, textarea');
				for (var i = 0, c = allInputs.length; i < c; ++i) {
					var name = allInputs[i].name;
					if (name) {
						var start = name.indexOf('[');
						var end = name.lastIndexOf(']');
						if (start !== -1 && end !== -1) {
							config[name.substr(start + 1, end - start - 1)] = getInputValue(allInputs[i]);
						}
					}
				}
			}

			return config;
		};

		var getConfigFromShortcode = function (shortcode) {

			var config = {};

			var shortcodeTag = '[' + eviivoConfig.shortcode;
			var segments = shortcode.substr(shortcodeTag.length + 1, shortcode.length - shortcodeTag.length - 2);

			if (segments) {
				segments = segments.split(' ');
				for (var i = 0, c = segments.length; i < c; ++i) {
					var segment = segments[i].split('=');
					if (segment) {
						var name = segment.shift();
						var value = segment.join('=');
						config[name] = value.substr(1, value.length - 2);
					}
				}
			}


			return config;
		}

		this.changeContent = function (content) {
			wrapper.innerHTML = content;

			var closeBtn = document.createElement('button');
			closeBtn.innerHTML = 'X';
			closeBtn.className = 'close';
			closeBtn.type = 'button';
			closeBtn.addEventListener('click', function (event) {
				instance.distroy();
				event.preventDefault();
			});
			wrapper.appendChild(closeBtn);
		};

		this.loadContent = function () {
			if (!loading) {
				loading = true;
				wrapper.classList.add('loading');
				wrapper.innerHTML = eviivoConfig.loadingMessage || 'Loading';

				var genericErrorMessage = eviivoConfig.genericErrorMessage || 'Error generating the preview. Please try again later';
				var request = new XMLHttpRequest();
				request.open('GET', eviivoConfig.bookingFormConfigScript, true);

				request.onload = function () {
					try {
						if (request.status >= 200 && request.status < 400) {
							var response = JSON.parse(request.responseText);
							instance.changeContent(response.form);

							/** @type Element */
							form = wrapper.querySelector('.eviivo-booking-form-config');

							var actions = form.querySelector('.eviivo-input-actions');
							if (actions) {
								var submitBtn = actions.querySelector('button[type="submit"]');
								if (submitBtn) {
									submitBtn.innerHTML = eviivoConfig.addLabel || 'Add';
									var closeBtn = document.createElement('button');
									closeBtn.innerHTML = eviivoConfig.cancelLabel || 'Cancel';
									closeBtn.type = 'reset';
									closeBtn.addEventListener('click', function (event) {
										instance.distroy();
										event.preventDefault();
									});

									submitBtn.parentNode.appendChild(closeBtn);
								}
							}

							var title = form.querySelector('h2');
							if (title) {
								title.innerHTML = eviivoConfig.shortCodeTitle || 'Embed eviivo booking form';
							}

							form.addEventListener('submit', function (event) {
								instance.success();
								event.preventDefault();
							});

							registerBookingFormConfig(wrapper);
							registerDatePickers(wrapper);

							initalConfig = getConfigFromForm(form);

							if (options.shortcode) {
								var config = getConfigFromShortcode(options.shortcode);
								for (var name in config) {
									var input = form.querySelector('[name$="[' + name + ']"]');
									setInputValue(input, config[name]);
								}
							}

							if (options.onDraw) {
								options.onDraw(form);
							}

							registerPreviewForm(wrapper, form);

							var triggerPreviewReload = form.querySelector('input');
							if (triggerPreviewReload) {
								var event = document.createEvent('HTMLEvents');
								event.initEvent('change', false, true);
								triggerPreviewReload.dispatchEvent(event);
							}

							var firstInput = form.querySelector('input, select, textarea');
							if (firstInput) {
								firstInput.focus();
							}
						} else {
							throw new Error();
						}
					} catch (exception) {
						instance.changeContent(genericErrorMessage);
					}

					loading = false;
					wrapper.classList.remove('loading');
				};

				request.onerror = function () {
					instance.changeContent(genericErrorMessage);
					wrapper.classList.remove('loading');
					loading = false;
				};

				request.send();
			}
		};

		this.show = function () {
			this.loadContent();
			wrapper.classList.add('open');

			return this;
		};

		this.hide = function () {
			wrapper.classList.remove('open');

			return this;
		};

		this.distroy = function () {
			wrapper.parentNode.removeChild(wrapper);
			delete wrapper;
		};

		this.success = function () {

			if (form) {
				if (options.onSuccess) {
					var config = {};
					var currentConfig = getConfigFromForm(form);

					for (var name in currentConfig) {
						if (typeof (initalConfig[name]) === 'undefined' || initalConfig[name] !== currentConfig[name]) {
							config[name] = currentConfig[name];
						}
					}

					var shortcode = '[' + (eviivoConfig.shortcode || 'eviivo-booking-form');
					var attributes = [];

					if (config) {
						for (var name in config) {
							attributes.push(name + "=\"" + config[name] + "\"");
						}

						if (attributes.length) {
							shortcode += ' ' + attributes.join(' ');
						}
					}
					shortcode += ']';

					options.onSuccess(shortcode, config);
				}
			}

			this.distroy();
		};

		init();
	};

	window.addEventListener('keyup', function (event) {
		if (event.keyCode === 27) {
			var lightboxes = document.querySelectorAll('.eviivo-lightbox');
			for (var i = 0, c = lightboxes.length; i < c; ++i) {
				var lightbox = lightboxes[i];
				if (lightbox._eviivoConfigWindow) {
					lightbox._eviivoConfigWindow.distroy();
				}
			}
		}
	});
});
