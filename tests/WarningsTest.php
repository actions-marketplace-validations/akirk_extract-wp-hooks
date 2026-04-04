<?php

class WarningsTest extends WpHookExtractor_Testcase {
	public function test_no_warning_for_valid_docblock() {
		$extractor = new WpHookExtractor();
		$extractor->extract_hooks_from_file( __DIR__ . '/fixtures/simple_filter.php' );

		$this->assertEmpty( $extractor->get_warnings() );
	}

	public function test_warning_for_block_comment_instead_of_phpdoc() {
		$extractor = new WpHookExtractor();
		$extractor->extract_hooks_from_file( __DIR__ . '/fixtures/block_comment_hook.php' );

		$warnings = $extractor->get_warnings();
		$this->assertCount( 1, $warnings );
		$this->assertStringContainsString( 'block_comment_hook', $warnings[0] );
		$this->assertStringContainsString( '/*', $warnings[0] );
		$this->assertStringContainsString( '/**', $warnings[0] );
	}

	public function test_no_warning_when_no_comment_at_all() {
		$extractor = new WpHookExtractor();
		$extractor->extract_hooks_from_file( __DIR__ . '/fixtures/zero_params.php' );

		$this->assertEmpty( $extractor->get_warnings() );
	}

	public function test_warning_for_empty_docblock() {
		$extractor = new WpHookExtractor();
		$extractor->extract_hooks_from_file( __DIR__ . '/fixtures/empty_docblock_hook.php' );

		$warnings = $extractor->get_warnings();
		$this->assertCount( 1, $warnings );
		$this->assertStringContainsString( 'empty_docblock_hook', $warnings[0] );
		$this->assertStringContainsString( 'empty', $warnings[0] );
	}

	public function test_warning_for_missing_param_tags() {
		$extractor = new WpHookExtractor();
		$extractor->extract_hooks_from_file( __DIR__ . '/fixtures/missing_params_docblock_hook.php' );

		$warnings = $extractor->get_warnings();
		$this->assertCount( 1, $warnings );
		$this->assertStringContainsString( 'missing_params_hook', $warnings[0] );
		$this->assertStringContainsString( '1', $warnings[0] );
		$this->assertStringContainsString( '3', $warnings[0] );
	}

	public function test_warning_for_extra_param_tags() {
		$extractor = new WpHookExtractor();
		$extractor->extract_hooks_from_file( __DIR__ . '/fixtures/extra_params_docblock_hook.php' );

		$warnings = $extractor->get_warnings();
		$this->assertCount( 1, $warnings );
		$this->assertStringContainsString( 'extra_params_hook', $warnings[0] );
		$this->assertStringContainsString( '3', $warnings[0] );
		$this->assertStringContainsString( '2', $warnings[0] );
	}

	public function test_warning_for_description_only_docblock_with_params() {
		$extractor = new WpHookExtractor();
		$extractor->extract_hooks_from_file( __DIR__ . '/fixtures/description_only_docblock_hook.php' );

		$warnings = $extractor->get_warnings();
		$this->assertCount( 1, $warnings );
		$this->assertStringContainsString( 'description_only_hook', $warnings[0] );
		$this->assertStringContainsString( '0', $warnings[0] );
		$this->assertStringContainsString( '2', $warnings[0] );
	}

	public function test_no_warning_when_param_count_matches() {
		$extractor = new WpHookExtractor();
		$extractor->extract_hooks_from_file( __DIR__ . '/fixtures/two_params.php' );

		$this->assertEmpty( $extractor->get_warnings() );
	}

	public function test_warnings_cleared_between_files() {
		$extractor = new WpHookExtractor();
		$extractor->extract_hooks_from_file( __DIR__ . '/fixtures/block_comment_hook.php' );
		$extractor->extract_hooks_from_file( __DIR__ . '/fixtures/simple_filter.php' );

		$warnings = $extractor->get_warnings();
		$this->assertCount( 1, $warnings );
	}
}
