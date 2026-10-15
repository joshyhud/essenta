# Google Maps configuration

The office map and ACF map fields share `essenta_get_google_maps_api_key()`.
The key is no longer embedded in theme source.

## Configure each environment

Set `ESSENTA_GOOGLE_MAPS_API_KEY` in the PHP server environment (not just a shell
running a build). Alternatively, define it in the site's Git-ignored
`wp-config.php`, before WordPress loads:

```php
define('ESSENTA_GOOGLE_MAPS_API_KEY', 'YOUR_RESTRICTED_BROWSER_KEY');
```

The WordPress constant takes precedence over the environment variable. The
existing `essenta_google_maps_api_key` filter remains available for deployment
integrations. Do not add real credentials to tracked files or this document.
The existing local key was migrated to the Git-ignored WordPress configuration;
other environments must be configured separately.

Without a key, the theme skips loading the office Maps script, displays an
unavailable message, and shows administrators a configuration warning.

## Browser visibility and Google Cloud restrictions

Google Maps JavaScript runs in the browser. Its key is necessarily visible in
the script URL/network requests, even when stored in server configuration.
This change keeps it out of tracked theme source, not out of the browser.
Do not proxy the Maps JavaScript API or obfuscate the key to try to hide it.

In Google Cloud Console:

1. Restrict the key's application type to **Websites (HTTP referrers)** and allow
   only the actual production/staging domains and necessary local development
   origins. Include paths such as `https://example.com/*` so admin pages work.
2. Restrict it to **Maps JavaScript API** and only the additional APIs needed by
   ACF map address search (for example Places and Geocoding, depending on the
   installed ACF version). Verify admin address search as well as the public map.
3. Use separate restricted keys for development and production where possible,
   and monitor quotas and billing.
4. Rotate the old key: it was previously committed in theme source. Configure
   the replacement on each environment, verify both maps, then revoke the old key.

If an integration needs a genuinely private server-side API key, use a separate
credential with server/IP and API restrictions; never send that key to browsers.
Google Cloud restrictions and rotation must be performed by a project owner;
moving configuration does not apply them automatically.
