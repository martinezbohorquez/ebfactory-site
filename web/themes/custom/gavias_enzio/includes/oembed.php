<?php

/**
 * Auto Embed.
 */
class AutoEmbed {
  /**
   * This is providers.
   *
   * @var providers
   */
  public $providers = [
    '#https?://(www\.)?youtube.com/watch.*#i'            => [
      'http://www.youtube.com/oembed', TRUE,
    ],
    'http://youtu.be/*'                                  => [
      'http://www.youtube.com/oembed', FALSE,
    ],
    'http://blip.tv/*'                                   => [
      'http://blip.tv/oembed/', FALSE,
    ],
    '#https?://(www\.)?vimeo\.com/.*#i'                  => [
      'http://vimeo.com/api/oembed.{format}', TRUE,
    ],
    '#https?://(www\.)?dailymotion\.com/.*#i'            => [
      'http://www.dailymotion.com/services/oembed', TRUE,
    ],
    '#https?://(www\.)?flickr\.com/.*#i'                 => [
      'http://www.flickr.com/services/oembed/', TRUE,
    ],
    '#https?://(.+\.)?smugmug\.com/.*#i'                 => [
      'http://api.smugmug.com/services/oembed/', TRUE,
    ],
    '#https?://(www\.)?hulu\.com/watch/.*#i'             => [
      'http://www.hulu.com/api/oembed.{format}', TRUE,
    ],
    '#https?://(www\.)?viddler\.com/.*#i'                => [
      'http://lab.viddler.com/services/oembed/', TRUE,
    ],
    'http://qik.com/*'                                   => [
      'http://qik.com/api/oembed.{format}', FALSE,
    ],
    'http://revision3.com/*'                             => [
      'http://revision3.com/api/oembed/', FALSE,
    ],
    'http://i*.photobucket.com/albums/*'                 => [
      'http://photobucket.com/oembed', FALSE,
    ],
    'http://gi*.photobucket.com/groups/*'                => [
      'http://photobucket.com/oembed', FALSE,
    ],
    '#https?://(www\.)?scribd\.com/.*#i'                 => [
      'http://www.scribd.com/services/oembed', TRUE,
    ],
    'http://wordpress.tv/*'                              => [
      'http://wordpress.tv/oembed/', FALSE,
    ],
    '#https?://(.+\.)?polldaddy\.com/.*#i'               => [
      'http://polldaddy.com/oembed/', TRUE,
    ],
    '#https?://(www\.)?funnyordie\.com/videos/.*#i'      => [
      'http://www.funnyordie.com/oembed', TRUE,
    ],
    '#https?://(www\.)?twitter.com/.+?/status(es)?/.*#i' => [
      'http://api.twitter.com/1/statuses/oembed.{format}', TRUE,
    ],
    '#https?://(www\.)?soundcloud\.com/.*#i'             => [
      'http://soundcloud.com/oembed', TRUE,
    ],
    '#https?://(www\.)?slideshare.net/*#'                => [
      'http://www.slideshare.net/api/oembed/2', TRUE,
    ],
    '#http://instagr(\.am|am\.com)/p/.*#i'               => [
      'http://api.instagram.com/oembed', TRUE,
    ],
  ];

  /**
   * Passes on any unlinked URLs that are on their own line for.
   *
   * Potential embedding.
   *
   * @param string $content
   *   The content to be searched.
   *
   * @return string
   *   Potentially modified $content.
   */
  public function parse($content) {
    return preg_replace_callback('|^\s*(https?://[^\s"]+)\s*$|im',
    [$this, 'autoembedCallback',
    ], $content);
  }

  /**
   * Callback function for {@link AutoEmbed::parse()}.
   *
   * @param array $match
   *   A regex match array.
   *
   * @return string
   *   The embed HTML on success, otherwise the original URL.
   */
  public function autoembedCallback(array $match) {
    $attr['discover'] = TRUE;
    $return = $this->getHtml($match[1], $attr);
    return "\n$return\n";
  }

