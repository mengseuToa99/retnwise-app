<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Property extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = 'property_details';
    protected $primaryKey = 'property_id';
    public $timestamps = true;

    protected $fillable = [
        'landlord_id',
        'property_name',
        'house_building_number',
        'street',
        'village',
        'commune',
        'district',
        'total_floors',
        'total_rooms',
        'description',
        'status',
        'property_type',
        'year_built',
        'property_size',
        'size_measurement',
        'amenities',
        'images',
        'is_pets_allowed',
        'created_at',
        'updated_at'
    ];
    
    protected $casts = [
        'amenities' => 'array',
        'images' => 'array',
        'is_pets_allowed' => 'boolean',
    ];

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id', 'user_id');
    }

    public function units()
    {
        return $this->hasMany(Unit::class, 'property_id', 'property_id');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'property_id', 'property_id');
    }
    
    public function propertyImages()
    {
        return $this->hasMany(PropertyImage::class, 'property_id', 'property_id');
    }
    
    public function __toString()
    {
        return $this->property_name;
    }

    // Model Events for Logging
    protected static function booted()
    {
        static::created(function ($property) {
            $property->logCreated('property', $property->property_name, "Address: {$property->street}, {$property->village}");
        });

        static::updated(function ($property) {
            $property->logUpdated('property', $property->property_name, "Property details updated");
        });

        static::deleted(function ($property) {
            $property->logDeleted('property', $property->property_name, "Property removed from system");
        });
    }
} 