<?php

namespace VersionAssets;

trait HashifyVersion
{
    /**
     * Register an item.
     *
     * Registers the item if no item of that name already exists.
     *
     * @param string           $handle Name of the item. Should be unique.
     * @param string           $src    Full URL of the item, or path of the item relative to the WordPress root directory.
     * @param array            $deps   Optional. An array of registered item handles this item depends on. Default empty array.
     * @param string|bool|null $ver    Optional. String specifying item version number, if it has one, which is added to the URL
     *                                 as a query string for cache busting purposes. If version is set to false, a version
     *                                 number is automatically added equal to current installed WordPress version.
     *                                 If set to null, no version is added.
     * @param mixed            $args   Optional. Custom property of the item. NOT the class property $args. Examples: $media, $in_footer.
     *
     * @return bool Whether the item has been registered. True on success, false on failure.
     */
    public function add($handle, $src, $deps = [], $ver = false, $args = null)
    {
        /**
         * Filter the asset version.
         *
         * @param string|mixed $version_hash string file hash or version passed to method if not locatable/hashable.
         * @param array        $asset_args   Arguments the asset was registered with.
         */
        $ver = apply_filters('version_assets/asset_version', get_hash($src) ?: $ver, compact('handle', 'src', 'deps', 'ver', 'args'));

        return parent::add($handle, $src, $deps, $ver, $args);
    }
}
