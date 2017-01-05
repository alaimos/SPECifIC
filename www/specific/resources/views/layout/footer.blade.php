<!-- Page JS Code -->
<script src="{{ elixir('js/all.js') }}"></script>
<script src="{{ elixir('js/app.js') }}"></script>
<script>
    jQuery(function () {
        // Init page helpers (Appear + CountTo plugins)
        App.initHelpers(['appear', 'appear-countTo', 'select2', 'rangeslider']);
    });
</script>
@stack('inline-scripts')
</body>
</html>