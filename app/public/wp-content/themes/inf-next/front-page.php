<?php
get_header();
$cs_posts=get_posts(array('post_type'=>'inf_course','posts_per_page'=>1,'meta_key'=>'inf_key','meta_value'=>'cs'));
$cs_post=$cs_posts[0]??null;
?>
<main id="main" class="cs-content">
<section class="hero cs-hero"><div class="wrap"><div class="hero-grid"><div class="hero-copy">
<span class="cs-kicker">หลักสูตรปริญญาตรี · มหาวิทยาลัยบูรพา</span>
<h1><?php echo esc_html(get_theme_mod('hero_title','วิทยาการ')); ?><br><em><?php echo esc_html(get_theme_mod('hero_highlight','คอมพิวเตอร์')); ?></em></h1>
<p class="cs-hero-english" lang="en">Computer Science — คิดให้เป็น สร้างให้ได้</p>
<p><?php echo esc_html(get_theme_mod('hero_description','จากความคิดเชิงตรรกะ สู่ซอฟต์แวร์และระบบอัจฉริยะ รู้จักหลักสูตร CS แบบเจาะลึก ทั้งเรื่องที่เรียน แผนตลอด 4 ปี และเส้นทางอาชีพหลังเรียนจบ')); ?></p>
<div class="actions"><a class="btn btn-lime" href="#curriculum">รู้จักหลักสูตร CS <span class="arrow" aria-hidden="true">↗</span></a><a class="btn btn-ghost" href="#study-plan">ดูแผนการเรียน 4 ปี <span aria-hidden="true">→</span></a></div>
<p class="hero-note">คณะวิทยาการสารสนเทศ · หลักสูตรปรับปรุง พ.ศ. 2565</p>
</div><div class="orbit-art" role="img" aria-label="วิทยาการคอมพิวเตอร์เชื่อมความรู้ด้านขั้นตอนวิธี ซอฟต์แวร์ ข้อมูล และระบบอัจฉริยะ">
<span class="art-corner">COMPUTER SCIENCE / BURAPHA</span>
<svg class="orbit-svg" viewBox="0 0 500 470" aria-hidden="true"><circle class="orbit-line" cx="250" cy="235" r="190"/><circle class="orbit-line dash" cx="250" cy="235" r="148"/><ellipse class="orbit-line" cx="250" cy="235" rx="220" ry="99" transform="rotate(-35 250 235)"/><ellipse class="orbit-line" cx="250" cy="235" rx="220" ry="99" transform="rotate(35 250 235)"/><path class="orbit-line dash" d="M250 20v430M20 235h460"/><g fill="#cefa69"><circle cx="250" cy="45" r="4"/><circle cx="440" cy="235" r="3"/><circle cx="250" cy="425" r="4"/><circle cx="60" cy="235" r="3"/></g></svg>
<div class="orbit-core"><strong>CS.</strong><span>THINK · CODE · CREATE</span></div><span class="orbit-label one"><b>Algorithm</b>คิดและแก้ปัญหา</span><span class="orbit-label two"><b>Software</b>ออกแบบและพัฒนา</span><span class="orbit-label three"><b>Data</b>จัดการและวิเคราะห์ข้อมูล</span><span class="orbit-label four"><b>Intelligence</b>สร้างระบบอัจฉริยะ</span><span class="art-caption">ONE PROGRAM. MANY POSSIBILITIES.</span>
</div></div>
<dl class="cs-facts"><div><dt>ชื่อปริญญา</dt><dd>วท.บ. <span>(วิทยาการคอมพิวเตอร์)</span></dd></div><div><dt>ระยะเวลาศึกษา</dt><dd>4 <span>ปี</span></dd></div><div><dt>หน่วยกิตตลอดหลักสูตร</dt><dd>123 <span>หน่วยกิตขึ้นไป</span></dd></div><div><dt>ภาษาที่ใช้สอน</dt><dd>ภาษาไทย</dd></div></dl>
</div></section>
<nav class="cs-subnav" aria-label="หัวข้อหลักสูตร CS"><div class="wrap cs-subnav-inner"><a href="#overview">รู้จัก CS</a><a href="#curriculum">โครงสร้างหลักสูตร</a><a href="#study-plan">แผน 4 ปี</a><a href="#skills">ทักษะที่ได้รับ</a><a href="#careers">อาชีพ</a><a href="#achievements">ข่าวรางวัล</a><a href="#faculty">อาจารย์</a><a href="#admissions">สมัครเรียน / ค่าใช้จ่าย</a><a href="#sources">เอกสารอ้างอิง</a></div></nav>
<?php if($cs_post): echo apply_filters('the_content',$cs_post->post_content); else: include get_template_directory().'/data/cs-guide.html'; endif; ?>
</main>
<?php get_footer(); ?>
