<?php

/**
 * Class SrOxArticle1
 *
 * This Software is the property of superReal and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.superreal.de
 * @package sroxarticle1\models
 * @copyright (C) superReal 2014
 * @author    Thomas Oppelt <t.oppelt _AT_ superreal.de>
 */
class SrOxArticle1 extends SrOxArticle1_parent
{

    public function getArticleFiles($blAddFromParent = false)
    {
        $oArticleFiles = parent::getArticleFiles($blAddFromParent);
        $oArticleFiles->offsetSet(0, 'faked file');

        return $oArticleFiles;
    }

} 