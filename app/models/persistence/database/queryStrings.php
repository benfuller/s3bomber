<?php
/**
 * Created by PhpStorm.
 * User: benfuller
 * Date: 10/25/14
 * Time: 5:02 PM
 */

namespace S3Bomber;


class queryStrings {
    private $queryBuilder;
    private $id_column;
    /**
     * Query to count the number of photos that need to be deleted
     *
     * Usually a COUNT() function on the id column to save time. :id_column_name and :table_name will be given
     *
     * @return string
     */
    public function countPhotosQuery ($column,$table){
        return "SELECT COUNT({$column}) as photosToDeleteCount FROM {$table};"; //we bastardize the pdo parameters here :(
    }
    public function getBatchOfPhotos ($id_column,$photo_column, $table){
        return "SELECT {$id_column} as delete_id, {$photo_column} as photo_url FROM {$table} LIMIT 1000;"; //we bastardize the pdo parameters here :(
    }
    public function startDeleteQuery ($table,$id_column)
    {
        $this->queryBuilder = "DELETE FROM " . $table . " WHERE ";
        $this->id_column = $id_column;
    }
    public function addPhotoToDeleteQuery($photo_id)
    {
        $this->queryBuilder .= "`" . $this->id_column . "` = '" . $photo_id . "' OR ";
    }
    public function finishPhotoDeleteQuery()
    {
        return  rtrim($this->queryBuilder, 'OR ');
    }
} 