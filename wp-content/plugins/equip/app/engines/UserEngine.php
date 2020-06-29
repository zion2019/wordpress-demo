<?php

namespace Equip\Engine;

use Equip\Factory;

/**
 * This engine is responsible to render the top-level "user" layout
 *
 * @author  8guild
 * @package Equip\Engine
 */
class UserEngine extends Engine {

	public function before_elements( $slug, $layout ) {
		// TODO: $settings for high-level layout?
		?>
		<table class="form-table">
		<?php
	}

public function before_element( $slug, $settings, $layout ) {
	?>
	<tr>
	<th><?php echo $settings['label']; ?></th>
	<td>
	<?php
}

	public function do_element( $slug, $settings, $values, $layout ) {
		$engine = Factory::engine( $layout );
		$engine->render( $slug, $layout, $values );
	}

public function after_element( $slug, $settings, $layout ) {
	?>
	</td>
	</tr>
	<?php
}

	public function after_elements( $slug, $layout ) {
		?>
		</table>
		<?php
	}

}