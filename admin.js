jQuery(document).ready(function ($) {
    // クイック編集が開かれたときの処理
    $(document).on('click', '.editinline', function () {
        var post_id = inlineEditPost.getId(this);
        var thumbnail_id = $('#post-' + post_id).find('.thumbnail-id').val();
        if (thumbnail_id) {
            var thumbnail_url = $('#post-' + post_id).find('.thumbnail-preview img').attr('src');
            $('#edit-' + post_id).find('.thumbnail-id').val(thumbnail_id);
            $('#edit-' + post_id).find('.thumbnail-preview').html('<img src="' + thumbnail_url + '" style="max-width: 100px;');
        }
    });

    // サムネイル設定ボタンのクリックイベント
    $(document).on('click', '.set-quick-edit-thumbnail', function () {
        var frame;
        var button = $(this);
        var thumbnail_id_field = button.closest('fieldset').find('.thumbnail-id');
        var thumbnail_preview = button.closest('fieldset').find('.thumbnail-preview');

        if (frame) {
            frame.open();
            return;
        }

        frame = wp.media({
            title: 'サムネイルを選択',
            button: {
                text: '選択'
            },
            multiple: false
        });

        frame.on('select', function () {
            var attachment = frame.state().get('selection').first().toJSON();
            thumbnail_id_field.val(attachment.id);
            thumbnail_preview.html('<img src="' + attachment.sizes.thumbnail.url + '" style="max-width: 100px;" />');
        });

        frame.open();
    });
});
