<?php

// get stylesheets/javascripts
$styles  = $this['asset']->get('css');
$scripts = $this['asset']->get('js');

// cache stylesheets/javascripts, if cache is writable
if (is_writable($this['path']->path('cache:'))) {

	$styles = array($this['asset']->cache('atomicpress.css', $this['asset']->get('css'), array('CSSImportResolver', 'CSSRewriteURL', 'CSSCompressor','CSSImageBase64')));
	foreach ($styles[0] as $style) {
		if ($style->getType() == 'File' && !$style->getPath()) {
			$styles[] = $style;
		}
	}

	$scripts = array($this['asset']->cache('atomicpress.js', $this['asset']->get('js'), array('JSCompressor')));
	foreach ($scripts[0] as $script) {
		if ($script->getType() == 'File' && !$script->getPath()) {
			$scripts[] = $script;
		}
	}
	
}

// render styles
foreach ($styles as $style) {
	if ($url = $style->getUrl()) {
		printf("<link rel=\"stylesheet\" href=\"%s\" />\n", $url);
	} else {
		printf("<style>%s</style>\n", $style->getContent());
	}
}

// render scripts
foreach ($scripts as $script) {
	if ($url = $script->getUrl()) {
		printf("<script src=\"%s\"></script>\n", $url);
	} else {
		printf("<script>%s</script>\n", $script->getContent());
	}
}

// internet explorer
if ($this['useragent']->browser() == 'msie') {
	printf("<!--[if lte IE 8]><link rel=\"stylesheet\" href=\"%s\" /><![endif]-->\n", $this['path']->url('atomicpress:css/ie.css'));
}