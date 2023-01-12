<script async src="https://www.googletagmanager.com/gtag/js?id={{ $pixel->value }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '{{ $pixel->value }}');
</script>