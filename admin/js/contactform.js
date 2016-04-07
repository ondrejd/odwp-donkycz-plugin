/**
 * Script that adds button with contact form shortcode into WordPress TinyMCE editor.
 *
 * @since 0.1
 * @link https://github.com/ondrejd/odwp-donkycz-plugin
 */
(function() {
	tinymce.create('tinymce.plugins.contactform', {
		init : function(editor, url) {
			editor.addButton('contactform', {
				// TODO Translate this!
				title : 'Contact form',
				// TODO image : url + '/contactformbutton.png',
				onclick : function() {
					editor.execCommand('mceInsertContent', false, '[contact-form]');
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			// TODO Translate this!
			return {
				longname : 'Contact Form',
				author : 'Ondřej Doněk',
				authorurl : 'http://ondrejd.info/',
				infourl : 'https://github.com/ondrejd/odwp-donkycz-plugin',
				version : '1.0'
			};
		}
	});
	tinymce.PluginManager.add('contactform', tinymce.plugins.contactform);
})();