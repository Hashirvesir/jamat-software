<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'name', 'email', 'phone', 'gender', 'cnic', 'marital_status',
        'community_id', 'member_type', 'spouse_id', 'description', 'is_deceased',
        'address', 'date_of_birth', 'status', 'profile_picture', 'blood_group', 'card_received', 'b_form_number',
        'country', 'death_date', 'education', 'skill',
        'membership_number', 'father_name', 'grandfather_name', 'caste',
    ];

    protected $casts = [
        'is_deceased' => 'boolean',
    ];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function parent()
    {
        return $this->belongsTo(Member::class, 'parent_id');
    }

    public function spouse()
    {
        return $this->belongsTo(Member::class, 'spouse_id');
    }

    public function children()
    {
        return $this->hasMany(Member::class, 'parent_id');
    }

    /**
     * Get all children for this member OR their spouse
     */
    public function getFamilyChildrenAttribute()
    {
        $parentIds = array_filter([$this->id, $this->spouse_id]);
        if (empty($parentIds)) {
            return collect();
        }
        return Member::whereIn('parent_id', $parentIds)->get();
    }

    /**
     * Get Father of this member
     */
    public function getFatherAttribute()
    {
        if ($this->parent) {
            if ($this->parent->gender === 'male') {
                return $this->parent;
            }
            if ($this->parent->spouse && $this->parent->spouse->gender === 'male') {
                return $this->parent->spouse;
            }
            return $this->parent;
        }
        return null;
    }

    /**
     * Get Mother of this member
     */
    public function getMotherAttribute()
    {
        if ($this->parent) {
            if ($this->parent->gender === 'female') {
                return $this->parent;
            }
            if ($this->parent->spouse && $this->parent->spouse->gender === 'female') {
                return $this->parent->spouse;
            }
        }
        return null;
    }

    /**
     * Get Siblings of this member
     */
    public function getSiblingsAttribute()
    {
        $parentIds = array_filter([
            $this->parent_id,
            $this->parent?->spouse_id,
        ]);

        if (empty($parentIds)) {
            return collect();
        }

        return Member::whereIn('parent_id', $parentIds)
            ->where('id', '!=', $this->id)
            ->get();
    }
}
