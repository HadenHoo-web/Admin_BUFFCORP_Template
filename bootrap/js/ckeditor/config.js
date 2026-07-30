/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.filebrowserBrowseUrl = '/bootrap/js/kcfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = '/bootrap/js/kcfinder/browse.php?type=Images';
	config.filebrowserFlashBrowseUrl = '/bootrap/js/kcfinder/browse.php?type=Flash';
	config.filebrowserUploadUrl = '/bootrap/js/kcfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = '/bootrap/js/kcfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl = '/bootrap/js/kcfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';

};
CKEDITOR.config.entities = false;