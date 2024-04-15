<?php

namespace XenioCookies;

use XenioCookies\Interfaces\StorageInterface;

class EmbedReplacer
{
    public function __construct(
        public StorageInterface $storage
    )
    {
        add_action( 'render_block_core/embed', array( $this, 'embedBlockRender' ), 10, 2);
    }

    public function embedBlockRender($block_content, $block)
    {
        $categories = $this->storage->getCategories();

        foreach ($categories as $category) {
            foreach ($category->vendors as $vendor) {
                if ($vendor->provider === $block['attrs']['providerNameSlug']) {
                    $args['category'] = $category;
                    $args['vendor'] = $vendor;
                }
            }
        }

        if (!isset($args['vendor'])) {
            return $block_content;
        }

        $args['blockContent'] = $block_content;

        ob_start();
        require XCM_DIR . '/templates/embed-replacer.php';
        $page = ob_get_contents();
        ob_end_clean();
        unset($args);
        return $page;
    }
}