<?php

namespace App\Support;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlSanitizer
{
	private const ALLOWED_CLASSES = [
		'ql-align-center',
		'ql-align-right',
		'ql-align-justify',
		'ql-indent-1',
		'ql-indent-2',
		'ql-indent-3',
		'ql-indent-4',
		'ql-indent-5',
		'ql-indent-6',
		'ql-indent-7',
		'ql-indent-8',
		'ql-size-small',
		'ql-size-large',
		'ql-size-huge',
	];

	private HTMLPurifier $purifier;

	public function __construct()
	{
		$cachePath = storage_path('framework/cache/htmlpurifier');
		if (! is_dir($cachePath)) {
			mkdir($cachePath, 0755, true);
		}

		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.Allowed', implode(',', [
			'p[class]', 'br[class]', 'strong[class]', 'b[class]', 'em[class]', 'i[class]', 'u[class]', 's[class]', 'span[class]',
			'ul[class]', 'ol[class]', 'li[class]', 'blockquote[class]',
			'h2[class]', 'h3[class]', 'h4[class]',
			'a[class|href|title|target|rel]',
			'img[class|src|alt|title|width|height]',
			'table[class]', 'thead[class]', 'tbody[class]', 'tr[class]', 'th[class]', 'td[class]',
		]));
		$config->set('Attr.AllowedClasses', self::ALLOWED_CLASSES);
		$config->set('Attr.AllowedFrameTargets', ['_blank', '_self', '_parent', '_top']);
		$config->set('URI.AllowedSchemes', [
			'http' => true,
			'https' => true,
			'mailto' => true,
		]);
		$config->set('HTML.SafeIframe', false);
		$config->set('URI.SafeIframeRegexp', null);
		$config->set('AutoFormat.RemoveEmpty', true);
		$config->set('Cache.SerializerPath', $cachePath);

		$this->purifier = new HTMLPurifier($config);
	}

	public function clean(?string $html): ?string
	{
		if ($html === null) {
			return null;
		}

		return $this->purifier->purify($html);
	}
}
