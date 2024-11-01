(function () {

    tinymce.PluginManager.add("WeddingRegistry", function(editor, url) {

        editor.addButton('bean_registry_button', {
            type: "splitbutton",
            title: "Insert Registry Shortcode",
            menu: [
                 createSubmenuButtonImmediate( "Bed Bath & Beyond",
                    '[registry info="bed-bath-and-beyond" url=""]'
                    ),
                 createSubmenuButtonImmediate( "Bloomingdales",
                    '[registry info="bloomingdales" url=""]'
                    ),
                 createSubmenuButtonImmediate( "Crate & Barrel",
                    '[registry info="crate-and-barrel" url=""]'
                    ),
                 createSubmenuButtonImmediate( "Etsy",
                    '[registry info="etsy" url=""]'
                    ),
                 createSubmenuButtonImmediate( "Honeyfund",
                    '[registry info="honeyfund" url=""]'
                    ),
                 createSubmenuButtonImmediate( "JCPenny",
                    '[registry info="jcpenny" url=""]'
                    ),
                 createSubmenuButtonImmediate( "Kohls",
                    '[registry info="kohls" url=""]'
                    ),
                 createSubmenuButtonImmediate( "Macys",
                    '[registry info="macys" url=""]'
                    ),
                 createSubmenuButtonImmediate( "NewlyWish",
                    '[registry info="newlywish" url=""]'
                    ),
                 createSubmenuButtonImmediate( "REI",
                    '[registry info="rei" url=""]'
                    ),
            ],
            onclick: function() {}
        });

        function createSubmenuButtonImmediate( title, sc ) {
            return {
                text: title,
                onclick: function() {
                    executeTinyMCECommand( 'mceInsertContent', sc );
                }
            }
        }

        function executeTinyMCECommand( command, args ) {
            if (typeof window.tinyMCE.activeEditor != 'undefined') {
                window.tinyMCE.activeEditor.selection.moveToBookmark(window.tinymce_cursor);
            }
            if (typeof window.tinyMCE.execInstanceCommand != 'undefined') {
                window.tinyMCE.execInstanceCommand('content', command, false, args);

            } else {
                if (typeof window.tinyMCE.execCommand != 'undefined') {
                    window.tinyMCE.get('content').execCommand(command, false, args);
                }
            }
        }
    });
})();