  /**
   * The do-it-all function that takes a URL and attempts to return the HTML.
   *
   * @param string $url
   *   The URL to the content that should be attempted to be embedded.
   * @param array $args
   *   Optional arguments.
   *
   * @return bool|string
   *   False on failure, otherwise the
   *   UNSANITIZED (and potentially unsafe) HTML that should be used to embed.
   */
  public function getHtml($url, array $args = '') {
    $provider = FALSE;

    if (!isset($args['discover'])) {
      $args['discover'] = TRUE;
    }

    foreach ($this->providers as $matchmask => $data) {
      [$providerurl, $regex] = $data;

      // Turn the asterisk-type provider URLs into regex.
      if (!$regex) {
        $matchmask = '#' . str_replace('___wildcard___', '(.+)', preg_quote(str_replace('*', '___wildcard___', $matchmask), '#')) . '#i';
        $matchmask = preg_replace('|^#http\\\://|', '#https?\://', $matchmask);
      }

      if (preg_match($matchmask, $url)) {
        // JSON is easier to deal with than XML.
        $provider = str_replace('{format}', 'json', $providerurl);
        break;
      }
    }

    if (!$provider && $args['discover']) {
      $provider = $this->discover($url);
    }

    if (!$provider || FALSE === $data = $this->fetch($provider, $url, $args)) {
      return FALSE;
    }

    return $this->data2html($data, $url);
  }

  /**
   * Attempts to find oEmbed provider discovery <link> tags at the given URL.
   *
   * @param string $url
   *   The URL that should be inspected for discovery <link> tags.
   *
   * @return bool|string
   *   False on failure, otherwise the oEmbed provider URL.
   */
  public function discover($url) {
    $providers = [];

    // Fetch URL content.
    if ($html = $this->myRemoteGet($url)) {

      // <link> types that contain oEmbed provider URLs
      $linktypes = [
        'application/json+oembed' => 'json',
        'text/xml+oembed' => 'xml',
      // Incorrect, but used by at least Vimeo.
        'application/xml+oembed' => 'xml',
      ];

      // Strip <body>.
      $html = substr($html, 0, stripos($html, '</head>'));

      // Do a quick check.
      $tagfound = FALSE;
      foreach ($linktypes as $linktype => $format) {
        if (stripos($html, $linktype)) {
          $tagfound = TRUE;
          break;
        }
      }

      if ($tagfound && preg_match_all('/<link([^<>]+)>/i', $html, $links)) {
        foreach ($links[1] as $link) {
          $atts = $this->parse_atts($link);

          if (!empty($atts['type']) && !empty($linktypes[$atts['type']]) && !empty($atts['href'])) {
            $providers[$linktypes[$atts['type']]] = $atts['href'];

            // Stop here if it's JSON (that's all we need)
            if ('json' == $linktypes[$atts['type']]) {
              break;
            }
          }
        }
      }
    }

    // JSON is preferred to XML.
    if (!empty($providers['json'])) {
      return $providers['json'];
    }
    elseif (!empty($providers['xml'])) {
      return $providers['xml'];
    }
    else {
      return FALSE;
    }
  }

  /**
   * Connects to a oEmbed provider and returns the result.
   *
   * @param string $provider
   *   The URL to the oEmbed provider.
   * @param string $url
   *   The URL to the content that is desired to be embedded.
   * @param array $args
   *   Optional arguments.
   *
   * @return bool|object
   *   False on failure, otherwise the result in the form of an object.
   */
  public function fetch($provider, $url, array $args = '') {
    $width = 500;
    $height = min(ceil($width * 1.5), 1000);
    $args = array_merge(compact('width', 'height'), $args);

    $provider = $this->addQueryArg('maxwidth', (int) $args['width'], $provider);
    $provider = $this->addQueryArg('maxheight', (int) $args['height'], $provider);
    $provider = $this->addQueryArg('url', $url, $provider);

    foreach (['json', 'xml'] as $format) {
      $result = $this->fetchWithFormat($provider, $format);
      return $result;
    }
    return FALSE;
  }

  /**
   * Fetches result from an oEmbed provider for a specific.
   *
   * Format and complete provider URL.
   *
   * @param string $provider_url_with_args
   *   URL to the provider with full arguments list (url, maxheight, etc.)
   * @param string $format
   *   Format to use.
   *
   * @access private
   *
   * @return bool|object
   *   False on failure, otherwise the result in the form of an object.
   */
  private function fetchWithFormat($provider_url_with_args, $format) {
    $provider_url_with_args = $this->addQueryArg('format', $format, $provider_url_with_args);
    if (!$body = $this->myRemoteGet($provider_url_with_args)) {
      return FALSE;
    }
    $parse_method = "_parse_$format";
    return $this->$parse_method($body);
  }

