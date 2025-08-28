<?php

namespace App\Http\Controllers\Admin;

use App\Horario as AppHorario;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Traits\DatatablTrait;
use App\Models\Municipio;
use Carbon\Carbon;
use Validator;

class HorarioController extends Controller
{
    use DatatablTrait;


    private $horario;


    public function __construct(Horario $horario)
    {

        $this->horario = $horario;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('listar_bairro'))
            return back();

        return view('admin.configuracoes.horario.horario');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.configuracoes.horario.horario_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'day_off' => 'required',
            'day_of_week' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()]);

        // Processar breaks: converter textarea em array
        $breaks = null;
        if (!empty($request->breaks)) {
            $breaks = array_filter(explode("\n", $request->breaks));
        }

        $dia = Horario::where('day_of_week', $request->day_of_week)->first();
        if ($dia) {
            $horario = Horario::where('day_of_week', $request->day_of_week)->update(
                [
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'interval_minutes' => $request->interval_minutes,
                    'breaks' => $breaks,
                    'day_off' => $request->day_off,
                ]
            );
        } else {
            $horario = Horario::create(
                [
                    'day_of_week' => $request->day_of_week,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'interval_minutes' => $request->interval_minutes,
                    'breaks' => $breaks,
                    'day_off' => $request->day_off,
                ]
            );
        }
        if ($horario) {

            return redirect()->route('horario.index')->with('success', "Horario adicionado com sucesso.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dados['bairro'] = $this->bairro->findOrFail($id);

        $dados['municipios'] = $this->municipio->all();

        return response()->json([
            'html' => view('admin.configuracoes.bairro.bairro_edit', $dados)->render()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bairro' => 'required',
            'municipio' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()]);


        $bairro = $this->bairro->findorfail($id);

        $bairro->update(['nome' => addslashes($request->bairro)]);

        $bairro->municipios()->sync($request->municipio);

        return response()->json([
            'success' => true,
            'message' => 'Dados actualizados',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function caseStatusList(Request $request)
    {

        $user = auth()->user();

        $isEdit = $user->can('bairro_edit');
        $isDelete = $user->can('bairro_delete');

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'day_of_week',
            2 => 'day_off',
            3 => 'interval_minutes',
            4 => 'breaks',
            5 => 'time',
            6 => 'action'
        );


        $totalData = Horario::count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = Horario::when($search, function ($query, $search) {
            return $query->where('day_of_week', 'LIKE', "%{$search}%")
                ->where('start_time', 'LIKE', "%{$search}%")
                ->where('end_time', 'LIKE', "%{$search}%")
                ->where('interval_minutes', 'LIKE', "%{$search}%")
                ->where('breaks', 'LIKE', "%{$search}%");
        });

        $totalFiltered = $customcollections->count();

        $customcollections = $customcollections->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        $data = [];

        foreach ($customcollections as $key => $item) {

            // $row['id'] = $item->id;

            if (empty($request->input('search.value'))) {
                $final = $totalRec - $start;
                $row['id'] = $final;
                $totalRec--;
            } else {
                $start++;
                $row['id'] = $start;
            }

            $row['day_of_week'] = htmlspecialchars(($item->day_of_week)?$item->day_of_week_pt:'-');

            if ($item->start_time) {
                $time_inicio = date('g:i a', strtotime($item->start_time));
                $time_fim = date('g:i a', strtotime($item->end_time));
            }
            $row['time'] = ($item->start_time) ? $time_inicio . ' - ' . $time_fim : '-';
            $row['interval_minutes'] = htmlspecialchars(($item->interval_minutes)?$item->interval_minutes:'-');
            $row['breaks'] = $item->breaks ? implode(', ', $item->breaks) : '-';

            if ($item->day_off == 1)
                $row['day_off'] = "Sim";
            else
                $row['day_off'] = "Não";

            if ($isEdit == "1" || $isDelete == "1") {

                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('horario.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('horario.destroy', $item->id),
                    ]),
                    'delete_permission' => $isDelete,
                ]);
            } else {
                $row['action'] = [];
            }

            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        return response()->json($json_data);
    }


    public function getByDay(Request $request)
    {
        $horario = Horario::where('day_of_week', $request->day_of_week)->first();

        if ($horario) {
            return response()->json([
                'success' => true,
                'data' => $horario
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => null
            ]);
        }
    }
    public function getAvailableTimes($date)
    {
        $dayOfWeek = \Carbon\Carbon::parse($date)->format('l'); // Monday, Tuesday, etc.

        $schedule = Horario::where('day_of_week', $dayOfWeek)->where('day_off', 1)->first();

        if (!$schedule) {
            return response()->json(['available_times' => []]);
        }

        $start = \Carbon\Carbon::parse($schedule->start_time);
        $end = \Carbon\Carbon::parse($schedule->end_time);
        $interval = $schedule->interval_minutes;
        $breaks = $schedule->breaks ?? [];

        // Buscar agendamentos já marcados para esta data
        $bookedTimes = \DB::table('agenda')
            ->whereDate('data', $date)
            ->pluck('hora')
            ->map(function($time) {
                return \Carbon\Carbon::parse($time)->format('H:i');
            })
            ->toArray();

        $times = [];

        while ($start->lt($end)) {
            $slotStart = $start->format('H:i');
            $inBreak = false;

            // Verificar se está em pausa
            foreach ($breaks as $break) {
                if (strpos($break, '-') !== false) {
                    [$bStart, $bEnd] = explode('-', $break);
                    if ($start->between(\Carbon\Carbon::parse($bStart), \Carbon\Carbon::parse($bEnd))) {
                        $inBreak = true;
                        break;
                    }
                }
            }

            // Verificar se não está em pausa e não está agendado
            if (!$inBreak && !in_array($slotStart, $bookedTimes)) {
                $times[] = $slotStart;
            }

            $start->addMinutes($interval);
        }

        return response()->json(['available_times' => $times]);
    }

    public function getBlockedDates()
    {
        // Buscar dias da semana com day_off = 0 (não trabalha)
        $blockedDaysOfWeek = Horario::where('day_off', 0)
            ->pluck('day_of_week')
            ->toArray();

        // Mapear dias da semana para números (0 = Domingo, 1 = Segunda, etc.)
        $dayMapping = [
            'sunday' => 0,
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6
        ];

        $blockedDayNumbers = [];
        foreach ($blockedDaysOfWeek as $day) {
            if (isset($dayMapping[$day])) {
                $blockedDayNumbers[] = $dayMapping[$day];
            }
        }

        return response()->json([
            'blocked_days' => $blockedDayNumbers,
            'min_date' => date('Y-m-d') // Data mínima é hoje
        ]);
    }
}
