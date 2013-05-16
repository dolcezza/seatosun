<div id="atomicpress" class="wrap">
	
	<?php if ($this['check']->notices()): ?>
	<div id="apress-systemcheck">
		<strong>Critical Issues</strong>
		<ul>
			<?php foreach($this['check']->get_notices() as $notice): ?>
			<li class="<?php echo $notice['type']; ?>"><?php echo $notice['message']; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>

	<div class="dashboard">
		<ul id="tabs" data-apressversion="<?php echo $this->atomicpress["version"];?>">
			<?php $this['event']->trigger('dashboard'); ?>
		</ul>
	</div>												

</div>