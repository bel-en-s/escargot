(function () {
	'use strict';

	function initImageField(field) {
		var input = field.querySelector('input[type="hidden"]');
		var preview = field.querySelector('.escargot-image-preview');
		var chooseBtn = field.querySelector('.escargot-image-choose');
		var removeBtn = field.querySelector('.escargot-image-remove');
		if (!input || !preview || !chooseBtn || !removeBtn) return;

		var frame;

		function updatePreview(url) {
			if (url) {
				var img = preview.querySelector('img');
				if (!img) {
					img = document.createElement('img');
					preview.appendChild(img);
				}
				img.src = url;
				preview.style.display = 'block';
				removeBtn.style.display = 'inline-block';
			} else {
				preview.style.display = 'none';
				removeBtn.style.display = 'none';
			}
		}

		chooseBtn.addEventListener('click', function (e) {
			e.preventDefault();
			if (frame) {
				frame.open();
				return;
			}
			frame = wp.media({
				title: 'Elegir imagen',
				library: { type: 'image' },
				multiple: false,
				button: { text: 'Usar esta imagen' }
			});
			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				input.value = attachment.id;
				updatePreview(attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url);
			});
			frame.open();
		});

		removeBtn.addEventListener('click', function (e) {
			e.preventDefault();
			input.value = '';
			updatePreview('');
		});
	}

	function initAll(scope) {
		var fields = (scope || document).querySelectorAll('.escargot-image-field');
		Array.prototype.forEach.call(fields, initImageField);
	}

	document.addEventListener('DOMContentLoaded', function () {
		initAll(document);
	});
})();
