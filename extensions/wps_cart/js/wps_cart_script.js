$(document).ready(function(){

  /* add to cart */
  $("body").on( "click", ".fn__wps__add_to_cart", function(){
    var _this    = $(this);
    var post_id  = _this.data("post_id");

    var data = {
      // system
      whatdo: "addToCart",
      post_id: post_id,
      // other
      //count : 5
    };

    function set_count(data){
      $("#fn__wps__cart_min_count").html(data.count);
      _this.addClass("success");
      setTimeout(function(){
        _this.removeClass("success");
      }, 1500);
    }
    wps__send_data( data, set_count );
  });


  /* remove from cart */
  $("body").on( "click", ".fn__wps__remove_from_cart", function(){
    var _this   = $(this);
    var item_id = _this.data("item_id");

    var data = {
      // system
      whatdo: "removeFromCart",
      item_id: item_id,
    };

    function reload(data){
      if ( data.success ){
        //console.info( data.success );
        location.reload();
      }
      if ( data.error ){
        console.warn( data.error );
      }
    }
    wps__send_data( data, reload );
  });


  /* clear cart */
  $("body").on( "click", ".fn__wps__clear_cart", function(){
    var data = {
      // system
      whatdo: "clearCart",
    };
    function reload(){
      location.reload();
    }
    wps__send_data( data, reload );
  });


  /* clear cart */
  $("body").on( "click", ".fn__wps__update_cart", function(){
    function reload(){
      location.reload();
    }
    wps_update_cart( reload );
  });

  function wps_update_cart( callback ){
    var items = [];
    $(".fn__wps__cart_item_wrap").each(function(){
      var data = {};
      data.item_id = $(this).data("item_id");
      data.count   = $(this).find(".fn__wps__cart_item_count").val();
      items.push( data );
    });
    var data = {
      // system
      whatdo: "updateCart",
      items: items
    };
    wps__send_data( data, callback );
  }


  $("body").on( "click", "#fn__wps__cart_go_order", function(){
    function reload(){
      window.location.replace("order/");
    }
    wps_update_cart( reload );
  });


  $("body").on( "submit", "#fn__wps__cart_send_order", function(){
    var form = $(this).serialize();
    var data = {
      // system
      whatdo: "sendCartOrder",
      form: form,
    };
    function reload(data){
      if ( data.reload != '' ){
        window.location.replace(data.reload);
      }
    }
    wps__send_data( data, reload );
    // break form send
    return false;
  });


  /* wps__send_data */
  function wps__send_data( data, callback ){
    // add action
    data.action = "cart_actions";
    // go request
    $.ajax({
      url: theme_ajax.url,
      type: "POST",
      data: data,
      dataType: "json",
      success: function(data) {
        if (callback) callback(data);
        console.log("WPS Cart Ajax Success.");
      },
      error: function(data){
        console.log("WPS Cart Ajax Error. Have Fun :)");
      }
    });
  }

  // -- methods
  // .fn__wps__clear_cart
  // .fn__wps__update_cart
  // .fn__wps__add_to_cart (data-post_id)
  // .fn__wps__remove_from_cart (data-item_id)
  // .fn__wps__cart_min_count
  // #fn__wps__cart_go_order
  // #fn__wps__cart_send_order
  // -- front methods
  // #fn__wps__cart
  // .fn__wps__cart_item_wrap
  // .fn__wps__cart_item_count
  // .fn__wps__cart_item_price
  // .fn__wps__cart_item_sum_price
  // #fn__wps__cart_fullprice

  if ( $("#fn__wps__cart").length ){
    $("body").on( "change", ".fn__wps__cart_item_count", function(){
      wps_set_sum_price( $(this) );
      wps_set_total_price();
    });
    $("body").on( "keyup", ".fn__wps__cart_item_count", function(){
      wps_set_sum_price( $(this) );
      wps_set_total_price();
    });
  }

  function wps_set_sum_price( _this ){
    var wrap  = _this.closest(".fn__wps__cart_item_wrap");
    var count = _this.val();
    var price = wrap.find(".fn__wps__cart_item_price").text();
    wrap.find( ".fn__wps__cart_item_sum_price" ).text( count * price );
  }

  function wps_set_total_price(){
    var sum = 0;
    $(".fn__wps__cart_item_wrap").each(function(){
      sum += parseInt( $(this).find(".fn__wps__cart_item_sum_price").text() );
    });
    $("#fn__wps__cart_fullprice").text(sum);
  }

});