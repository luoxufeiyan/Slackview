<?php

register_nav_menus(array(
	'main' => __( 'Main Menu','slackview' ),
));


register_sidebar(array(
	'name' => __( 'Sidebar', 'slackview' ),
	'id' => 'sidebar',
	'description' => 'Right Sidebar',
	'class' => 'widget',
	'before_widget' => '<aside class="widget">',
	'after_widget' => '</aside>',
	'before_title' => '<h3>',
	'after_title' => '</h3>',
));

add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'title-tag' );


function dpt_pagenav() {
	global $wp_query, $wp_rewrite;
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

	$pagination = array(
		'base' => @add_query_arg('paged','%#%'),
		'format' => '',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'show_all' => false,
		'type' => 'plain',
		'end_size'=>'1',
		'prev_text' => __('← Prev Page','slackview'),
		'next_text' => __('Next Page →','slackview')
	);

	if ( $wp_rewrite->using_permalinks() )
		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg('s',get_pagenum_link(1) ) ) . 'page/%#%/', 'paged');

	if ( !empty($wp_query->query_vars['s']) )
		$pagination['add_args'] = array('s'=>get_query_var('s'));

	echo paginate_links($pagination);
}

function dpt_send_static() {

	function dpt_send() {
		?><script type="text/javascript">$(document).ready(function(){$.get("http://work.dimpurr.com/theme/theme_tj.php?theme_name=Slackview&blog_url=<?=get_bloginfo('url')?>&t=" + Math.random())})</script><?php
		update_option( 'dpt_send', true );
	};

	if ( get_option('dpt_send') != true ) {
		dpt_send();
	}

}

function mv_browser_body_class($classes) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
	if ( $is_lynx ) $classes[] = 'lynx';
	elseif ( $is_gecko ) $classes[] = 'gecko';
	elseif ( $is_opera ) $classes[] = 'opera';
	elseif ( $is_NS4 ) $classes[] = 'ns4';
	elseif ( $is_safari ) $classes[] = 'safari';
	elseif ( $is_chrome ) $classes[] = 'chrome';
	elseif ( $is_IE ) {
		$classes[] = 'ie';
		if(preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browser_version))
			$classes[] = 'ie'.$browser_version[1];
	} else $classes[] = 'unknown';
	if ( $is_iphone ) $classes[] = 'iphone';
	if ( stristr( $_SERVER['HTTP_USER_AGENT'],"mac") ) {
		$classes[] = 'osx';
	} elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"linux") ) {
		$classes[] = 'linux';
	} elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"windows") ) {
		$classes[] = 'windows';
	}
	return $classes;
}

/*======Copyright info======*/ 
function feed_copyright($content) {
    if(is_single() or is_feed()) { 
    $content.= '<hr>';
	$content.= '<div>原文转自：<a rel="bookmark" title="'.get_the_title().'" href="'.get_permalink().'">'.get_the_title().'</a>|<a href="https://www.luoxufeiyan.com">落絮飞雁的个人网站</a></div>';
	$content.= '<div>授权协议：创作共用 <a href="http://creativecommons.org/licenses/by-nc/2.5/cn/">署名-非商业性使用 2.5 中国大陆</a></div>'; 
	$content.= '<div>除注明外，本站文章均为原创；转载时请保留上述链接。</div>';

    }
    return $content;
}
add_filter ('the_content', 'feed_copyright');
/*======Copyright info End======*/

/* comment_mail_notify v1.0 */
function comment_mail_notify($comment_id) {
  $comment = get_comment($comment_id);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  $spam_confirmed = $comment->comment_approved;
  if (($parent_id != '') && ($spam_confirmed != 'spam')) {
    $wp_email = 'no-reply@luoxufeiyan.com'; 
    $to = trim(get_comment($parent_id)->comment_author_email);
    $subject = '您在 [' . get_option("blogname") . '] 的留言有了回复';
    $message = '
    <div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px;">
      <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
      <p><strong>您曾在《' . get_the_title($comment->comment_post_ID) . '》的留言:</strong><br />'
       . trim(get_comment($parent_id)->comment_content) . '</p>
      <p><strong>' . trim($comment->comment_author) . ' 给您的回复:</strong><br />'
       . trim($comment->comment_content) . '<br /></p>
      <p>您可以点击 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '" target="_blank">查看回复完整內容</a></p>
      <p>欢迎再度光临 <a href="https://www.luoxufeiyan.com" target="_blank">' . get_option('blogname') . '</a></p>
      <p>(此邮件由系统自动发送，请勿回复.)</p>
    </div>';
      $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
      $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
      wp_mail( $to, $subject, $message, $headers );
  }
}
add_action('comment_post', 'comment_mail_notify');
/* comment_mail_notify v1.0 END */

/*use-China-CDN-Gavatar*/
function wizhi_get_avatar($avatar) 
{
    $avatar = str_replace(array("www.gravatar.com","0.gravatar.com","1.gravatar.com","2.gravatar.com"),"cn.gravatar.com",$avatar);
    return $avatar;
}
add_filter( 'get_avatar', 'wizhi_get_avatar', 10, 3 );
/*use-China-Gavatar-END*/

/*小工具支持PHP代码*/
add_filter('widget_text', 'do_shortcode');   
add_filter('widget_text','execute_php',100);   
function execute_php($html){   
     if(strpos($html,"<"."?php")!==false){   
          ob_start();   
          eval("?".">".$html);   
          $html=ob_get_contents();   
          ob_end_clean();   
     }   
     return $html;   
}   
/*小工具支持PHP代码 end*/

/* 清理垃圾评论 */
function custom_spam_delete_interval() {
    return 1;
}
add_filter( 'akismet_delete_comment_interval', 'custom_spam_delete_interval' );
/* 清理垃圾评论 end*/

/* 评论验证码 */

/* 评论验证码 end */

add_filter('body_class','mv_browser_body_class');

?>
