<?php

/** @var ArrayObject $args */

?>

<div class="xcm-embed-replacer" data-provider="<?php echo $args['vendor']->provider; ?>" data-origin="<?php echo esc_html($args['blockContent']); ?>">
    <div class="xcm-embed-replacer__content">
        <div class="xcm-embed-replacer__warning">
            <?php echo $args['vendor']->name; ?> on this website is disabled for you.<br>
            User consent is required to use third-party services in the category: <b><?php echo $args['category']->name; ?></b>.
        </div>
        <div class="xcm-embed-replacer__buttons">
            <a href="#contest-overview.cat.<?php echo $args['category']->id; ?>" class="xcm-embed-replacer-button">Change contest</a>
        </div>
    </div>
</div>