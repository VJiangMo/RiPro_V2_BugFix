<?php 
  $post_id = get_the_ID();
  $author_id = (int)get_post_field( 'post_author', $post_id );
  $question_comment_num = get_question_comment_num($post_id);

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('article-content'); ?>>

  <div class="container">
    <?php if (_cao('is_single_breadcrumb','1')) : ?>
    <div class="article-crumb"><?php ripro_v2_breadcrumb('breadcrumb'); ?></div>
    <?php endif;?>
  
    <div class="entry-wrapper">
      <?php //do_action('ripro_v2_ads', 'ad_single_top'); ?>

      <?php rizhuti_v2_entry_title(array('link' => false, 'tag' => 'h1'));?>
      <div class="entry-content u-text-format u-clearfix">
        <?php 
          the_content();
          ripro_v2_pagination(5);
        ?>

      </div>
      
      <div class="entry-footer">
          <div class="entry-meta">
            <span class="meta-author">
              <div class="d-flex align-items-center"><?php
                  echo get_avatar($author_id);
                  echo get_the_author_meta( 'display_name', $author_id );
                ?>
              </div>
            </span>
            <span class="meta-date">
              <time datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>">
                  <i class="fa fa-clock-o"></i>
                  <?php
                    if ( _cao('is_post_list_date_diff',true) ) {
                      echo sprintf( __( '%s前','rizhuti-v2' ), human_time_diff( get_the_time( 'U', $post_id ), current_time( 'timestamp' ) ) );
                    } else {
                      echo esc_html( get_the_date( null, $post_id ) );
                    }
                    echo esc_html__(' 提问','rizhuti-v2');
                  ?>
               </time>
            </span>
            <span class="meta-views"><i class="fa fa-eye"></i> <?php echo _get_post_views($post_id); ?></span>

            <span class="meta-comment">
              <a href="<?php echo esc_url( get_the_permalink( $post_id ) . '#comments' ); ?>">
                 <i class="fa fa-comments-o"></i>
                <?php printf( _n( '%s', esc_html( get_comments_number( $post_id ) ), 'rizhuti-v2' ) ); ?>
              </a>
            </span>
          </div>
      </div>

    </div>
    

  </div>
</article>
<?php do_action('ripro_v2_ads', 'ad_single_bottum'); ?>

<?php comments_template( '/comments-question.php', true ); ?>

<script type="text/javascript">
  jQuery(function() {
      'use strict';
      //点赞
      $(".go-question-liek").on("click", function() {
          var _this = $(this);
          var cid = _this.data("cid");
          var like_num = parseInt(_this.children("span").text());
          var _icon = _this.children("i").attr("class");
          rizhuti_v2_ajax({
              "action": "go_question_like",
              "cid": cid,
          }, function(before) {
              _this.children("i").attr("class", "fa fa-spinner fa-spin")
          }, function(result) {
              if (result.status == 1) {
                  ripro_v2_toast_msg("success", result.msg)
                  _this.children("span").text(like_num + 1)
              } else {
                  ripro_v2_toast_msg("info", result.msg)
              }
          }, function(complete) {
              _this.children("i").attr("class", _icon)
          });
      });
      
      //提交问题
      $(document).on('click', ".go-inst-question-new", function(e) {
          e.preventDefault();
          var _this = $(this);
          var d = {};
          var t = $('.question-form').serializeArray();
          $.each(t, function() {
              d[this.name] = this.value;
          });
          var _icon = _this.children("i").attr("class");
          rizhuti_v2_ajax({
              "action": "add_question_new",
              "text": d.content,
              "title": d.title,
          }, function(before) {
              _this.children("i").attr("class", "fa fa-spinner fa-spin")
          }, function(result) {
              if (result.status == 1) {
                  ripro_v2_toast_msg("success", result.msg, function() {
                      location.reload();
                  })
              } else {
                  ripro_v2_toast_msg("info", result.msg)
              }
          }, function(complete) {
              _this.children("i").attr("class", _icon)
          });
      });
  });
</script>