<?php
/**
 * Plugin Name: WT whatsnew
 * Description: 新着情報。
 * Version: 0.1
 */


/*------------------------ memo --------------------------*/
//アーカイブテンプレート ：archive-faq.php :register_post_typeの第1引数をファイル名のハイフンの後につけること。

define( 'WTWHATS_ROOT', plugins_url( '', __FILE__ ) );
define( 'WTWHATS_IMAGES', WTWHATS_ROOT . '/images/' );
define( 'WTWHATS_STYLES', WTWHATS_ROOT . '/css/' );
define( 'WTWHATS_THEME', get_stylesheet_directory() );
define( 'WTWHATS_THEMEURI', get_stylesheet_directory_uri() );

function wtwhatsnew_style_script() {
	if(file_exists(THEME . '/wt-whatsnew.css.css')) {
		wp_enqueue_style('wt-whatsnew', WTWHATS_THEMEURI . '/wt-whatsnew.css', false, '0.1', 'all');
	} else {
		wp_enqueue_style('wt-whatsnew', WTWHATS_STYLES . 'wt-whatsnew.css', false, '0.1', 'all');
	}
	//wp_enqueue_script('wt-faq', SCRIPTS . 'wt-faq.js', array( 'jquery'), '0.1', true);
}
add_action( 'wp_enqueue_scripts', 'wtwhatsnew_style_script' );

/*------------------------ admin --------------------------*/
//管理画面にメニューを追加
function add_pages(){
	//create new top-level menu
	add_menu_page('新着情報', 'WT新着情報 設定', 'level_8', WTWHATS_ROOT, 'wtwhatsnew_view', 'dashicons-tag',26);
	
	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
}
add_action('admin_menu', 'add_pages');


function register_mysettings() {
	// 設定を登録します(入力値チェック用)。
	// register_setting( $option_group, $option_name, $sanitize_callback )
	//   $option_group      : 設定のグループ名
	//   $option_name       : 設定項目名(DBに保存する名前)
	//   $sanitize_callback : 入力値調整をする際に呼ばれる関数
	//register_setting( 'test_setting', 'test_setting', array( $this, 'sanitize' ) );	
	register_setting( 'wtwhatsnew_option-group', 'wt_text' );
	register_setting( 'wtwhatsnew_option-group', 'wt_radio' );
	register_setting( 'wtwhatsnew_option-group', 'wt_select' );
	register_setting( 'wtwhatsnew_option-group', 'wt_new_checkbox' );
}


