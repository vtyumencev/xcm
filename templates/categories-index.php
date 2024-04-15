<?php

/** @var ArrayObject $args */
$categories = $args['categories'];

if (isset($_COOKIE[XCM_NAME])) {
    $json = json_decode(stripslashes($_COOKIE[XCM_NAME]), true);
    $contestList = $json['contest'] ?? array();
}

?>

<div class="xcc-manager">
    <div class="xcc-manager__desc">
        <h2 class="xcc-manager__headline"><?php echo $args['title']; ?></h2>
        <?php echo $args['description']; ?>
    </div>
    <form action="" class="js-xcc-manager-form">
        <div class="xcc-manager-list">
            <?php foreach ($categories as $category): ?>
                <div class="xcc-manager-category js-xcc-manager-category-review" data-id="<?php echo $category->id; ?>">
                    <div class="xcc-manager-category__header xcc-manager-category-header">
                        <div class="xcc-manager-category-header__about">
                            <div class="xcc-manager-category-header__headline">
                                <?php echo $category->name; ?>
                            </div>
                            <div class="xcc-manager-category-header__metas">
                                <?php echo esc_html(sprintf( _n( '%d vendor', '%d vendors', $category->vendors, 'xcm' ), $category->vendors ) ); ?>
                            </div>
                        </div>
                        <div class="xcc-manager-category-header__control">
                            <button class="xcc-manager-category-header__toggle js-xcc-manager-category-toggle" type="button"></button>
                            <?php if ($category->necessary): ?>
                                <div class="">Always active</div>
                            <?php else: ?>
                                <label class="xcc-manager-category-switch">
                                    <input
                                            type="checkbox"
                                            name="category[<?php echo $category->id; ?>]"
                                            <?php echo (isset($contestList[$category->id]) && $contestList[$category->id] === true ? 'checked' : ''); ?>
                                    />
                                    <span class="xcc-manager-category-switch__wrapper">
                                        <span class="xcc-manager-category-switch__dot"></span>
                                    </span>
                                </label>
                            <?php endif; ?>
                            <div class="xcc-manager-category-header__arrow"></div>
                        </div>
                    </div>
                    <div class="xcc-manager-category__body">
                        <?php echo $category->description; ?>
                            <?php if (count($category->vendors) > 0): ?>
                                <div class="xcc-manager-category__vendors">
                                    <?php foreach ($category->vendors as $vendor): ?>
                                        <div class="xcc-manager-vendor">
                                            <div class="xcc-manager-vendor__header">
                                                <div class="xcc-manager-vendor__heading">
                                                    <?php echo $vendor->name; ?>
                                                </div>
                                                <?php if ($vendor->cookies_count): ?>
                                                    <div class="">
                                                        <?php echo esc_html(sprintf( _n( '%d cookie', '%d cookies', $vendor->cookies_count, 'xcm' ), $vendor->cookies_count ) ); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <button
                                                    class="xcc-manager-vendor__button js-xcc-vendor-review"
                                                    type="button"
                                                    data-vendor-id="<?php echo $vendor->id; ?>"></button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="xcc-manager-footer">
            <div class="xcc-manager-footer__bg"></div>
            <div class="xcc-manager-footer__buttons">
                <button class="xcc-manager-footer__button" data-action="accept-selected"><?php echo __('Einstellungen anwenden'); ?></button>
                <button class="xcc-manager-footer__button xcc-manager-footer__button--primary" data-action="accept-all"><?php echo __('Allen zustimmen'); ?></button>
            </div>
        </div>
    </form>
</div>
