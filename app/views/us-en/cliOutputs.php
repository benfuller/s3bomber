<?php
/**
 * Created by PhpStorm.
 * User: benfuller
 * Date: 10/26/14
 * Time: 7:24 PM
 */

namespace S3Bomber\views;


class cliOutputs {

    public function bomb () {
        return <<<TAG

     ,__\!/
  __/   -*-
,BOOM.  /!\
SBOMB3
`MOOB'\n
TAG
        ;
    }

    /**
     * The system adds a comma separated number at the end of this string representing the number of items to be deleted.
     * @return string
     */
    public function welcome ()
    {
        return "Number of items to delete: ";
    }

    /**
     *
     * The system will replace :qtydeleted, :time, and :qtyleft with the number of items deleted this batch, the time
     * it took to delete them, and the quantity yet to delete respectively.
     *
     * @return string
     */
    public function deletedBatch ()
    {
        return "Deleted :qtydeleted items in :time seconds, :qtyleft left.";
    }

    /**
     * The system adds a comma separated number at the end of this string representing the number of items deleted.
     * @return string
     */
    public function exitMessage ()
    {
        return "Number of items deleted: ";
    }
} 