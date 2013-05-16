<li id="tooltip" data-name="Tooltip">
	<div class="info">
		<a href="#" class="howtouse">How to use</a>
		<div class="howtouse">
			<p>You can use the Shortcode Editor or the HTML5 custom data attribute <code>data-tooltip</code> to activate the tooltip. For example:</p>
<pre>
&lt;a href=&quot;image_lb.jpg&quot; <code>data-tooltip=&quot;on&quot;</code> title=&quot;Hi There! Im a ToolTip&quot; &gt;
    &lt;img src=&quot;image.jpg&quot; width=&quot;180&quot; height=&quot;120&quot; alt=&quot;&quot; /&gt;
&lt;/a&gt;
</pre>		
			<p>To create a custom tooltip use a div element with the CSS class <code>tip-conten</code>t. You can use any HTML inside the div element. For example:</p>
<pre>
&lt;a data-tooltip=&quot;on&quot; href=&quot;mypage.html&quot;&gt;
    &lt;img src=&quot;image.jpg&quot; width=&quot;180&quot; height=&quot;120&quot; alt=&quot;&quot; /&gt;
    &lt;div <code>class=&quot;tip-content&quot;</code>&gt;Tooltip content goes here..&lt;/div&gt;
&lt;/a&gt;
</pre>
		</div>
	</div>
	<div class="config">
		<form method="post" action="<?php echo $this['system']->link(array('task' => 'config_tooltip', 'ajax' => true)); ?>">
			<ul class="properties">
				<li class="separator">Settings</li>
				<?php
					foreach ($xml->settings->setting as $setting) {
						$name    = (string) 'tooltip_'.$setting->attributes()->name;
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