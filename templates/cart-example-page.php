<?php
  /**
   * Template Name: WPS Корзина
   */
  get_header();
?>


<style>
  .wrap {
    max-width: 1200px;
    margin: 150px auto;
  }
  table {
    width: 100%;
    table-layout: fixed;

  }
  table td {
    border: 1px solid #ccc;
    padding: 10px;
    overflow: auto;
  }
  .item_count {
    display: block;
    width: 50px;
    height: 30px;
    border: 2px solid #333;
    margin: 20px auto;
    text-align: center;
    font-size: 18px;
  }
</style>



<?php 

## основные методы работы с корзиной

// WPS_Cart::getCountCart();   - количество товаров
// WPS_Cart::getCartProduct(); - получить массив товаров
// WPS_Cart::get_actualy_price( $post_id ) - получить реальную цену товара
// WPS_Cart::get_total_price(); - получить сумму в корзине
// WPS_Cart::get_currency(); - получить валюту

?>



<div class="wrap" id="fn__wps__cart">

  <?php 
    $count = WPS_Cart::getCountCart();
    if ( $count <= 0 ) : 
  ?>

  <p>Корзина пуста</p>

  <?php else:
    $products = WPS_Cart::getCartProduct();
    $currency = WPS_Cart::get_currency();
  ?>

  <table>

    <tr>
      <td>
        print_r($product)
      </td>
      <td>
        Цена
      </td>
      <td>
        Количество
      </td>
      <td>
        Сумма
      </td>
      <td>
        Название
      </td>
      <td>
        Изображение
      </td>
      <td>
        Удалить
      </td>
    </tr>

    <?php foreach ($products as $product ) : 
      $post_id  = $product["post_id"];
      $item_id  = $product["item_id"];
      $title    = $product["post_title"];
      $count    = $product["count"];
      // other
      $link     = get_permalink( $post_id );
      $image    = get_post_meta( $post_id, "item_prev", true);
      $price    = WPS_Cart::get_actualy_price( $post_id );
      $sum      = $price * $count;
    ?>

    <tr class="fn__wps__cart_item_wrap" data-item_id="<?= $item_id; ?>" >
        <td>
          <?php pre_print_r( $product ) ?>
        </td>
        <td>
          <span class="fn__wps__cart_item_price"><?php echo $price; ?> <?php echo $currency; ?></span>
        </td>
        <td>
          <input type="number" min="1" class="item_count fn__wps__cart_item_count" value="<?php echo $count; ?>">
        </td>
        <td>
          <span class="fn__wps__cart_item_sum_price"><?php echo $sum; ?> <?php echo $currency; ?></span>
        </td>
        <td>
          <a href="<?php echo $link; ?>"><?php echo $title; ?></a>
        </td>
        <td>
          <?php echo wp_get_attachment_image( $image, array(150, 150) ); ?>
        </td>
        <td>
          <button class="fn__wps__remove_from_cart" data-item_id="<?= $item_id; ?>" >x</button>
        </td>
    </tr>

    <?php endforeach; ?>

    <tr>
      <td colspan="2">
        <button class="fn__wps__clear_cart">Очистить корзину</button>
      </td>
      <td colspan="2">
        <button class="fn__wps__update_cart">Обновить корзину</button>
      </td>
      <td colspan="2">
        Всего: 
      </td>
      <td>
        <span id="fn__wps__cart_fullprice"><?php echo WPS_Cart::get_total_price(); ?> <?php echo $currency; ?></span>
      </td>
    </tr>

  </table>

  <?php endif; ?>
</div>




<?php get_footer(); ?>