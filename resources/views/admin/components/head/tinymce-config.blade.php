<script src="{{ asset('/build/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script nonce="{{ csp_nonce() }}">
    tinymce.init({
        selector: 'textarea#update-content-editor',
        plugins: 'link code table lists image codesample',
        height: 700,
        browser_spellcheck: true,
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | fontsizeselect | codesample',
        forced_root_block: '',
    });
</script>
