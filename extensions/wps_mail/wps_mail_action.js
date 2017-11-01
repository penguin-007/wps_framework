if ( typeof(MailActions) === "undefined" ||  typeof(MailActions) === "null" ){
  var MailActions = {};
}
(function ($) {
  // wps_form
  $("body").on( "submit", ".wps_form_js", function(){
    var form       = $(this);
    var btn_submit = form.find('[type=submit]');
    var data       = new FormData(form[0]);
    // action for wp
    data.append("action", "wps_form_send");
    // callback
    var callback = form.data("callback");

    $.ajax({
      url : theme_ajax.url, 
      type: 'POST', 
      data: data,
      dataType:'json',
      cache: false,
      processData: false,
      contentType: false,
      beforeSend: function(data){ 
        form.addClass('sending');
        btn_submit.addClass('sending');
      },
      success: function(data) {
        // if redirect
        if ( data.location !== undefined && data.location !== "" ) {
          window.location.replace(data.location+"/");
        } else {
          form.trigger('reset');
          // clear styles
          form.removeClass('sending');
          btn_submit.removeClass('sending');
          // style success
          form.addClass('success');
          btn_submit.addClass('success');
          // clear styles success
          setTimeout(function(){
            form.removeClass('success');
            btn_submit.removeClass('success');
          }, 2500);
          // message
          console.log("mail send success");
        }
        // if callback
        if ( typeof MailActions[callback] !== "undefined" ){
          MailActions[callback]();
        }
      },
      error: function(data){
        console.log("Mail ajax error");
      }
    });
    return false;
  });
})(jQuery);