<?php

/** @var ArrayObject $args */

?>
<div class="xcm-embed-replacer" data-provider="<?php echo $args['vendor']->provider; ?>" data-origin="<?php echo esc_html($args['blockContent']); ?>">
    <div class="xcm-embed-replacer__content">
        <div class="xcm-embed-replacer__warning">
            <?php echo sprintf(__("%s on this website is disabled for you.", 'xcm'), $args['vendor']->name); ?><br>
            <?php echo sprintf(__("User consent is required to use third-party services in the category: <b>%s</b>.", 'xcm'), $args['category']->name); ?>
        </div>
        <div class="xcm-embed-replacer__buttons">
            <a href="#consent-overview.cat.<?php echo $args['category']->id; ?>" class="xcm-embed-replacer-button"><?php echo __('Change consent', 'xcm'); ?></a>
        </div>
    </div>
</div>