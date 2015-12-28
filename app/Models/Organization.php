<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Organization extends Model
{
    protected $fillable = ['id', 'name'];


    public static function findByNames($names)
    {
        //TODO: Performance notice!
        $query = "SELECT id, name FROM organizations WHERE FIND_IN_SET(name, '" . implode(',', $names) . "')";
        $result = DB::select($query);
        return array_reduce($result, function(&$newData, $orgItem) {
           $newData[$orgItem->id] = $orgItem->name;
           return $newData;
        }, []);
    }

    public function relationship()
    {
        return $this->hasMany('App\Models\OrganizationRelationship', 'org_id', 'id');
    }
}