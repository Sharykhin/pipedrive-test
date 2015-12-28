<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationRelationship extends Model
{
    protected $fillable = array('org_id', 'type', 'linked_org_id');

    public $timestamps = false;

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization', 'org_id', 'id');
    }

    public function linked()
    {
        return $this->belongsTo('App\Models\Organization', 'linked_org_id', 'id');
    }
}