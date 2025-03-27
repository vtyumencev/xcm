<?php

/** @var ArrayObject $args */
$vendor = $args['vendor'];
$periods = $args['periods'];

?>

<div class="xcc-manager xcc-vendor">
    <div class="xcc-manager__desc">
        <h3 class="xcc-manager__headline"><?php echo $vendor->name; ?></h3>
        <?php echo $vendor->description; ?>
        <?php if ($vendor->link): ?>
        <div class="xcc-vendor-links">
            <a class="xcc-vendor-links__link" href="<?php echo $vendor->link; ?>" target="_blank">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path d="M10.0002 5H8.2002C7.08009 5 6.51962 5 6.0918 5.21799C5.71547 5.40973 5.40973 5.71547 5.21799 6.0918C5 6.51962 5 7.08009 5 8.2002V15.8002C5 16.9203 5 17.4801 5.21799 17.9079C5.40973 18.2842 5.71547 18.5905 6.0918 18.7822C6.5192 19 7.07899 19 8.19691 19H15.8031C16.921 19 17.48 19 17.9074 18.7822C18.2837 18.5905 18.5905 18.2839 18.7822 17.9076C19 17.4802 19 16.921 19 15.8031V14M20 9V4M20 4H15M20 4L13 11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                </svg>
                <span class="xcc-vendor-links__link-label">
                    <?php echo __("Vendor's privacy page", 'xcm'); ?>
                </span>
            </a>
        </div>
        <?php endif; ?>
        <?php if ($vendor->cookies): ?>
            <div class="xcc-manager-cookies">
                <div class="xcc-manager-cookies__header">
                    <div class="xcc-manager-cookies"><?php echo __("Cookie name", 'xcm'); ?></div>
                    <div class="xcc-manager-cookies"><?php echo __("Duration", 'xcm'); ?></div>
                </div>
                <div class="xcc-manager-cookies__list">
                    <?php foreach ($vendor->cookies as $cookie): ?>
                        <div class="xcc-manager-cookies__item xcc-manager-cookie">
                            <div class="xcc-manager-cookie__heading">
                                <?php echo $cookie->name; ?>
                            </div>
                            <div class="xcc-manager-cookie__duration">
                                <?php echo sprintf($periods[$cookie->duration->period], $cookie->duration->value); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
