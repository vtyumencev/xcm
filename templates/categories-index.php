<?php

/** @var ArrayObject $args */
$categories = $args['categories'];

if (isset($_COOKIE[XCM_NAME])) {
    $json = json_decode(stripslashes($_COOKIE[XCM_NAME]), true);
    $consentList = $json['consent'] ?? array();
}

?>

<section class="xcm-manager">
    <div class="xcm-manager__desc">
        <h1 class="xcm-manager__headline"><?php echo $args['title']; ?></h1>
        <?php echo $args['description']; ?>
    </div>
    <form action="" class="js-xcm-manager-form">
        <div class="xcm-manager-list">
            <?php foreach ($categories as $category): ?>
                <div class="xcm-manager-category js-xcm-manager-category-review" data-id="<?php echo $category->id; ?>">
                    <div class="xcm-manager-category__header xcm-manager-category-header">
                        <div class="xcm-manager-category-header__about">
                            <div class="xcm-manager-category-header__headline">
                                <?php echo $category->name; ?>
                            </div>
                            <div class="xcm-manager-category-header__metas">
                                <?php echo esc_html(sprintf( _n( '%d vendor', '%d vendors', count($category->vendors), 'xcm' ), count($category->vendors) ) ); ?>
                            </div>
                        </div>
                        <div class="xcm-manager-category-header__control">
                            <button class="xcm-manager-category-header__toggle js-xcm-manager-category-toggle" type="button"></button>
                            <?php if ($category->necessary): ?>
                                <div class=""><?php echo __("Always active", 'xcm'); ?></div>
                            <?php else: ?>
                                <label class="xcm-manager-category-switch">
                                    <input
                                            type="checkbox"
                                            name="category[<?php echo $category->id; ?>]"
                                            <?php echo (isset($consentList[$category->id]) && $consentList[$category->id] === true ? 'checked' : ''); ?>
                                    />
                                    <span class="xcm-manager-category-switch__wrapper">
                                        <span class="xcm-manager-category-switch__dot"></span>
                                    </span>
                                </label>
                            <?php endif; ?>
                            <svg class="xcm-manager-category-header__arrow" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px" viewBox="0 -4.5 20 20" version="1.1">
                                <g stroke="none" stroke-width="1" fill-rule="evenodd">
                                    <g transform="translate(-260.000000, -6684.000000)">
                                        <g transform="translate(56.000000, 160.000000)">
                                            <path d="M223.707692,6534.63378 L223.707692,6534.63378 C224.097436,6534.22888 224.097436,6533.57338 223.707692,6533.16951 L215.444127,6524.60657 C214.66364,6523.79781 213.397472,6523.79781 212.616986,6524.60657 L204.29246,6533.23165 C203.906714,6533.6324 203.901717,6534.27962 204.282467,6534.68555 C204.671211,6535.10081 205.31179,6535.10495 205.70653,6534.69695 L213.323521,6526.80297 C213.714264,6526.39807 214.346848,6526.39807 214.737591,6526.80297 L222.294621,6534.63378 C222.684365,6535.03868 223.317949,6535.03868 223.707692,6534.63378" id="arrow_up-[#337]"></path>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div class="xcm-manager-category__body">
                        <?php echo $category->description; ?>
                            <?php if (count($category->vendors) > 0): ?>
                                <div class="xcm-manager-category__vendors">
                                    <?php foreach ($category->vendors as $vendor): ?>
                                        <div class="xcm-manager-vendor">
                                            <div class="xcm-manager-vendor__header">
                                                <div class="xcm-manager-vendor__heading">
                                                    <?php echo $vendor->name; ?>
                                                </div>
                                            </div>
                                            <button
                                                    class="xcm-manager-vendor__button js-xcm-vendor-review"
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
        <div class="xcm-manager-footer">
            <div class="xcm-manager-footer__bg"></div>
            <div class="xcm-manager-footer__buttons">
                <button class="xcm-manager-footer__button" data-action="accept-selected"><?php echo __('Confirm settings', 'xcm'); ?></button>
                <button class="xcm-manager-footer__button xcm-manager-footer__button--primary" data-action="accept-all"><?php echo __('Accept all', 'xcm'); ?></button>
            </div>
        </div>
    </form>
</section>