  /**
   * Parses a json response body.
   *
   * @access private
   */
  /* private function parseJson($response_body) {
  return (($data = json_decode(trim($response_body)))
  && is_object($data)) ? $data : FALSE;
  } */

  /**
   * Parses an XML response body.
   *
   * @access private
   */
  /* private function parseXml($response_body) {
  if (!function_exists('simplexml_load_string')) {
  return FALSE;
  }

  if (!class_exists('DOMDocument')) {
  return FALSE;
  }

  $errors = libxml_use_internal_errors(TRUE);
  $old_value = NULL;
  if (function_exists('libxml_disable_entity_loader')) {
  $old_value = libxml_disable_entity_loader(TRUE);
  }

  $dom = new DOMDocument();
  $success = $dom->loadXML($response_body);

  if (!is_null($old_value)) {
  libxml_disable_entity_loader($old_value);
  }
  libxml_use_internal_errors($errors);

  if (!$success || isset($dom->doctype)) {
  return FALSE;
  }

  $data = simplexml_import_dom($dom);
  if (!is_object($data)) {
  return FALSE;
  }

  $return = new stdClass();
  foreach ($data as $key => $value) {
  $return->$key = (string) $value;
  }
  return $return;
  } */

  /**
   * Converts a data object and returns the HTML.
   *
   * @param object $data
   *   A data object result from an oEmbed provider.
   * @param string $url
   *   The URL to the content that is desired to be embedded.
   *
   * @return bool|string
   *   False on error, otherwise the HTML needed to embed.
   */
  public function data2html($data, $url) {
    if (!is_object($data) || empty($data->type)) {
      return FALSE;
    }

    $return = FALSE;

    switch ($data->type) {
      case 'photo':
        if (empty($data->url) || empty($data->width) || empty($data->height)) {
          break;
        }
        if (!is_string($data->url) || !is_numeric($data->width) || !is_numeric($data->height)) {
          break;
        }

        $title = !empty($data->title) && is_string($data->title) ? $data->title : '';
        $return = '<a href="' . $this->escUrl($url) . '"><img src="' . htmlspecialchars($data->url, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '" width="' . htmlspecialchars($data->width, ENT_QUOTES, 'UTF-8') . '" height="' . htmlspecialchars($data->height, ENT_QUOTES, 'UTF-8') . '" /></a>';
        break;

      case 'video':
      case 'rich':
        if (!empty($data->html) && is_string($data->html)) {
          $return = $data->html;
        }
        break;

      case 'link':
        if (!empty($data->title) && is_string($data->title)) {
          $return = '<a href="' . $this->escUrl($url) . '">' . htmlspecialchars($data->title, ENT_QUOTES, 'UTF-8') . '</a>';
        }
        break;

      default:
        $return = FALSE;
    }

    // Strip any new lines from the HTML.
    if (FALSE !== strpos($return, "\n")) {
      $return = str_replace(["\r\n", "\n"], '', $return);
    }

    return $return;
  }

  /**
   * Grabs the response from a remote URL.
   *
   * @param string $url
   *   The remote URL.
   *
   * @return bool|string
   *   False on error, otherwise the response body.
   */
  public function myRemoteGet($url) {
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 5);
    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($handle, CURLOPT_FOLLOWLOCATION, FALSE);
    curl_setopt($handle, CURLOPT_HEADER, FALSE);
    curl_setopt($handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    $response = curl_exec($handle);
    curl_close($handle);
    return $response;
  }

  /**
   * Add HTTP query arguments.
   */
  public function addQueryArg() {
    $ret = '';
    $args = func_get_args();
    if (is_array($args[0])) {
      if (count($args) < 2 || FALSE === $args[1]) {
        $uri = $_SERVER['REQUEST_URI'];
      }
      else {
        $uri = $args[1];
      }
    }
    else {
      if (count($args) < 3 || FALSE === $args[2]) {
        $uri = $_SERVER['REQUEST_URI'];
      }
      else {
        $uri = $args[2];
      }
    }

    if ($frag = strstr($uri, '#')) {
      $uri = substr($uri, 0, -strlen($frag));
    }
    else {
      $frag = '';
    }

    if (0 === stripos('http://', $uri)) {
      $protocol = 'http://';
      $uri = substr($uri, 7);
    }
    elseif (0 === stripos('https://', $uri)) {
      $protocol = 'https://';
      $uri = substr($uri, 8);
    }
    else {
      $protocol = '';
    }

    if (strpos($uri, '?') !== FALSE) {
      $parts = explode('?', $uri, 2);
      if (1 == count($parts)) {
        $base = '?';
        $query = $parts[0];
      }
      else {
        $base = $parts[0] . '?';
        $query = $parts[1];
      }
    }
    elseif ($protocol || strpos($uri, '=') === FALSE) {
      $base = $uri . '?';
      $query = '';
    }
    else {
      $base = '';
      $query = $uri;
    }

    parse_str($query, $qs);
    if (is_array($args[0])) {
      $kayvees = $args[0];
      $qs = array_merge($qs, $kayvees);
    }
    else {
      $qs[$args[0]] = $args[1];
    }

    foreach ($qs as $k => $v) {
      if ($v === FALSE) {
        unset($qs[$k]);
      }
    }
    $ret = http_build_query($qs, NULL, '&');
    $ret = trim($ret, '?');
    $ret = preg_replace('#=(&|$)#', '$1', $ret);
    $ret = $protocol . $base . $ret . $frag;
    $ret = rtrim($ret, '?');
    return $ret;
  }

  /**
   * Checks and cleans a URL.
   *
   * @param string $url
   *   The URL to be cleaned.
   *
   * @return string
   *   The cleaned $url.
   */
  public function escUrl($url) {

    if ('' == $url) {
      return $url;
    }
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
    $strip = ['%0d', '%0a', '%0D', '%0A'];
    $url = $this->deepReplace($strip, $url);
    $url = str_replace(';//', '://', $url);
    /* If the URL doesn't appear to contain a scheme, we
     * presume it needs http:// appended (unless a relative
     * link starting with /, # or ? or a php file).
     */
    if (strpos($url, ':') === FALSE && !in_array($url[0], ['/', '#', '?']) &&
                  !preg_match('/^[a-z0-9-]+?\.php/i', $url)) {
      $url = 'http://' . $url;
    }

    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);

    return $url;
  }

