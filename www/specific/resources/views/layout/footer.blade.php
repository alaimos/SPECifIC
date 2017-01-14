<!-- Page JS Code -->
<script src="{{ url('js/all.js') }}"></script>
<script src="{{ url('js/app.js') }}"></script>
<script>
    jQuery(function () {
        // Init page helpers (Appear + CountTo plugins)
        App.initHelpers(['appear', 'appear-countTo', 'select2']);
    });
</script>
@stack('inline-scripts')
</body>
</html>