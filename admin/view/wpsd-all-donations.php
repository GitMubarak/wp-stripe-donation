<?php
$wpsdDonations          = $this->wpsd_get_all_donations();
$wpsdGeneralSettings    = stripslashes_deep( unserialize( get_option('wpsd_general_settings') ) );
$wpsdDonateCurrency     = isset( $wpsdGeneralSettings['wpsd_donate_currency'] ) ? $wpsdGeneralSettings['wpsd_donate_currency'] : 'USD';
?>
<div id="wpsd-wrap-all" class="wrap">
    <h2><?php esc_html_e('Donations Information', WPSD_TXT_DOMAIN); ?></h2><br>
    <table class="wp-list-table widefat fixed striped posts" cellspacing="0" id="wpc_data_table">
        <thead>
            <tr>
                <?php print_column_headers('wpsd-column-table'); ?>
            </tr>
        </thead>
        <tbody id="the-list">
            <?php
            if ( count( $wpsdDonations ) > 0 ) {
                foreach ( $wpsdDonations as $donation ) {
                    ?>
                    <tr>
                        <td class="wpsd-donated-amount"><?php printf('%s', $donation->wpsd_donated_amount); ?></td>
                        <td><?php esc_html_e( $wpsdDonateCurrency ); ?></td>
                        <td><?php printf('%s', $donation->wpsd_donation_for); ?></td>
                        <td><?php printf('%s', $donation->wpsd_donator_name); ?></td>
                        <td><?php printf('%s', $donation->wpsd_donator_email); ?></td>
                        <td><?php printf('%s', $donation->wpsd_donator_phone); ?></td>
                        <td><?php printf('%s', date('D d M Y - h:i A', strtotime($donation->wpsd_donation_datetime))); ?>
                        </td>
                    </tr>
                    <?php 
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <?php print_column_headers('wpsd-column-table', false); ?>
            </tr>
        </tfoot>
    </table>
</div>