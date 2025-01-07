<?php

namespace App\Http\Controllers;

use App\Models\Frequencia;
use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Http\Requests\StoreFrequenciaRequest;
use App\Http\Requests\UpdateFrequenciaRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FrequenciaController extends Controller
{
    private $frequencia;

    public function __construct(Frequencia $frequencia)
    {
        $this->frequencia = $frequencia;
    }

    public function index (Request $request)
    {
        $paginado = $request->input('per_page', 20);
        $page = $request->input('page', 1);

        $frequencia = Frequencia::whereDate('data_acesso', Carbon::today())
            ->with('aluno')
            ->orderBy('registro_acesso', 'desc')
            ->paginate($paginado, ['*'], 'page', $page);

        return Response()->json($frequencia, 200);
    }

    public function frequenciaAluno(Request $request, $id)
    {
    
        $paginado = $request->input('per_page', 20);
        $page = $request->input('page', 1);
    
        $frequencia = Frequencia::where('id_aluno', $id)
            ->with('aluno')
            ->orderBy('registro_acesso', 'desc')
            ->paginate($paginado,['*'], 'page', $page);
    
        return response()->json($frequencia, 200);
    }
    
    public function historicoFrequencia (Request $request)
    {
        $paginado = $request->input('per_page', 20);
        $page = $request->input('page', 1);

        $frequencia = Frequencia::with('aluno')
            ->orderBy('registro_acesso', 'desc')
            ->paginate($paginado, ['*'], 'page', $page);

        return Response()->json($frequencia, 200);
    }
    
    public function registrarAcesso(Request $request) 
    {
        $alunoId = $request->input('id_aluno');
        
        $aluno = Aluno::find($alunoId);

        if (!$aluno) {
            return response()->json(['message' => 'Aluno não encotrado'], 404);
        } 

        $dataHoraAtual = Carbon::now();

        $daysOfWeek = [
            'Monday' => 'Segunda-feira',
            'Tuesday' => 'Terça-feira',
            'Wednesday' => 'Quarta-feira',
            'Thursday' => 'Quinta-feira',
            'Friday' => 'Sexta-feira',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];

        $diaSemana = $dataHoraAtual->format('l');

        $diaSemanaPortugues = $daysOfWeek[$diaSemana] ?? 'Desconhecido';

        $frequencia = Frequencia::create([
            'id_aluno' => $alunoId,
            'registro_acesso' => $dataHoraAtual,
            'data_acesso' => $dataHoraAtual->toDateString(),
            'hora_acesso' => $dataHoraAtual->toTimeString(),
            'dia_semana' => $diaSemanaPortugues
        ]);

        return response()->json($frequencia, 201);
    }

    public function acessosPorPeriodo(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(7)->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());
        $groupBy = $request->input('group_by', 'day');

        $formatoData = match ($groupBy) {
            'day' => '%Y-%m-%d',
            'week' => '%X-%V',
            'month' => '%Y-%m',
            default => '%Y-%m-%d',
        };

        $frequencia = Frequencia::selectRaw("DATE_FORMAT(data_acesso, '{$formatoData}') as data, count(*) as total_acessos")
            ->whereBetween('data_acesso', [$startDate, $endDate])
            ->groupBy('data')
            ->get();

        return response()->json([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'group_by' => $groupBy,
            'data' => $frequencia,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFrequenciaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Frequencia $frequencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Frequencia $frequencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFrequenciaRequest $request, Frequencia $frequencia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Frequencia $frequencia)
    {
        //
    }
}
