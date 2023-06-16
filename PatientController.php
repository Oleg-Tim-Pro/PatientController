<?php
 
namespace App\Http\Controllers;

//PerformPatient - используется для обработки сущности в очереди
use App\Jobs\PerformPatient;
use App\Models\Patient;
use Illuminate\Http\Request;
// Carbon - библиотека для работы с датами
use Carbon\Carbon;
// Cache - работа с кешом
use Cache;

class PatientController extends Controller
{
   /**
     * Метод для получения списка всех пациентов.
     */
    public function index()
    {
        // Проверяем наличие списка пациентов в кеше
        if (Cache::has('patients')) {
            // Если список пациентов есть в кеше, возвращаем его из кеша
            return Cache::get('patients');
        } else {
            // Если список пациентов отсутствует в кеше, получаем всех пациентов из базы данных
            $patients = Patient::all();
            foreach ($patients as &$patient) {
                // Добавляем поле name  соединяем first_name и last_name
                $patient->name = $patient->first_name . ' ' . $patient->last_name;
                // Форматируем дату рождения в формат день.месяц.год
                $patient->birthdate = Carbon::parse($patient->birthdate)->format('d.m.Y');
                // Добавляем единицу измерения к возрасту (д/м/г)
                if ($patient->age_type == 'день') {
                    $patient->age .= ' день';
                } elseif ($patient->age_type == 'месяц') {
                    $patient->age .= ' месяц';
                } else {
                    $patient->age .= ' год';
                }
            }
            // Сохраняем в кеше на 5 минут
            Cache::put('patients', $patients, 300);
            return $patients;
        }
    }
  
    /**
     * Метод для создания нового пациента.
     */
    public function store(Request $request)
    {
        $patient = new Patient;
        // Заполнение полей first_name, last_name и birthdate
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
                // Если возраст меньше месяца, то возраст в днях
                $age = $birthdate->diffInDays($now);
                //  Возраста в денях
                $patient->age_type = 'день';
            } else {
                // Возраста в месяцах
                $patient->age_type = 'месяц';
            }
        } else {
            // Возраста в годах
            $patient->age_type = 'год';
        }
        // Сохраняем возраст в поле age
        $patient->age = $age;

        // Сохраняем  в базу данных
        $patient->save();

        // Сохраняем в кеше на 5 минут
        Cache::put('patient_' . $patient->id, $patient, 300);

        // Отправка в очередь для обработки
        PerformPatient::dispatch($patient);
    }
}
