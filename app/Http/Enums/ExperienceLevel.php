<?php

namespace App\Http\Enums;

enum ExperienceLevel: string
{
    case INTERN   = '0x000';
    case ENTRY    = '0x001';
    case MIDDLE   = '0x002';
    case SENIOR   = '0x003';
    case LEAD     = '0x004';
    case MANAGER  = '0x005';
    case DIRECTOR = '0x006';
    case VP       = '0x007';
    case C_LEVEL  = '0x008';

    public function label(): string
    {
        return match ($this) {
            self::INTERN   => __('Intern'),
            self::ENTRY    => __('Entry Level'),
            self::MIDDLE   => __('Middle Level'),
            self::SENIOR   => __('Senior Level'),
            self::LEAD     => __('Lead'),
            self::MANAGER  => __('Manager'),
            self::DIRECTOR => __('Director'),
            self::VP       => __('Vice President'),
            self::C_LEVEL  => __('C-Level Executive'),
        };
    }

    public function value(): string
    {
        return $this->value;
    }
}
