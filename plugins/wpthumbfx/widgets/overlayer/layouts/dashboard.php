<li id="overlayer" data-name="Overlayer">
	<div class="info">
		<a href="#" class="howtouse">How to use</a>
		<div class="howtouse">
			<p>You can use the Shortcode Editor or it can be activated via HTML5 data attribute <code>data-overlayer</code>. For example:</p>
			<pre>&lt;a <code>data-overlayer=&quot;on&quot;</code> href=&quot;link.html&quot;&gt;&lt;img src=&quot;image.jpg&quot; alt=&quot;&quot; /&gt;&lt;/a&gt;</pre>
			<p>To use a custom overlay you need to add a div tag inside the desired element and give it a class <code>overlay</code>. You can also use the <code>effect</code> parameter to change the animation effect. For example</p>
			<pre>
&lt;a <code>data-overlayer=&quot;effect:bottom;&quot;</code> href=&quot;mypage.html&quot;&gt;
    &lt;img src=&quot;image.jpg&quot; alt=&quot;&quot; /&gt;
    &lt;<code>div class=&quot;overlay&quot;</code>&gt;Custom content goes here&lt;/div&gt;
&lt;/a&gt;</pre>
		</div>
	</div>
	<div class="config">
		<form method="post" action="<?php echo $this['system']->link(array('task' => 'config_overlayer', 'ajax' => true)); ?>">
			<ul class="properties">
				<li class="separator">Settings</li>
				<?php
					foreach ($xml->settings->setting as $setting) {
						$name    = (string) 'overlayer_'.$setting->attributes()->name;
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