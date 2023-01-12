<?php


namespace App\Traits;

use App\Models\Link;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

trait LinkTrait
{
    /**
     * Store the Link.
     *
     * @param Request $request
     * @return Link
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function linkStore(Request $request)
    {
        return $this->model($request, new Link, $request->input('url'), 0);
    }

    /**
     * Store multiple Links.
     *
     * @param Request $request
     * @return array
     */
    protected function linksStore(Request $request)
    {
        $urls = preg_split('/\n|\r/', $request->input('urls'), -1, PREG_SPLIT_NO_EMPTY);

        $data = [];
        foreach ($urls as $url) {
            $data[] = $this->model($request, new Link, $url, 0);
        }

        return $data;
    }

    /**
     * Update the Link.
     *
     * @param Request $request
     * @param Link $link
     * @return Link
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function linkUpdate(Request $request, Link $link)
    {
        return $this->model($request, $link, $request->input('url'), 1);
    }

    /**
     * Create or update the model.
     *
     * @param Request $request
     * @param Link $link
     * @param string $url The URL to be shortened
     * @param int $type
     * @return Link
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function model(Request $request, Link $link, $url, int $type)
    {
        $metadata = $this->parseUrl($url);

        if ($url) {
            $link->url = $url;
            $link->title = !empty($metadata) && isset($metadata['title']) ? trim(Str::limit($metadata['title'], 128)) : null;
            $link->description = !empty($metadata) && isset($metadata['description']) ? trim(Str::limit($metadata['description'], 512)) : null;
            $link->image = !empty($metadata) && isset($metadata['og:image']) ? trim($metadata['og:image']) : null;
        }

        if ($type == 0) {
            $link->user_id = ($request->user()->id ?? 0);
            $link->alias = $request->input('alias') ?? $this->generateAlias();
        } else {
            if ($request->has('alias') && !$request->input('multiple_links')) {
                $link->alias = $request->input('alias');
            }
        }

        if ($request->has('disabled')) {
            $link->disabled = $request->input('disabled');
        }

        if ($request->has('privacy')) {
            $link->privacy = $request->input('privacy');
        }

        if ($request->has('privacy_password')) {
            $link->privacy_password = $request->input('privacy_password');
        }

        if ($request->has('space')) {
            $link->space_id = $request->input('space');
        }

        if ($type == 0) {
            if ($request->has('domain')) {
                $link->domain_id = $request->input('domain');
            }
        }

        if ($request->has('expiration_url')) {
            $link->expiration_url = $request->input('expiration_url');
        }

        if ($request->has('expiration_date') && $request->has('expiration_time')) {
            $link->ends_at = $request->input('expiration_date') && $request->input('expiration_time') ? Carbon::createFromFormat('Y-m-d H:i', $request->input('expiration_date').' '.$request->input('expiration_time'), $request->user()->timezone ?? config('app.timezone'))->tz(config('app.timezone'))->toDateTimeString() : null;
        }

        if ($request->has('expiration_clicks')) {
            $link->expiration_clicks = $request->input('expiration_clicks');
        }

        if ($request->has('password')) {
            $link->password = $request->input('password');
        }

        if ($request->has('target_type')) {
            $link->target_type = $request->input('target_type');
        }

        if ($request->has('country')) {
            $link->country_target = array_filter(array_map('array_filter', array_values($request->input('country')))) ?? null;
        }

        if ($request->has('platform')) {
            $link->platform_target = array_filter(array_map('array_filter', array_values($request->input('platform')))) ?? null;
        }

        if ($request->has('language')) {
            $link->language_target = array_filter(array_map('array_filter', array_values($request->input('language')))) ?? null;
        }

        if ($request->has('rotation')) {
            $link->rotation_target = array_filter(array_map('array_filter', array_values($request->input('rotation')))) ?? null;
        }

        $link->save();

        if ($request->has('pixels')) {
            $link->pixels()->sync(array_filter($request->input('pixels')) ?? []);
        }

        return $link;
    }

    /**
     * Generate a random unique alias.
     *
     * @return string|null
     */
    private function generateAlias()
    {
        $alias = null;
        $unique = false;
        $fails = 0;

        while (!$unique) {
            $alias = $this->generateString(5 + $fails);

            // Check if the alias exists
            if(!Link::where('alias', '=', $alias)->exists()) {
                $unique = true;
            }

            $fails++;
        }

        return $alias;
    }

    /**
     * Generate a random string.
     *
     * @param int $length
     * @return string
     */
    private function generateString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Parse the contents of a given URL.
     *
     * @param $url
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function parseUrl($url)
    {
        $metadata = [];

        $httpClient = new HttpClient();

        try {
            $content = $httpClient->request('GET', $url, [
                'proxy' => [
                    'http' => getRequestProxy(),
                    'https' => getRequestProxy()
                ],
                'timeout' => config('settings.request_timeout'),
                'http_errors' => false,
                'headers' => [
                    'User-Agent' => config('settings.request_user_agent')
                ],
                'on_headers' => function (ResponseInterface $response) {
                    if ($response->getHeaderLine('Content-Length') > 2097152) {
                        throw new \Exception('The file size exceeded the limits.');
                    }
                }
            ]);

            $headerType = $content->getHeader('content-type');
            $parsed = \GuzzleHttp\Psr7\Header::parse($headerType);
            $metadata = $this->formatMetaTags(mb_convert_encoding($content->getBody(), 'UTF-8', in_array($parsed[0]['charset'], mb_list_encodings()) ? $parsed[0]['charset'] : ($parsed[0]['charset'] == 'MS949' && in_array('UHC', mb_list_encodings()) ? 'CP949' : 'UTF-8')));
        } catch (\Exception $e) {
        }

        return $metadata;
    }

    /**
     * Parse and format the meta tags.
     *
     * @param $value
     * @return array|false
     */
    public function formatMetaTags($value)
    {
        $array = [];

        // Match the meta tags
        $pattern = '
            ~<\s*meta\s
        
            # using lookahead to capture type to $1
            (?=[^>]*?
            \b(?:name|property|http-equiv)\s*=\s*
            (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
            ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
            )
        
            # capture content to $2
            [^>]*?\bcontent\s*=\s*
            (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
            ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
            [^>]*>
        
            ~ix';
        if(preg_match_all($pattern, $value, $out)) {
            $array = array_combine(array_map('strtolower', $out[1]), $out[2]);
        }

        // Match the title tags
        preg_match("/<title[^>]*>(.*?)<\/title>/is", $value, $title);
        $array['title'] = $title[1];

        // Return the result
        return $array;
    }
}