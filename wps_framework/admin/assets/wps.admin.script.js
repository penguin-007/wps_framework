(function ($) {

  // form option save
  $("body").on( "submit", ".wps_option_form", function(){
    var msg = $(this);
    var btn = $(this).find(".wps_option_submit");
    var curscr = $(this).attr("data-scr");

    var data = {
      action: 'wps_save_option_form',
      data: msg.serialize(),
      curscr: curscr,
    };

    $.ajax({
      url : ajaxurl, 
      type: 'POST', 
      data: data,
      beforeSend: function(xhr){
        btn.addClass("load");
      },
      success: function(data) {
        btn.removeClass("load");
        console.log("Option save");
      },
      error: function(data){
        alert("Oh my God, you killed Kenny!");
      }
    });
    return false;
  });


})(jQuery);