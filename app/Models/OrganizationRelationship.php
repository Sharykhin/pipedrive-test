<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationRelationship extends Model
{
    protected $fillable = array('org_id', 'type', 'linked_org_id');

    public $timestamps = false;

    public function org_id()
    {
        return $this->belongsTo('App\Models\Organization', 'org_id', 'id')->select(['id','name','created_at']);
    }

    public function linked_org_id()
    {
        return $this->belongsTo('App\Models\Organization', 'linked_org_id', 'id')->select(['id','name','created_at']);
    }
}