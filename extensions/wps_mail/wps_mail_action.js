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
    // redirect
    var redirect = form.find('[name=form_redirect]').val();

    $.ajax({
      url : theme_ajax.url, 
      type: 'POST', 
      data: data,
      dataType:'json',
      cache: false,
      processData: false,
      contentType: false,
      beforeSend: function(){ 
        form.addClass('sending');
        btn_submit.addClass('sending');
        btn_submit.prop('disabled', true);
      },
      success: function(data) {
        // if redirect
        if ( redirect !== undefined && redirect !== "" ) {
          window.location.replace(redirect+"/");
        } else {
          form.trigger('reset');
          // clear styles
          btn_submit.prop('disabled', false);
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
          console.log("WPS Mail success");
        }
        // if callback
        if ( typeof MailActions[callback] !== "undefined" ){
          MailActions[callback]();
        }
      },
      error: function(data){
        console.log("WPS Mail error");
        btn_submit.prop('disabled', false);
      }
    });
    return false;
  });
})(jQuery);