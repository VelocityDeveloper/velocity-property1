<?php
/**
 * Property archive query + Filter Shortcode (single select + extra meta filters)
 *
 * @package velocity
 */

defined('ABSPATH') || exit;

/**
 * Helper: sanitize single ID from $_GET
 */
function velocity_sanitize_id($val) {
    $id = is_array($val) ? 0 : intval($val);
    return $id > 0 ? $id : 0;
}

/**
 * Pre get posts: terapkan filter & sorting
 */
function velocity_property_custom_query($query) {
    if (is_admin()) return;

    if (is_archive() && is_post_type_archive('property') && $query->is_main_query()) {

        // Keyword (override search)
        $sq = isset($_GET['sq']) ? sanitize_text_field($_GET['sq']) : '';
        if ($sq !== '') {
            $query->set('s', $sq);
        }

        // ===== Sorting =====
        $short = isset($_GET['short']) ? sanitize_text_field($_GET['short']) : '';
        switch ($short) {
            case 'murah':
                $query->set('orderby', 'meta_value_num');
                $query->set('meta_key', 'harga');
                $query->set('order', 'ASC');
                break;
            case 'mahal':
                $query->set('orderby', 'meta_value_num');
                $query->set('meta_key', 'harga');
                $query->set('order', 'DESC');
                break;
            case 'baru':
                $query->set('orderby', 'date');
                $query->set('order', 'DESC');
                break;
            case 'lama':
                $query->set('orderby', 'date');
                $query->set('order', 'ASC');
                break;
            case 'namaa':
                $query->set('orderby', 'title');
                $query->set('order', 'ASC');
                break;
            case 'namaz':
                $query->set('orderby', 'title');
                $query->set('order', 'DESC');
                break;
        }

        // ===== Meta Query =====
        $metaquery = array();

        // harga min
        $hargamin = isset($_GET['hargamin']) && $_GET['hargamin'] !== '' ? (int) $_GET['hargamin'] : 0;
        if ($hargamin > 0) {
            $metaquery[] = array(
                'key'     => 'harga',
                'value'   => $hargamin,
                'compare' => '>=',
                'type'    => 'NUMERIC',
            );
        }

        // harga max
        $hargamax = isset($_GET['hargamax']) && $_GET['hargamax'] !== '' ? (int) $_GET['hargamax'] : 0;
        if ($hargamax > 0) {
            $metaquery[] = array(
                'key'     => 'harga',
                'value'   => $hargamax,
                'compare' => '<=',
                'type'    => 'NUMERIC',
            );
        }

        // status (Dijual / Disewakan) - exact match (string)
        $status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
        if ($status !== '') {
            // Normalisasi ke format penyimpanan (asumsi kapital huruf pertama)
            $status_allowed = array('dijual' => 'Dijual', 'disewakan' => 'Disewakan');
            $status_key = strtolower($status);
            if (isset($status_allowed[$status_key])) {
                $metaquery[] = array(
                    'key'     => 'status',
                    'value'   => $status_allowed[$status_key],
                    'compare' => '=',
                    'type'    => 'CHAR',
                );
            }
        }

        // kamar tidur (minimal)
        $kamar_tidur = isset($_GET['kamar_tidur']) && $_GET['kamar_tidur'] !== '' ? (int) $_GET['kamar_tidur'] : 0;
        if ($kamar_tidur > 0) {
            $metaquery[] = array(
                'key'     => 'kamar_tidur',
                'value'   => $kamar_tidur,
                'compare' => '>=',
                'type'    => 'NUMERIC',
            );
        }

        // kamar mandi (minimal)
        $kamar_mandi = isset($_GET['kamar_mandi']) && $_GET['kamar_mandi'] !== '' ? (int) $_GET['kamar_mandi'] : 0;
        if ($kamar_mandi > 0) {
            $metaquery[] = array(
                'key'     => 'kamar_mandi',
                'value'   => $kamar_mandi,
                'compare' => '>=',
                'type'    => 'NUMERIC',
            );
        }

        if (count($metaquery) > 1) {
            $metaquery['relation'] = 'AND';
        }
        if (!empty($metaquery)) {
            $query->set('meta_query', $metaquery);
        }

        // ===== Taxonomy Query (single select) =====
        $taxquery = array();

        // kategori (single)
        $kategori = isset($_GET['setkategori']) ? velocity_sanitize_id($_GET['setkategori']) : 0;
        if ($kategori > 0) {
            $taxquery[] = array(
                'taxonomy' => 'property-category',
                'field'    => 'term_id',
                'terms'    => array($kategori),
            );
        }

        // lokasi (single)
        $location = isset($_GET['setlocation']) ? velocity_sanitize_id($_GET['setlocation']) : 0;
        if ($location > 0) {
            $taxquery[] = array(
                'taxonomy' => 'property-location',
                'field'    => 'term_id',
                'terms'    => array($location),
            );
        }

        if (count($taxquery) > 1) {
            $taxquery['relation'] = 'AND';
        }
        if (!empty($taxquery)) {
            $query->set('tax_query', $taxquery);
        }
    }
}
add_filter('pre_get_posts', 'velocity_property_custom_query');


