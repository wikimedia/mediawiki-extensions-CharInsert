<?php

namespace MediaWiki\Extension\CharInsert;

use MediaWiki\Hook\ParserFirstCallInitHook;
use MediaWiki\Output\Hook\BeforePageDisplayHook;

class Hooks implements
	BeforePageDisplayHook,
	ParserFirstCallInitHook
{
	/** @inheritDoc */
	public function onBeforePageDisplay( $out, $skin ): void {
		if ( $out->getTitle()->isSpecial( 'Upload' ) ||
			in_array( $out->getActionName(), [ 'edit', 'submit' ] )
		) {
			$out->addModules( 'ext.charinsert' );
			$out->addModuleStyles( 'ext.charinsert.styles' );
		}
	}

	/** @inheritDoc */
	public function onParserFirstCallInit( $parser ) {
		$parser->setHook( 'charinsert', [ CharInsert::class, 'charInsertHook' ] );
	}
}
