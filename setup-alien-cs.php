<?php
// Run with WP-CLI only, from outside the public web root.
if (!defined('WP_CLI') || !WP_CLI) { exit; }
$posts=get_posts(array('post_type'=>'inf_course','post_status'=>'any','posts_per_page'=>-1));
$snapshot=array('posts'=>$posts,'blogname'=>get_option('blogname'),'blogdescription'=>get_option('blogdescription'),'theme_mods'=>get_theme_mods());
$snapshot_path=__DIR__.'/backups/alien-buu-before-cs-20261022/course-content.json';
if (!is_dir(dirname($snapshot_path)) && !wp_mkdir_p(dirname($snapshot_path))) { WP_CLI::error('Cannot create the content backup directory.'); }
if(!file_exists($snapshot_path)) { file_put_contents($snapshot_path,wp_json_encode($snapshot,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); }
$cs_id=0;
foreach($posts as $post) {
    $key=get_post_meta($post->ID,'inf_key',true);
    if($key==='cs') { $cs_id=$post->ID; }
    elseif(in_array($key,array('itdi','se','aai','ds-master','ds-doctoral'),true)) {
        $result=wp_update_post(array('ID'=>$post->ID,'post_status'=>'draft'),true);
        if(is_wp_error($result)) { WP_CLI::error($result->get_error_message()); }
    }
}
$html=file_get_contents(__DIR__.'/app/public/wp-content/themes/inf-next/data/cs-guide.html');
// One editable Classic block per section keeps the guide easy to edit in Visual mode.
$chunks=preg_split('/(?=<section class="section"|<div class="wrap cs-bottom-cta")/',$html);
$content='';
foreach($chunks as $chunk) { if(trim($chunk)!=='') { $content.="<!-- wp:freeform -->\n".trim($chunk)."\n<!-- /wp:freeform -->\n\n"; } }
kses_remove_filters();
$result=wp_insert_post(array('ID'=>$cs_id,'post_type'=>'inf_course','post_status'=>'publish','post_name'=>'cs','post_title'=>'วิทยาการคอมพิวเตอร์','post_excerpt'=>'เจาะลึกหลักสูตร CS คณะวิทยาการสารสนเทศ มหาวิทยาลัยบูรพา พร้อมโครงสร้าง 123 หน่วยกิต แผนเรียน 4 ปี ทักษะ อาชีพ การรับเข้า และประมาณการค่าใช้จ่าย','post_content'=>wp_slash($content)),true);
kses_init_filters();
if(is_wp_error($result)) { WP_CLI::error($result->get_error_message()); }
update_post_meta($result,'inf_key','cs');
update_option('blogname','Alien Buu — วิทยาการคอมพิวเตอร์ CS');
update_option('blogdescription','เจาะลึกหลักสูตร CS มหาวิทยาลัยบูรพา ตั้งแต่เรื่องที่เรียนถึงเส้นทางอาชีพ');
set_theme_mod('hero_title','วิทยาการ');
set_theme_mod('hero_highlight','คอมพิวเตอร์');
set_theme_mod('hero_description','จากความคิดเชิงตรรกะ สู่ซอฟต์แวร์และระบบอัจฉริยะ รู้จักหลักสูตร CS แบบเจาะลึก ทั้งเรื่องที่เรียน แผนตลอด 4 ปี และเส้นทางอาชีพหลังเรียนจบ');
flush_rewrite_rules();
WP_CLI::success('Alien Buu now presents CS only; detailed guide saved, other seeded courses retained as drafts.');
