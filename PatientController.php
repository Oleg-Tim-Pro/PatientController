<?php

namespace App\Http\Controllers;

//PerformPatient - используется для обработки сущности в очереди
use App\Jobs\PerformPatient;
use App\Models\Patient;
use Illuminate\Http\Request;
// Inertia.js - это библиотека для работы с React, Vue

use Inertia\Inertia;
// Carbon - библиотека для работы с датами
use Carbon\Carbon;
// Cache - работа с кешом
use Illuminate\Support\Facades\Cache;

class PatientController extends Controller
{
    /**
     * Получения списка всех пациентов.
     */
    public function index()
    {
        // Проверяем наличие списка пациентов в кеше
        if (Cache::has('patients')) {
            // Если список пациентов есть в кеше, возвращаем его из кеша
            $patients = Cache::get('patients');
        } else {
            // Если список пациентов отсутствует в кеше, получаем всех пациентов из базы данных
            $patients = Patient::all();
            foreach ($patients as &$patient) {
                // Добавляем поле name объединяем first_name и last_name
                $patient->name = $patient->first_name . ' ' . $patient->last_name;
                // Форматируем дату рождения в формат день.месяц.год
                $patient->birthdate = Carbon::parse($patient->birthdate)->format('d.m.Y');
                // Добавляем единицу измерения к возрасту день.месяц.год
                if ($patient->age_type == 'день') {
                    $patient->age .= ' день';
                } elseif ($patient->age_type == 'месяц') {
                    $patient->age .= 'месяц';
                } else {
                    $patient->age .= 'год';
                }
            }
            // Сохраняем список пациентов в кеше на 5 минут
            Cache::put('patients', $patients, 300);
        }

        return Inertia::render('Patients/Index', ['patients' => $patients]);
    }

    /**
     * Отображения формы создания пациента.
     */
    public function create()
    {
        return Inertia::render('Patients/Create');
    }

    /**
     * Сохранения нового пациента.
     */
    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
        ]);

        $patient = new Patient;
        // Заполнение полей first_name, last_name и birthdate из запроса
        $patient->first_name = $request->first_name;
        $patient->last_name = $request->last_name;
        $patient->birthdate = $request->birthdate;

        // Расчитываем возраст пациента
        $birthdate = Carbon::parse($request->birthdate);
        $now = Carbon::now();
        $age = $birthdate->diffInYears($now);
        if ($age < 1) {
            // Если возраст меньше года, то в месяцах
            $age = $birthdate->diffInMonths($now);
            if ($age < 1) {
                // Если возраст меньше месяца, то в днях
                $age = $birthdate->diffInDays($now);
                //  Возраст в днях
                $patient->age_type = 'день';
            } else {
                // Возраст в месяцах
                $patient->age_type = 'месяц';
            }
        } else {
            // Возраст в годах
            $patient->age_type = 'год';
        }
        // Сохраняем возраст в поле age
        $patient->age = $age;

        // Сохраняем пациента в базе данных
        $patient->save();

        // Сохраняем в кеше на 5 минут (300 секунд)
        Cache::put('patient_' . $patient->id, $patient, 300);

        // Отправка в очередь для обработки
        PerformPatient::dispatch($patient);

        // Перенаправление на страницу со списком пациентов после создания
        return redirect()->route('patients.index');
    }
}
