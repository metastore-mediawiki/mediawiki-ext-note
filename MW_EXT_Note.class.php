<?php

namespace MediaWiki\Extension\MW_EXT_Note;

use OutputPage, Parser, PPFrame, Skin;
use MediaWiki\Extension\MW_EXT_Core\MW_EXT_Core;

/**
 * Class MW_EXT_Note
 * ------------------------------------------------------------------------------------------------------------------ */
class MW_EXT_Note {

	/**
	 * Get JSON data.
	 *
	 * @return mixed
	 * -------------------------------------------------------------------------------------------------------------- */

	private static function getData() {
		$getData = file_get_contents( __DIR__ . '/storage/note.json' );
		$outData = json_decode( $getData, true );

		return $outData;
	}

	/**
	 * Get note.
	 *
	 * @param $note
	 *
	 * @return mixed
	 * -------------------------------------------------------------------------------------------------------------- */

	private static function getNote( $note ) {
		$getData = self::getData();

		if ( ! isset( $getData['note'][ $note ] ) ) {
			return false;
		}

		$getNote = $getData['note'][ $note ];
		$outNote = $getNote;

		return $outNote;
	}

	/**
	 * Get note ID.
	 *
	 * @param $note
	 *
	 * @return mixed
	 * -------------------------------------------------------------------------------------------------------------- */

	private static function getNoteID( $note ) {
		$note = self::getNote( $note ) ? self::getNote( $note ) : '';

		if ( ! isset( $note['id'] ) ) {
			return false;
		}

		$getID = $note['id'];
		$outID = $getID;

		return $outID;
	}

	/**
	 * Get note icon.
	 *
	 * @param $note
	 *
	 * @return mixed
	 * -------------------------------------------------------------------------------------------------------------- */

	private static function getNoteIcon( $note ) {
		$note = self::getNote( $note ) ? self::getNote( $note ) : '';

		if ( ! isset( $note['icon'] ) ) {
			return false;
		}

		$getIcon = $note['icon'];
		$outIcon = $getIcon;

		return $outIcon;
	}

	/**
	 * Register tag function.
	 *
	 * @param Parser $parser
	 *
	 * @return bool
	 * @throws \MWException
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setHook( 'note', __CLASS__ . '::onRenderTag' );

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
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onRenderTag( $input, $args = [], Parser $parser, PPFrame $frame ) {
		// Argument: type.
		$getType = MW_EXT_Core::outClear( $args['type'] ?? '' ?: '' );
		$outType = MW_EXT_Core::outConvert( $getType );

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
		$outHTML = '<div class="mw-ext-note mw-ext-note-' . $outID . '">';
		$outHTML .= '<div class="mw-ext-note-body">';
		$outHTML .= '<div class="mw-ext-note-icon"><div><i class="' . $outIcon . '"></i></div></div>';
		$outHTML .= '<div class="mw-ext-note-content">' . $outContent . '</div>';
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
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModuleStyles( [ 'ext.mw.note.styles' ] );

		return true;
	}
}
