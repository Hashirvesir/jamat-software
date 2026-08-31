<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembersExport implements FromCollection, WithHeadings, WithStyles
{
    protected $members;

    public function __construct($members)
    {
        $this->members = $members;
    }

    public function collection()
    {
        return $this->members->map(function ($member) {
            return [
                'Membership Number' => $member->membership_number ?? 'N/A',
                'Name' => $this->sanitize($member->name),
                'Father Name' => $this->sanitize($member->father_name) ?? 'N/A',
                'Grandfather Name' => $this->sanitize($member->grandfather_name) ?? 'N/A',
                'Cast / Gote' => $this->sanitize($member->caste) ?? 'N/A',
                'CNIC' => $this->sanitize($member->cnic) ?? 'N/A',
                'Community' => $this->sanitize($member->community->name ?? null) ?? 'N/A',
                'Marital Status' => $member->marital_status,
                'Blood Group' => $member->blood_group ?? 'N/A',
                'Education' => $this->sanitize($member->education),
                'Skill' => $this->sanitize($member->skill),
                'Email' => $member->email ?? 'N/A',
                'Phone' => $member->phone ?? 'N/A',
                'Gender' => $member->gender,
                'Member Type' => ucwords(str_replace('_', ' ', $member->member_type)),
                'Address' => $this->sanitize($member->address) ?? 'N/A',
                'Date of Birth' => $member->date_of_birth ?? 'N/A',
                'Is Deceased' => $member->is_deceased ? 'Yes' : 'No',
                'Death Date' => $member->death_date ?? 'N/A',
                'B-Form Number' => $member->b_form_number ?? 'N/A',
                'Country' => $member->country,
            ];
        });
    }

    /**
     * Neutralize leading =, +, -, @ so Excel/Sheets can't interpret
     * user-supplied text (name, address, etc.) as a formula.
     */
    protected function sanitize($value)
    {
        if (!is_string($value) || $value === '') {
            return $value;
        }

        return preg_match('/^[=+\-@]/', $value) ? "'" . $value : $value;
    }

    public function headings(): array
    {
        return [
            'Membership Number / رکنیت نمبر',
            'Name / نام',
            'Father Name / ولدیت',
            'Grandfather Name / دادا کا نام',
            'Cast / Gote / گوت',
            'CNIC / شناختی کارڈ نمبر',
            'Community / برادری',
            'Marital Status / ازدواجی حیثیت',
            'Blood Group / خون کا گروہ',
            'Education / تعلیم',
            'Skill / ہنر',
            'Email / ای میل',
            'Phone / فون نمبر',
            'Gender / جنس',
            'Member Type / رکن کی قسم',
            'Address / پتہ',
            'Date of Birth / تاریخ پیدائش',
            'Is Deceased / متوفی',
            'Death Date / تاریخ وفات',
            'B-Form Number / بی فارم نمبر',
            'Country / ملک',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1B5E3F'],
                ],
            ],
        ];
    }
}
