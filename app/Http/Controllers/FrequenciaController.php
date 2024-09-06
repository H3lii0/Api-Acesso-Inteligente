<?php

namespace App\Http\Controllers;

use App\Models\Frequencia;
use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Http\Requests\StoreFrequenciaRequest;
use App\Http\Requests\UpdateFrequenciaRequest;
use Carbon\Carbon;

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
