<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Hello_Child_Widget_Heading extends \Elementor\Widget_Heading {

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( '' === $settings['title'] ) {
            return;
        }

        // Your custom HTML here — same $settings keys as the original
        $tag = \Elementor\Utils::validate_html_tag( $settings['header_size'] );
        ?>
        <div class="my-heading-wrapper">
            <<?php echo $tag; ?> class="my-heading">
                <?php echo wp_kses_post( $settings['title'] ); ?>
            </<?php echo $tag; ?>>
        </div>
        <?php
    }
}
