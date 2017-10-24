(function ($) {

  tinymce.create('tinymce.plugins.Wptuts', {
    init : function(ed, url) {

      /* wps_bold */
      ed.addButton('wps_bold', {
        title : 'Жирный текст (<span class="strong"></span>)',
        cmd   : 'wps_bold',
        text: "B"
      });
      ed.addCommand('wps_bold', function() {
        var selected_text = ed.selection.getContent();
        var return_text = '';
        return_text = '<span class="strong">' + selected_text + '</span>';
        ed.execCommand('mceInsertContent', 0, return_text);
      });

      ed.addButton('collapse_btn', {
        title : 'Обернуть блок',
        cmd : 'collapse_block',
        text: "[^]"
      });
      ed.addCommand('collapse_block', function() {
        var selected_text = ed.selection.getContent();
        var return_text = '';
        return_text = '[collapse_block]' + '<p>' + selected_text + '</p>' + '[/collapse_block]';
        ed.execCommand('mceInsertContent', 0, return_text);
      });

    },
  });
  // Register plugin
  tinymce.PluginManager.add( 'wptuts', tinymce.plugins.Wptuts );

})(jQuery);