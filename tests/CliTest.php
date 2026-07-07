<?php

class CliTest extends WpHookExtractor_Testcase {
	private $temp_dirs = array();

	public function tearDown(): void {
		foreach ( $this->temp_dirs as $dir ) {
			$this->remove_dir( $dir );
		}
	}

	public function test_wiki_directory_is_relative_to_project_root_when_base_dir_is_subdirectory() {
		$project_dir = $this->make_temp_dir();
		mkdir( $project_dir . '/src', 0777, true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir

		copy( __DIR__ . '/fixtures/zero_params.php', $project_dir . '/src/hooks.php' );
		$config_json = json_encode( // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
			array(
				'base_dir'        => 'src',
				'wiki_directory'  => 'wiki',
				'github_blob_url' => 'https://github.com/test/repo/blob/main/',
			)
		);
		file_put_contents( $project_dir . '/.extract-wp-hooks.json', $config_json ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents

		$cwd = getcwd();
		chdir( $project_dir );
		exec( escapeshellarg( PHP_BINARY ) . ' ' . escapeshellarg( __DIR__ . '/../extract-wp-hooks.php' ) . ' 2>&1', $output, $exit_code ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_exec
		chdir( $cwd );

		$this->assertSame( 0, $exit_code, implode( PHP_EOL, $output ) );
		$this->assertFileExists( $project_dir . '/wiki/zero_param_hook.md' );
		$this->assertFileDoesNotExist( $project_dir . '/src/wiki/zero_param_hook.md' );
	}

	private function make_temp_dir() {
		$dir = sys_get_temp_dir() . '/extract-wp-hooks-' . uniqid();
		mkdir( $dir ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir
		$this->temp_dirs[] = $dir;
		return $dir;
	}

	private function remove_dir( $dir ) {
		if ( ! is_dir( $dir ) ) {
			return;
		}

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ),
			RecursiveIteratorIterator::CHILD_FIRST
		);

		foreach ( $iterator as $path ) {
			if ( $path->isDir() ) {
				rmdir( $path->getPathname() ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir
			} else {
				unlink( $path->getPathname() ); // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
			}
		}

		rmdir( $dir ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir
	}
}
