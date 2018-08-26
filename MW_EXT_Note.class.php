<?php

namespace MediaWiki\Extension\MetaStore;

use OutputPage, Parser, PPFrame, Skin;

/**
 * Class MW_EXT_Note
 */
class MW_EXT_Note {

	/**
	 * Get note.
	 *
	 * @param $type
	 *
	 * @return array
	 */
	private static function getNote( $type ) {
		$get = MW_EXT_Kernel::getJSON( __DIR__ . '/storage/note.json' );
		$out = $get['note'][ $type ] ?? [] ?: [];

		return $out;
	}

	/**
	 * Get note ID.
	 *
	 * @param $type
	 *
	 * @return mixed|string
	 */
	private static function getNoteID( $type ) {
		$note = self::getNote( $type ) ? self::getNote( $type ) : '';
		$out  = $note['id'] ?? '' ?: '';

		return $out;
	}

	/**
	 * Get note icon.
	 *
	 * @param $type
	 *
	 * @return mixed|string
	 */
	private static function getNoteIcon( $type ) {
		$note = self::getNote( $type ) ? self::getNote( $type ) : '';
		$out  = $note['icon'] ?? '' ?: '';

		return $out;
	}

	/**
	 * Register tag function.
	 *
	 * @param Parser $parser
	 *
	 * @return bool
	 * @throws \MWException
	 */
	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setHook( 'note', [ __CLASS__, 'onRenderTag' ] );

		return true;
	}

	/**
	 * Render tag function.
	 *
	 * @param $input
	 * @param array $args
	 * @param Parser $parser
	 * @param PPFrame $frame
	 *
	 * @return null|string
	 */
	public static function onRenderTag( $input, $args = [], Parser $parser, PPFrame $frame ) {
		// Argument: type.
		$getType = MW_EXT_Kernel::outClear( $args['type'] ?? '' ?: '' );
		$outType = MW_EXT_Kernel::outNormalize( $getType );

		// Check note type, set error category.
		if ( ! self::getNote( $outType ) ) {
			$parser->addTrackingCategory( 'mw-ext-note-error-category' );

			return null;
		}

		// Get icon.
		$getIcon = self::getNoteIcon( $outType );
		$outIcon = $getIcon;

		// Get ID.
		$getID = self::getNoteID( $outType );
		$outID = $getID;

		// Get content.
		$getContent = trim( $input );
		$outContent = $parser->recursiveTagParse( $getContent, $frame );

		// Out HTML.
		$outHTML = '<div class="mw-ext-note mw-ext-note-' . $outID . ' navigation-not-searchable mw-ext-box">';
		$outHTML .= '<div class="mw-ext-note-body">';
		$outHTML .= '<div class="mw-ext-note-icon"><div><i class="' . $outIcon . '"></i></div></div>';
		$outHTML .= '<div class="mw-ext-note-content">' . "\n\r" . $outContent . "\n\r" . '</div>';
		$outHTML .= '</div></div>';

		// Out parser.
		$outParser = $outHTML;

		return $outParser;
	}

	/**
	 * Load resource function.
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 *
	 * @return bool
	 */
	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModuleStyles( [ 'ext.mw.note.styles' ] );

		return true;
	}
}
