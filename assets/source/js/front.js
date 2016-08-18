document.addEventListener('DOMContentLoaded', function () {

	window.registerBookingForm = function (form) {
		if (form.eviivo_registerd) {
			return false;
		}

		form.eviivo_registerd = true;
		var roomTemplate = form.querySelector('.eviivo-room');

		if (roomTemplate) {
			var roomTemplateClone = roomTemplate.cloneNode(true);
			var checkInDate = form.querySelector('.eviivo-input-checkin input');
			var checkOutate = form.querySelector('.eviivo-input-checkout input');
			var dateFormat = 'YYYY-MM-DD';
			var i18n = JSON.parse(form.getAttribute('data-calendar-i18n'));
			var checkoutType = checkOutate.getAttribute('data-eviivo-checkout-type') || 'relative';
			var checkoutDays = parseInt(checkOutate.getAttribute('data-eviivo-checkout-days'), 10) || 0;

			var updatePickers = function (field) {
				var today = new Date().getTime();
				var checkInTime = checkInPicker.getDate().getTime();
				var checkOutTime = checkOutPicker.getDate().getTime();

				if (field === 'checkin') {
					switch (checkoutType) {
						case 'relative_to_checkin_date':
							checkOutTime = checkInTime + (checkoutDays * 86400000);
							checkOutPicker.setDate(new Date(checkOutTime), true);
							break;
					}
				}

				if (today > checkInTime) {
					checkInTime = today;
					checkInPicker.setDate(new Date(checkInTime), true);
				}

				if (checkInTime >= checkOutTime) {
					var nextDay = new Date(checkInTime);
					nextDay.setDate(nextDay.getDate() + 1);
					checkOutPicker.setDate(nextDay, true);
				}

				checkOutPicker.setMinDate(new Date(checkInTime));
			};

			var redrawPickadateCalendar = function (calendar) {
				var cells = calendar.el.querySelectorAll('tbody td button');

				/** @type Date */
				var checkInDate = checkInPicker.getDate();

				/** @type Date */
				var checkOutDate = checkOutPicker.getDate();

				var minDate = checkInDate.getFullYear() * 10000 + checkInDate.getMonth() * 100 + checkInDate.getDate();
				var maxDate = checkOutDate.getFullYear() * 10000 + checkOutDate.getMonth() * 100 + checkOutDate.getDate();

				for (var i = 0, c = cells.length; i < c; ++i) {
					var cell = cells[i];
					var day = parseInt(cell.getAttribute('data-pika-day') || 0, 10);
					var month = parseInt(cell.getAttribute('data-pika-month') || 0, 10);
					var year = parseInt(cell.getAttribute('data-pika-year') || 0, 10);

					var currentDate = year * 10000 + month * 100 + day;

					if (currentDate >= minDate && currentDate <= maxDate) {
						cell.classList.add('eviivo-interval');
					} else {
						cell.classList.remove('eviivo-interval');
					}
				}
			};

			var checkInPicker = new Pikaday({
				'field': checkInDate,
				'format': dateFormat,
				'onSelect': function () {
					updatePickers('checkin');
				},
				'minDate': new Date(),
				'i18n': i18n,
				'onDraw': redrawPickadateCalendar
			});

			var checkOutPicker = new Pikaday({
				'field': checkOutate,
				'format': dateFormat,
				'onSelect': function () {
					updatePickers('checkout');
				},
				'i18n': i18n,
				'onDraw': redrawPickadateCalendar
			});

			updatePickers();

			/** @type HTMLSelectElement */
			var roomSelect = form.querySelector('.eviivo-room-select');

			/** @type Element */
			var roomsWrapper = form.querySelector('.eviivo-rooms');

			/**
			 * 
			 * @type Element
			 */
			var roomsSelector = form.querySelector('.eviivo-rooms-selector');

			var redrawRooms = function () {
				/** @type HTMLOptionElement */
				var selectedOption = roomSelect.options[roomSelect.selectedIndex];
				var rooms = parseInt(selectedOption.value, 10);

				var currentRooms = form.querySelectorAll('.eviivo-room');

				if (rooms !== currentRooms.length) {
					if (rooms > currentRooms.length) {
						for (var i = 0, c = rooms - currentRooms.length; i < c; ++i) {
							var newRoom = roomTemplateClone.cloneNode(true);
							roomsWrapper.appendChild(newRoom);
						}
					} else {
						for (var i = 0, c = currentRooms.length - rooms; i < c; ++i) {
							if (roomsWrapper.childNodes.length === 0) {
								break;
							}

							roomsWrapper.removeChild(roomsWrapper.childNodes[roomsWrapper.childNodes.length - 1]);
						}
					}
				}

				var oldRoomCount = roomsSelector.getAttribute('data-eviivo-room-count');

				if (oldRoomCount) {
					roomsSelector.classList.remove('eviivo-room-count-' + oldRoomCount);
				}

				roomsSelector.classList.add('eviivo-room-count-' + rooms);
				if (rooms > 1) {
					roomsSelector.classList.add('eviivo-multiple-rooms');
				} else {
					roomsSelector.classList.remove('eviivo-multiple-rooms');
				}

				roomsSelector.setAttribute('data-eviivo-room-count', rooms);


				var currentRooms = form.querySelectorAll('.eviivo-room');
				for (var i = 0, c = currentRooms.length; i < c; ++i) {
					var room = currentRooms[i];
					var id = i + 1;
					var roomLabel = room.querySelector('.eviivo-room-label');
					var adultsSelect = room.querySelector('.eviivo-room-adults');
					var childrenSelect = room.querySelector('.eviivo-room-children');
					var adultsLabel = room.querySelector('.eviivo-adults label');
					var childrenLabel = room.querySelector('.eviivo-children label');

					adultsSelect.name = "adults" + id;
					childrenSelect.name = "children" + id;


					adultsSelect.id = adultsSelect.id.substr(0, adultsSelect.id.lastIndexOf('-')) + '-' + id;
					childrenSelect.id = childrenSelect.id.substr(0, childrenSelect.id.lastIndexOf('-')) + '-' + id;
					roomLabel.innerHTML = roomLabel.getAttribute('data-label-format').replace('%d', id);

					adultsLabel.setAttribute('for', adultsSelect.id);
					childrenLabel.setAttribute('for', childrenSelect.id);
				}
			};

			roomSelect.addEventListener('change', redrawRooms);
			redrawRooms();
		}
	};

	var bookingForms = document.querySelectorAll('.eviivo-booking-form');
	for (var i = 0, c = bookingForms.length; i < c; ++i) {
		window.registerBookingForm(bookingForms[i]);
	}

});