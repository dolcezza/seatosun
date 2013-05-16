<li id="lightbox" data-name="Lightbox">
	<div class="info">
        <a href="#" class="howtouse">How to use</a>
		<div class="howtouse">
			<p>You can use the Shortcode Editor or it can be activated via HTML5 data attribute <code>data-lightbox</code>. For example:</p>
			<pre>&lt;a <code>data-lightbox="on"</code> href="big_image.jpg"&gt;&lt;img src="thumb.jpg" alt="" /&gt;&lt;/a&gt;</pre>
			<p>If you want to create a group/gallery for your images or videos use the <code>group</code> parameter. For example:</p>
			<pre>&lt;a <code>data-lightbox="group:gallery"</code> href="image1.jpg"&gt;&lt;img src="thumb.jpg" alt="" /&gt;&lt;/a&gt;
&lt;a <code>data-lightbox="group:gallery"</code> href="link.html"&gt;&lt;img src="thumb.jpg" alt="" /&gt;&lt;/a&gt;</pre>
		</div>
	</div>
	<div class="config">
		<form method="post" action="<?php echo $this['system']->link(array('task' => 'config_lightbox', 'ajax' => true)); ?>">
			<ul class="properties">
				<li class="separator">Settings</li>
				<?php
					foreach ($xml->settings->setting as $setting) {
						$name    = (string) 'lightbox_'.$setting->attributes()->name;
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