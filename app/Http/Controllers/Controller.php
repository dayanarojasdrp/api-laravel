<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function documentFacultyId(): ?int
    {
        $value = request()->header('X-Facultad')
            ?? request('facultad_id')
            ?? request('id_facultad');

        return is_numeric($value) ? (int) $value : null;
    }
}
