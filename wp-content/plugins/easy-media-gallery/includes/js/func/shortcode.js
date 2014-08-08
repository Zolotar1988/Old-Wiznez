(function() {
   tinymce.create('tinymce.plugins.emgbutton', {
      init : function(ed, url) {
         ed.addButton('emgbutton', {
            title : 'Easy Media Gallery',
            image : url+'/../../images/easymedia-32x32.png',
            onclick : function() {
               var posts = prompt("Number of Media ( Leave empty to show all )   ", "4");

                  if (posts != null && posts != '')
                     ed.execCommand('mceInsertContent', false, '[easy-media posts="'+posts+'"]');
                  else
                     ed.execCommand('mceInsertContent', false, null);
    
            }
         });
      },
      createControl : function(n, cm) {
         return null;
      },
      getInfo : function() {
         return {
            longname : "Easy Media Gallery",
            author : 'Konstantinos Kouratoras',
            authorurl : 'http://www.kouratoras.gr',
            infourl : 'http://wp.smashingmagazine.com',
            version : "1.0"
         };
      }
   });
   tinymce.PluginManager.add('emgbutton', tinymce.plugins.emgbutton);
})();