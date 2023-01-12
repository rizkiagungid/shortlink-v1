<script>
    var google_conversion_id = "{{ $pixel->value }}";
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
</script>

<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>

<noscript>
    <div style="display: inline;">
        <img height="1" width="1" style="border-style: none;" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/{{ $pixel->value }}/?guid=ON&amp;script=0"/>
    </div>
</noscript>
