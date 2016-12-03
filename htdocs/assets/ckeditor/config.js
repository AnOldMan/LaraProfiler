/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.resize_enabled = true;
	config.resize_maxWidth = 825;
	config.resize_minHeight = 400;
	config.height = 600;
	config.contentsCss = ['/css_js/style.css'];
	config.bodyClass = 'cms content wysiwyg';
	config.fillEmptyBlocks = false;	// Prevent filler nodes in all empty blocks.
    config.filebrowserBrowseUrl = '/css_js/ckeditor/filemanager/index.html';
	config.toolbar =
	[
		{ name: 'document',    items : [ 'Source','DocProps' ] },
		{ name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing',     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Subscript','Superscript','-','RemoveFormat' ] },
		{ name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
		{ name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'insert',      items : [ 'Image','Flash','Table','HorizontalRule','SpecialChar' ] },
		{ name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
		{ name: 'colors',      items : [ 'TextColor','BGColor' ] },
		{ name: 'tools',       items : [ 'Maximize', 'ShowBlocks','-','About' ] }
	];
	config.toolbar_Full =
	[
		{ name: 'document',    items : [ 'Source','DocProps' ] },
		{ name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing',     items : [ 'Find','Replace','-','SelectAll' ] },
		{ name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'insert',      items : [ 'Image','HorizontalRule','SpecialChar' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Subscript','Superscript','-','RemoveFormat' ] },
		{ name: 'colors',      items : [ 'TextColor','BGColor' ] },
		{ name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
		{ name: 'styles',      items : [ 'Styles','Format','Font','FontSize', 'cssheader', 'csstip' ] }
	];
	config.toolbar_Basic =
	[
		[ 'Source', '-', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ]
	];
};
