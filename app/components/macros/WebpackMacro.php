<?php

namespace PP\Macros;

use Latte;

/**
 * @author Andrej Souček
 */
class WebpackMacro extends Latte\Macros\MacroSet {

	public static function install(Latte\Compiler $compiler) {
		$set = new static($compiler);
		$set->addMacro('webpack', array($set, 'macro'));
		return $set;
	}

	public function macro(Latte\MacroNode $node, Latte\PhpWriter $writer) : string {
		return $writer->write(
			'echo PP\Macros\WebpackMacro::render(%node.word)'
		);
	}

	public static function render(string $entry) : ?string {
		if (empty($entry)) {
			throw new \RuntimeException('Missing $entry string.');
		}
		$manifest = file_get_contents(__DIR__.'/../../../www/dist/manifest.json');
		if (!$manifest) {
			throw new \RuntimeException('Unable to read manifest.json in %wwwDir%/dist/.');
		}
		$json = json_decode($manifest, true);
		if (isset($json['entrypoints'][$entry]) && isset($json['entrypoints'][$entry]['js'])) {
			$out = '';
			foreach ($json['entrypoints'][$entry]['js'] as $path) {
				$out .= "<script src='/dist/$path'></script>";
			}
			return $out;
		} else {
			throw new \RuntimeException("Unable to find entrypoint '$entry' in manifest.json.");
		}
	}
}
