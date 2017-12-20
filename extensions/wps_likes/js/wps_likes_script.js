$(document).ready(function(){

  var WPS_Likes = {

    /* wps__send_data */
    send_data: function ( data, callback ){
      // add action
      data.action = "likes_actions";
      // go request
      $.ajax({
        url: theme_ajax.url,
        type: "POST",
        data: data,
        dataType: "json",
        success: function(data) {
          if (callback) callback(data);
          console.log("WPS Likes Ajax Success."); 
        },
        error: function(data){
          console.log("WPS Likes Ajax Error. Have Fun :)");
        }
      });
    }
    
  }

  $("body").on( "click", ".fn__wps__likes_holder", function(){
    var _this    = $(this);
    var post_id  = _this.data("post_id");

    var data = {
      // system
      whatdo: "clickLike",
      // data
      post_id: post_id
    };
    // block btn
    _this.prop('disabled', true);

    function check_like(data){
      if (data.like){
        _this.addClass("active");
      } else {
        _this.removeClass("active");
      }
      _this.find(".fn__wps__likes_holder__count").html(data.count);
      _this.prop('disabled', false);
    }

    WPS_Likes.send_data( data, check_like );
  });

});