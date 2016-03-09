<footer class="footer">
    <a href="<?=U('/index/teacher')?>" class="link <?php if(isset($active_menu) && $active_menu == 'index_teacher') echo 'on';?>">
        <i class="nav nav1"></i>
        <p>首页</p>
    </a>
    <a href="reviews.html" class="link <?php if(isset($active_menu) && $active_menu == 'ask') echo 'on';?>">
        <i class="nav nav4"></i>
        <p>学生求教</p>
    </a>
    <a href="<?=U('/member/teacher/index')?>" class="link <?php if(isset($active_menu) && $active_menu == 'teacher_index') echo 'on';?>">
        <i class="nav nav3"></i>
        <p>我的</p>
    </a>
</footer>