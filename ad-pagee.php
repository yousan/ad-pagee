<?php
/**
 * Plugin Name: ad-pagee
 */



//test2();
test();
//f2014();

function test2() {
    include_once 'includes/class-templatesearcher.php';
    include_once 'includes/functions.php';
}

function test() {
    include_once 'includes/class-templatesearcher.php';
    include_once 'includes/class-loader.php';

    include_once "74efaa97a6418a337854/class-addrewriterules.php";
    include_once "7cabd3ffa10f4e6885fd/class-addpage.php";

    $templates = AP_TemplateSearcher::getTemplates();
    $loader = AP_Loader::getInstance($templates);

    //add_action('template_redirect', array('AP_Loader', 'query_vars'));
    //add_action('template_redirect', array('AP_Loader', 'template_redirect'));


    //add_action('template_redirect', 'my_template_redirect');
    //add_filter('query_vars', 'my_query_vars');


    foreach ($templates as $template) {
        $slug = $template->getTemplateSlug();
//        $mypage = new WP_AddPage(
        ////$slug.'$',       // URLの正規表現
        //'hoge$',
        //'俺達は自由だー！', // 追加するページのタイトル
        //'i_am_free'         // 指定したURLで実行されるコールバック
    //);

    //    $mypage->set_filter(true);
    //    $mypage->init(); // ページを登録
    }


    function my_template_redirect() {
        global $wp_query;
        if (isset($wp_query->query['events'])) {
            $template = get_stylesheet_directory().'/pages/page-hoge.php';
            apply_filters( "page_template", $template );
            if ( $template = apply_filters( 'template_include', $template ) ) {
                include($template);
            }
            exit; // WordPressの処理を止める！
        }
    }


    function my_query_vars($vars) {
        $vars[] = 'events';
        return $vars;
    }
}


/**
 * https://firegoby.jp/archives/5309
 */
function f2014() {
// プラグインの有効化時/無効化時の処理を登録
    register_activation_hook( __FILE__ , 'my_activation_callback' );
    register_deactivation_hook( __FILE__ , 'my_deactivation_callback' );

// 他のプラグインや管理者の操作によってflush_rewrite_rules()が発火した際にこのプラグイン用のrewrite ruleを再登録する
    add_action( 'delete_option', 'my_delete_option', 10, 1 );


    add_action('init', 'my_init');
    add_filter('query_vars', 'my_query_vars');

    function my_query_vars($vars) {
        $vars[] = 'piyo';
        return $vars;
    }

    add_action('template_redirect', 'my_template_redirect');

    function my_template_redirect() {
        global $wp_query;
        if (isset($wp_query->query['piyo'])) {
            // ここでイベントカレンダー的なものを出力
            echo 'いべんとからんだーだおらおらー';
            exit; // WordPressの処理を止める！
        }
    }

    function my_init() {
        add_rewrite_endpoint('piyo', EP_ROOT);
    }

// 有効化時の処理
    function my_activation_callback() {
        /*
         * プラグインが有効化されていることをオプションに保存する
         * この時点でis_plugin_active()の戻り値はfalse
         */
        update_option( 'my_plugin_activated', true );
        flush_rewrite_rules();
    }

// 無効化時の処理
    function my_deactivation_callback() {
        /*
         * プラグインが無効化された！
         * この時点でis_plugin_active()の戻り値はtrue
         */
        delete_option( 'my_plugin_activated' );
        flush_rewrite_rules();
    }

// delete_optionフックのコールバック関数
    function my_delete_option($option){
        /*
         * flush_rewrite_rules()が発火&プラグインが有効化されている場合に限りrewrite ruleを再登録
         * register_activation_hook()発火時にはまだis_plugin_active()の戻り値はtrueのままなのでget_option()の値で評価する必要がある。
         */
        if ( 'rewrite_rules' === $option && get_option('my_plugin_activated') ) {
            add_rewrite_endpoint( 'piyo', EP_ROOT );
        }
    }
}

//f2013();

function f2013()
{

    include_once "74efaa97a6418a337854/class-addrewriterules.php";
    include_once "7cabd3ffa10f4e6885fd/class-addpage.php";

// プラグイン有効化時にリライトルールをクリアする。
    register_activation_hook(__FILE__, 'flush_rewrite_rules');

    /*
     * 任意のURLでコールバック関数を実行させるための処理
     */
    $mypage = new WP_AddPage(
        'i-am-free2$',       // URLの正規表現
        '俺達は自由だー！', // 追加するページのタイトル
        'i_am_free'         // 指定したURLで実行されるコールバック
    );
// trueにするとthe_contentフィルターが適用されるんだぜ
    $mypage->set_filter(true);
    $mypage->init(); // ページを登録
    /*
     * コールバック関数
     */
    function i_am_free()
    {
        return "SQLを叩いちゃうなりAPIを呼ぶなり、ここでもう好きにしてください。";
    }

}