/**
 * Shortcode: [velocity-filter]
 */
function velocity_filter_shortcode() {
    ob_start();

    $uniqid       = uniqid();
    $s            = isset($_GET['sq']) ? sanitize_text_field($_GET['sq']) : '';
    $listshort    = isset($_GET['short']) ? sanitize_text_field($_GET['short']) : '';
    $hargamin     = isset($_GET['hargamin']) ? esc_attr($_GET['hargamin']) : '';
    $hargamax     = isset($_GET['hargamax']) ? esc_attr($_GET['hargamax']) : '';

    $selKat       = isset($_GET['setkategori']) ? velocity_sanitize_id($_GET['setkategori']) : 0;
    $selLoc       = isset($_GET['setlocation']) ? velocity_sanitize_id($_GET['setlocation']) : 0;

    // status (select)
    $status       = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
    // kamar tidur / kamar mandi (number)
    $form_k_tidur = isset($_GET['kamar_tidur']) ? esc_attr($_GET['kamar_tidur']) : '';
    $form_k_mandi = isset($_GET['kamar_mandi']) ? esc_attr($_GET['kamar_mandi']) : '';

    // optional: jika berada di archive term kategori/lokasi, jadikan default terpilih
    $qo = get_queried_object();
    if ($selKat === 0 && $qo && isset($qo->taxonomy) && $qo->taxonomy === 'property-category') {
        $selKat = (int) $qo->term_id;
    }
    if ($selLoc === 0 && $qo && isset($qo->taxonomy) && $qo->taxonomy === 'property-location') {
        $selLoc = (int) $qo->term_id;
    }
    ?>

    <form class="p-3 card" action="<?php echo esc_url(get_post_type_archive_link('property')); ?>" method="get">
        <div class="mb-3">
            <label for="cari-<?php echo esc_attr($uniqid); ?>" class="form-label mb-1">Masukkan Kata Kunci</label>
            <input type="text" id="cari-<?php echo esc_attr($uniqid); ?>" class="form-control form-control-sm" name="sq" value="<?php echo esc_attr($s); ?>"/>
        </div>

        <div class="form-group mb-2">
            <label for="hargamin-<?php echo esc_attr($uniqid); ?>" class="form-label mb-1">Harga Minimal</label>
            <input type="number" id="hargamin-<?php echo esc_attr($uniqid); ?>" min="0" name="hargamin" value="<?php echo esc_attr($hargamin); ?>" class="form-control form-control-sm">
        </div>

        <div class="form-group mb-3">
            <label for="hargamax-<?php echo esc_attr($uniqid); ?>" class="form-label mb-1">Harga Maksimal</label>
            <input type="number" id="hargamax-<?php echo esc_attr($uniqid); ?>" min="0" name="hargamax" value="<?php echo esc_attr($hargamax); ?>" class="form-control form-control-sm">
        </div>

        <!-- Status (single select) -->
        <div class="form-group mb-3">
            <label for="status-<?php echo esc_attr($uniqid); ?>" class="form-label mb-1">Status</label>
            <select class="form-select form-select-sm" name="status" id="status-<?php echo esc_attr($uniqid); ?>">
                <option value="">-- Semua Status --</option>
                <?php
                $status_options = array('Dijual', 'Disewakan');
                foreach ($status_options as $opt) {
                    $selected = selected(strtolower($status), strtolower($opt), false);
                    echo '<option value="' . esc_attr($opt) . '" ' . $selected . '>' . esc_html($opt) . '</option>';
                }
                ?>
            </select>
        </div>

        <!-- Kamar Tidur (minimal) -->
        <div class="form-group mb-3">
            <label for="kamar_tidur-<?php echo esc_attr($uniqid); ?>" class="form-label mb-1">Kamar Tidur (minimal)</label>
            <input type="number" id="kamar_tidur-<?php echo esc_attr($uniqid); ?>" min="0" name="kamar_tidur" value="<?php echo esc_attr($form_k_tidur); ?>" class="form-control form-control-sm" placeholder="cth: 2">
        </div>

        <!-- Kamar Mandi (minimal) -->
        <div class="form-group mb-3">
            <label for="kamar_mandi-<?php echo esc_attr($uniqid); ?>" class="form-label mb-1">Kamar Mandi (minimal)</label>
            <input type="number" id="kamar_mandi-<?php echo esc_attr($uniqid); ?>" min="0" name="kamar_mandi" value="<?php echo esc_attr($form_k_mandi); ?>" class="form-control form-control-sm" placeholder="cth: 1">
        </div>

        <!-- Shorting (single select) -->
        <div class="form-group mb-3">
            <label for="short-<?php echo esc_attr($uniqid); ?>" class="form-label mb-1">Urutkan Berdasarkan</label>
            <select class="form-select form-select-sm" name="short" id="short-<?php echo esc_attr($uniqid); ?>">
                <option value="">-- Pilih --</option>
                <?php
                $shorting = array(
                    'Terbaru'   => 'baru',
                    'Terlama'   => 'lama',
                    'Termurah'  => 'murah',
                    'Termahal'  => 'mahal',
                    'Nama A-Z'  => 'namaa',
                    'Nama Z-A'  => 'namaz'
                );
                foreach ($shorting as $label => $val) {
                    echo '<option value="' . esc_attr($val) . '" ' . selected($listshort, $val, false) . '>' . esc_html($label) . '</option>';
                }
                ?>
            </select>
        </div>

        <!-- Kategori (single select) -->
        <div class="form-group mb-3">
            <label for="setkategori-<?php echo esc_attr($uniqid); ?>" class="form-label mb-1">Berdasarkan Kategori</label>
            <?php
            $cats = get_categories(array(
                'taxonomy'   => 'property-category',
                'hide_empty' => 0,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ));
            ?>
            <select class="form-select form-select-sm" name="setkategori" id="setkategori-<?php echo esc_attr($uniqid); ?>">
                <option value="0">-- Semua Kategori --</option>
                <?php
                if (!empty($cats) && !is_wp_error($cats)) {
                    foreach ($cats as $kat) {
                        $selected = selected($selKat, (int)$kat->term_id, false);
                        echo '<option value="' . (int)$kat->term_id . '" ' . $selected . '>' . esc_html($kat->name) . '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <!-- Lokasi (single select, parent & child jadi satu daftar) -->
        <div class="form-group mb-3">
            <label for="setlocation-<?php echo esc_attr($uniqid); ?>" class="form-label">Berdasarkan Lokasi</label>
            <?php
            $all_locs = get_terms(array(
                'taxonomy'   => 'property-location',
                'hide_empty' => false,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ));

            // Susun parent > child (indent child dengan prefix)
            $ordered = array();
            if (!empty($all_locs) && !is_wp_error($all_locs)) {
                $by_parent = array();
                foreach ($all_locs as $t) {
                    $by_parent[$t->parent][] = $t;
                }
                $build = function($parent_id, $prefix = '') use (&$build, &$ordered, $by_parent) {
                    if (empty($by_parent[$parent_id])) return;
                    foreach ($by_parent[$parent_id] as $term) {
                        $ordered[] = array('id' => (int)$term->term_id, 'name' => $prefix . $term->name);
                        $build($term->term_id, $prefix . '— ');
                    }
                };
                $build(0, '');
            }
            ?>
            <select class="form-select form-select-sm" name="setlocation" id="setlocation-<?php echo esc_attr($uniqid); ?>">
                <option value="0">-- Semua Lokasi --</option>
                <?php
                if (!empty($ordered)) {
                    foreach ($ordered as $item) {
                        $selected = selected($selLoc, $item['id'], false);
                        echo '<option value="' . $item['id'] . '" ' . $selected . '>' . esc_html($item['name']) . '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <input type="hidden" name="post_type" value="property">

        <div class="text-end">
            <button type="submit" class="btn btn-outline-dark btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
                    <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
                </svg>
                Filter
            </button>
        </div>
    </form>

    <?php
    return ob_get_clean();
}
add_shortcode('velocity-filter', 'velocity_filter_shortcode');
