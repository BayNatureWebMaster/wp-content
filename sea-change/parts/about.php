<div class="row" id="about" style="padding:0px;">
    <div class="contain">
        <div class="row" style="min-height:40px;"><h2>About</h2></div>
        <div class="row">
            <?php
            while ( $sea_query->have_posts() ) :
                $sea_query->the_post();
                echo "<article class='about-desc'>";
                    echo the_field( 'about');
                echo "</article>";
                echo "<div class='force-clear'></div>";
                echo "<div class='three'>";
                    echo "<h4>Editor</h4>";
                    echo "<p>";
                        echo the_field( 'about_director');
                    echo "</p>";?>
                    <img src="<?php the_field('about_director_image'); ?>" width="100%"/>
                <?php echo "</div>";

                echo "<div class='three'>";
                    echo "<h4>Writer</h4>";
                    echo "<p>";
                        echo the_field( 'about_writer');
                    echo "</p>";?>
                    <img src="<?php the_field('about_writer_image'); ?>" width="100%"/>
                <?php echo "</div>";

                echo "<div class='three'>";
                    echo "<h4>Filmmaker</h4>";
                    echo "<p>";
                        echo the_field( 'about_filmmaker');
                    echo "</p>";?>
                    <img src="<?php the_field('about_filmmaker_image'); ?>" width="100%"/>
                <?php echo "</div>";

                echo "<div class='three'>";
                    echo "<h4>Web Developer</h4>";
                    echo "<p>";
                        echo the_field( 'about_web_developer');
                    echo "</p>";?>
                    <img src="<?php the_field('about_web_developer_image'); ?>" />
                <?php echo "</div>";
            endwhile;  ?>
        </div>
    </div>
</div>
