<?php
$wpsdDonations = $this->wpsd_get_all_donations();
?>

<div id="wpsd-wrap-all" class="wrap">
    <h2><?php esc_html_e('List of all donations', WPSD_TXT_DOMAIN); ?></h2><br>
    <table class="wp-list-table widefat fixed striped posts" cellspacing="0" id="wpc_data_table">
        <thead>
            <tr>
                <?php print_column_headers('wpsd-column-table'); ?>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <?php print_column_headers('wpsd-column-table', false); ?>
            </tr>
        </tfoot>
        <tbody id="the-list">
            <?php
            if (count($wpsdDonations) > 0) :
                foreach ($wpsdDonations as $donation) : ?>
            <tr>
                <td class="wpsd-donated-amount"><?php printf('%s', $donation->wpsd_donated_amount); ?></td>
                <td>USD</td>
                <td><?php printf('%s', $donation->wpsd_donation_for); ?></td>
                <td><?php printf('%s', $donation->wpsd_donator_name); ?></td>
                <td><?php printf('%s', $donation->wpsd_donator_email); ?></td>
                <td><?php printf('%s', $donation->wpsd_donator_phone); ?></td>
                <td><?php printf('%s', date('D d M Y - h:i A', strtotime($donation->wpsd_donation_datetime))); ?>
                </td>
            </tr>
            <?php endforeach;
            endif;
            ?>
        </tbody>
    </table>
</div>