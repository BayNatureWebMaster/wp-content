<div class="row" id="act" style="padding:0px;">
    <div class="contain">
        <div class="row" style="min-height:40px;"><h2>Act</h2></div>
        <div class="row">
            <?php
            while ( $sea_query->have_posts() ) :
                $sea_query->the_post();
                echo "<p class='about-desc'>";
                    echo the_field( 'act');
                echo "</p>";
                echo "<div class='force-clear'></div>";
                echo "<div class='three'>";
                    echo "<h6>";
                        echo the_field( 'act_1_title');
                    echo "</h6>";?>
                    <img src="<?php the_field('act_1_image'); ?>" width="100%"/>
                    <?php echo "<p>";
                        echo the_field( 'act_1_text');
                    echo "</p>";
                echo "</div>";

                echo "<div class='three'>";
                    echo "<h6>";
                        echo the_field( 'act_2_title');
                    echo "</h6>";?>
                    <img src="<?php the_field('act_2_image'); ?>"  width="100%"/>
                    <?php echo "<p>";
                        echo the_field( 'act_2_text');
                    echo "</p>";
                echo "</div>";

                echo "<div class='three'>";
                    echo "<h6>";
                        echo the_field( 'act_3_title');
                    echo "</h6>";?>
                    <img src="<?php the_field('act_3_image'); ?>"  width="100%"/>
                    <?php echo "<p>";
                        echo the_field( 'act_3_text');
                    echo "</p>";
                echo "</div>";

                echo "<div class='three'>";
                    echo "<h6>";
                        echo the_field( 'act_4_title');
                    echo "</h6>";?>
                        <img src="<?php the_field('act_4_image'); ?>"  width="100%"/>
                    <?php echo "<p>";
                        echo the_field( 'act_4_text');
                    echo "</p>";
                echo "</div>";
            echo "</div>"; // end row

            //second row of act grid
            echo "<div class='row'>";

                echo "<div class='three'>";
                    echo "<h6>";
                        echo the_field( 'act_5_title');
                    echo "</h6>";?>
                    <img src="<?php the_field('act_5_image'); ?>"  width="100%"/>
                    <?php echo "<p>";
                        echo the_field( 'act_5_text');
                    echo "</p>";
                echo "</div>";

                echo "<div class='three'>";
                    echo "<h6>";
                        echo the_field( 'act_6_title');
                    echo "</h6>";?>
                    <img src="<?php the_field('act_6_image'); ?>"  width="100%"/>
                    <?php echo "<p>";
                        echo the_field( 'act_6_text');
                    echo "</p>";
                echo "</div>";

                echo "<div class='three'>";
                    echo "<h6>";
                        echo the_field( 'act_7_title');
                    echo "</h6>";?>
                    <img src="<?php the_field('act_7_image'); ?>"  width="100%"/>
                    <?php echo "<p>";
                        echo the_field( 'act_7_text');
                    echo "</p>";
                echo "</div>";

                echo "<div class='three'>";
                    echo "<h6>";
                        echo the_field( 'act_8_title');
                    echo "</h6>";?>
                    <img src="<?php the_field('act_8_image'); ?>"  width="100%"/>
                    <?php echo "<p>";
                        echo the_field( 'act_8_text');
                    echo "</p>";
                echo "</div>";
            endwhile;
        ?>
        </div>
    </div>
</div>
