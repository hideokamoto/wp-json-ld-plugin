<?php

require_once 'ejls-master.php';
class WPCustomizationTest extends WP_UnitTestCase {

	function test_null_json_data() {
		// replace this with some actual testing code
		$null_data = null;
		$jsonld_output = ejls_make_html($null_data);
		$this->assertEquals( "<script type='application/ld+json'></script>", $jsonld_output );
	}
}
