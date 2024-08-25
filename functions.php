// クイック編集にサムネイルフィールドを追加
function add_thumbnail_to_quick_edit($column_name, $post_type) {
    if ($column_name != 'thumbnail') return;

    ?>
    <fieldset class="inline-edit-col-right inline-edit-thumbnail">
        <div class="inline-edit-col">
            <label>
                <span class="title">サムネイル</span>
                <span class="input-text-wrap">
                    <input type="button" class="button set-quick-edit-thumbnail" value="サムネイルを設定" />
                    <input type="hidden" name="_thumbnail_id" class="thumbnail-id" value="" />
                    <div class="thumbnail-preview" style="margin-top: 10px;">
                        <!-- 現在のサムネイルを表示するためのスペース -->
                    </div>
                </span>
            </label>
        </div>
    </fieldset>
    <?php
}
add_action('quick_edit_custom_box', 'add_thumbnail_to_quick_edit', 10, 2);

// クイック編集に現在のサムネイルを設定
function set_quick_edit_thumbnail($column_name, $post_type) {
    if ($column_name != 'thumbnail') return;

    $post_id = get_the_ID();
    $thumbnail_id = get_post_thumbnail_id($post_id);
    if ($thumbnail_id) {
        $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'thumbnail');
        if ($thumbnail_url) {
            ?>
            <script type="text/javascript">
                (function($) {
                    $('#post-<?php echo $post_id; ?>').find('.thumbnail-id').val('<?php echo $thumbnail_id; ?>');
                    $('#post-<?php echo $post_id; ?>').find('.thumbnail-preview').html('<img src="<?php echo esc_url($thumbnail_url); ?>" style="max-width: 100px;" />');
                })(jQuery);
            </script>
            <?php
        }
    }
}
add_action('manage_posts_custom_column', 'set_quick_edit_thumbnail', 10, 2);


// JavaScriptを追加してメディアライブラリを呼び出す
function enqueue_quick_edit_thumbnail_script()
{
    wp_enqueue_media();
    $version = filemtime(get_template_directory() . '/public/assets/js/admin.min.js');
    wp_enqueue_script('quick-edit-thumbnail', get_template_directory_uri() . '/public/assets/js/admin.min.js',$version, array('jquery'),true);
}
add_action('admin_enqueue_scripts', 'enqueue_quick_edit_thumbnail_script');

// サムネイルを保存
function save_thumbnail_from_quick_edit($post_id)
{
    if (isset($_POST['_thumbnail_id'])) {
        $thumbnail_id = intval($_POST['_thumbnail_id']);
        if ($thumbnail_id) {
            set_post_thumbnail($post_id, $thumbnail_id);
        } else {
            delete_post_thumbnail($post_id);
        }
    }
}
add_action('save_post', 'save_thumbnail_from_quick_edit');
