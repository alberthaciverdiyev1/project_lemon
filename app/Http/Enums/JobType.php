<?php

namespace App\Http\Enums;

enum JobType: string
{
    case FULL_TIME  = '0x001';
    case PART_TIME  = '0x002';
    case CONTRACT   = '0x003';
    case INTERNSHIP = '0x004';
    case FREELANCE  = '0x005';

    public function label(): string
    {
        return match ($this) {
            self::FULL_TIME  => __('Full Time'),
            self::PART_TIME  => __('Part Time'),
            self::CONTRACT   => __('Contract'),
            self::INTERNSHIP => __('Internship'),
            self::FREELANCE  => __('Freelance'),
        };
    }

    public function value(): string
    {
        return $this->value;
    }
}
