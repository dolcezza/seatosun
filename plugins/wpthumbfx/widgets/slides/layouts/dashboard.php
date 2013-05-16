<li id="slides" data-name="Slides">
	<div class="info">
		<a href="#" class="howtouse">How to use</a>
		<div class="howtouse">
			<p>You can use the Shortcode Editor or the HTML5 custom data attribute <code>data-slides</code> to activate the slideshow. For example:</p>
<pre>
&lt;div <code>data-slides=&quot;on&quot;</code>&gt;
    &lt;img src=&quot;image_1.jpg&quot; /&gt;
    &lt;img src=&quot;image_2.jpg&quot; /&gt;
    &lt;img src=&quot;image_3.jpg&quot; /&gt;
&lt;/div&gt;
</pre>
		</div>
	</div>
	<div class="config">
		<form method="post" action="<?php echo $this['system']->link(array('task' => 'config_slides', 'ajax' => true)); ?>">
			<ul class="properties">
				<li class="separator">Settings</li>
				<?php
					foreach ($xml->settings->setting as $setting) {
						$name    = (string) 'slides_'.$setting->attributes()->name;
						$type    = (string) $setting->attributes()->type;
						$label   = (string) $setting->attributes()->label;
						$value   = (string) $this['system']->options->has($name) ? $this['system']->options->get($name) : (string) $setting->attributes()->default;

						echo '<li>';
						echo '<div class="label">'.$label.'</div>';
						echo '<div class="field">'.$this['field']->render($type, $name, $value, $setting).'</div>';
						echo '<div class="description">'.$setting->attributes()->description.'</div>';
						echo '</li>';
					}
				?>
			</ul>
			<p><input type="submit" value="Save changes" class="button-primary"/><span></span></p>
		</form>
	</div>
</li>