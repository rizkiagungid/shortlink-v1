<script>
    !function(q,e,v,n,t,s){if(q.qp) return; n=q.qp=function(){n.qp?n.qp.apply(n,arguments):n.queue.push(arguments);}; n.queue=[];t=document.createElement(e);t.async=!0;t.src=v; s=document.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s);}(window, 'script', 'https://a.quora.com/qevents.js');
    qp('init', '{{ $pixel->value }}');
    qp('track', 'ViewContent');
</script>

<noscript>
    <img height="1" width="1" style="display: none;" src="https://q.quora.com/_/ad/{{ $pixel->value }}/pixel?tag=ViewContent&noscript=1"/>
</noscript>