<?php
$wpsdGeneralSettings = stripslashes_deep(unserialize(get_option('wpsd_general_settings')));
if (is_array($wpsdGeneralSettings)) {
	$wpsdDonationEmail = !empty($wpsdGeneralSettings['wpsd_donation_email']) ? $wpsdGeneralSettings['wpsd_donation_email'] : "";
	$wpsdPaymentTitle = !empty($wpsdGeneralSettings['wpsd_payment_title']) ? $wpsdGeneralSettings['wpsd_payment_title'] : "Donation Tile";
	$wpsdDonateButtonText = !empty($wpsdGeneralSettings['wpsd_donate_button_text']) ? $wpsdGeneralSettings['wpsd_donate_button_text'] : "Donate Now";
} else {
	$wpsdDonationEmail = "";
	$wpsdPaymentTitle = "Donate Us";
	$wpsdDonateButtonText = "Donate Now";
}
?>
<div class="wpsd-master-wrapper wpsd-template-0" id="wpsd-wrap-all">
    <div class="wpsd-wrapper-header">
        <h2><?php esc_html_e('WP Stripe Donation', WPSD_TXT_DOMAIN); ?></h2>
    </div>
    <div class="wpsd-wrapper-content">
        <h2><?php echo esc_html__($wpsdPaymentTitle); ?></h2>
        <form action="" method="POST" id="wpsd-donation-form-id">
            <!-- Input section -->
            <label for="wpsd_donation_for"
                class="wpsd-donation-form-label"><?php esc_html_e('Donation For:', WPSD_TXT_DOMAIN); ?></label>
            <input type="text" name="wpsd_donation_for" id="wpsd_donation_for" class="wpsd-text-field"
                placeholder="<?php esc_attr_e('Donation For', WPSD_TXT_DOMAIN); ?>">
            <!-- Input section -->
            <label for="wpsd_donator_name"
                class="wpsd-donation-form-label"><?php esc_html_e('Donator Name:', WPSD_TXT_DOMAIN); ?></label>
            <input type="text" name="wpsd_donator_name" id="wpsd_donator_name" class="wpsd-text-field"
                placeholder="<?php esc_attr_e('Donator Name', WPSD_TXT_DOMAIN); ?>">
            <!-- Input section -->
            <label for="wpsd_donator_email"
                class="wpsd-donation-form-label"><?php esc_html_e('Donator Email:', WPSD_TXT_DOMAIN); ?></label>
            <input type="email" name="wpsd_donator_email" id="wpsd_donator_email" class="wpsd-text-field"
                placeholder="<?php esc_attr_e('Donator Email', WPSD_TXT_DOMAIN); ?>">
            <!-- Input section -->
            <label for="wpsd_donator_phone"
                class="wpsd-donation-form-label"><?php esc_html_e('Donator Phone:', WPSD_TXT_DOMAIN); ?></label>
            <input type="number" name="wpsd_donator_phone" id="wpsd_donator_phone" class="wpsd-text-field"
                placeholder="<?php esc_attr_e('Donator Phone', WPSD_TXT_DOMAIN); ?>">
            <!-- Input section -->
            <label for="wpsd_donate_amount"
                class="wpsd-donation-form-label"><?php esc_html_e('Donate Amount:', WPSD_TXT_DOMAIN); ?></label>
            <ul id="wpsd_donate_amount">
                <li>
                    <div class="form-group">
                        <input type="radio" id="amount_1" name="wpsd_donate_amount"
                            value="<?php esc_attr_e('10', WPSD_TXT_DOMAIN); ?>" checked>
                        <label for="amount_1">$10</label>
                    </div>
                </li>
                <li>
                    <div class="form-group">
                        <input type="radio" id="amount_2" name="wpsd_donate_amount"
                            value="<?php esc_attr_e('20', WPSD_TXT_DOMAIN); ?>">
                        <label for="amount_2">$20</label>
                    </div>
                </li>
                <li>
                    <div class="form-group">
                        <input type="radio" id="amount_3" name="wpsd_donate_amount"
                            value="<?php esc_attr_e('50', WPSD_TXT_DOMAIN); ?>">
                        <label for="amount_3">$50</label>
                    </div>
                </li>
                <li>
                    <div class="form-group">
                        <input type="radio" id="amount_4" name="wpsd_donate_amount"
                            value="<?php esc_attr_e('100', WPSD_TXT_DOMAIN); ?>">
                        <label for="amount_4">$100</label>
                    </div>
                </li>
                <li>
                    <div class="form-group">
                        / Other Amount:
                    </div>
                </li>
                <li>
                    <div class="form-group">
                        <input id="wpsd_donate_other_amount" type="number" class="wpsd_donate_other_amount"
                            name="wpsd_donate_other_amount">
                    </div>
                </li>

            </ul>
            <input type="submit" name="wpsd-donate-button" class="wpsd-donate-button"
                value="<?php echo esc_attr($wpsdDonateButtonText); ?>">
        </form>

        <p class="wpsd-total-donation-today">
            Total&nbsp;<span id="wpsd-total-donation-number">$0</span>&nbsp;Donation Today
        </p>
        <span id="wpsd-donation-message" class="wpsd-alert">&nbsp;</span>
    </div>
</div>