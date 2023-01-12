<?php

namespace App\Rules;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Contracts\Validation\Rule;

class ValidateGoogleSafeBrowsingRule implements Rule
{
    /**
     * @var
     */
    private $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!config('settings.gsb')) {
            return true;
        }

        $urls = preg_split('/\n|\r/', $value, -1, PREG_SPLIT_NO_EMPTY);

        $data = [];
        foreach ($urls as $url) {
            // Check if the protocol is http or https
            if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
                $data[] = ['url' => $url];
            }
        }

        // Prevent doing an API call if there's no link set
        if (empty($data)) {
            return true;
        }

        $httpClient = new HttpClient();

        try {
            $api = $httpClient->request('POST', 'https://safebrowsing.googleapis.com/v4/threatMatches:find?key=' . config('settings.gsb_key'), [
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode([
                        'client' => [
                            'clientId' => mb_strtolower(config('settings.title')),
                            'clientVersion' => config('info.software.version'),
                        ],
                        'threatInfo' => [
                            'threatTypes' => [
                                'MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'
                            ],
                            'platformTypes' => [
                                'ALL_PLATFORMS',
                            ],
                            'threatEntryTypes' => [
                                'URL', 'EXECUTABLE'
                            ],
                            'threatEntries' => [
                                $data
                            ],
                        ],
                    ])
                ]
            );
        } catch (\Exception $e) {
            $this->message = $e->getResponse()->getBody()->getContents();
            return false;
        }

        $response = json_decode($api->getBody()->getContents(), true);

        // If no threats found
        if (empty($response)) {
            return true;
        }

        $this->message = __('This link has been banned.');
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
