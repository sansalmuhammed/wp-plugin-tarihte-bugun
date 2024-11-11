<?php
/*
Plugin Name: Tarihte Bugün
Description: Her gün için tarihi olayları gösteren WordPress eklentisi
Version: 1.2
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/constants.php';

// Aktivasyon hook'u
register_activation_hook(__FILE__, 'tarihte_bugun_activate');

function tarihte_bugun_activate() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'tarihte_bugun';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        gun int NOT NULL,
        ay int NOT NULL,
        yil int NOT NULL,
        olay text NOT NULL,
        kategori varchar(20) NOT NULL DEFAULT 'genel',
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Admin menüsü
add_action('admin_menu', 'tarihte_bugun_menu');

function tarihte_bugun_menu() {
    add_menu_page(
        'Tarihte Bugün',
        'Tarihte Bugün',
        'manage_options',
        'tarihte-bugun',
        'tarihte_bugun_admin_page',
        'dashicons-calendar-alt'
    );
}

// Admin sayfası
function tarihte_bugun_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tarihte_bugun';
    
    if (isset($_POST['submit'])) {
        $gun = intval($_POST['gun']);
        $ay = intval($_POST['ay']);
        $yil = intval($_POST['yil']);
        $olay = sanitize_textarea_field($_POST['olay']);
        $kategori = sanitize_text_field($_POST['kategori']);
        
        $wpdb->insert(
            $table_name,
            array(
                'gun' => $gun,
                'ay' => $ay,
                'yil' => $yil,
                'olay' => $olay,
                'kategori' => $kategori
            ),
            array('%d', '%d', '%d', '%s', '%s')
        );
        
        echo '<div class="updated"><p>Olay başarıyla eklendi.</p></div>';
    }
    
    // Admin form
    ?>
    <div class="wrap">
        <h2>Tarihte Bugün - Yeni Olay Ekle</h2>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th><label for="gun">Gün</label></th>
                    <td>
                        <select name="gun" id="gun" required>
                            <?php for($i = 1; $i <= 31; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="ay">Ay</label></th>
                    <td>
                        <select name="ay" id="ay" required>
                            <?php 
                            $aylar = array(
                                1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
                                5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
                                9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
                            );
                            foreach($aylar as $key => $value): ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="yil">Yıl</label></th>
                    <td>
                        <input type="number" name="yil" id="yil" required>
                    </td>
                </tr>
                <tr>
                    <th><label for="kategori">Kategori</label></th>
                    <td>
                        <select name="kategori" id="kategori" required>
                            <?php foreach(OLAY_KATEGORILERI as $key => $value): ?>
                                <option value="<?php echo $key; ?>">
                                    <?php echo $value['icon'] . ' ' . $value['title']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="olay">Olay</label></th>
                    <td>
                        <textarea name="olay" id="olay" rows="5" cols="50" required></textarea>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="submit" class="button-primary" value="Olay Ekle">
            </p>
        </form>
        
        <h3>Kayıtlı Olaylar</h3>
        <?php
        $olaylar = $wpdb->get_results("SELECT * FROM $table_name ORDER BY ay, gun, yil");
        if ($olaylar): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Tarih</th>
                        <th>Kategori</th>
                        <th>Olay</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($olaylar as $olay): 
                        $kategori = OLAY_KATEGORILERI[$olay->kategori];
                    ?>
                        <tr>
                            <td><?php echo $olay->gun . ' ' . $aylar[$olay->ay] . ' ' . $olay->yil; ?></td>
                            <td><?php echo $kategori['icon'] . ' ' . $kategori['title']; ?></td>
                            <td><?php echo esc_html($olay->olay); ?></td>
                            <td>
                                <a href="?page=tarihte-bugun&action=delete&id=<?php echo $olay->id; ?>" 
                                   onclick="return confirm('Bu olayı silmek istediğinizden emin misiniz?')"
                                   class="button button-small">Sil</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Henüz kayıtlı olay bulunmamaktadır.</p>
        <?php endif; ?>
    </div>
    <?php
}

// Stil ve script dosyalarını ekle
function tarihte_bugun_enqueue_scripts() {
    wp_enqueue_style('tarihte-bugun-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
}
add_action('wp_enqueue_scripts', 'tarihte_bugun_enqueue_scripts');

// Shortcode
add_shortcode('tarihte_bugun', 'tarihte_bugun_shortcode');

function tarihte_bugun_shortcode($atts) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tarihte_bugun';
    
    $bugun = getdate();
    $secili_gun = isset($_GET['gun']) ? intval($_GET['gun']) : $bugun['mday'];
    $secili_ay = isset($_GET['ay']) ? intval($_GET['ay']) : $bugun['mon'];
    
    $output = '<div class="tarihte-bugun">';
    
    // Tarih seçici ve navigasyon
    $output .= '<div class="tarih-navigasyon">';
    $output .= '<a href="?gun=' . date('j', strtotime('-1 day')) . '&ay=' . date('n', strtotime('-1 day')) . '" class="nav-btn">« Dün</a>';
    $output .= '<a href="' . remove_query_arg(array('gun', 'ay')) . '" class="nav-btn' . (!isset($_GET['gun']) ? ' active' : '') . '">Bugün</a>';
    $output .= '<a href="?gun=' . date('j', strtotime('+1 day')) . '&ay=' . date('n', strtotime('+1 day')) . '" class="nav-btn">Yarın »</a>';
    $output .= '</div>';
    
    // Kategori filtreleri
    $output .= '<div class="kategori-filtreler">';
    foreach(OLAY_KATEGORILERI as $key => $value) {
        $output .= sprintf(
            '<span class="kategori-filtre %s" data-kategori="%s">%s %s</span>',
            $value['class'], $key, $value['icon'], $value['title']
        );
    }
    $output .= '</div>';
    
    // Tarih seçici
    $output .= '<div class="tarih-secici">';
    $output .= '<input type="text" id="datepicker" class="tarih-input" placeholder="Tarih Seç">';
    $output .= '</div>';
    
    // Seçili tarihteki olaylar
    $olaylar = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE gun = %d AND ay = %d ORDER BY yil",
        $secili_gun, $secili_ay
    ));
    
    $aylar = array(
        1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
        5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
        9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
    );
    
    $output .= '<h3>Tarihte ' . ($secili_gun == $bugun['mday'] && $secili_ay == $bugun['mon'] ? 'Bugün' : 'Bu Gün') . 
               ' (' . $secili_gun . ' ' . $aylar[$secili_ay] . ')</h3>';
    
    if ($olaylar) {
        $output .= '<ul>';
        foreach ($olaylar as $olay) {
            $kategori = OLAY_KATEGORILERI[$olay->kategori];
            $output .= sprintf(
                '<li class="olay-item %s" data-kategori="%s">
                    <span class="olay-kategori">%s</span>
                    <span class="olay-yil">%d</span>
                    <span class="olay-icerik">%s</span>
                </li>',
                $kategori['class'],
                $olay->kategori,
                $kategori['icon'],
                $olay->yil,
                esc_html($olay->olay)
            );
        }
        $output .= '</ul>';
    } else {
        $output .= '<p>Bu tarih için kayıtlı olay bulunmamaktadır.</p>';
    }
    
    // Kategori filtreleme JavaScript'i
    $output .= "
    <script>
    jQuery(document).ready(function($) {
        $('#datepicker').datepicker({
            dateFormat: 'dd-mm',
            onSelect: function(dateText) {
                var selectedDate = $(this).datepicker('getDate');
                var day = selectedDate.getDate();
                var month = selectedDate.getMonth() + 1;
                window.location.href = '?" . remove_query_arg('') . "&gun=' + day + '&ay=' + month;
            }
        });

        // Kategori filtreleme
        $('.kategori-filtre').click(function() {
            var kategori = $(this).data('kategori');
            $(this).toggleClass('active');
            
            if($('.kategori-filtre.active').length === 0) {
                $('.olay-item').show();
            } else {
                $('.olay-item').hide();
                $('.kategori-filtre.active').each(function() {
                    $('.olay-item[data-kategori=\"' + $(this).data('kategori') + '\"]').show();
                });
            }
        });
    });
    </script>";
    
    $output .= '</div>';
    
    return $output;
}