<?php

use Parser;
use OutputPage;
use Skin;
use ContentHandler;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;

class NoTocNumHooks {

    /**
     * Registers __NOTOCNUMBERS__ as a double-underscore behavior switch.
     *
     * This makes MediaWiki recognize the internal magic word ID
     * "notocnumbers" as a valid __MAGICWORD__-style behavior switch.
     *
     * @param array &$doubleUnderscoreIDs
     * @return bool
     */
    public static function onGetDoubleUnderscoreIDs( &$doubleUnderscoreIDs ) {
        $doubleUnderscoreIDs[] = 'notocnumbers';

        return true;
    }

    /**
     * Removes __NOTOCNUMBERS__ from visible page output.
     *
     * Even though the word is formally registered as a magic word,
     * this is kept as a compatibility fallback.
     *
     * @param Parser $parser
     * @param string &$text
     * @param mixed $stripState
     * @return bool
     */
    public static function onInternalParseBeforeLinks( Parser $parser, &$text, $stripState ) {
        if ( preg_match( '/__NOTOCNUMBERS__/i', $text ) ) {
            $text = preg_replace( '/__NOTOCNUMBERS__/i', '', $text );
        }

        return true;
    }

    /**
     * Applies the CSS that hides TOC numbering.
     *
     * Rules:
     * - Normal wiki pages: only if the source wikitext contains __NOTOCNUMBERS__.
     * - Special pages: only if $wgNoTocNumEnableSpecialPages is true.
     *
     * @param OutputPage $out
     * @param Skin $skin
     * @return bool
     */
    public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
        global $wgNoTocNumEnableSpecialPages;

        $title = $out->getTitle();

        if ( !$title ) {
            return true;
        }

        try {
            /*
             * Special pages do not usually have normal editable wikitext.
             * Therefore, they are controlled by configuration.
             */
            if ( defined( 'NS_SPECIAL' ) && $title->getNamespace() === NS_SPECIAL ) {
                if ( !empty( $wgNoTocNumEnableSpecialPages ) ) {
                    self::addNoTocNumbersCss( $out );
                }

                return true;
            }

            /*
             * Ignore pages that cannot exist and are not special pages.
             */
            if ( !$title->canExist() ) {
                return true;
            }

            $wikitext = self::getPageWikitext( $title );

            if ( $wikitext === null ) {
                return true;
            }

            if ( preg_match( '/__NOTOCNUMBERS__/i', $wikitext ) ) {
                self::addNoTocNumbersCss( $out );
            }
        } catch ( Throwable $e ) {
            /*
             * Fail silently.
             * The extension should never break page rendering.
             */
            return true;
        }

        return true;
    }

    /**
     * Gets the source wikitext of a normal wiki page.
     *
     * @param Title $title
     * @return string|null
     */
    private static function getPageWikitext( $title ) {
        $services = MediaWikiServices::getInstance();
        $wikiPageFactory = $services->getWikiPageFactory();
        $page = $wikiPageFactory->newFromTitle( $title );

        if ( !$page || !$page->exists() ) {
            return null;
        }

        $content = $page->getContent( RevisionRecord::RAW );

        if ( !$content ) {
            return null;
        }

        if ( method_exists( $content, 'getText' ) ) {
            return $content->getText();
        }

        return ContentHandler::getContentText( $content );
    }

    /**
     * Adds inline CSS to hide TOC numbering.
     *
     * @param OutputPage $out
     * @return void
     */
    private static function addNoTocNumbersCss( OutputPage $out ) {
        $css = '
            .mw-parser-output .tocnumber,
            #toc .tocnumber,
            .toc .tocnumber {
                display: none !important;
            }

            .mw-parser-output .vector-toc-numb,
            .vector-toc-numb {
                display: none !important;
            }
        ';

        $out->addInlineStyle( $css );
    }
}