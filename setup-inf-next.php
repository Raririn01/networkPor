<?php
// Run with wp eval-file; this file is intentionally outside the public web root.
if (!defined('WP_CLI') || !WP_CLI) { exit; }
$backup=array();
foreach(array('template','stylesheet','blogname','blogdescription','WPLANG','show_on_front','page_on_front') as $option) { $backup[$option]=get_option($option); }
$backup_file=__DIR__.'/inf-next-original-options.json';
if(!file_exists($backup_file)) { file_put_contents($backup_file,wp_json_encode($backup,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)); }
switch_theme('inf-next');
require_once __DIR__.'/app/public/wp-content/themes/inf-next/functions.php';
inf_next_register_courses();
$catalog=inf_next_catalog(); $order=0;
foreach($catalog as $key=>$course) {
    $existing=get_posts(array('post_type'=>'inf_course','post_status'=>'any','meta_key'=>'inf_key','meta_value'=>$key,'numberposts'=>1));
    if($existing) { continue; }
    $sections=array('รู้จักหลักสูตร'=>$course['description'],'เรียนรู้เรื่องอะไร'=>$course['focus'],'เหมาะกับใคร'=>$course['fit'],'เส้นทางอาชีพที่เกี่ยวข้อง'=>$course['careers']);
    $content='';
    foreach($sections as $heading=>$text) { $content.='<!-- wp:heading --><h2 class="wp-block-heading">'.esc_html($heading).'</h2><!-- /wp:heading --><!-- wp:paragraph --><p>'.esc_html($text).'</p><!-- /wp:paragraph -->'; }
    $content.='<!-- wp:heading --><h2 class="wp-block-heading">ก่อนตัดสินใจสมัคร</h2><!-- /wp:heading --><!-- wp:paragraph --><p>อ่านแผนการศึกษา คุณสมบัติผู้สมัคร และค่าใช้จ่ายให้ตรงกับปีที่เข้าศึกษา จาก <a href="'.esc_url($course['source']).'" target="_blank" rel="noopener noreferrer">หน้าหลักสูตรของคณะวิทยาการสารสนเทศ มหาวิทยาลัยบูรพา</a> ข้อมูลหน้านี้เรียบเรียงเพื่อช่วยสำรวจความสนใจ แนวทางอาชีพเป็นตัวอย่างและไม่ได้รับประกันการจ้างงาน</p><!-- /wp:paragraph -->';
    $id=wp_insert_post(array('post_type'=>'inf_course','post_status'=>'publish','post_title'=>$course['title'],'post_name'=>$key,'post_excerpt'=>$course['description'],'post_content'=>$content,'menu_order'=>$order++),true);
    if(is_wp_error($id)) { WP_CLI::error($id->get_error_message()); }
    update_post_meta($id,'inf_key',$key);
}
update_option('WPLANG','th');
flush_rewrite_rules();
require __DIR__.'/setup-alien-cs.php';