//管理画面view
function wtwhatsnew_view(){
    // POSTデータがあれば設定を更新
    if (isset($_POST['wt_text'])) {
        // POSTデータの'"などがエスケープされるのでwp_unslashで戻して保存
        update_option('wt_text', wp_unslash($_POST['wt_text']));
        update_option('wt_radio', $_POST['wt_radio']);
        update_option('wt_select', $_POST['wt_select']);
        // チェックボックスはチェックされないとキーも受け取れないので、ない時は0にする
        $wt_checkbox = isset($_POST['wt_checkbox']) ? 1 : 0;
        update_option('wt_checkbox', $wt_checkbox);
    }
?>
<div class="wrap">
<h2>新着情報 設定</h2>
<?php
    // 更新完了を通知
    if (isset($_POST['wt_text'])) {
        echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
            <p><strong>設定を保存しました。</strong></p></div>';
    }
?>

<form method="post" action="options.php">
	
<?php
     settings_fields( 'wtwhatsnew_option-group' );
     do_settings_sections( 'wtwhatsnew_option-group' );
?>	
	
<table class="form-table">
    <tr style="display:none;">
        <th scope="row"><label for="my_text">testテキスト</label></th>
        <td><input name="wt_text" type="text" id="wt_text" value="<?php echo esc_attr( get_option('wt_text') ); ?>" class="regular-text" /></td>
    </tr>
    <tr>
        <th scope="row"><label for="wt_new_checkbox">公開してから7日間はNew表示</label></th>
        <td><label><input name="wt_new_checkbox" type="checkbox" id="wt_new_checkbox" value="1" <?php checked( 1, get_option( 'wt_new_checkbox' ) ); ?> /> チェック</label></td>
    </tr>
    <tr style="display:none;">
        <th scope="row">testラジオ</th>
        <td><p><label><input name="wt_radio" type="radio" value="0" <?php checked( 0, get_option( 'wt_radio' ) ); ?>	/>ラジオ0</label><br />
                <label><input name="wt_radio" type="radio" value="1" <?php checked( 1, get_option( 'wt_radio' ) ); ?> />ラジオ1</label></p>
        </td>
    </tr>
    <tr style="display:none;">
        <th scope="row"><label for="wt_select">testセレクト</label></th>
        <td>
            <select name="wt_select" id="wt_select">
                <option value="0" <?php selected( 0, get_option( 'wt_select' ) ); ?> >セレクト0</option>
                <option value="1" <?php selected( 1, get_option( 'wt_select' ) ); ?> >セレクト1</option>
            </select>
        </td>
    </tr>
</table>
<?php submit_button(); ?>
</form>
</div>
<?php	
	
// DBの設定値を取得します。
        //$this->options = get_option( 'wt_text' );	
        //$message = isset( $this->options['wt_text'] ) ? $this->options['wt_text'] : '';
		
		//echo get_option( 'wt_text' );	
?>
<div class="wrap">
	<h2>shortcode</h2>
	<h2>what's New</h2>
	<div><p>ショートコード：[whatsnew limit="2" cat_id="7"] </p></div>
	<div><p>limit=表示件数、cat_id=カテゴリID(数字)、style=""=default</p></div>
	<div><p>style= default,　title_list(タイトルのみ),　title_contents(タイトルと本文)</p></div>
	<br>
	<h2>FAQ shortcode</h2>
	<div><p>デフォルトショートコード：[faq]</p></div>
	<div><p>[faq style="list"]</p></div>
	<div><p>[faq style="block"]</p></div>
	<div><p>カテゴリスラッグ：[faq category="faq_company"]</p></div>
	<div><p>FAQ記事ID：[faq ids="97" style="block"]</p></div>
	<div>http://vccw3.dev/archives/faq-category/faq_company</div>
	<div>http://vccw3.dev/archives/faq</div>
</div><!-- end .wrap -->
<?php
}





