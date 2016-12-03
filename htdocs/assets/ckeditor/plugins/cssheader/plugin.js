CKEDITOR.plugins.add( 'cssheader',{
	init: function( editor ){
		editor.addCommand( 'insertcssheader',{
				exec : function( editor ){    
					var theSelectedText = editor.getSelection().getNative();
					var string = String(theSelectedText);
					if(string.length) string = string.replace( /^\s+/g, '' );// strip leading
					if(string.length) string = string.replace( /\s+$/g, '' );// strip trailing
					if(string.length){
						editor.insertHtml('<span class="editor-headline">'+theSelectedText+'</span>');
					}
					else{
						alert('Please select the text you wish to modify');
					}
				}
			});
		editor.ui.addButton('cssheader',{
			label: 'Format Header Style',
			command: 'insertcssheader',
			icon: this.path + 'images/icon.gif'
		});
	}
});
