<?php
/**
 * Created by PhpStorm.
 * User: benfuller
 * Date: 10/25/14
 * Time: 4:54 PM
 */

namespace S3Bomber;


class s3bomberDatabase extends mysqlDatabase
{
    private $query;
    public function countPhotosToBeDeleted ($id_column,$table_name)
    {
        $this->query = new queryStrings();
        $result = $this->select($this->query->countPhotosQuery($id_column,$table_name));
        return (int) $result[0]["photosToDeleteCount"];
    }
    public function getBatchOfPhotosToBeDeleted($id_column,$photo_column,$table_name)
    {
        $this->query = new queryStrings();
        $result = $this->select($this->query->getBatchOfPhotos($id_column,$photo_column,$table_name));
        return $result;
    }
    public function startDeleteQuery($id_column,$table_name)
    {
        $this->query = new queryStrings();
        $this->query->startDeleteQuery($table_name,$id_column);
    }
    public function addPhotoToDeleteList ($photo)
    {
        $this->query->addPhotoToDeleteQuery($photo);
    }
    public function finishDeleteQuery ()
    {
        return $this->delete($this->query->finishPhotoDeleteQuery());

    }
} 