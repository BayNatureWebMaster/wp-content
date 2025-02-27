<?php defined('ABSPATH') or exit;


/**
 * @var MC4WP_Graph $graph
 */
?>

    <form method="get">
        <div class="tablenav top">
            <div class="alignleft actions">
                <input type="hidden" name="page" value="mailchimp-for-wp-reports">

                <select id="mc4wp-graph-range" name="range">
                    <option value="today" <?php selected($graph->range, 'today'); ?>><?php _e('Today', 'mailchimp-for-wp'); ?></option>
                    <option value="yesterday" <?php selected($graph->range, 'yesterday'); ?>><?php _e('Yesterday', 'mailchimp-for-wp'); ?></option>
                    <option value="this_week" <?php selected($graph->range, 'this_week'); ?>><?php _e('This Week', 'mailchimp-for-wp'); ?></option>
                    <option value="last_week" <?php selected($graph->range, 'last_week'); ?>><?php _e('Last Week', 'mailchimp-for-wp'); ?></option>
                    <option value="this_month" <?php selected($graph->range, 'this_month'); ?>><?php _e('This Month', 'mailchimp-for-wp'); ?></option>
                    <option value="last_month" <?php selected($graph->range, 'last_month'); ?>><?php _e('Last Month', 'mailchimp-for-wp'); ?></option>
                    <option value="this_quarter" <?php selected($graph->range, 'this_quarter'); ?>><?php _e('This Quarter', 'mailchimp-for-wp'); ?></option>
                    <option value="last_quarter" <?php selected($graph->range, 'last_quarter'); ?>><?php _e('Last Quarter', 'mailchimp-for-wp'); ?></option>
                    <option value="this_year" <?php selected($graph->range, 'this_year'); ?>><?php _e('This Year', 'mailchimp-for-wp'); ?></option>
                    <option value="last_year" <?php selected($graph->range, 'last_year'); ?>><?php _e('Last Year', 'mailchimp-for-wp'); ?></option>
                    <option value="custom" <?php selected($graph->range, 'custom'); ?>><?php _e('Custom', 'mailchimp-for-wp'); ?></option>
                </select>

                <div id="mc4wp-graph-custom-range-options" <?php if ($graph->range !== 'custom') {
                    ?>style="display:none;"<?php
                                                           } ?>>
                    <span><?php _e('From', 'mailchimp-for-wp'); ?> </span>

                    <label for="start_day" class="screen-reader-text"><?php _e('Start day', 'mailchimp-for-wp'); ?></label>
                    <select name="start_day" id="start_day">
                        <option disabled><?php _e('Day'); ?></option>
                        <?php for ($day = 1; $day <= 31; $day++) {
                            ?>
                            <option <?php selected($start_day, $day); ?>><?php echo $day++; ?></option>
                            <?php
                        } ?>
                    </select>
                    <label for="start_month" class="screen-reader-text"><?php _e('Start month', 'mailchimp-for-wp'); ?></label>
                    <select name="start_month" id="start_month">
                        <option disabled><?php _e('Month'); ?></option>
                        <?php foreach (range(1, 12) as $month_number) {
                            ?>
                            <option value="<?php echo $month_number; ?>" <?php selected($start_month, $month_number); ?>><?php echo $month_number; ?></option>
                            <?php
                        }?>
                    </select>

                    <label for="start_year" class="screen-reader-text"><?php _e('Start year', 'mailchimp-for-wp'); ?></label>
                    <select name="start_year" id="start_year">
                        <option disabled><?php _e('Year'); ?></option>
                        <?php foreach (range(2013, date('Y')) as $year) {
                            ?>
                            <option value="<?php echo $year; ?>" <?php selected($start_year, $year); ?>><?php echo $year; ?></option>
                            <?php
                        } ?>
                    </select>
                    <span><?php _e('To', 'mailchimp-for-wp'); ?> </span>

                    <label for="end_day" class="screen-reader-text"><?php _e('End day', 'mailchimp-for-wp'); ?></label>
                    <select name="end_day" id="end_day">
                        <option disabled><?php _e('Day'); ?></option>
                        <?php for ($day = 1; $day <= 31; $day++) {
                            ?>
                            <option <?php selected($end_day, $day); ?>><?php echo $day; ?></option>
                            <?php
                        } ?>
                    </select>

                    <label for="end_month" class="screen-reader-text"><?php _e('End month', 'mailchimp-for-wp'); ?></label>
                    <select name="end_month" id="end_month">
                        <option disabled><?php _e('Month'); ?></option>
                        <?php foreach (range(1, 12) as $month_number) {
                            ?>
                            <option value="<?php echo $month_number; ?>" <?php selected($end_month, $month_number); ?>><?php echo $month_number; ?></option>
                            <?php
                        }?>
                    </select>

                    <label for="end_year" class="screen-reader-text"><?php _e('End year', 'mailchimp-for-wp'); ?></label>
                    <select name="end_year" id="end_year">
                        <option disabled><?php _e('Year'); ?></option>
                        <?php foreach (range(2013, date('Y')) as $year) {
                            ?>
                            <option value="<?php echo $year; ?>" <?php selected($end_year, $year); ?>><?php echo $year; ?></option>
                            <?php
                        } ?>
                    </select>
                </div>
                <input type="submit" class="button-secondary" value="<?php esc_attr_e('Filter', 'mailchimp-for-wp'); ?>">
            </div>
        </div>
    </form>

    <?php if (empty($graph->datasets)) { ?>
        <p>No data to show for the selection time period.</p>
    <?php } else { ?>
    <canvas id="mc4wp-graph"></canvas>
    <?php } ?>

