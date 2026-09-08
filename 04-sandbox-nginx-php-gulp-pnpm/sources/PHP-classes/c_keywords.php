<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 13/6/2018
 * Time: 20:42
 */


class c_keywords
{
    public $title = 'Ceco.net download free AutoCAD blocks drawings dwg dxf';
    public $description = 'autocad drawings, blocks, templates, libraries in .dwg and .dxf format';
    public $keywords = 'autocad block, autocad drawing, autocad, free, blocks, drawings, symbols, templates, sketches, designs, patterns, designing, figures, pictures, images, planes, blueprints';
    public $main_title_img = 'autocad-drawings-blocks-templates-symbols-dwg-dxf.png';


    public function __construct($page)
    {
        if ($page['block_key'] > 0) {
            global $x_block;
            $this->title = $x_block['block_title'];
            $this->description = saca_elemento($x_block['block_description'], ';', '1');
            $this->keywords = $x_block['block_keywords'];
        }elseif($page['block_key'] < 0){
            global $x_block;
            $this->title = 'New ' . $x_block['block_title'];
            $this->description = 'New ' . saca_elemento($x_block['block_description'], ';', '1');
            $this->keywords = 'new,' .$x_block['block_keywords'];
        } elseif ($page['lib_key'] != 0) {
            if ($page['lib_key'] > 0) {
                global $x_lib;
                $this->title = $x_lib['lib_title'];
                $this->description = $x_lib['lib_description'];
                $this->keywords = $x_lib['lib_keywords'];
            } else {
                $this->title = 'CAD library of ';
                $this->description = 'CAD library of ';
                $this->keywords = 'CAD,library,';


            }
        } elseif($page['tab_menu'] == 300){
            $this->title = 'Ceco.net Options, cad download free AutoCAD blocks & drawings dwg dxf';
            $this->description = 'Ceco.net Options autocad drawings, blocks, templates, libraries .dwg and .dxf format';
            $this->keywords = 'Ceco.net,Options,autocad block, autocad drawing, autocad, free, blocks, drawings, symbols, templates, sketches, designs, patterns, designing, figures, pictures, images, planes, blueprints';
        } elseif($page['tab_menu'] == 404){
            $this->title = 'Ceco.net 404 , Block Not Found';
            $this->description = 'Ceco.net 404 , the AutoCAD drawing you are looking for was not found , sorry ...';
            $this->keywords = 'Ceco.net,404, autocad drawing Not Found, sorry ';
        } else {


            //KEYS | CAT1 & CAT 2 -------------------------------------------------------------------------------
            if($page['keys']!=''){
                // KEYS ////////////////////////////////////
                $x_key = 'Autocad blocks of '.$page['keys'];
                if($page['cat1'] > 0){
                    global $CATs;
                    if ($page['cat2'] > 0) {
                        $x_key.=', in '.$CATs[$page['cat1']]['cat2'][$page['cat2']]['name']. ' '.$CATs[$page['cat1']]['name'];
                    }else{
                        $x_key.=', in '.$CATs[$page['cat1']]['name'];
                    }
                }
                $this->title = $page['keys'] .' , ' . $x_key .' in Ceco.net AutoCAD drawings library, blocks in dwg dxf format';
                $this->description = $page['keys'] .' , ' .  $x_key. ' in Ceco.net AutoCAD drawings library for free download , templates, sketch in dwg and dxf format and and in the metric and imperial systems';
                $this->keywords = $x_key.",{$page['keys']},AutoCAD,blocks,drawings,templates,symbols,sketch,library,free,download,Ceco.net";
            }elseif ($page['cat1'] > 0 ) {
                // CAT1 & CAT 2 ///////////////////////////////////
                global $CATs;
                if ($page['cat2'] > 0) {
                    $this->title = $CATs[$page['cat1']]['cat2'][$page['cat2']]['cat2_title'];
                    $this->description = $CATs[$page['cat1']]['cat2'][$page['cat2']]['cat2_description'];
                    $this->keywords = $CATs[$page['cat1']]['cat2'][$page['cat2']]['cat2_keywords'];
                } else {
                    $this->title = $CATs[$page['cat1']]['cat1_title'];
                    $this->description = $CATs[$page['cat1']]['cat1_description'];
                    $this->keywords = $CATs[$page['cat1']]['cat1_keywords'];
                }
            }
            //KEYS | CAT1 & CAT 2 -------------------------------------------------------------------------------


            // VIEW ---------------------------------------------------------------------------------------------
            if($page['view'] != 'X'){
                $x_view = ' in '.strtolower(CECO_UCS_VIEWS_FOR_HUMANS[$page['view']]);
                $this->title .= $x_view;
                $this->description .= $x_view;
                $this->keywords .= $x_view;
            }
            // VIEW ---------------------------------------------------------------------------------------------

            // PAGE ---------------------------------------------------------------------------------------------
            if ($page['page'] > 1) {
                $this->title .= ' page ' . $page['page'];
                $this->description .= ' page ' . $page['page'];
                $this->keywords .= ', page ' . $page['page'];
            }
            // PAGE ---------------------------------------------------------------------------------------------

        }





        // MAIN TITLE IMG ---------------------------------------------------------------------------
        if ($page['cat1'] > 0) {
            global $CATs;
            if ($page['cat2'] > 0) {
                $this->main_title_img = $CATs[$page['cat1']]['cat2'][$page['cat2']]['main_title'];
            } else {
                $this->main_title_img = $CATs[$page['cat1']]['main_title'];
            }
        }
        // MAIN TITLE IMG ---------------------------------------------------------------------------


    }


}