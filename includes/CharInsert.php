<?php

namespace MediaWiki\Extension\CharInsert;

use Parser;
use Sanitizer;
use Xml;

class CharInsert {
	/** @var array XML-style attributes passed to the tag */
	private $params;
	/** @var Parser */
	private $parser;

	/**
	 * Main entry point, called by the parser.
	 * @param string $data The textual content of the <charinsert> tag
	 * @param array $params XML-style attributes of the <charinsert> tag
	 * @param Parser $parser
	 * @return string
	 */
	public static function charInsertHook( $data, array $params, Parser $parser ): string {
		return ( new self( $params, $parser ) )->expand( $data );
	}

	/**
	 * Constructor.
	 * @param array $params XML-style attributes of the <charinsert> tag
	 * @param Parser $parser
	 */
	private function __construct( array $params, Parser $parser ) {
		$this->params = $params;
		$this->parser = $parser;
	}

	/**
	 * Parse the content of a whole <charinsert> tag.
	 * @param string $data The textual content of the <charinsert> tag
	 * @return string HTML to be inserted in the parser output
	 */
	private function expand( $data ): string {
		$data = $this->parser->getStripState()->unstripBoth( $data );
		$this->parser->getOutput()->addModules( [ 'ext.charinsert' ] );
		$this->parser->getOutput()->addModuleStyles( [ 'ext.charinsert.styles' ] );
		return implode( "<br />\n",
			array_map( [ $this, 'processLine' ],
				explode( "\n", trim( $data ) ) ) );
	}

	/**
	 * Parse a single line in the <charinsert> tag.
	 * @param string $data Textual content of the line
	 * @return string HTML to be inserted in the parser output
	 */
	private function processLine( string $data ): string {
		return implode( "\n",
			array_map( [ $this, 'processItem' ],
				preg_split( '/\s+/', $this->armor( $data ) ) ) );
	}

	/**
	 * Escape literal whitespace characters in <nowiki> tags. Whitespace
	 * within <nowiki> tags is not considered to be an insert boundary.
	 * @param string $data Textual content of the line to escape whitespace in
	 * @return string The textual content with whitespace escaped and <nowiki>
	 *  tags removed
	 */
	private function armor( string $data ): string {
		return preg_replace_callback(
			'!<nowiki>(.*?)</nowiki>!i',
			static function ( array $matches ) {
				return strtr( $matches[1], [
					'\t' => '&#9;',
					'\r' => '&#12;',
					' ' => '&#32;',
				] );
			},
			$data
		);
	}

	/**
	 * Parse a single insert, i.e. largest portion of the input that
	 * (after going through armor()) doesn’t contain ASCII whitespace.
	 * @param string $data The single insert.
	 * @return string HTML to be inserted in the parser output
	 */
	private function processItem( string $data ): string {
		$chars = explode( '+', $data );
		if ( count( $chars ) > 1 && $chars[0] !== '' ) {
			return $this->insertChar( $chars[0], $chars[1] );
		} elseif ( count( $chars ) === 1 ) {
			return $this->insertChar( $chars[0] );
		} else {
			return $this->insertChar( '+' );
		}
	}

	/**
	 * Create the HTML for a single insert.
	 * @param string $start The start part of the insert, inserted before
	 *  the text selected by the user (if there is any).
	 * @param string $end The end part of the insert, inserted after
	 *  the text selected by the user (if there is any).
	 * @return string HTML to be inserted in the parser output
	 */
	private function insertChar( string $start, string $end = '' ): string {
		$estart = $this->getInsertAttribute( $start );
		$eend = $this->getInsertAttribute( $end );
		$inline = $this->params['label'] ?? ( $estart . $eend );

		// Having no href attribute makes the link be more
		// easily copy and pasteable for non-js users.
		return Xml::element( 'a',
			[
				'data-mw-charinsert-start' => $estart,
				'data-mw-charinsert-end' => $eend,
				'class' => 'mw-charinsert-item'
			], $inline
		);
	}

	/**
	 * Double-escape non-breaking spaces. This allows the user to
	 * differentiate the user normal and non-breaking spaces.
	 * @param string $text The text to double-escape NBSPs in
	 * @return string
	 */
	private function getInsertAttribute( string $text ): string {
		static $invisibles = [ '&nbsp;', '&#160;' ];
		static $visibles = [ '&amp;nbsp;', '&amp;#160;' ];
		return Sanitizer::decodeCharReferences(
			str_replace( $invisibles, $visibles, $text ) );
	}
}
