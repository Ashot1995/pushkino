<?php

namespace App\Http\Controllers;


use App\Models\Vacancy;

class VacancyController extends Controller
{
    public function index()
    {
        $vacancies = Vacancy::active()->latest('date')->get();

        return response()->json([
            'vacancyList' => $vacancies->map(function (Vacancy $vacancy) {
                return [
                    'type' => $vacancy->type,
                    'logo' => $this->storageFullPath($vacancy->logo),
                    'alt' => $vacancy->alt,
                    'position' => $vacancy->position,
                    'employerName' => $vacancy->employerName,
                    'date' => $vacancy->date?->translatedFormat('d F Y'),
                    'conditions' => $vacancy->conditions,
                    'requirements' => $vacancy->requirements,
                    'duties' => $vacancy->duties,
                    'email' => $vacancy->email,
                    'phoneNumber' => $vacancy->phoneNumber,
                ];
            })->groupBy('type'),
        ]);
    }
}
