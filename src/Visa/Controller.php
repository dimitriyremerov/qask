<?php
namespace Qask\Visa;

class Controller
{
    const URL_PATTERN = 'https://www.timaticweb.com/cgi-bin/tim_website_client.cgi?SpecData=1&VISA=&page=visa&NA=%s&AR=00&PASSTYPES=PASS&DE=%s&TR=%s&user=KLMB2C&subuser=KLMB2C';

    /**
     * @param string $nat
     * @param string $dest
     * @param null|string $transit
     * @return callable
     */
    public function getResultTemplateHandler(string $nat, string $dest, ?string $transit = null) : callable
    {
        $transit = $transit ?: '';
        $replace = [
            'url' => sprintf(self::URL_PATTERN, $nat, $dest, $transit)
        ];
        return function (string $template) use ($replace) : string {
            return str_replace(array_map(function (string $val) : string {
                return '{{' . $val . '}}';
            }, array_keys($replace)), array_values($replace), $template);
        };
    }
}
