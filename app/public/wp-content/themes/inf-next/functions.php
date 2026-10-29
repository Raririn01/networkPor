<?php
if (!defined('ABSPATH')) { exit; }
function inf_next_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));
    register_nav_menus(array('primary' => 'เมนูหลัก'));
}
add_action('after_setup_theme', 'inf_next_setup');
function inf_next_assets() {
    wp_enqueue_style('inf-next', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css'));
    wp_enqueue_style('alien-cs', get_template_directory_uri().'/assets/cs.css', array('inf-next'), filemtime(get_template_directory().'/assets/cs.css'));
    wp_enqueue_script('inf-next', get_template_directory_uri() . '/assets/site.js', array(), filemtime(get_template_directory() . '/assets/site.js'), true);
}
add_action('wp_enqueue_scripts', 'inf_next_assets');
function inf_next_register_courses() {
    register_post_type('inf_course', array(
        'labels' => array('name'=>'หลักสูตร', 'singular_name'=>'หลักสูตร', 'add_new'=>'เพิ่มหลักสูตร', 'add_new_item'=>'เพิ่มหลักสูตรใหม่', 'edit_item'=>'แก้ไขหลักสูตร'),
        'public'=>true, 'show_in_rest'=>true, 'has_archive'=>false,
        'rewrite'=>array('slug'=>'program'), 'menu_icon'=>'dashicons-welcome-learn-more',
        'supports'=>array('title','editor','excerpt','thumbnail','page-attributes','custom-fields')
    ));
}
add_action('init', 'inf_next_register_courses');
function inf_next_catalog() {
    return json_decode(file_get_contents(get_template_directory() . '/data/courses.json'), true);
}
function inf_next_course_data($post_id) {
    $catalog=inf_next_catalog();
    $key=get_post_meta($post_id,'inf_key',true);
    $data=$catalog[$key]??array('code'=>'NEW','english'=>'Program','level'=>'bachelor','degree'=>'หลักสูตร','duration'=>'ดูรายละเอียด','focus'=>'','careers'=>'','source'=>'https://www.informatics.buu.ac.th/?page_id=4');
    foreach(array('code','english','level','degree','duration','focus','careers','source') as $field) {
        $value=get_post_meta($post_id,'_inf_'.$field,true);
        if($value!=='') { $data[$field]=$value; }
    }
    return $data;
}
function inf_next_courses() {
    $posts = get_posts(array('post_type'=>'inf_course','posts_per_page'=>-1,'orderby'=>'menu_order','order'=>'ASC','meta_key'=>'inf_key','meta_value'=>'cs'));
    $courses = array();
    foreach ($posts as $post) {
        $key = get_post_meta($post->ID, 'inf_key', true);
        $data = inf_next_course_data($post->ID);
        $data['id']=$post->ID; $data['title']=get_the_title($post); $data['description']=get_the_excerpt($post); $data['url']=get_permalink($post); $data['key']=$key;
        $courses[]=$data;
    }
    return $courses;
}
function inf_next_level($level) {
    return array('bachelor'=>'ปริญญาตรี','master'=>'ปริญญาโท','doctoral'=>'ปริญญาเอก')[$level] ?? 'หลักสูตร';
}
function inf_next_symbol($index) {
    $shapes = array(
        '<path d="M56 35 20 75l36 40M94 35l36 40-36 40M86 18 64 132"/>',
        '<rect x="50" y="50" width="50" height="50" rx="8"/><path d="M75 15v35M75 100v35M15 75h35M100 75h35M25 25l30 30M95 95l30 30M125 25 95 55M55 95l-30 30"/><circle cx="75" cy="15" r="8"/><circle cx="135" cy="75" r="8"/><circle cx="75" cy="135" r="8"/><circle cx="15" cy="75" r="8"/>',
        '<path d="m75 15 60 35-60 35-60-35 60-35Zm-60 60 60 35 60-35M15 100l60 35 60-35"/>',
        '<circle cx="75" cy="75" r="49"/><ellipse cx="75" cy="75" rx="23" ry="62"/><ellipse cx="75" cy="75" rx="62" ry="23"/><circle cx="75" cy="75" r="9"/>',
        '<path d="M25 125V80h22v45M64 125V45h22v80M103 125V15h22v110M15 135h120"/>',
        '<circle cx="75" cy="75" r="58"/><circle cx="75" cy="75" r="38"/><circle cx="75" cy="75" r="18"/><path d="M75 5v140M5 75h140"/>'
    );
    return '<svg class="course-symbol" aria-hidden="true" viewBox="0 0 150 150" fill="none" stroke="currentColor" stroke-width="1.7">'.$shapes[$index % 6].'</svg>';
}
function inf_next_customize($wp_customize) {
    $wp_customize->add_section('inf_next_home',array('title'=>'Alien Buu: หน้าแรก'));
    foreach (array('hero_title'=>array('หัวเรื่องบรรทัดแรก','วิทยาการ'),'hero_highlight'=>array('หัวเรื่องสีเขียว','คอมพิวเตอร์'),'hero_description'=>array('คำอธิบายหน้าแรก','จากความคิดเชิงตรรกะ สู่ซอฟต์แวร์และระบบอัจฉริยะ รู้จักหลักสูตร CS แบบเจาะลึก ทั้งเรื่องที่เรียน แผนตลอด 4 ปี และเส้นทางอาชีพหลังเรียนจบ')) as $key=>$setting) {
        $wp_customize->add_setting($key,array('default'=>$setting[1],'sanitize_callback'=>'sanitize_text_field'));
        $wp_customize->add_control($key,array('label'=>$setting[0],'section'=>'inf_next_home','type'=>'text'));
    }
}
add_action('customize_register','inf_next_customize');
function inf_next_add_course_box() {
    add_meta_box('inf-course-details','ข้อมูลสำหรับการ์ดและตารางเปรียบเทียบ','inf_next_course_box','inf_course','normal','high');
}
add_action('add_meta_boxes','inf_next_add_course_box');
function inf_next_course_box($post) {
    $data=inf_next_course_data($post->ID);
    wp_nonce_field('inf_next_save_course','inf_next_nonce');
    echo '<p>แก้ชื่อหลักสูตร คำอธิบายย่อ และเนื้อหาจากตัวแก้ไข WordPress ด้านบน ส่วนข้อมูลต่อไปนี้ใช้ในการ์ด ตารางเปรียบเทียบ และแถบข้อมูลหลักสูตร</p>';
    echo '<p><label for="inf-level"><strong>ระดับการศึกษา</strong></label><br><select id="inf-level" name="inf_details[level]">';
    foreach(array('bachelor','master','doctoral') as $level) { echo '<option value="'.esc_attr($level).'" '.selected($data['level'],$level,false).'>'.esc_html(inf_next_level($level)).'</option>'; }
    echo '</select></p>';
    foreach(array('code'=>'ชื่อย่อ เช่น CS','english'=>'ชื่อภาษาอังกฤษ','degree'=>'ชื่อปริญญา','duration'=>'ระยะเวลา เช่น 4 ปี','focus'=>'จุดเน้นการเรียน','careers'=>'แนวทางอาชีพ','source'=>'ลิงก์แหล่งข้อมูลทางการ') as $key=>$label) {
        echo '<p><label for="inf-'.esc_attr($key).'"><strong>'.esc_html($label).'</strong></label><br><input class="widefat" id="inf-'.esc_attr($key).'" name="inf_details['.esc_attr($key).']" type="'.($key==='source'?'url':'text').'" value="'.esc_attr($data[$key]).'"></p>';
    }
}
function inf_next_save_course($post_id) {
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
    if(wp_is_post_revision($post_id) || !current_user_can('edit_post',$post_id)) { return; }
    if(!isset($_POST['inf_next_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['inf_next_nonce'])),'inf_next_save_course')) { return; }
    $input=isset($_POST['inf_details']) && is_array($_POST['inf_details']) ? wp_unslash($_POST['inf_details']) : array();
    foreach(array('code','english','level','degree','duration','focus','careers','source') as $key) {
        if(!isset($input[$key]) || !is_string($input[$key])) { continue; }
        if($key==='level' && !in_array($input[$key],array('bachelor','master','doctoral'),true)) { continue; }
        $value=$key==='source'?esc_url_raw($input[$key],array('http','https')):sanitize_text_field($input[$key]);
        update_post_meta($post_id,'_inf_'.$key,$value);
    }
}
add_action('save_post_inf_course','inf_next_save_course');
add_filter('body_class',function($classes) { $classes[]='cs-site'; return $classes; });
