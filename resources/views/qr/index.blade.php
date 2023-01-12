<div style="position: absolute; margin: auto; top: 0; right: 0; bottom: 0; left:0; width: 250px; height: 250px;">
    {!! QrCode::encoding('UTF-8')->size(250)->margin(0)->generate(($link->domain->url ?? config('app.url')) . '/' . $link->alias); !!}
</div>