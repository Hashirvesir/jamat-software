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
        'address', 'date_of_birth', 'status', 'profile_picture','blood_group', 'card_received', 'b_form_number',
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
}