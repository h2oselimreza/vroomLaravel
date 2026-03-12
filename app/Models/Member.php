<?php

namespace App\Models;

use App\Traits\TracksUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Member extends Model
{
    use HasFactory, TracksUser;
    
    const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';

    protected $fillable = [
        'member_id',
        'member_name',
        'member_bangla_name',
        'member_image',
        'designation',
        'first_joining_date',
        'gender',
        'religion',
        'nationality',
        'dob',
        'blood_group',
        'marital_status',
        'spouse_name',
        'spouse_occupation',
        'spouse_contact',
        'spouse_office_address',
        'member_tnt_phone',
        'primary_mobile',
        'secendary_mobile',
        'emer_contact_name',
        'emer_contact_relation',
        'emer_contact_mobile',
        'emer_contact_address',
        'email',
        'present_address',
        'member_permanent_address',
        'national_id',
        'father_name',
        'father_occupation',
        'father_office_address',
        'father_contact',
        'mother_name',
        'mother_occupation',
        'mother_office_address',
        'mother_contact',
        'guardian_name',
        'guardian_contact',
        'guardian_relation',
        'guardian_house_address',
        'last_organization',
        'last_org_address',
        'last_org_designation',
        'last_org_from_date',
        'last_org_to_date',
        'passport_no',
        'passport_expiry_date',
        'driving_license_no',
        'driving_license_expiry_date',
        'anniversary',
        'society_block',
        'society_road',
        'society_plot',
        'society_flat',
        'member_type',
        'first_introduced_by',
        'second_introduced_by',
        'member_occupation',
        'institution_name',
        'is_active',
        'system_user',
        'birthday_sms_status',
        'anniversary_sms_status',
        'donar_member_id',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm',
    ];

    public function block()
    {
        return $this->belongsTo(Block::class, 'society_block', 'block_code');
    }

    public function road()
    {
        return $this->belongsTo(Road::class, 'society_road', 'road_code');
    }

    public static function getBirthdayOrAnniversaryMember($flag = 1)
    {
        $today = Carbon::today()->format('m-d');

        // Determine which column & status to use
        if ($flag == 1) {
            $dateColumn = 'dob';
            $statusColumn = 'birthday_sms_status';
        } else {
            $dateColumn = 'anniversary';
            $statusColumn = 'anniversary_sms_status';
        }

        $query = DB::table('members')
            ->select(
                'members.id',
                'members.member_id',
                'members.member_name',
                'members.father_contact as contact_no',
                'blocks.block_name as block',
                'roads.road_name as road',
                'members.society_plot',
                'members.society_flat',
                'members.is_active as status',
            )
            ->leftJoin('blocks', 'blocks.block_code', '=', 'members.society_block')
            ->leftJoin('roads', 'roads.road_code', '=', 'members.society_road')
            ->whereRaw("DATE_FORMAT(members.{$dateColumn}, '%m-%d') = ?", [$today])
            ->where('members.is_active', 1)
            ->orderBy('blocks.block_name')
            ->orderBy('roads.road_name');

        // Only pick members whose status is 0 (unsent)
        $query->where("members.{$statusColumn}", 0);

        return $query->get();
    }

    public static function getMemberDetails(array $arr)
    {
        $query = DB::table('members')
            ->select(
                'members.*',
                'blocks.block_name',
                'roads.road_name',
                'member_type_tb.element as member_type_name',
                'occupation_member_tb.element as member_occupation_name'
            )
            ->leftJoin('blocks', 'blocks.block_code', '=', 'members.society_block')
            ->leftJoin('roads', 'roads.road_code', '=', 'members.society_road')
            ->leftJoin('common_table as member_type_tb', 'member_type_tb.element_code', '=', 'members.member_type')
            ->leftJoin('common_table as occupation_member_tb', 'occupation_member_tb.element_code', '=', 'members.member_occupation')
            ->where('members.is_active', 1)
            
            // Dynamic filters
            ->when(!empty($arr['memberType']), function ($q) use ($arr) {
                $q->whereIn('members.member_type', explode(',', $arr['memberType']));
            })
            ->when(!empty($arr['block']), function ($q) use ($arr) {
                $q->whereIn('members.society_block', explode(',', $arr['block']));
            })
            ->when(!empty($arr['road']), function ($q) use ($arr) {
                $q->whereIn('members.society_road', explode(',', $arr['road']));
            })
            ->when(!empty($arr['occupation']), function ($q) use ($arr) {
                $q->whereIn('members.member_occupation', explode(',', $arr['occupation']));
            })
            ->when(!empty($arr['bloodGroup']), function ($q) use ($arr) {
                $q->whereIn('members.blood_group', explode(',', $arr['bloodGroup']));
            });

        return $query->get()->toArray(); // returns array of stdClass objects
    }

    public static function getSmsMemberList(array $memberIdArr)
    {
        $query = DB::table('members')
            ->select(
                'members.*',
                'blocks.block_name',
                'roads.road_name',
                'member_type_tb.element as member_type_name',
                'occupation_member_tb.element as member_occupation_name'
            )
            ->leftJoin('blocks', 'blocks.block_code', '=', 'members.society_block')
            ->leftJoin('roads', 'roads.road_code', '=', 'members.society_road')
            ->leftJoin('common_table as member_type_tb', 'member_type_tb.element_code', '=', 'members.member_type')
            ->leftJoin('common_table as occupation_member_tb', 'occupation_member_tb.element_code', '=', 'members.member_occupation')
            ->where('members.is_active', 1)
            ->whereIn('members.id', $memberIdArr);

        // Return as array of arrays (similar to CI's result_array())
        return $query->get()->toArray();
    }
}
