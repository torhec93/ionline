<?php

namespace App\Http\Controllers\Indicators;

use App\Http\Controllers\Controller;
use App\Indicators\Establecimiento;
use App\Indicators\HealthGoal;
use App\Indicators\Indicator;
use App\Indicators\Percapita;
use App\Indicators\ProgramAps;
use App\Indicators\Rem;
use App\Indicators\Value;
use App\Models\Commune;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramApsController extends Controller
{
    public function index()
    {
        return view('indicators.programming_aps.index');
    }

    public function show($year, $commune_id)
    {
        if(!in_array($commune_id, range(0,8)) OR !preg_match('/^\d+$/', $commune_id)) abort(404);
        $program_aps = ProgramAps::with('tracers')->where('year', $year)->firstOrFail();
        $this->loadValues($year, $commune_id, $program_aps);
        // return $program_aps;
        return view('indicators.programming_aps.show', compact('program_aps', 'commune_id'));
    }

    private function loadValues($year, $commune_id, $program_aps)
    {
        $communes = array(1 => 'COLCHANE', 2 => 'HUARA', 3 => 'CAMIÑA', 4 => 'POZO ALMONTE', 5 => 'PICA', 6 => 'IQUIQUE', 7 => 'ALTO HOSPICIO', 8 => 'HECTOR REYNO');
        $establishments_filter = collect();
        foreach($program_aps->tracers as $tracer){
            $establishments = collect();

            if($tracer->numerator_cods != null && $tracer->numerator_cols != null){
                //procesamos los datos necesarios para las consultas rem
                $cods = array_map('trim', explode(',', $tracer->numerator_cods));
                $cols = array_map('trim', explode(',', $tracer->numerator_cols));
                $raws = null;
                foreach($cols as $col)
                    $raws .= next($cols) ? 'SUM(COALESCE('.$col.', 0)) + ' : 'SUM(COALESCE('.$col.', 0))';
                $raws .= ' AS valor, IdEstablecimiento, Mes';

                $result = Rem::year($year)->selectRaw($raws)
                ->when($commune_id != 0, function($query){ return $query->with('establecimiento'); })
                ->when(isset($communes[$commune_id]) && $commune_id != 8, function($q) use ($communes, $commune_id){
                    return $q->whereHas('establecimiento', function($q2) use ($communes, $commune_id){
                        return $q2->where('comuna', $communes[$commune_id])->where('Codigo', '!=', 102307);
                        });
                })
                ->when(isset($communes[$commune_id]) && $commune_id == 8, function($q){
                    return $q->whereHas('establecimiento', function($q2){
                        return $q2->where('Codigo', 102307);
                        });
                })
                ->whereIn('CodigoPrestacion', $cods)
                ->whereIn('Mes',[1,2,3,4,5,6,7,8,9,10,11])
                ->whereNotIn('CodigoPrestacion', ['102100','102600','102601','102602','102011'])
                ->groupBy('IdEstablecimiento','Mes')->orderBy('Mes')->get();

                foreach($result as $item){
                    $value = new Value(['month' => $item->Mes, 'factor' => 'numerador', 'value' => $item->valor]);
                    if($commune_id != 0){ // No es resumen por lo que procedo a guardar comuna y establecimiento del valor
                        $value->commune = $commune_id != 8 ? $item->establecimiento->comuna : $communes[$commune_id];
                        $value->establishment = $commune_id != 8 ? $item->establecimiento->alias_estab : null;
                        if($commune_id != 8) $establishments[] = $value->establishment; //No es necesario ejecutar si el valor viene del Hector Reyno
                    }
                    $tracer->values->add($value);
                }
            }

            // Consultamos si existen en el denominador asignamos valores manuales por comuna y establecimiento
            if($tracer->denominator_values_by_commune != null){
                $values = array_map('trim', explode(',', $tracer->denominator_values_by_commune));
                if($commune_id == 0){ //RESUMEN denominador
                    foreach($values as $index => $value){
                        $value = new Value(['month' => 12, 'factor' => 'denominador', 'value' => (int)$value]);
                        $tracer->values->add($value);
                    }
                }else {
                    foreach($values as $index => $value){
                        // Seteamos valores nuevos segun comuna y factor denominador
                        if($commune_id == $index+1){
                            $value = new Value(['month' => 12, 'factor' => 'denominador', 'value' => (int)$value]);
                            $value->commune = $communes[$commune_id];
                            $tracer->values->add($value);
                            break;
                        }
                    }

                    foreach($establishments->unique() as $establishment){
                        foreach($values as $index => $value){
                            // Seteamos los mismos valores segun comuna para los establecimientos
                            if($commune_id == $index+1){
                                $value = new Value(['month' => 12, 'factor' => 'denominador', 'value' => (int)$value]);
                                $value->establishment = $establishment;
                                $establishments_filter[] = $establishment; // Necesario para luego filtrar por establecimiento en la vista
                                $tracer->values->add($value);
                                break;
                            }
                        }
                    }
                }
            }
        }

        $program_aps->establishments = $establishments_filter->unique();
        $program_aps->communes = $communes;
    }
}