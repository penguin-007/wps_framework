$(document).ready(function(){

  /* cart add to cart */
  $("body").on( "click", ".add_to_cart_js", function(){

    var btn        = $(this);
    var item_id    = btn.attr("data-id");
    var cart_alert = $("#single_cart__alert");
    var size       = $.trim( $('#filter_size__wrap').find( 'input[name=size]:checked' ).val() );
    var count      = $(".count__item__input").val();

    if ( size == '' ) {
      cart_alert.text('Выберите размер')
      .delay(0).css({'opacity' : '1'})
      .delay(500).animate({'opacity' : '0'}, 700);
      return;
    }

    var data = {
      action: "cart_action",
      id: item_id,
      size: size,
      count: count
    };
    
    $.ajax({
      url: theme_ajax.url,
      type: "POST",
      data: data,
      beforeSend: function() {
      },
      success: function(data) {
        $(".cart_count").text(data);
        cart_alert.text('Добавлено в коризну')
        .delay(0).css({'opacity' : '1'})
        .delay(500).animate({'opacity' : '0'}, 1000);
      },
      error: function(data){
      }
    });
    return false;
  });


  /* cart wps_clear_cart */
  $("body").on( "click", "#wps_clear_cart", function(){
    var data = {
      action: "clear_cart",
    };
    $.ajax({
      url: theme_ajax.url,
      type: "POST",
      data: data,
      success: function(data) {
        //console.log(data);
        location.reload();
      },
      error: function(data){
        console.error("clear error :(");
      }
    });
    return false;
  });


  /*  cart_go_order */
  $("body").on( "click", "#cart_go_order", function(){

    var goods = [];

    $(".cart_row").each(function(){
      var item = $(this);
      var item_uid = $.trim( item.attr("data-uid") );
      //var item_id  = $.trim( item.attr("data-id") );
      var size     = $.trim( item.find('.size_select').val() );
      var count    = item.find(".count__item__input").val();
      goods.push( [ item_uid, size, count ] );
    });

    var data = {
      action: "cart_go_order",
      goods: goods
    };

    $.ajax({
      url: theme_ajax.url,
      type: "POST",
      data: data,
      success: function(data) {
        //console.log(data);
        window.location.replace("checkout/");
      },
      error: function(data){
        console.error("update error :(");
      }
    });
    return false;
  });


  $("body").on( "change", "#select_sity", function(){
    var cur_item  = $(this).find('option:selected');
    var city      = cur_item.val();
    var data_city = cur_item.attr("data-city");

    var data = {
      action: "select_sity",
      city: city,
      data_city: data_city,
    };

    $.ajax({
      url: theme_ajax.url,
      type: "POST",
      data: data,
      success: function(data) {
        $("#warehouses").html(data);
        //$(".order_page").html(data);
        //console.log(data);
      },
      error: function(data){
        console.error("update error :(");
      }
    });
    return false;
  });


  // cart_order_form
  $("body").on( "submit", "#order_send_form", function(){

    var msg = $(this).serialize();

    var data = {
      action: "cart_order_form",
      msg: msg,
    };

    $.ajax({
      url : theme_ajax.url, 
      type: 'POST', 
      data: data,
      success: function(data) {
        console.log(data);
        //$('form').trigger('reset');
        //location.reload();
        window.location.replace("thank_you/");
      },
      error: function(data){
        btn.val('Ошибка');
      }
    });
    return false;
  });

});

