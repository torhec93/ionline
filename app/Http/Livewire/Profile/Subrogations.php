<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use App\Models\Profile\Subrogation;
use Illuminate\Support\Facades\Auth;

class Subrogations extends Component
{
    public $subrogations;
    public $view;

    public $subrogation;
    public $user_id, $subrogant_id, $organizational_unit_id;

    public $absent;

    public $hide_own_subrogation;
    public $type;

    public $organizationalUnit = null;

    protected $listeners = ['searchedUser', 'ouSelected'];

    public function ouSelected($ou_id)
    {
        $this->organizational_unit_id = $ou_id;
    }

    public function searchedUser($searchedUser)
    {
        $this->subrogant_id = $searchedUser['id'];
    }

    protected function rules()
    {
        return [
            'user_id' => 'required',
            //'subrogant_id' => 'required',
            //'organizational_unit_id' => 'required',
        ];
    }

    protected $messages = [
        'user_id.required' => 'El usuario es requerido.',
        //'subrogant_id.required' => 'El subrogante es requerido.',
        //'organizational_unit_id.required' => 'La unidad organizacional es requerida.',
    ];

    public function mount()
    {
        $this->user_id = auth()->id();
        if ($this->organizationalUnit and $this->type) {
            $this->subrogations = Subrogation::with('user')
                ->where('organizational_unit_id', $this->organizationalUnit->id)
                ->where('type', $this->type)
                ->orderBy('level')
                ->get();
        } else {
            $this->subrogations = Subrogation::with('user')
                ->where('user_id', $this->user_id)
                ->where('organizational_unit_id', null)
                ->where('type', null)
                ->orderBy('level')
                ->get();
        }
        $this->view = 'index';
        $this->absent = auth()->user()->absent;
    }

    public function index()
    {
        $this->view = 'index';
    }

    public function create()
    {
        $this->view = 'create';
        $this->subrogation = null;

        $this->subrogant_id = null;
        $this->organizational_unit_id = null;
    }

    public function store()
    {
        if ($this->organizationalUnit and $this->type) {
            Subrogation::firstOrCreate(
                [
                    'user_id' => $this->subrogant_id,
                    'subrogant_id' => $this->subrogant_id,
                    'organizational_unit_id' => $this->organizationalUnit->id,
                ],
                [
                    'type' => $this->type,
                    'level' => Subrogation::where('organizational_unit_id', $this->organizationalUnit->id)->where('type', $this->type)->count() + 1
                ]
            );
        } else {
            // dd('entre aca');
            // $dataValidated = $this->validate();
            // $dataValidated['level'] = Subrogation::whereUserId($this->user_id)->count() + 1;
            // Subrogation::create($dataValidated);
            Subrogation::firstOrCreate(
                [
                    'user_id' => $this->user_id,
                    'subrogant_id' => $this->subrogant_id,
                ],
                [
                    
                    'level' => Subrogation::whereUserId($this->user_id)->where('organizational_unit_id', null)->where('type', null)->count() + 1
                ]
            );
        }

        $this->mount();
        $this->view = 'index';
    }

    public function edit(Subrogation $subrogation)
    {
        $this->view = 'edit';
        $this->subrogation = $subrogation;

        /** cambair por subrogant_id */
        $this->subrogant_id = $subrogation->subrogant->id;
        $this->organizational_unit_id = $subrogation->organizational_unit_id;
    }

    public function update(Subrogation $subrogation)
    {
        $subrogation->update($this->validate());

        $this->mount();
        $this->view = 'index';
    }

    public function delete(Subrogation $subrogation)
    {
        $subrogations = Subrogation::query()
            ->where('level', '>', $subrogation->level)
            ->get();

        foreach ($subrogations as $itemSubrogation) {
            $itemSubrogation->update([
                'level' => $itemSubrogation->level - 1
            ]);
        }

        $subrogation->delete();
        $this->mount();
    }

    public function toggleAbsent()
    {
        $this->absent = !$this->absent;
        auth()->user()->absent = $this->absent;
        auth()->user()->save();
    }

    public function up(Subrogation $subrogation)
    {
        if ($subrogation->organizational_unit_id) {
            $subrogationUp = Subrogation::query()
                ->where('organizational_unit_id', $subrogation->organizational_unit_id)
                ->whereType($subrogation->type)
                ->whereLevel($subrogation->level - 1)
                ->first();

            $subrogation->update([
                'level' => $subrogation->level - 1,
            ]);

            $subrogationUp->update([
                'level' => $subrogationUp->level + 1,
            ]);
        } else {
            $subrogationUp = Subrogation::query()
                ->whereUserId(Auth::id())
                ->whereLevel($subrogation->level - 1)
                ->first();

            $subrogation->update([
                'level' => $subrogation->level - 1,
            ]);

            $subrogationUp->update([
                'level' => $subrogationUp->level + 1,
            ]);
        }
        $this->mount();
    }

    public function down(Subrogation $subrogation)
    {
        if ($subrogation->organizational_unit_id) {
            $subrogationDown = Subrogation::query()
                ->where('organizational_unit_id', $subrogation->organizational_unit_id)
                ->whereType($subrogation->type)
                ->whereLevel($subrogation->level + 1)
                ->first();

            $subrogation->update([
                'level' => $subrogation->level + 1,
            ]);
            $subrogationDown->update([
                'level' => $subrogationDown->level - 1,
            ]);
        } else {
            $subrogationDown = Subrogation::query()
                ->whereUserId(Auth::id())
                ->whereLevel($subrogation->level + 1)
                ->first();

            $subrogation->update([
                'level' => $subrogation->level + 1,
            ]);

            $subrogationDown->update([
                'level' => $subrogationDown->level - 1,
            ]);
        }

        $this->mount();
    }

    public function render()
    {
        return view('livewire.profile.subrogations')->extends('layouts.app');;
    }
}
