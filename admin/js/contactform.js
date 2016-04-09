/**
 * Script that adds button with contact form shortcode into WordPress TinyMCE editor.
 *
 * @since 0.1
 * @link https://github.com/ondrejd/odwp-donkycz-plugin
 *
 * @todo Rename this file (because it will be not just contact form button)!
 * @todo Translate all strings!
 */
(function() {
	tinymce.create('tinymce.plugins.donkycz', {
		init : function(editor, url) {
			editor.addButton('donkycz', {
				title: 'Donky.cz',
				image: url + '/../../icon-20.png',
				type: 'menubutton',
				menu: [
					{
						text: 'Contact Form',
						//icon: 'icon dashicons-edit',
						onclick: function(event) {
							event.stopPropagation();
							editor.execCommand('mceInsertContent', false, '[contact-form]');
						}
					}
				]
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname: 'Donky.cz',
				author: 'Ondřej Doněk',
				authorurl: 'http://ondrejd.info/',
				infourl: 'https://github.com/ondrejd/odwp-donkycz-plugin',
				version: '1.0'
			};
		}
	});
	tinymce.PluginManager.add('donkycz', tinymce.plugins.donkycz);
})();