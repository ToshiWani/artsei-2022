<?php get_header();?>

<!-- Main -->
<section id="main" class="wrapper style2">
    <div class="inner">
        <header class="major special">
            <h1>お探しのページが見つかりませんでした</h1>
        </header>
        
        <p>アクセスしようとしたページは、削除されたか、入力したURLが間違っている可能性があるため、表示することができません。</p>
        <p>お手数ですが、URLをご確認の上再度お試し頂くか、下記のいずれかからご希望のページへお進みください。</p>

        <ul class="links">
            <?php
                $menuParameters = array(
                    'container'       => false,
                    'echo'            => false,
                    'items_wrap'      => '%3$s',
                );

                echo strip_tags(wp_nav_menu($menuParameters), '<li><a>' );
            ?>
        </ul>
    </div>
</section>


<?php get_footer(); ?>