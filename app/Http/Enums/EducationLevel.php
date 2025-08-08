<?php

namespace App\Http\Enums;

enum EducationLevel: string
{
    case Doctor = '0x001';            // 1
    case Master = '0x002';            // 2
    case Bachelor = '0x003';          // 3
    case IncompleteEducation = '0x004'; // 4
    case Higher = '0x005';            // 5
    case Secondary = '0x006';         // 6

    public function label(): string
    {
        return match ($this) {
            self::Doctor => __('Doctorate'),
            self::Master => __('Master'),
            self::Bachelor => __('Bachelor'),
            self::IncompleteEducation => __('Incomplete Education'),
            self::Higher => __('Higher Education'),
            self::Secondary => __('Secondary Education'),
        };
    }

    public function value(): int
    {
        return $this->value;
    }
}