/*------------------------ wtwhatsnew_shortcode --------------------------*/
add_shortcode( 'whatsnew', 'wtwhatsnew_shortcode' );
function wtwhatsnew_shortcode( $atts ) {
	global $post;

	ob_start();
	extract( shortcode_atts( array ('limit' => '', 'cat_id' => '','style' => 'default'), $atts ) );
	$args = array( 'posts_per_page' => $limit, 'cat' => $cat_id );
	$args_old = array( 'posts_per_page' => $limit, 'cat' => $cat_id, 'order' => 'ASC');//古い記事順
	
	global $post;
	
	//$wtwhatsnew = new WP_Query( $args_old );//古い記事順
	//$wtwhatsnew = new WP_Query( $args );//新しい記事順
	$wtwhatsnew = new WP_Query( $args );
	
	if ( $wtwhatsnew->have_posts() ) : 
		
		$outputhtml = "";
		$outputhtml .= '<div class="whatsnew-cateid">';
		$outputhtml .= '<ul>';
		// ---------------------------------------------------------------------------
		// ■ title_list タイトルのみの場合（デフォルト）
		// ---------------------------------------------------------------------------
		if($style=="title_list") : 
			while ($wtwhatsnew->have_posts()) : $wtwhatsnew->the_post(); 
				$whatsnewid = $post->ID; 
				$cats = get_the_category($post->id);
				$cat = $cats[0];
				$cat_name = $cat->cat_name; // カテゴリー名
				$cat_slug  = $cat->category_nicename; // カテゴリースラッグ
				$category_icon = '<dd class="item topic_cate"><span class="category_icon ' . $cat_slug . '">' . $cat_name . '</span></dd>';// カテゴリーアイコン
				$topic_time = $post->post_date;
				$topic_time = date('Y/m/d/', strtotime($topic_time)); // 日付をフォーマット
				//NEW
				$newicon = '';
				if( get_option( 'wt_new_checkbox' )) {// DBをみる
					$days = 7;// 公開してから7日間はNew表示
					$today = date_i18n('U');
					$entry = get_the_time('U');
					$elapsed = date('U',($today - $entry)) / 86400;
					if( $days > $elapsed ){
						$newicon =  '<span class="newicon">New</span>';
					}
				}
				$outputhtml .= '<!-- カテゴリIDで抽出 -->';
				$outputhtml .= '<li class="whatsnew-item" id="' .$whatsnewid .'" style="list-style-type: none;">';
				$outputhtml .= '<div class="whatsnew-question " id="' .$whatsnewid .'">';
				$outputhtml .= '<div class="whatsnew_text_01 flexwrap">';
				$outputhtml .= '<dd class="item topic_time">';
				$outputhtml .= $topic_time;
				$outputhtml .= '</dd>';
				$outputhtml .= $category_icon;
				$outputhtml .= '<dd class="item topic_title">';
				$outputhtml .= '<a href="' .$post->guid .'" class="css_arrow sample4-6" target="">' .$post->post_title  .'</a>';
				$outputhtml .= $newicon;
				$outputhtml .= '</dd>';
				$outputhtml .= '</div>';
				$outputhtml .= '</div>';
				$outputhtml .= '</li>';
			endwhile;
		// ---------------------------------------------------------------------------
		// ■ title_list タイトルとコンテンツの場合
		// ---------------------------------------------------------------------------
		elseif($style=="title_contents") :
			while ($wtwhatsnew->have_posts()) : $wtwhatsnew->the_post(); 
				$whatsnewid = $post->ID; 
				$cats = get_the_category($post->id);
				$cat = $cats[0];
				$cat_name = $cat->cat_name; // カテゴリー名
				$cat_slug  = $cat->category_nicename; // カテゴリースラッグ
				$category_icon = '<dd class="item topic_cate"><span class="category_icon ' . $cat_slug . '">' . $cat_name . '</span></dd>';// カテゴリーアイコン
				$topic_time = $post->post_date;
				$topic_time = date('Y/m/d/', strtotime($topic_time)); // 日付をフォーマット
				//NEW
				$newicon = '';
				if( get_option( 'wt_new_checkbox' )) {// DBをみる
					$days = 7;// 公開してから7日間はNew表示
					$today = date_i18n('U');
					$entry = get_the_time('U');
					$elapsed = date('U',($today - $entry)) / 86400;
					if( $days > $elapsed ){
						$newicon =  '<span class="newicon">New</span>';
					}
				}
				$outputhtml .= '<!-- カテゴリIDで抽出 -->';
				$outputhtml .= '<li class="whatsnew-item" id="' .$whatsnewid .'" style="list-style-type: none;">';
				$outputhtml .= '<div class="whatsnew-question " id="' .$whatsnewid .'">';
				$outputhtml .= '<div class="whatsnew_text_01 flexwrap">';
				$outputhtml .= '<dd class="item topic_time">';
				$outputhtml .= $topic_time;
				$outputhtml .= '</dd>';
				$outputhtml .= $category_icon;
				$outputhtml .= '<dd class="item topic_title">';
				$outputhtml .= '<a href="' .$post->guid .'" class="css_arrow sample4-6" target="">' .$post->post_title  .'</a>';
				$outputhtml .= $newicon;
				$outputhtml .= '</dd>';
				$outputhtml .= '</div>';
				$outputhtml .= '</div>';
				$outputhtml .= '</li>';
			endwhile;
		endif; 
			$outputhtml .= '</ul>';
			$outputhtml .= '</div>';
			print_r( $outputhtml);
			//print_r( "<br>");
			//print_r( $post);

			wp_reset_query();
			$myvariable = ob_get_clean();
			return $myvariable;
	endif; 
	//echo $outputhtml;

}


/*------------------------ Include additional files --------------------------*/
//include( 'inc/reorder.php' );

/*------------------------ Flush rewrite rules --------------------------*/
function wtwhatsnew_activation_callback() {
	//カスタム投稿タイプ（FAQ）を「追加」する。
    // 注意：ここで「追加」と括弧を付けて書いたのは、まだ FAQ がデータベースに登録されないため。
    // 後ほど、この FAQ の投稿を追加したときに初めて
    // 投稿エントリーの post_type カラム（データベースの列）からのみ FAQ が参照される。
    create_wtwhatsnew_custom_post_type();
    
    // 重要：下記はこの例のプラグイン有効化フックの中で *のみ* 実行される！
    // これをページ読み込みの度に呼び出すことは *絶対に行ってはならない*！！
    flush_rewrite_rules();
}
//パーマリンクを正しく働かせる為、有効化するときリライトルールをフラッシュする
//register_activation_hook( __FILE__, 'wtwhatsnew_activation_callback' );