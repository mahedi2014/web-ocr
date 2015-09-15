<?php
/**
 * Created by Mahedi.
 * User: RDF
 * Date: 1/13/15
 * Time: 10:56 AM
 */
class Hook {
    /**
     * Dump a array
     * @param $data
     */
    public function  dumpPrint($data) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }
    /**
     * Replace multiple space to single space
     */
    public function  replaceMultipleSpaceToSingle($text) {
        return preg_replace('/\s\s+/', ' ', trim( $text ) );
    }

}

