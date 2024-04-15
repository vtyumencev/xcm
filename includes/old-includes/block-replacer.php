<?php
$providers = array(
    'youtube' => array(
        'name' => 'YouTube',
        'dashicon' => 'youtube',
    ),
    'twitter' => array(
        'name' => 'Twitter',
        'dashicon' => 'twitter',
    ),
);
/** @var ArrayObject $blockAttrs */
$align = 'align' . ($blockAttrs['align'] ?? '');
$aspectRatio = ($blockAttrs['type'] === 'video' ? 'aspect-ratio-16-9' : 'aspect-ratio-default');
$provider = $providers[$blockAttrs['providerNameSlug']]
?>
<div class="disabled-embed disabled-embed--<?php echo $blockAttrs['providerNameSlug']; ?> <?php echo $align; ?> <?php echo $aspectRatio; ?>">
    <div class="disabled-embed__wrapper">
        <div class="disabled-embed__inner">
            <div class="disabled-embed-content">
                <div class="disabled-embed-content__title">
                    Empfohlener externer Inhalt
                </div>
                <div class="disabled-embed-content__replacement">
                    <div class="disabled-embed-content__replacement-text">
                        <p>An dieser Stelle finden Sie einen externen Inhalt von <?php echo $provider['name']; ?>, der den Artikel ergänzt und von der Redaktion empfohlen wird. Sie können ihn sich mit einem Klick anzeigen lassen und wieder ausblenden.</p>
                    </div>
                    <div class="disabled-embed-content__replacement-picture">
                        <div class="disabled-embed-content__replacement-picture-inner">
                            <div class="disabled-embed-content__replacement-icon">
                                <span class="dashicons dashicons-<?php echo $provider['dashicon']; ?>"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="disabled-embed-switch">
                    <div class="disabled-embed-switch__icon"></div>
                    <div class="disabled-embed-switch__text">
                        Externer Inhalt
                    </div>
                </div>
                <div class="disabled-embed-content__warning">
                    <p>Ich bin damit einverstanden, dass mir externe Inhalte angezeigt werden. Damit können personenbezogene Daten an Drittplattformen übermittelt werden. Mehr dazu in unserer Datenschutzerklärung.</p>
                </div>
            </div>
        </div>
    </div>
</div>