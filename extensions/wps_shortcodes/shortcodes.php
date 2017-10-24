<?php

  // collapse_block
  add_shortcode( "collapse_block", "collapse_block" );
  function collapse_block($atts, $content = ''){
    return '
      <div class="single__descrip__text__drop">
        <span class="single__descrip__text__drop_btn"><i class="icon-down-open"></i></span>
        <div class="single__descrip__hidden">
          '.$content.'
        </div>
      </div>
    ';
  }

?>