  /**
   * Perform a deep string replace operation to ensure.
   *
   *   The values in $search are no longer present.
   *
   * @param string|array $search
   *   Search.
   * @param string $subject
   *   Subject
   *
   *   Repeats the replacement operation until it no longer.
   *
   *   Replaces anything so as to remove "nested" values.
   *
   *   e.g. $subject = '%0%0%0DDD', $search ='%0D', $result =''.
   *
   *   Rather than the '%0%0DD' that str_replace would return.
   *
   * @access private
   *
   * @return string
   *   The processed string.
   */
  private function deepReplace($search, $subject) {
    $found = TRUE;
    $subject = (string) $subject;
    while ($found) {
      $found = FALSE;
      foreach ((array) $search as $val) {
        while (strpos($subject, $val) !== FALSE) {
          $found = TRUE;
          $subject = str_replace($val, '', $subject);
        }
      }
    }

    return $subject;
  }

  /**
   * Retrieve all attributes from the tag.
   *
   * @param string $text
   *   Text.
   *
   * @return array
   *   List of attributes and their value.
   */
  public function shortcodeParseAtts($text) {
    $atts = [];
    $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
    $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
    if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
      foreach ($match as $m) {
        if (!empty($m[1])) {
          $atts[strtolower($m[1])] = stripcslashes($m[2]);
        }
        elseif (!empty($m[3])) {
          $atts[strtolower($m[3])] = stripcslashes($m[4]);
        }
        elseif (!empty($m[5])) {
          $atts[strtolower($m[5])] = stripcslashes($m[6]);
        }
        elseif (isset($m[7]) and strlen($m[7])) {
          $atts[] = stripcslashes($m[7]);
        }
        elseif (isset($m[8])) {
          $atts[] = stripcslashes($m[8]);
        }
      }
    }
    else {
      $atts = ltrim($text);
    }
    return $atts;
  }

}
