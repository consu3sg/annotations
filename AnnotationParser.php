<?php

/**
 * The <b>AnnotationParser</b> has some useful functions which 
 * can be used with an instance of AnnotationObject
 * 
 * @package    br.com.3sg.core.annotations
 * @subpackage Core
 * @author     Guilherme Oliveira Toccacelli <consu3sg@gmail.com>
 */
abstract class AnnotationParser {

    /**
     * Parses a docComment to an instance of Annotation
     * @param type $docComment
     * @return \Annotation
     */
    static function parse($docComment) {
        $text = self::uncomment($docComment);  
        $annotations = self::retrieveAnnotations($text);
        $map = [];
        forEach ($annotations as $annotation) {
            $parameters = [];
            $hasParameters = preg_match('/(?<=' . $annotation . '[\(])[\w\W]*?(?=[\)])/', $text, $parameters);
            $map[$annotation] = $hasParameters ? json_decode($parameters[0]) : null;
        }
        return new Annotation($map);
    }

    /**
     * Function returns array with all annoted value
     * @param type $contents
     * @return array
     */
    private static function retrieveAnnotations($contents) {
        $regex = '/@[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(?=(([^"]*"){2})*[^"]*$)(?=(([^\']*\'){2})*[^\']*$)/';
        $annotations = [];
        preg_match_all($regex, $contents, $annotations);
        return $annotations[0];
    }

    /**
     * Method uncomments the document comments 
     * @param type $docComments
     * @return type
     */
    private static function uncomment($docComments) {
        $text = preg_replace('/[\/\/]*(?=(([^"]*"){2})*[^"]*$)(?=(([^\']*\'){2})*[^\']*$)/', "", $docComments);
        return preg_replace('/[*]*(?=(([^"]*"){2})*[^"]*$)/', '', preg_replace('/[\/]*(?=(([^"]*"){2})*[^"]*$)(?=(([^\']*\'){2})*[^\']*$)/', '', trim($text)));
    }

}
