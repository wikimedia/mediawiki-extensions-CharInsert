<?php

namespace MediaWiki\Extension\CharInsert;

class Hooks implements
	\MediaWiki\Hook\BeforePageDisplayHook,
	\MediaWiki\Hook\ParserFirstCallInitHook
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
