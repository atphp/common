<?php

namespace AndyTruong\Common\Utility;

/**
 * Helper class URL based methods.
 */
class StringHelper
{

    public static function isValidURL($url, $absolute = false)
    {
        if (!$absolute) {
            return (bool) preg_match("/^(?:[\w#!:\.\?\+=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})+$/i", $url);
        }

        if ($absolute) {
            return (bool) preg_match("
                /^                                                      # Start at the beginning of the text
                (?:ftp|https?|feed):\/\/                                # Look for ftp, http, https or feed schemes
                (?:                                                     # Userinfo (optional) which is typically
                  (?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*      # a username or a username and password
                  (?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@          # combination
                )?
                (?:
                  (?:[a-z0-9\-\.]|%[0-9a-f]{2})+                        # A domain name or a IPv4 address
                  |(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\])         # or a well formed IPv6 address
                )
                (?::[0-9]+)?                                            # Server port number (optional)
                (?:[\/|\?]
                  (?:[\w#!:\.\?\+=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})   # The path and query (optional)
                *)?
              $/xi", $url);
        }
    }

}
