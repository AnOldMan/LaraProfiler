CKEDITOR.plugins.add( 'csstip',{
	init: function( editor ){
		editor.addCommand( 'insertcsstip',{
				exec : function( editor ){    
					var theSelectedText = editor.getSelection().getNative();
					var string = String(theSelectedText);
					if(string.length) string = string.replace( /^\s+/g, '' );// strip leading
					if(string.length) string = string.replace( /\s+$/g, '' );// strip trailing
					if(string.length){
						editor.insertHtml('<div class="editor-tip"><span class="tip-title">Tip:</span>'+theSelectedText+'</div>');
					}
					else{
						alert('Please select the text you wish to modify');
					}
				}
			});
		editor.ui.addButton('csstip',{
			label: 'Format Tip Style',
			command: 'insertcsstip',
			icon: this.path + 'images/icon.gif'
		});
	}
});
