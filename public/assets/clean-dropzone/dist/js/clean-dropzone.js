/**
 *
 * bs-dropzone v0.2
 *
 * https://github.com/wilnicho/bs-dropzone.js
 *
 * copyright (c) 2020 wilfredo nina choquetarqui
 *
 */

'use strict';

jQuery.fn.extend({

    clean_dropzone: function (options) {

		let $group, $element, $area, $box, $input, $preview, $line, $column, $image, defaults, files, flag, reader, extension;

		defaults = {
			preview: false,
			allowed: ['jpg', 'jpeg', 'png', 'bmp', 'webp', 'jfif', 'svg', 'ico', 'gif'],
			accepted: [],
			dropzoneTemplate: '<div class="clean-dropzone"><div class="clean-dropzone-area"></div><div class="clean-dropzone-message"></div><div class="clean-dropzone-preview"></div></div>',
			parentTemplate: '<div class="row-padding"></div>',
			childTemplate: '<div class="col s6 m6 l6"></div>',
			boxClass: 'alert text-center',
			imageClass: 'img-fluid',
			noneColorClass: 'alert info',
			dragColorClass: 'alert warning',
			doneColorClass: 'alert success',
			failColorClass: 'alert danger',
			language: {
				emptyText: '[Drop File Here or Click To Upload]',
				dragText: '[Drop File Here]',
				dropText: '[_t_ File(s)]'
			},
			change: function () {}
		};

		options = $.extend({}, defaults, options);
		$group = this;

		$group.each(function (i) {

			$element = $(this);
			if ($element.attr('accept') == undefined && options.accepted.length > 0) {
				$element.attr('accept', '.' + options.accepted.join(', .'));
			}
			$element.after(options.dropzoneTemplate);
			$element.appendTo($element.next().find('.clean-dropzone-area'));
			$area = $('<div></div>');
			$area.attr('class', 'clean-dropzone-box ' + options.boxClass + ' ' + options.noneColorClass).text(options.language.emptyText);
			$element.before($area);

			$element.on('dragover', function () {

				$(this).prev().attr('class', 'clean-dropzone-box ' + options.boxClass + ' ' + options.dragColorClass).text(options.language.dragText);

			}).on('dragleave drop', function () {

				$input = $(this);
				files = this.files;
				$input.prev().attr('class', 'clean-dropzone-box ' + options.boxClass + ' ' + options.noneColorClass).text((files.length > 0) ? (options.language.dropText.replace(/_t_/g, files.length)) : options.language.emptyText);
				$input.trigger('blur');

			}).on('change', function () {

				$input = $(this);
				$input.trigger('preview');
				$input.trigger('blur');
				options.change.call(this, $input, this.files);

			}).on('preview', function () {

				$input = $(this);
				$box = $input.prev();
				$preview = $input.closest('.clean-dropzone').find('.clean-dropzone-preview');
				$preview.empty();
				files = this.files;
				flag = false;
				if (files.length > 0) {
					if (options.preview) {
						$line = $(options.parentTemplate);
						$.each(files, function (i) {
							extension = files[i].name.match(/\.[0-9a-z]+$/i);
							extension = (extension) ? extension[0].split('.').pop().toLowerCase() : null;
							if ($.inArray(extension, options.allowed) + 1 > 0) {
								reader = new FileReader();
								reader.onload = function (e) {
									$image = $('<img src="' + e.target.result + '" class="' + options.imageClass + '">');
									$column = $image.wrap(options.childTemplate).parent();
									$column.appendTo($line);
								}
								reader.readAsDataURL(this);
								flag = true;
							}
						});
						if (flag) {
							$line.appendTo($preview);
							$preview.show();
						}
					}
					$box.text(options.language.dropText.replace(/_t_/g, files.length));
				} else {
					$preview.hide();
					$box.text(options.language.emptyText);
				}

			}).on('clear', function () {

				$input = $(this);
				$box = $input.prev();
				$preview = $input.closest('.clean-dropzone').find('.clean-dropzone-preview');
				$box.attr('class', 'clean-dropzone-box ' + options.boxClass + ' ' + options.noneColorClass).text(options.language.emptyText);
				$preview.empty();
				$preview.hide();

			}).on('validation', function (event, state) {

				$input = $(this);
				$box = $input.prev();
				$preview = $input.closest('.clean-dropzone').find('.clean-dropzone-preview');
				if (state) {
					$box.attr('class', 'clean-dropzone-box ' + options.boxClass + ' ' + options.doneColorClass);
				} else {
					$box.attr('class', 'clean-dropzone-box ' + options.boxClass + ' ' + options.failColorClass);
					$preview.hide();
				}
				$input.css('height', $box.outerHeight());

			});

			$element.closest('form').on('reset', function () {

				$(this).find(':file').each(function () {

					$(this).trigger('clear');

				});

			});

		});

	}

});
