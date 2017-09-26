<?php
  /*
  Template Name: Пример WPS
  */
  get_header();
?>



<style type="text/css">
.tftable {font-size:14px;color:#333333;width:100%;border-width: 1px;border-color: #729ea5;border-collapse: collapse;}
.tftable th {font-size:14px;background-color:#acc8cc;border-width: 1px;padding: 8px;border-style: solid;border-color: #729ea5;text-align:left;}
.tftable tr {background-color:#d4e3e5;}
.tftable td {font-size:14px;border-width: 1px;padding: 8px;border-style: solid;border-color: #729ea5;}
.tftable tr:hover {background-color:#ffffff;}
</style>

<table class="tftable" border="1">
<tr><th>Значение поля</th><th>Отображение</th></tr>


<tr>
<td>Форма</td>
<td>
  <form class="wps_form_js" autocomplete="off" >
  <input type="text" name="ФИО" required autocomplete="off">
  <input type="checkbox" name="check" >
  <input type="tel"  name="Поле3" autocomplete="off">

  <br>
  <br>
  <input type="tel" name="Телефон">
  <br>
  <br>
  <!-- hidden input -->
  <input type="hidden" name="form_subject"  value="Тема1">
  <input type="hidden" name="form_title"    value="Заголовок">
  <!-- hidden input -->
  <input type="submit" value="Отправить">
</form>
</td>
</tr>


<tr>
<td>Форма с файлом</td>
<td>
  <form class="wps_form_js" >

    <input type="text" name="ФИО" required autocomplete="off">
    <br><br>
    <input type="tel"  name="Телефон" autocomplete="off">
    <br><br>
    <!-- обязательно name="file" -->
    <input type="file" name="file">
    <input type="file" name="file2">
    <br><br>

    <!-- hidden input -->
    <input type="hidden" name="form_subject"  value="с файлом">
    <input type="hidden" name="form_title"    value="Заголовок">
    <!-- hidden input -->

    <input type="submit" value="Отправить">

  </form>
</td>
</tr>


<tr>
<td>Форма по шаблону + редирект</td>
<td>
  <form class="wps_form_js" >

    <input type="text" name="name" placeholder="name" required autocomplete="off">
    <br><br>
    <input type="tel"  name="pass" placeholder="pass" autocomplete="off">
    <br><br>

    <!-- hidden input -->
    <input type="hidden" name="form_subject"  value="Регистрация">
    <input type="hidden" name="form_title"    value="Данные регистрации">
    <input type="hidden" name="form_redirect" value="success">
    <input type="hidden" name="form_template" value="register">
    <!-- hidden input -->

    <input type="submit" value="Отправить">

  </form>
</td>
</tr>


<tr>
<td>UI Repeater</td>
<td>
  <?php 
    $repeater = UI_Repeater::wps__get_repeater( 'repeater' );
    pre_print_r( $repeater );
  ?>
</td>
</tr>

<tr>
<td>UI Textarea</td>
<td>
  <?php pre_print_r( get_post_meta( $post->ID, 'textarea', true ) ); ?>
</td>
</tr>



</table>
<?php get_footer(); ?>